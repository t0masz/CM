<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class ConcertsPresenter extends SecuredPresenter
{

	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->concertsManager->getCountAll();
		$this->template->addFilter('interval', 'Helpers::interval');
		$this->template->page = $paginator->page;
		$this->template->concerts = $this->concertsManager->findAll()->order('date_from DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->concertsManager->findBy(array('id'=>$id))->fetch();
			$this['concertsForm']->setDefaults($val);
			if ($page>1) $this['concertsForm']->setDefaults(['page' => $page]);
		}
	}

	protected function createComponentConcertsForm($name)
	{
		$articles = $this->articlesManager->findAll()->order('created DESC');
		$aarticles = array();
		foreach ($articles as $article) {
			$aarticles[$article->url] = date('j.n.Y',strtotime($article->created)) . ' | ' . $article->title;
		}
		$form = new ConcertsForm($this, $name, $aarticles);
		$form->onSuccess[] = array($this, 'concertsFormSubmitted');
		return $form;
	}
	
	public function concertsFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		if (empty($values->id)) {
			unset($values->id);
		}
		$state = $this->concertsManager->save((array)$values);
		if ($state == 'inserted')
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Akce byla přidána na web.'), 'success');
		elseif ($state == 'updated')
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Akce byla upravena.'), 'success');
		else
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání akce.'), 'danger');
		if ($page > 1)
			$this->redirect('Concerts:Show', array('vp-page' => $page));
		else
			$this->redirect('Concerts:Show');
	}
	
	public function handleDelete($id)
	{
		$this->concertsManager->findBy(array('id'=>$id))->delete();
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Akce byla smazána.'), 'success');
		$this->redirect('this');
	}

}
