<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer,
	Vodacek\Forms\Controls\DateInput;

class ArticlesForm extends Form {
	
	private $rubrics;

	public function __construct($parent = NULL, $name = NULL, $rubrics = array())
	{
		parent::__construct($parent, $name);
		
		$this->rubrics = $rubrics;
		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('title', 'Název článku')
			->addRule(Form::FILLED, 'Zadej prosím název článku!');
		$this->addSelect('rubric','Rubrika', $this->rubrics)
			->setPrompt('Vyber rubriku')
			->addRule(Form::FILLED, 'Vyber rubriku!');
		$this->addCheckbox('hp', 'Zobrazit na HP');
		$this->addText('url', 'URL')
			->addRule(Form::FILLED, 'Zadej prosím URL zkratku!');
		$this->addTextArea('content', 'Obsah')->getControlPrototype()->class('ckeditor');
		$this->addDate('created', 'Publikuj', DateInput::TYPE_DATETIME)
			->setAttribute('class', 'form-control');
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

