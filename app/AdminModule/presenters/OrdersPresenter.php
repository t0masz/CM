<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html;

class OrdersPresenter extends SecuredPresenter
{

	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->shopManager->getCountAllOrders();
		$this->template->page = $paginator->page;
		$this->template->orders = $this->shopManager->findAllOrders()->order('sent DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val =  $this->shopManager->findOrderBy(array('id'=>$id))->fetch();
			$this['ordersForm']->setDefaults($val);
			if ($page>1) $this['ordersForm']->setDefaults(['page' => $page]);
		}
	}

	protected function createComponentOrdersForm($name)
	{
		$form = new OrdersForm($this, $name);
		$form->onSuccess[] = array($this, 'ordersFormSubmitted');
		return $form;
	}
	
	public function ordersFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		$state = $this->shopManager->saveOrderState($values);
		if ($state == true) {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Objednávka byla upravena.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání objednávky.'), 'danger');
		}
		
		if ($page > 1)
			$this->redirect('Orders:Show', array('vp-page' => $page));
		else
			$this->redirect('Orders:Show');
	}
	
	public function handleDelete($id)
	{
			$this->shopManager->deleteOrderById($id);
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Objednávka byla smazána.'), 'success');
			$this->redirect('this');
	}

}
