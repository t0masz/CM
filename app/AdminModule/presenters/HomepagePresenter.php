<?php
namespace App\AdminModule;

use Nette\Mail\Message,
	Nette\Mail\IMailer,
	Nette\Forms\Controls,
	Nette\Utils\ArrayHash,
	Nette\Application\UI\Form,
	App\AdminModule\Forms;

class HomepagePresenter extends SecuredPresenter
{

	/** @var IMailer */
	private $mailer;

	public function __construct(IMailer $mailer)
	{
		$this->mailer = $mailer;
	}


	public function renderDefault()
	{
		$this->template->users = $this->usersManager->getCountAll();
		$this->template->concerts = $this->concertsManager->getCountAll();
		$this->template->articles = $this->articlesManager->getCountAll();
		$this->template->rubrics = $this->articlesManager->getCountAllRubrics();
		$this->template->galleries = $this->galleriesManager->getCountAll();
		$this->template->videos = $this->videosManager->getCountAll();
		$this->template->items = $this->shopManager->getCountAll();
		$this->template->orders = $this->shopManager->getCountAllOrders();
		$this->template->pages = $this->pagesManager->getCountAll();
	}

	protected function createComponentContactForm($name)
	{
		$form = new Forms\ContactForm($this, $name);
		$form->onSuccess[] = array($this, 'contactFormSubmitted');
		return $form;
	}

	public function contactFormSubmitted(Form $form, ArrayHash $values)
	{
		$user = $this->getUser();
		$mail = new Message;
		$mail->setFrom($user->getIdentity()->email, $user->getIdentity()->name)
			->addTo($this->config->admin['to_mail'], $this->config->admin['to'])
			->setSubject('CM Bukovinka')
			->setBody("Vzkaz zaslaný pomocí webového formuláře:\n\n".$values['vzkaz']);
		try {
			$this->mailer->send($mail);
			$this->flashMessage('Vzkaz byl odeslán, co nejdříve se mu budu věnovat. Tomáš', 'success');
		} catch(Mail\SendException $e) {
			Tracy\Debugger::log('Chyba pri odeslani emailu s objednavkou Bukovince');
			$this->flashMessage('Došlo k chybě při odeslání vzkazu. Zkuste to prosím později, nebo mi dejte vědět přes Katku. Díky, Tomáš', 'danger');
		}
		$this->redirect('this');
	}

}
