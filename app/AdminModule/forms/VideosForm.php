<?php

namespace App\AdminModule;

use Nette\Application\UI\Form;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;

class VideosForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('title', 'Název videa')
			->addRule(Form::FILLED, 'Musíte vyplnit název!');
		$this->addTextArea('code', 'Kód')
			->addRule(Form::FILLED, 'Musíte vyplnit kód!');
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

