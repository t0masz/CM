<?php

namespace App\AdminModule;

use Nette\Application\UI\Form;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;

class RubricsForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('url', 'URL')
			->addRule(Form::FILLED, 'Musíte vyplnit URL zkratku!');
		$this->addText('name', 'Název')
			->addRule(Form::FILLED, 'Musíte vyplnit název!');
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

