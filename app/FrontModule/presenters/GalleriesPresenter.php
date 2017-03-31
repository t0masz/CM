<?php

namespace App\FrontModule;

class GalleriesPresenter extends DefaultPresenter
{
	/** @var \Model\GalleriesManager @inject */
	public $galleriesManager;

	/** @var \Model\VideosManager @inject */
	public $videosManager;

	public function renderShowGalleries()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->config->paging['galleries'];
		$paginator->itemCount = $this->galleriesManager->getCount(array());
		$this->template->pagecontent = $this->pagesManager->getBy(array('typ'=>'Galleries:show'));
		$this->template->galleries = $this->galleriesManager->findAll()->order('order DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function renderShowVideos()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->config->paging['videos'];
		$paginator->itemCount = $this->videosManager->getCount(array());
		$this->template->pagecontent = $this->pagesManager->getBy(array('typ'=>'Galleries:showVideo'));
		$this->template->videos = $this->videosManager->findAll()->order('id DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

}
