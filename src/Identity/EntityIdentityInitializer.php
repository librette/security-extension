<?php
namespace Librette\SecurityExtension\Identity;

use Kdyby\Doctrine\EntityManager;
use Librette\SecurityNamespaces\Identity\IIdentityInitializer;
use Nette\Object;
use Nette\Security\IIdentity;

/**
 * @author David Matejka
 */
class EntityIdentityInitializer extends Object implements IIdentityInitializer
{

	/** @var \Kdyby\Doctrine\EntityManager */
	protected $em;


	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}


	public function initialize(IIdentity $identity)
	{
		if ($identity instanceof EntityIdentity && !$identity->isLoaded()) {
			$identity->load($this->em->getDao($identity->getEntityClass()));
		}

		return $identity;
	}

}