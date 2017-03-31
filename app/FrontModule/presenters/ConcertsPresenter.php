<?php

namespace App\FrontModule;

class ConcertsPresenter extends DefaultPresenter
{
	public function renderShow()
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemsPerPage = $this->config->paging['concerts'];
		$paginator->itemCount = $this->concertsManager->getCount(array('date_from >= ?'=>date('Y-m-d')));
		$this->template->addFilter('interval', 'Helpers::interval');
		$this->template->title = "Plánované akce";
		$this->template->concerts = $this->concertsManager->findBy(array('date_from >= ?'=>date('Y-m-d')))->order('date_from ASC')->limit($paginator->itemsPerPage,$paginator->offset);
	}

}
