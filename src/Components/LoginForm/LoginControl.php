<?php
namespace Librette\SecurityExtension\Components\LoginForm;

use Librette\Forms\Form;
use Librette\Forms\FormFactory;
use Nette\Application\UI\Control;
use Nette\Security\AuthenticationException;
use Nette\Security\User;

/**
 * @author David Matejka
 */
class LoginControl extends Control
{

	public $onLogin;

	/** @var \Nette\Security\User */
	protected $user;

	/** @var \Librette\Forms\FormFactory */
	protected $formFactory;


	/**
	 * @param User $user
	 * @param FormFactory $formFactory
	 */
	public function __construct(User $user, FormFactory $formFactory)
	{
		$this->user = $user;
		$this->formFactory = $formFactory;

	}


	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = $this->formFactory->create();
		$form->addText('email', 'E-mail')->setRequired();
		$form->addPassword('password', 'Password')->setRequired();
		$form->addSubmit('ok', 'Login');
		$form->onSuccess[] = $this->processLoginForm;

		return $form;
	}


	/**
	 * @param Form $form
	 */
	public function processLoginForm(Form $form)
	{
		$data = $form->getValues();
		try {
			$this->user->login($data->email, $data->password);
			$this->onLogin($this, $this->user->identity);
		} catch (AuthenticationException $e) {
			$form->addError($e->getMessage());
			$form->abort();
		}
	}


	public function render()
	{
		if (!$this->template->getFile()) {
			$this->template->setFile(__DIR__ . '/loginControl.latte');
		}
		$this->template->render();
	}
}