<?php
namespace App\AdminModule;

use Nette\Application\UI\Form,
	Nette\Forms\Controls\SubmitButton,
	Nette\Http\Request,
	Nette\Security,
	Nette\Utils\Json,
	Nette\Utils\ArrayHash,
	Nette\Utils\DateTime,
	App\AdminModule\Forms;

class SignPresenter extends BasePresenter
{

	/** @var Request @inject */
	public $httpRequest;

	protected function createComponentSignInForm($name)
	{
		$form = new Forms\SignInForm($this, $name);
		$form->onSuccess[] = array($this, 'signInFormSubmitted');
		return $form;
	}

	public function signInFormSubmitted(Form $form, ArrayHash $values)
	{
		$logValues = array(
			'user' => $values->username,
			'message' => '',
			'ip' => $this->httpRequest->getRemoteAddress(),
			'browser' => $this->httpRequest->getHeader('User-Agent'),
		);
		$log = array(
			'ts' => new DateTime,
			'values' => '',
		);
	    try {
			$user = $this->getUser();
	        $user->login($values->username, $values->password);
			$logValues['message'] = 'SignIn success.';
			$log['values'] = Json::encode($logValues);
			$this->logManager->save($log);
	        $this->flashMessage('Přihlášení bylo úspěšné.', 'success');
	        $this->redirect('Homepage:');
	    } catch (Security\AuthenticationException $e) {
			$logValues['message'] = $e->getMessage();
			$log['values'] = Json::encode($logValues);
			$this->logManager->save($log);
	        $this->flashMessage('Neplatné uživatelské jméno nebo heslo.');
	        $this->redirect('this');
	    }
	}

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Byl jsi odhlášen.');
		$this->redirect('in');
	}

}
