<?php
namespace Librette\SecurityExtension\Components\EditForm;

use Librette\Doctrine\Forms\FormFactory;
use Librette\Forms\Form;
use Librette\SecurityExtension\Models\IUser;
use Nette\Application\UI\Control;

/**
 * @author David Matejka
 */
class EditControl extends Control
{

	public $onSave = array();

	/** @var IUser */
	protected $user;

	/** @var FormFactory */
	protected $formFactory;

	/** @var bool */
	protected $allowPasswordEdit = FALSE;


	/**
	 * @param IUser $user
	 * @param FormFactory $formFactory
	 */
	public function __construct(IUser $user, FormFactory $formFactory)
	{
		$this->user = $user;
		$this->formFactory = $formFactory;
	}


	/**
	 * @param boolean $allowPasswordEdit
	 */
	public function setAllowPasswordEdit($allowPasswordEdit)
	{
		$this->allowPasswordEdit = $allowPasswordEdit;
	}


	/**
	 * @return boolean
	 */
	public function getAllowPasswordEdit()
	{
		return $this->allowPasswordEdit;
	}


	/**
	 * @return Form
	 */
	public function createComponentForm()
	{
		$form = $this->formFactory->create($this->user);
		$form->addText('username', 'Username');
		$form->addText('email', 'E-mail');
		if ($this->allowPasswordEdit) {
			$form->addPassword('password', 'Password');
		}
		$form->addSubmit('ok', 'Save');
		$form->onAfterSuccess[] = function () {
			$this->onSave($this, $this->user);
		};

		return $form;
	}


	public function render()
	{
		if (!$this->template->getFile()) {
			$this->template->setFile(__DIR__ . '/editControl.latte');
		}
		$this->template->render();
	}

}