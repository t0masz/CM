<?php

namespace App\AdminModule;

use Model;
use IPub\VisualPaginator\Components as VisualPaginator;

abstract class BasePresenter extends \Nette\Application\UI\Presenter
{
	/** @var Model\ArticlesManager @inject */
	public $articlesManager;
	/** @var Model\ConcertsManager @inject */
	public $concertsManager;
	/** @var Model\GalleriesManager @inject */
	public $galleriesManager;
	/** @var Model\ImagesManager @inject */
	public $imagesManager;
	/** @var Model\PagesManager @inject */
	public $pagesManager;
	/** @var Model\ShopManager @inject */
	public $shopManager;
	/** @var Model\VideosManager @inject */
	public $videosManager;
	/** @var Model\LogManager @inject */
	public $logManager;
	/** @var Model\UsersManager  @inject*/
	public $usersManager;
	/** @var Model\SetupManager @inject */
	public $config;

	public function handleSignOut()
	{
	    $this->getUser()->logout();
	    $this->redirect('Sign:in');
	}

	public function createComponentVp($name)
	{
		$visualPaginator = new VisualPaginator\Control;
        $visualPaginator->disableAjax();
		$visualPaginator->setTemplateFile('bootstrap.latte');
		$visualPaginator->getPaginator()->itemsPerPage = $this->config->paging['administration'];
		return $visualPaginator;
	}
}
