<?php

namespace App\FrontModule;

use Nette\Application\BadRequestException;

class PagePresenter extends DefaultPresenter
{
	public function renderShowPage($id)
	{
		$page = $this->pagesManager->getBy(array('url'=>$id));
		if ($page !== false) {
			$this->template->obsah = $page;
		} else {
			throw new BadRequestException('Stránka nenalezena');
		}
	}
}
