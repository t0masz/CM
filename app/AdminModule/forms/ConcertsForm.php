<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer,
	Vodacek\Forms\Controls\DateInput;

class ConcertsForm extends Form {
	
	private $articles;

	public function __construct($parent = NULL, $name = NULL, $articles = array())
	{
		parent::__construct($parent, $name);

		$this->articles = $articles;
		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addDate('date_from', 'Od', DateInput::TYPE_DATE)
			->addRule(Form::FILLED, 'Zadej prosím datum!')
			->setAttribute('class', 'form-control');
		$this->addDate('date_to', 'Do', DateInput::TYPE_DATE)
			->setAttribute('class', 'form-control');
		$this->addDate('time_from', 'Začátek', DateInput::TYPE_TIME)
			->setAttribute('class', 'form-control');
		$this->addText('title', 'Název')
			->addRule(Form::FILLED, 'Zadej prosím název akce!');
		$this->addTextArea('content', 'Obsah')->getControlPrototype()->class('ckeditor');
		$this->addSelect('url','Odkaz', $this->articles)
			->setPrompt('Vyber odkazovaný článek');
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

