<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class ShopPresenter extends SecuredPresenter
{
	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->shopManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->items = $this->shopManager->findAll()->order('title')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->shopManager->findBy(array('id'=>$id))->fetch();
			$this['shopForm']->setDefaults($val);
			if ($page>1) $this['shopForm']->setDefaults(['page' => $page]);
		}
	}

	protected function createComponentShopForm($name)
	{
		$articles = $this->articlesManager->findAll()->order('created DESC');
		$aarticles = array();
		$aarticles[''] = '';
		foreach ($articles as $article) {
			$aarticles[$article->url] = date('j.n.Y',strtotime($article->created)) . ' | ' . $article->title;
		}
		$form = new ShopForm($this, $name, $aarticles);
		$form->onSuccess[] = array($this, 'shopFormSubmitted');
		return $form;
	}
	
	public function shopFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		$state = $this->shopManager->save((array)$values);
		if ($state == 'inserted') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Položka byla přidána.'), 'success');
		} elseif ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Položka byla změněna.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání položky.'), 'danger');
		}
		if ($page > 1)
			$this->redirect('Shop:Show', array('vp-page' => $page));
		else
			$this->redirect('Shop:Show');
	}
	
	public function handleDelete($id)
	{
		$this->shopManager->deleteById($id);
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Položka byla smazána.'), 'success');
		$this->redirect('this');
	}

}
