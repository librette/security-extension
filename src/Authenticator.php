<?php
namespace Librette\SecurityExtension;

use Kdyby\Doctrine\EntityDao;
use Librette\SecurityExtension\Identity\EntityIdentity;
use Librette\SecurityExtension\Models\IUser;
use Nette\Object;
use Nette\Security as NS;

/**
 * @author David Matejka
 */
class Authenticator extends Object implements NS\IAuthenticator
{

	/** @var EntityDao */
	protected $userDao;


	/**
	 * @param EntityDao $dao
	 */
	public function __construct(EntityDao $dao)
	{
		$this->userDao = $dao;
	}


	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;
		$user = $this->userDao->findOneBy(array(strpos($username, '@') === FALSE ? 'username' : 'email' => $username));
		$this->validateUser($user, $username);
		$this->validatePassword($user, $password);

		return new EntityIdentity($user);
	}


	/**
	 * @param IUser|null $user
	 * @param $username
	 * @throws \Nette\Security\AuthenticationException
	 */
	private function validateUser($user, $username)
	{
		if (!$user) {
			throw new NS\AuthenticationException("User $username not found.", self::IDENTITY_NOT_FOUND);
		}
	}


	/**
	 * @param IUser $user
	 * @param string $password
	 * @throws \Nette\Security\AuthenticationException
	 */
	private function validatePassword(IUser $user, $password)
	{
		if (!$user->validatePassword($password)) {
			throw new NS\AuthenticationException("Invalid password.", self::INVALID_CREDENTIAL);
		}
	}

}
