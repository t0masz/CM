<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class VideosPresenter extends SecuredPresenter
{
	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->videosManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->videos = $this->videosManager->findAll()->order('id DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->videosManager->findBy(array('id'=>$id))->fetch();
			$this['videosForm']->setDefaults($val);
		}
	}

	protected function createComponentVideosForm($name)
	{
		$form = new VideosForm($this, $name);
		$form->onSuccess[] = array($this, 'videosFormSubmitted');
		return $form;
	}
	
	public function videosFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		$state = $this->videosManager->save((array)$values);
		if ($state == 'inserted') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Video bylo přidáno.'), 'success');
		} elseif ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Video bylo změněno.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání videa.'), 'danger');
		}
		if ($page > 1)
			$this->redirect('Videos:Show', array('vp-page' => $page));
		else
			$this->redirect('Videos:Show');
	}
	
	public function handleDelete($id)
	{
		$this->videosManager->deleteByID($id);
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Video bylo smazáno.'), 'success');
		$this->redirect('this');
	}

}
