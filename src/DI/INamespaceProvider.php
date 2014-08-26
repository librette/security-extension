<?php
namespace Librette\SecurityExtension\DI;

/**
 * @author David Matejka
 */
interface INamespaceProvider
{

	/**
	 * @return array of name => class implementing IUser
	 */
	public function getSecurityNamespaces();

}
