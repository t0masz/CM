<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls,
	Nette\Utils\Strings,
	Nette\Utils\ArrayHash,
	Nette\Utils\Html,
	Nette\Image;


class ImagesPresenter extends BasePresenter
{
	public function renderShow($id)
	{
		$vp = $this['vp'];
		$paginator = $vp->getPaginator();
		$paginator->itemCount = $this->imagesManager->getCountAll();
		$this->template->page = $paginator->page;
		$this->template->pictures = $this->imagesManager->findAll()->limit($paginator->itemsPerPage,$paginator->offset);
	}

	public function actionEdit($id,$page)
	{
		if ($id > 0) {
			$val = $this->imagesManager->findBy(array('id'=>$id))->fetch();
			$this['picturesForm']->setDefaults($val);
			if ($page>1) $this['picturesForm']->setDefaults(['page' => $page]);
		}
	}

	public function handleImport(){
		$srcDir = realpath( __DIR__ . '/../../../data/import/');
		$dstDir = realpath( __DIR__ . '/../../../data/images/grafika/');

		if ($handle = opendir($srcDir)) {
			$i = 0;
			$values = array('file' => '', 'title' => '', 'position' => '', 'active' => '');
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if (($i<=5)) {
						$values['file'] = $file;
						$values['title'] = '';
						$values['position'] = 'head';
						$values['active'] = 0;
						copy($srcDir.'/'.$file, $dstDir.'/'.$file);
						unlink($srcDir.'/'.$file);
						$this->imagesManager->save($values);
						$i++;
					}
				}
			}
			closedir($handle);
			if ($i>0)
				$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Fotky ('.$i.') byly přidány.'), 'success');
			$this->redirect('Images:Show');
		}
	}

	protected function createComponentPicturesForm($name)
	{
		$form = new PicturesForm($this, $name);
		$form->onSuccess[] = array($this, 'picturesFormSubmitted');
		return $form;
	}
	
	public function picturesFormSubmitted(Form $form, ArrayHash $values)
	{
		$page = $values->page ? $values->page : '';
		unset($values->page);
		$state = $this->imagesManager->save((array)$values);
		if ($state == 'inserted') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Obrázek byl přidán.'), 'success');
		} elseif ($state == 'updated') {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Obrázek byl změněn.'), 'success');
		} else {
			$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-exclamation-sign"></span> Chyba při ukládání obrázku.'), 'danger');
		}
		if ($page > 1)
			$this->redirect('Images:Show', array('vp-page' => $page));
		else
			$this->redirect('Images:Show');
	}
	
	public function handleDelete($id)
	{
		$picture = $this->imagesManager->findBy(array('id'=>$id))->fetch();
		$file = realpath( __DIR__ . '/../../../data/images/grafika/'.$picture->file);
		if ($file) 
			unlink($file);
		$picture = $this->imagesManager->deleteById($id);
		$this->flashMessage(Html::el()->setHtml('<span class="glyphicon glyphicon-ok"></span> Fotka byla smazána.'), 'success');
		$this->redirect('this');
	}

}
