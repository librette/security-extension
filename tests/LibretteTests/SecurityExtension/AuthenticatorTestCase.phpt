<?php
namespace LibretteTests\SecurityExtension;

use Librette\SecurityExtension\Authenticator;
use Nette\Security\IAuthenticator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


class AuthenticatorTestCase extends ORMTestCase
{

	/** @var Authenticator */
	protected $authenticator;

	/** @var UserEntity */
	protected $user;


	protected function setUp()
	{
		$em = $this->createMemoryManager();
		$this->user = $entity = new UserEntity();
		$entity->setUsername('foo');
		$entity->setEmail('foo@bar');
		$entity->setPassword('pass');
		$em->persist($entity);
		$em->flush();
		$this->authenticator = new Authenticator($em->getDao($entity::getClassName()));
	}


	public function testUsernameAuthentication()
	{
		$identity = $this->authenticator->authenticate(array('foo', 'pass'));
		Assert::same($this->user, $identity->getEntity());
	}


	public function testEmailAuthentication()
	{
		$identity = $this->authenticator->authenticate(array('foo@bar', 'pass'));
		Assert::same($this->user, $identity->getEntity());
	}


	public function testInvalidUser()
	{
		Assert::exception(function () {
			$this->authenticator->authenticate(array('bar', '123'));
		}, '\Nette\Security\AuthenticationException', 'User %a% not found.', IAuthenticator::IDENTITY_NOT_FOUND);

	}


	public function testInvalidPassword()
	{
		Assert::exception(function () {
			$this->authenticator->authenticate(array('foo', '123'));
		}, '\Nette\Security\AuthenticationException', 'Invalid password.', IAuthenticator::INVALID_CREDENTIAL);
	}
}


run(new AuthenticatorTestCase());
