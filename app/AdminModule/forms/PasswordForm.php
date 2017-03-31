<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer;

class PasswordForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addPassword('oldPassword', 'Staré heslo', 30)
			->addRule(Form::FILLED, 'Je nutné zadat staré heslo.');
		$this->addPassword('newPassword', 'Nové heslo', 30)
			->addRule(Form::REQUIRED, 'Je nutné zadat nové heslo.')
			->addRule(Form::MIN_LENGTH, 'Nové heslo musí mít alespoň %d znaků.', 6);
		$this->addPassword('confirmPassword', 'Potvrzení hesla', 30)
			->addRule(Form::REQUIRED, 'Nové heslo je nutné zadat ještě jednou pro potvrzení.')
			->addRule(Form::EQUAL, 'Zadná hesla se neshodují!', $this['newPassword']);
		$this->addSubmit('ok', 'Změnit heslo');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

