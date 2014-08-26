<?php
namespace Librette\SecurityExtension;

use Librette\SecurityExtension\Identity\EntityIdentity;
use Librette\SecurityExtension\Models\IUser;
use Librette\SecurityNamespaces\INamespaceDetector;
use Nette\Object;
use Nette\Security as NS;

/**
 * @author David Matejka
 */
class Authenticator extends Object implements NS\IAuthenticator
{

	/** @var \Librette\SecurityNamespaces\INamespaceDetector */
	protected $namespaceDetector;


	/**
	 * @param INamespaceDetector $namespaceDetector
	 */
	public function __construct(INamespaceDetector $namespaceDetector)
	{
		$this->namespaceDetector = $namespaceDetector;
	}


	public function authenticate(array $credentials)
	{
		/** @var SecurityNamespace $namespace */
		$namespace = $this->namespaceDetector->getNamespace();
		InvalidSecurityNamespaceException::validate($namespace);
		list($username, $password) = $credentials;
		$field = $namespace->isNamed() && strpos($username, '@') === FALSE ? 'username' : 'email';
		$user = $namespace->getDao()->findOneBy([$field => $username]);
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
