<?php

namespace App\FrontModule;

use Nette\Application\UI\Control,
	Model;

class Menu extends Control
{
	private $manager;

	public function __construct(Model\PagesManager $manager)
	{
//		parent::__construct(); # vždy je potřeba volat rodičovský konstruktor
		$this->manager = $manager;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/Menu.latte');
		$this->template->items = $this->manager->findBy(array('menu'=>1))->order('order');
		$this->template->render();
	}

}
