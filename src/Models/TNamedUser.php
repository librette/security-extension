<?php
namespace Librette\SecurityExtension\Models;

/**
 * @author David Matejka
 */
trait TNamedUser
{


	/**
	 * @var string
	 * @ORM\Column
	 */
	protected $username;


	/**
	 * @param string $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}


	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}


	public function getPresentableName()
	{
		return $this->getUsername();
	}
}
