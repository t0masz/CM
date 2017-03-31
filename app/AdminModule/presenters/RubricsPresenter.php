<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class RubricsPresenter extends SecuredPresenter
{
	public function renderShow() {
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->articlesManager->getCountAllRubrics();
		$this->template->page = $paginator->page;
		$this->template->rubrics = $this->articlesManager->findAllRubrics()->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->articlesManager->findRubricsBy(array('id'=>$id))->fetch();
			$this['rubricsForm']->setDefaults($val);
			if ($page>1) $this['rubricsForm']->setDefaults(['page' => $page]);
		}
	}

	protected function createComponentRubricsForm($name)
	{
		$form = new RubricsForm($this, $name);
		$form->onSuccess[] = array($this, 'rubricsFormSubmitted');
		return $form;
	}
	
	public function rubricsFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		$state = $this->articlesManager->saveRubric((array)$values);
		if ($state == 'inserted') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Rubrika byla přidána.'), 'success');
		} elseif ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Rubrika byla změněna.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání rubriky.'), 'danger');
		}
		if ($page > 1)
			$this->redirect('Rubrics:Show', array('vp-page' => $page));
		else
			$this->redirect('Rubrics:Show');
	}
	
	public function handleDelete($id)
	{
		$this->articlesManager->deleteRubricById($id);
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Rubrika byla smazána.'), 'success');
		$this->redirect('this');
	}

}
