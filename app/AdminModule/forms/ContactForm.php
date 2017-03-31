<?php

namespace App\AdminModule\Forms;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer;

class ContactForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		
		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addTextArea('vzkaz', 'Vzkaz')
			->addRule(Form::FILLED, 'Vyplň prosím vzkaz');
		$this->addSubmit('ok', 'Odeslat vzkaz');
		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

