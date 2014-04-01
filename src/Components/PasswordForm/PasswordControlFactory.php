<?php
namespace Librette\SecurityExtension\Components\PasswordForm;

use Librette\SecurityExtension\Models\IUser;

/**
 * @author David Matejka
 */
interface PasswordControlFactory
{

	/**
	 * @param IUser $user
	 * @return PasswordControl
	 */
	public function create(IUser $user);
}