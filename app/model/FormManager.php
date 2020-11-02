<?php
namespace Model;

use Nette,
	Nette\Mail,
	Nette\Mail\Message,
	Nette\Mail\IMailer,
	Nette\Utils\Strings,
	Latte,
	Tracy;

class FormManager
{

	/** @var SetupManager */
	private $config;

	/** @var IMailer */
	private $mailer;

	public function __construct(SetupManager $setup, IMailer $mailer)
	{
		$this->config = $setup;
		$this->mailer = $mailer;
	}


	/**
	 * Send e-mail
	 * @return bool
	 */
	public function sendContactMessage($values)
	{
		# send e-mail
		$latte = new Latte\Engine;
		$params = array(
			'webName' => $this->config->mail['name'],
			'userName' => $values['name'],
			'note' => $values['message']
		);
		$message = new Message;
		$message->setFrom($values['email'],$values['name'])
			->addTo($this->config->mail['to_mail'],$this->config->mail['to'])
			->setHtmlBody($latte->renderToString('app/FrontModule/templates/Emails/contactMessage.latte', $params));
		try {
			$this->mailer->send($message);
			return TRUE;
		} catch(Mail\SendException $e) {
			Tracy\Debugger::log('Chyba při odeslání kontaktního emailu Bukovince');
			return FALSE;
		}
	}



}
