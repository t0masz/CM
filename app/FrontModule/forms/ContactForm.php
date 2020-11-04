<?php

namespace App\FrontModule\Forms;

use Nette\Application\UI\Form;
use Nette\Forms\Controls;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;
use Nette\Utils\Html;

class ContactForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL) {
		parent::__construct($parent, $name);
		$this->buildForm();
	}
	
	protected function buildForm() {
		$this->addText('name', 'Jméno', 53)
			->addRule(Form::REQUIRED, 'Vyplňte, prosím, Vaše jméno');
		$this->addText('email', 'E-mail', 53)
			->setType('email')
			->setDefaultValue('@')
			->addRule(Form::REQUIRED, 'Vyplňte, prosím, Váš e-mail')
			->addRule(Form::EMAIL, 'Zadaný e-mail má chybný formát!');
		$this->addTextArea('message', 'Vzkaz')
			->addRule(Form::REQUIRED, 'Vyplňte, prosím, vzkaz');
//		$this->addAntiSpam("mail2", 120, 15);
		$this->addSubmit('ok', 'Odeslat vzkaz');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

