<?php

namespace App\FrontModule;

use App,
	Nette\Application\BadRequestException;
use IPub\VisualPaginator\Components as VisualPaginator;

class ArticlesPresenter extends DefaultPresenter
{
	/** @var \Model\ArticlesManager @inject */
	public $articlesManager;


	public function renderShowHome($id = '')
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->config->paging['articles'];
		$paginator->itemCount = $this->articlesManager->getCount(array('hp'=>1));
		$this->template->addFilter('tags', 'Helpers::tags');
		$this->template->pagecontent = $this->pagesManager->getBy(array('typ'=>'Articles:showHome'));
		$this->template->articles = $this->articlesManager->findBy(array('created <= ?' => date('Y-m-d H:i:s'), 'hp' => 1))->order('created DESC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function renderShowRubric($id)
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->config->paging['articles'];
		$paginator->itemCount = $this->articlesManager->getCount(array('rubric'=>$id));
		$rubric = $this->pagesManager->getBy(array('rubric'=>$id));
		if ($rubric) {
			$this->template->addFilter('tags', 'Helpers::tags');
			$this->template->pagecontent = $rubric;
			$this->template->articles = $this->articlesManager->findBy(array('created <= ?' => date('Y-m-d H:i:s'), 'rubric' => $id))->order('created DESC')->limit($paginator->itemsPerPage,$paginator->offset);
		} else {
			throw new BadRequestException('Rubrika nenalezena');
		}
	}

	public function renderShowArticle($id)
	{
		$article = $this->articlesManager->getBy(array('url'=>$id));
		if ($article !== false) {
			$this->template->article = $article;
		} else {
			throw new BadRequestException('Článek nenalezen');
		}
	}

}
