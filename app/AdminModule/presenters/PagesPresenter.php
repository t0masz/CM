<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class PagesPresenter extends SecuredPresenter
{

	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->pagesManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->pages = $this->pagesManager->findAll()->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->pagesManager->findBy(array('id'=>$id))->fetch();
			$this['pagesForm']->setDefaults($val);
			if ($page>1) $this['pagesForm']->setDefaults(['page' => $page]);
		}
	}

	protected function createComponentPagesForm($name)
	{
		$rubrics = $this->articlesManager->findAllRubrics();
		$arubrics = array();
		foreach ($rubrics as $rubric) {
			$arubrics[$rubric->url] = 'Rubric:'.$rubric->name;
		}
		$form = new PagesForm($this, $name, $arubrics);
		$form->onSuccess[] = array($this, 'pagesFormSubmitted');
		return $form;
	}
	
	public function pagesFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		$state = $this->pagesManager->save((array)$values);
		if ($state == 'inserted') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Stránka byla přidána.'), 'success');
		} elseif ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Stránka byla změněna.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání stránky.'), 'danger');
		}
		if ($page > 1)
			$this->redirect('Pages:Show', array('vp-page' => $page));
		else
			$this->redirect('Pages:Show');
	}
	
	public function handleDelete($id)
	{
			$this->pagesManager->deleteById($id);
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Stránka byla smazána.'), 'success');
			$this->redirect('this');
	}

}
