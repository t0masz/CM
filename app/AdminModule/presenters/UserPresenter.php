<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class UserPresenter extends SecuredPresenter
{

	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->usersManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->users = $this->usersManager->findAll()->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function renderLog()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->logManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->logs = $this->logManager->findLast($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->usersManager->findBy(array('id'=>$id))->fetch();
			$this['userForm']->setDefaults($val);
			if ($page>1) $this['userForm']->setDefaults(['page' => $page]);
		} else {
			$this['userForm']->setDefaults(array(
			  'role' => 'redaktor',
			));
		}
	}

	protected function createComponentPasswordForm($name)
	{
		$form = new PasswordForm($this, $name);
		$form->onSuccess[] = array($this, 'passwordFormSubmitted');
		return $form;
	}

	protected function createComponentUserForm($name)
	{
		$form = new UserForm($this, $name);
		$form->onSuccess[] = array($this, 'userFormSubmitted');
		return $form;
	}
	
	public function passwordFormSubmitted(Form $form, ArrayHash $values)
	{
		$user = $this->getUser();
		if($values['newPasswod'] != $values['confirmPassword']) {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Nové heslo a potvrzení hesla se liší, zadej je znovu.'), 'danger');
			$this->redirect('this');
		}
		$state = $this->usersManager->savePassword($values, $user->getId());
		if ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Heslo bylo změněno.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání hesla.'), 'danger');
		}
		$this->redirect('Homepage:');
	}

	public function userFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		$user = $this->getUser();
		if ($values->id == '')
			unset($values->id);
		if ($values->password == '')
			unset($values->password);
		$state = $this->usersManager->save((array)$values);
		if ($state == 'inserted') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Uživatel byl přidán.'), 'success');
		} elseif ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Uživatel byl změněn.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání uživatele.'), 'danger');
		}
		if ($page > 1)
			$this->redirect('User:Show', array('vp-page' => $page));
		else
			$this->redirect('User:Show');
	}
	
	public function handleDelete($id)
	{
		$this->usersManager->deleteById($id);
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Uživatel byl smazán.'), 'success');
		$this->redirect('this');
	}

}
