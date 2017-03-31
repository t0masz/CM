<?php

namespace App\FrontModule;

use Nette\Application\UI\Control,
	Model;

class Actual extends Control
{
	private $manager;
	private $pagination;

	public function __construct(Model\ConcertsManager $manager, $pagination)
	{
		parent::__construct();
		$this->manager = $manager;
		$this->pagination = $pagination;
	}

	public function render()
	{
		$this->template->addFilter('interval', 'Helpers::interval');
		$this->template->setFile(__DIR__ . '/Actual.latte');
		$this->template->concerts = $this->manager->findBy(array('date_from >= ?' => date('Y-m-d')))->order('date_from')->limit($this->pagination);
		$this->template->render();
	}

}
