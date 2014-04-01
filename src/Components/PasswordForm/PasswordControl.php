<?php
namespace Librette\SecurityExtension\Components\PasswordForm;

use Librette\Doctrine\Forms\FormFactory;
use Librette\SecurityExtension\Models\IUser;
use Nette\Application\UI\Control;

/**
 * @author David Matejka
 */
class PasswordControl extends Control
{

	public $onChange = array();

	/** @var \Librette\SecurityExtension\Models\IUser */
	protected $user;

	/** @var \Librette\Doctrine\Forms\FormFactory */
	protected $formFactory;


	/**
	 * @param IUser $user
	 * @param FormFactory $formFactory
	 */
	public function __construct(IUser $user, FormFactory $formFactory)
	{
		$this->user = $user;
		$this->formFactory = $formFactory;
	}


	public function createComponentForm()
	{
		$form = $this->formFactory->create($this->user);
		$form->addPassword('old_password', 'Old password')->addRule(function ($input) {
			return $this->user->validatePassword($input->value);
		}, 'Password does not match with your password');

		$form->addPassword('password', 'New password')
			 ->setRequired()
			 ->addRule($form::MIN_LENGTH, 'Minimum length of the password is %d characters', 6);
		$form->addPassword('password_repeat', 'Repeat password')
			 ->setRequired()
			 ->addRule($form::EQUAL, 'Passwords do not match', $form['password']);
		$form->addSubmit('ok', 'Change');
		$form->onAfterSuccess[] = function () {
			$this->onChange($this, $this->user);
		};

		return $form;
	}


	public function render()
	{
		if (!$this->template->getFile()) {
			$this->template->setFile(__DIR__ . '/passwordControl.latte');
		}
		$this->template->render();
	}
}