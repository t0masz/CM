<?php

namespace App\AdminModule;

use Nette\Application\UI\Form;
use Nextras\FormsRendering\Renderers\Bs3FormRenderer;

class ShopForm extends Form {
	
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
		$this->addText('title', 'Název položky')
			->addRule(Form::FILLED, 'Musíte vyplnit název!');
		$this->addText('picture', 'Název náhledové fotky')
			->getControlPrototype()->onclick("openKCFinder_singleFile(this)");
		$this->addSelect('url','Odkaz', $this->articles)
			->setPrompt('Vyber odkazovaný článek');
		$this->addText('price', 'Cena')
			->addRule(Form::FILLED, 'Musíte vyplnit cenu!');
		$this->addCheckbox('pack', 'Výhodný balíček');
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

