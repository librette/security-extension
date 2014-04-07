<?php
namespace Librette\SecurityExtension\Identity;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\MemberAccessException;
use Librette\SecurityExtension\Models\IUser;
use Nette;

/**
 * @author David Matejka
 */
class EntityIdentity extends Nette\Object implements Nette\Security\IIdentity
{

	/** @var int */
	protected $id;

	/** @var IUser */
	protected $entity;

	/** @var string */
	protected $entityClass;

	/** @var bool is entity loaded? */
	protected $loaded = FALSE;

	/** @var array */
	protected $roles = array();


	/**
	 * @param IUser $entity
	 * @param array $roles
	 */
	public function __construct(IUser $entity, $roles = array())
	{
		$this->id = $entity->getId();
		$this->entity = $entity;
		$this->roles = $roles;
		$this->loaded = TRUE;
		$this->entityClass = get_class($this->entity);
	}


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return array
	 */
	public function getRoles()
	{
		return $this->roles;
	}


	public function __sleep()
	{
		return array("id", "entityClass");
	}


	public function __wakeup()
	{
		$this->loaded = FALSE;
	}


	/**
	 * @param EntityDao $dao
	 */
	public function load(EntityDao $dao)
	{
		$this->entity = $dao->find($this->id);
		$this->loaded = TRUE;
	}


	/**
	 * @return bool
	 */
	public function isLoaded()
	{
		return $this->loaded;
	}


	/**
	 * @return IUser
	 */
	public function getEntity()
	{
		return $this->entity;
	}


	public function &__get($name)
	{
		if ($this->entity) {
			try {
				return $this->entity->{$name};
			} catch (MemberAccessException $e) {
			}
		}

		return parent::__get($name);
	}


	/**
	 * @return string
	 */
	public function getEntityClass()
	{
		return $this->entityClass;
	}

}