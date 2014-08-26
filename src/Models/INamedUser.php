<?php
namespace Librette\SecurityExtension\Models;

/**
 * @author David Matejka
 */
interface INamedUser
{

	/**
	 * @param string
	 * @return void
	 */
	public function setUsername($username);


	/**
	 * @return string
	 */
	public function getUsername();
}
