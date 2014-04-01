<?php
namespace Librette\SecurityExtension\Components\LoginForm;


/**
 * @author David Matejka
 */
interface LoginControlFactory
{

	/**
	 * @return LoginControl
	 */
	public function create();
}