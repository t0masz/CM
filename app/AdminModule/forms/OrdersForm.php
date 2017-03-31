<?php

namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nextras\Forms\Rendering\Bs3FormRenderer;

class OrdersForm extends Form {
	
	public function __construct($parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);

		$this->buildForm();
	}
	
	protected function buildForm()
	{
		$this->addHidden('id');
		$this->addHidden('page');
		$this->addText('sent', 'Odesláno')->setDisabled();
		$this->addText('name', 'Jméno')->setDisabled();
		$this->addText('mail', 'E-mail')->setDisabled();
		$this->addText('address', 'Adresa')->setDisabled();
		$this->addText('contact', 'Kontakt')->setDisabled();
		$this->addTextArea('items', 'Položky')->setDisabled();
		$this->addText('price', 'Cena')->setDisabled();
		$this->addText('delivery', 'Způsob doručení')->setDisabled();
		$this->addSelect('state','Stav', array(
				'wait' => 'čeká na vyřízení',
				'ok' => 'Vyřízeno',
				'canceled' => 'Zrušeno',
			));
		$this->addSubmit('ok', 'Uložit');

		$this->setRenderer(new Bs3FormRenderer());
	}
	
}

