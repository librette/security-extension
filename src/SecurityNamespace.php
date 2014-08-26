<?php
namespace Librette\SecurityExtension;

use Doctrine\ORM\Mapping\ClassMetadata;
use Kdyby\Doctrine\EntityDao;
use Librette\SecurityExtension\Models\IUser;
use Librette\SecurityNamespaces\SecurityNamespace as BaseSecurityNamespace;

/**
 * @author David Matejka
 */
class SecurityNamespace extends BaseSecurityNamespace
{

	/** @var EntityDao */
	protected $dao;


	/** @var bool|null */
	protected $isNamed;


	public function setDao(EntityDao $dao)
	{
		$this->dao = $dao;
	}


	public function getDao()
	{
		return $this->dao;
	}


	public function isNamed()
	{
		if ($this->isNamed === NULL) {
			$this->isNamed = class_implements($this->getClassMetadata()->name, 'Librette\SecurityExtension\Models\INamedUser');
		}

		return $this->isNamed;
	}


	/**
	 * @return ClassMetadata
	 */
	public function getClassMetadata()
	{
		return $this->dao->getClassMetadata();
	}


	public function isValid(IUser $user)
	{
		return get_class($user) === $this->getClassMetadata()->name;
	}


	public function validateUser(IUser $user)
	{
		if (!$this->isValid($user)) {
			throw new InvalidArgumentException("User entity " . get_class($user) . ' is not related to ' . $this->name . ' namespace');
		}
	}
}