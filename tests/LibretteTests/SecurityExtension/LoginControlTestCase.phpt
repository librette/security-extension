<?php
namespace LibretteTests\SecurityExtension;

use Librette\Forms\FormFactory;
use Librette\SecurityExtension\Components\LoginForm\LoginControl;
use Nette\Application\Request;
use Nette\Security\SimpleAuthenticator;
use Nette\Security\User;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/mocks/PresenterMock.php';
require_once __DIR__ . '/mocks/UserStorageMock.php';

class LoginControlTestCase extends TestCase
{

	/** @var User */
	protected $user;

	/** @var PresenterMock */
	protected $presenter;

	public function setUp()
	{
		$authenticator = new SimpleAuthenticator(array('john' => '123'));
		$storage = new UserStorageMock();
		$this->user = $user = new User($storage, $authenticator);
		$this->presenter = $this->createPresenter($user);
	}

	public function testLogin()
	{
		$post = array('do' => 'loginControl-form-submit', 'username' => 'john', 'password' => 123, 'ok'=>'Login');
		$this->presenter->run(new Request('Foo', 'post', array(), $post));
		Assert::true($this->user->isLoggedIn());
		Assert::type('Nette\Security\IIdentity', $id = $this->user->getIdentity());
		Assert::same('john', $id->getId());
	}

	public function testLoginFailure()
	{
		$post = array('do' => 'loginControl-form-submit', 'username' => 'john', 'password' => 456, 'ok' => 'Login');
		$this->presenter->run(new Request('Foo', 'post', array(), $post));

		Assert::false($this->user->isLoggedIn());
		Assert::false($this->presenter['loginControl']['form']->valid);

	}

	/**
	 * @param $user
	 * @return PresenterMock
	 */
	protected function createPresenter($user)
	{
		$presenterMock = PresenterMock::create();
		$presenterMock->onStartup[] = function (PresenterMock $presenterMock) use ($user) {
			$loginControl = new LoginControl($user, new FormFactory());
			$loginControl['form']->getElementPrototype();
			$loginControl['form']->setAction('/');
			$presenterMock->addComponent($loginControl, 'loginControl');
		};

		return $presenterMock;
	}

}

run(new LoginControlTestCase());
