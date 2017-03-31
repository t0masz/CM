<?php

namespace App\FrontModule;

use Nette;
use VisualPaginator;

class DefaultPresenter extends Nette\Application\UI\Presenter
{
	/** @var \Model\ImagesRepository @inject */
	public $imagesRepository;

	/** @var \Model\ConcertsManager @inject */
	public $concertsManager;

	/** @var \Model\PagesManager @inject */
	public $pagesManager;

	/** @var \Model\SetupManager */
	protected $config;

	public function __construct(\Model\SetupManager $setup)
	{
		$this->config = $setup;
	}

	public function beforeRender()
	{
		$this->template->pictures = $this->imagesRepository->findBy(array('active'=>1,'position'=>'head'))->order('id')->limit($this->config->items['header']);
		$images = $this->imagesRepository->findBy(array('active'=>1,'position'=>'gallery'))->order('id DESC')->limit(1)->fetch();
		$image = rand(1, $images->id);
		$this->template->gallery = $this->imagesRepository->findBy(array('active'=>1,'position'=>'gallery','id >= ?'=>$image))->limit(1)->fetch();
	}

	public function handleSignOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Právě jste se odlásili.');
		$this->redirect('this');
	}
    
	/* custom components */
	public function createComponentActual()
	{
		return new Actual($this->concertsManager, $this->config->items['actions_box']);
	}
	public function createComponentMenu()
	{
		return new Menu($this->pagesManager);
	}

    /**
     * Create items paginator
     *
     * @return VisualPaginator
     */
	public function createComponentVp($name)
	{
		$visualPaginator = new VisualPaginator($this, $name);
		$visualPaginator->getPaginator()->itemsPerPage = $this->config->paging['administration'];
		return $visualPaginator;
	}
}
