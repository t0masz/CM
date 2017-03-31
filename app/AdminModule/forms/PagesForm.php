<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
    Nextras\Forms\Rendering\Bs3FormRenderer;

class PagesForm extends Form {
	
	private $rubrics;

	public function __construct($parent = NULL, $name = NULL, $rubrics = array())
	{
		parent::__construct($parent, $name);

		$rubrics['Contact'] = 'Form:Kontakt';
		$rubrics['Shop'] = 'Form:Shop';
		$this->rubrics = $rubrics;
		
		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('url', 'URL');
		$this->addCheckbox('menu', 'Zobrazit v menu');
		$this->addText('order', 'Pořadí');
		$this->addText('titulek', 'Titulek')
			->addRule(Form::FILLED, 'Prosím, vyplň titulek!');
		$this->addText('nadpis', 'Nadpis')
			->addRule(Form::FILLED, 'Prosím, vyplň nadpis!');
		$this->addSelect('typ','Typ', array(
				'Page:showPage' => 'Stránka s obsahem',
				'Articles:showHome' => 'Úvodní stránka',
				'Articles:showRubric' => 'Články z rubriky',
				'Galleries:show' => 'Galerie',
				'Galleries:showVideo' => 'Videa',
				'Concerts:show' => 'Koncerty',
				'Forms:show' => 'Formulář',
			))
			->setPrompt('Vyber typ')
			->addRule(Form::FILLED, 'Prosím, vyber typ stránky!');
		$this->addSelect('rubric','Rubrika', $this->rubrics)
			->setPrompt('Vyber rubriku');
		$this->addTextArea('obsah', 'Text')->getControlPrototype()->class('ckeditor');
		$this->addCheckbox('system', 'Systémová stránka');
		$this->addSubmit('ok', 'Uložit');
		
		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

