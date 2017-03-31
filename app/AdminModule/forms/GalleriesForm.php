<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer;

class GalleriesForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('name', 'Název galerie')
			->addRule(Form::FILLED, 'Musíte vyplnit název!');
		$this->addTextArea('content', 'Popis');
		$this->addText('picture', 'Název náhledové fotky')
			->getControlPrototype()->onfocus("openKCFinder_singleFile(this); this.onfocus = null");
		$this->addText('url', 'Odkaz')
			->addRule(Form::FILLED, 'Musíte vyplnit Odkaz!');
		$this->addText('order', 'Pořadí');
		$this->addSubmit('ok', 'Uložit')
			->setAttribute('class', 'btn btn-primary');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

