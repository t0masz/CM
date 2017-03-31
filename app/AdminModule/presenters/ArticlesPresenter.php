<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Utils\Strings,
	Nette\Utils\Html,
	Nette\Utils\ArrayHash,
	Nette\Forms\Controls;

class ArticlesPresenter extends SecuredPresenter
{

	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->articlesManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->articles = $this->articlesManager->findAll()->order('created DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->articlesManager->findBy(array('id'=>$id))->fetch();
			$this['articlesForm']->setDefaults($val);
			if ($page>1) $this['articlesForm']->setDefaults(['page' => $page]);
		}
	}

	protected function createComponentArticlesForm($name)
	{
		$rubrics = $this->articlesManager->findAllRubrics();
		$arubrics = array();
		foreach ($rubrics as $rubric) {
			$arubrics[$rubric->url] = $rubric->name;
		}
		$form = new ArticlesForm($this, $name, $arubrics);
		$form->onSuccess[] = array($this, 'articlesFormSubmitted');
		return $form;
	}
	
	public function articlesFormSubmitted(Form $form, ArrayHash $values)
	{
		$values->url = $values->url ? $values->url : Strings::webalize($values->title);
		$page = $values->page ? $values->page : '';
		unset($values->page);
		if (empty($values->id)) {
			unset($values->id);
			$values->created = $values->created=='' ? date('Y-m-d H:i:s') : $values->created;
		}
		$state = $this->articlesManager->save((array)$values);
		if ($state == 'inserted')
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Článek byl přidán na web.'), 'success');
		elseif ($state == 'updated')
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Článek byl upraven.'), 'success');
		else
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání článku.'), 'danger');

		if ($page > 1)
			$this->redirect('Articles:Show', array('vp-page' => $page));
		else
			$this->redirect('Articles:Show');

	}
	
	public function handleDelete($id)
	{
		$this->articlesManager->findBy(array('id'=>$id))->delete();
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Článek byl smazán.'), 'success');
		$this->redirect('this');
	}

}
