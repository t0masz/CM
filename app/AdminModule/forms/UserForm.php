<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer;

class UserForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('name', 'Jméno', 30);
		$this->addText('username', 'Uživatelské jméno', 30);
		$this->addText('email', 'E-mail', 30);
		$this->addSelect('role', 'Práva', array('admin' => 'Admin','redaktor' => 'Redaktor'))
			->addRule(Form::FILLED, 'Musíte vybrat uživatelská práva!');
		$this->addPassword('password', 'Nové heslo', 30);
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

