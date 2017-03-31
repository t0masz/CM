<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class GalleriesPresenter extends SecuredPresenter
{

	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->galleriesManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->galleries = $this->galleriesManager->findAll()->order('create DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->galleriesManager->findBy(array('id'=>$id))->fetch();
			$this['galleriesForm']->setDefaults($val);
		}
	}

	protected function createComponentGalleriesForm($name)
	{
		$form = new GalleriesForm($this, $name);
		$form->onSuccess[] = array($this, 'galleriesFormSubmitted');
		return $form;
	}
	
	public function galleriesFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		$values->order = $values->order ? $values->order : '0';
		unset($values->page);
		$state = $this->galleriesManager->save((array)$values);
		if ($state == 'inserted') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Galerie byla přidána.'), 'success');
		} elseif ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Galerie byla změněna.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání galerie.'), 'danger');
		}
		if ($page > 1)
			$this->redirect('Galleries:Show', array('vp-page' => $page));
		else
			$this->redirect('Galleries:Show');
	}
	
	public function handleDelete($id)
	{
		$this->galleriesManager->deleteByID($id);
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Galerie byla smazána.'), 'success');
		$this->redirect('this');
	}

}
