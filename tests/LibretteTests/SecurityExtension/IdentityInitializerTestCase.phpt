<?php
namespace LibretteTests\SecurityExtension;

use Librette\SecurityExtension\Identity\EntityIdentity;
use Librette\SecurityExtension\Identity\EntityIdentityInitializer;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class IdentityInitializerTestCase extends ORMTestCase
{

	public function testInitializer()
	{
		$em = $this->createMemoryManager();
		$entity = new UserEntity();
		$entity->setUsername('foo');
		$entity->setEmail('foo@bar');
		$entity->setPassword('pass');
		$em->persist($entity);
		$em->flush();

		$identity = new EntityIdentity($entity);
		$identity = unserialize(serialize($identity));

		Assert::equal($entity->getId(), $identity->id);

		$initializer = new EntityIdentityInitializer($em);
		/** @var \Librette\SecurityExtension\Identity\EntityIdentity $identity */
		$identity = $initializer->initialize($identity);

		Assert::same($entity, $identity->getEntity());
	}
}


run(new IdentityInitializerTestCase());
