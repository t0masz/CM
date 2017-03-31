<?php
namespace App\AdminModule;

use Acl\Security;

abstract class SecuredPresenter extends BasePresenter
{
	protected function startup()
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect('Sign:in');
		}
		if (!$this->user->isAllowed($this->name, $this->action)) {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Nepovolený přístup.'), 'danger');
			$this->redirect('Homepage:');
		}
	}
}
