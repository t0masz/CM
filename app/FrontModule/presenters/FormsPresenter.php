<?php

namespace App\FrontModule;

use Nette\Mail\Message,
	Nette\Mail\SmtpMailer,
	Nette\Forms\Controls\SubmitButton,
	Nette\Utils\ArrayHash,
	Nette\Application\UI\Form,
	App\FrontModule\Forms,
	Model;

class FormsPresenter extends DefaultPresenter
{

	/** @var Model\FormManager @inject */
	public $formManager;

	/** @var Model\ShopManager @inject */
	public $shopManager;

	/** @var Model\SetupManager @inject */
	public $config;


	public function renderShowContact($id)
	{
		$this->template->title = 'Kontaktní formulář';
	}

	protected function createComponentContactForm($name)
	{
		$form = new Forms\ContactForm($this, $name);
		$form->onSuccess[] = array($this, 'contactFormSucceeded');
		return $form;
	}

	public function contactFormSucceeded(Form $form, ArrayHash $values)
	{
		$result = $this->formManager->sendContactMessage($values);
		if ($result === TRUE) {
			$this->flashMessage('Děkujeme za zaslání vzkazu.', 'success');
		} else {
			$this->flashMessage('Došlo k chybě při odeslání vzkazu. Zkuste jej odeslat později znovu.', 'danger');
		}
		$this->redirect('this');
	}

	public function renderShowShop($id)
	{
		$this->template->title = 'Objednávka CD';
	}

	protected function createComponentShopForm($name)
	{
		$form = new Forms\ShopForm($this, $name, $this->shopManager, $this->config);
		$form->onSuccess[] = array($this, 'shopFormSucceeded');
		return $form;
	}

	public function shopFormSucceeded(Form $form, ArrayHash $values)
	{
		$result = $this->shopManager->saveOrder($values);
		if ($result === TRUE) {
			$this->flashMessage('Děkujeme za odeslání objednávky. Ozveme se Vám zpět.', 'success');
		} else {
			$this->flashMessage('Došlo k chybě při odeslání objednávky. Zkuste ji odeslat později znovu.', 'danger');
		}
		$this->redirect('this');
	}

}
