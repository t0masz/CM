<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer;

class PicturesForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('file', 'Jméno souboru');
		$this->addText('title', 'Název');
		$this->addSelect('position', 'Pozice', array('head'=>'fotka v hlavičce','gallery'=>'upoutávka v boxíku na fotogalerii'));
		$this->addSelect('active', 'Stav', array(0=>'neaktivní',1=>'aktivní'));
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

