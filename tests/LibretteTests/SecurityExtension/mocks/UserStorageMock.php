<?php
namespace LibretteTests\SecurityExtension;

use Nette\Security\IIdentity;
use Nette\Security\IUserStorage;

class UserStorageMock implements IUserStorage
{

	protected $namespace;

	protected $authenticated = FALSE;

	protected $identity;


	public function getNamespace()
	{
		return $this->namespace;
	}


	public function setNamespace($namespace)
	{
		$this->namespace = $namespace;
	}


	function setAuthenticated($state)
	{
		$this->authenticated = $state;
	}


	function isAuthenticated()
	{
		return $this->authenticated;
	}


	function setIdentity(IIdentity $identity = NULL)
	{
		$this->identity = $identity;
	}


	function getIdentity()
	{
		return $this->identity;
	}


	function setExpiration($time, $flags = 0)
	{

	}


	function getLogoutReason()
	{
		return NULL;
	}

}