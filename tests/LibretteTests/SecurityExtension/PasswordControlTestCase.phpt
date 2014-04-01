<?php
namespace LibretteTests\SecurityExtension;

use Librette\SecurityExtension\Components\PasswordForm\PasswordControl;
use Nette\Application\Request;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/mocks/PresenterMock.php';
require_once __DIR__ . '/models/UserEntity.php';


class PasswordControlTestCase extends ORMTestCase
{

	/** @var PasswordControl */
	protected $control;

	/** @var UserEntity */
	protected $user;

	/** @var PresenterMock */
	protected $presenter;


	public function setUp()
	{
		$em = $this->createMemoryManager(TRUE);

		$this->user = $user = new UserEntity();
		$user->setUsername('foo');
		$user->setEmail('foo@bar');
		$user->setPassword('123');
		$em->persist($user);

		$this->control = $control = new PasswordControl($user, $this->serviceLocator->getByType('Librette\Doctrine\Forms\FormFactory'));
		$this->presenter = $presenter = PresenterMock::create();
		$presenter->onStartup[] = function (PresenterMock $presenter) use ($control) {
			$presenter->addComponent($control, 'passwordControl');
		};

	}


	public function testBasic()
	{
		$eventInvoked = FALSE;
		$this->control->onChange[] = function () use (&$eventInvoked) {
			$eventInvoked = TRUE;
		};
		$post = array('do' => 'passwordControl-form-submit', 'old_password' => '123', 'password' => '456789', 'password_repeat' => '456789');
		$request = new Request('Foo', 'post', array(), $post);
		$this->presenter->run($request);

		Assert::true($eventInvoked);
		Assert::true($this->user->validatePassword('456789'));
	}


	public function testBadOldPassword()
	{
		$this->control['form']->setAction('/');
		$eventInvoked = FALSE;
		$this->control->onChange[] = function () use (&$eventInvoked) {
			$eventInvoked = TRUE;
		};
		$post = array('do' => 'passwordControl-form-submit', 'old_password' => '1234', 'password' => '456789', 'password_repeat' => '456789');
		$request = new Request('Foo', 'post', array(), $post);
		$this->presenter->run($request);

		Assert::false($eventInvoked);
		Assert::true($this->user->validatePassword('123'));
		Assert::false($this->control['form']->isValid());
		Assert::equal('Password does not match with your password', $this->control['form']['old_password']->error);

	}


	public function testRender()
	{
		$this->control['form']->setAction('/');
		$this->serviceLocator->callInjects($this->presenter);
		$this->presenter->run(new Request('Foo', 'get', array()));
		ob_start();
		$this->control->render();
		$content = ob_get_clean();
		Assert::matchFile(__DIR__ . '/expected/passwordControl.html', $content);
	}

}


run(new PasswordControlTestCase());
