<?php
namespace Librette\SecurityExtension\Models;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette\Security\Passwords;


/**
 *
 * @author David Matejka
 *
 * @ORM\MappedSuperclass
 */
abstract class UserEntity extends BaseEntity implements IUser
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 * @ORM\Column
	 */
	protected $email;

	/**
	 *
	 * @var string
	 * @ORM\Column
	 */
	protected $password;

	/**
	 *
	 * @var string
	 * @ORM\Column
	 */
	protected $username;


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @param string $email
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}


	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}


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


	/**
	 *
	 * @param string $password plaintext
	 * @return $this
	 */
	public function setPassword($password)
	{
		$this->password = Passwords::hash($password);

		return $this;
	}


	/**
	 * @param string $password
	 * @return bool
	 */
	public function validatePassword($password)
	{
		return Passwords::verify($password, $this->password);
	}

}