<?php
namespace Librette\SecurityExtension\Models;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
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

	use Identifier;

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


	public function setEmail($email)
	{
		$this->email = $email;
	}


	public function getEmail()
	{
		return $this->email;
	}


	public function setPassword($password)
	{
		$this->password = Passwords::hash($password);
	}


	public function validatePassword($password)
	{
		return Passwords::verify($password, $this->password);
	}


	public function getPresentableName()
	{
		return $this->getEmail();
	}

}
