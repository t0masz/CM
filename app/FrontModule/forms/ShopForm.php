<?php

namespace App\FrontModule\Forms;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nextras\Forms\Rendering\Bs3FormRenderer,
    Nette\Utils\Html,
	Model;

class ShopForm extends Form {

	/** @var ShopManager */
	private $shopManager;
	
	/** @var SetupManager */
	private $config;
	
	public function __construct($parent = NULL, $name = NULL, Model\ShopManager $manager, Model\SetupManager $config) {
		parent::__construct($parent, $name);
		$this->shopManager = $manager;
		$this->config = $config;
		$this->buildForm($parent);
	}
	
	protected function buildForm($parent) {
		$this->addGroup('Výběr CD',true);
		$zbozi = $this->shopManager->findAll()->order('title');
		foreach ($zbozi as $id => $row) {
			$popis = Html::el("p", $row->title);
			if ($row->pack) $popis->class('balicek');
			if ($row->picture) {
				if (!$row->url)
					$popis->addHtml(Html::el("img")->src($row->picture)->class('shopimg'));
				else
					$popis->addHtml(Html::el("a")->href('/clanek/'.$row->url.'.html')->setHtml("<img src='$row->picture' class='shopimg' />"));
			}
			$this->addGroup($row->id)
				->setOption("description", $popis);
			$this->addText('item'.$id, 'Počet kusů')
				->setType('number')
				->setOption('description', $row->price.' Kč/ks')
				->addCondition(Form::FILLED)
				->addRule(Form::RANGE, 'Počet kusů musí být větší než jedna.', array(1, NULL));
		}
		$popis = Html::el("p", "Uvedená cena je za jeden kus či jednu kombinaci. K výsledné ceně je připočítáno balné {$this->config->postage['packing']} Kč a poštovné {$this->config->postage['transfer']} Kč (při platbě převodem) nebo {$this->config->postage['cash_on_delivery']} Kč (při platbě na dobírku).");
		$popis->class('info');
		$this->addGroup($row->id)
			->setOption("description", $popis);
		$this->addHidden('item0');

		$this->addGroup('Osobní údaje');
		$this->addText('jmeno', 'Jméno')
			->addRule(Form::FILLED, 'Vyplňte Vaše jméno');
		$this->addText('prijmeni', 'Příjmení')
			->addRule(Form::FILLED, 'Vyplňte Vaše příjmení');
		$this->addText('adresa', 'Ulice a číslo domu')
			->addRule(Form::FILLED, 'Vyplňte Vaši adresu');
		$this->addText('obec', 'Obec')
			->addRule(Form::FILLED, 'Vyplňte obec');
		$this->addText('psc', 'PSČ')
			->addRule(Form::FILLED, 'Vyplňte PSČ');
		$this->addText('telefon', 'Telefon')
			->addRule(Form::FILLED, 'Vyplňte Váš telefon');
		$this->addText('email', 'E-mail')
			->setDefaultValue('@')
			->addRule(Form::FILLED, 'Vyplňte Váš e-mail')
			->addRule(Form::EMAIL, 'E-mail není korektně vyplněn!')
			->setOption('description', 'Zkontrolujte si zadaný e-mail, zda je správný.');
		$prevod = (int)$this->config->postage['transfer']+(int)$this->config->postage['packing'];
		$dobirka = (int)$this->config->postage['cash_on_delivery']+(int)$this->config->postage['packing'];
		$options = array(
			'prevod' => "Převod (poštovné a balné {$prevod} Kč)",
			'dobirka' => "Dobírka (poštovné a balné {$dobirka} Kč)",
			'osobne'  => 'Osobně',
		);
		$this->addSelect('doruceni', 'Způsob doručení',$options)
			->addRule(Form::FILLED, 'Musíte zvolit způsob doručení')
			->setPrompt('Zvolte způsob doručení');
		$this->addTextArea('poznamka', 'Poznámka');
		$this->addAntiSpam("mail2", 120, 15);
		$this->addSubmit('ok', 'Odeslat objednávku');
		
		$this->setRenderer(new Bs3FormRenderer());
		
		// setup form rendering
		$renderer = $this->getRenderer();
		$renderer->wrappers['control']['container'] = 'div class=col-sm-9';
		$renderer->wrappers['label']['container'] = 'div class="col-sm-3 control-label"';
	}
	
}

