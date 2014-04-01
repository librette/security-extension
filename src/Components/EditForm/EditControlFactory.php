<?php
namespace Librette\SecurityExtension\Components\EditForm;

use Librette\SecurityExtension\Models\IUser;

/**
 * @author David Matejka
 */
interface EditControlFactory
{

	/**
	 * @param IUser $user
	 * @return EditControl
	 */
	public function create(IUser $user);
}