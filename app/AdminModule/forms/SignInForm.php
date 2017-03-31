<?php

namespace App\AdminModule\Forms;

use Nette\Application\UI\Form;

class SignInForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addText('username', 'Uživatelské jméno', 30, 20);
		$this->addPassword('password', 'Heslo', 30);
		$this->addSubmit('login', 'Přihlásit se');
	}
	
}

