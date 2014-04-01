<?php
namespace LibretteTests\SecurityExtension;

use Librette\SecurityExtension\Components\EditForm\EditControl;
use Nette\Application\Request;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/mocks/PresenterMock.php';
require_once __DIR__ . '/models/UserEntity.php';

class EditControlTestCase extends ORMTestCase
{
	/** @var UserEntity */
	protected $user;

	/** @var EditControl */
	protected $control;

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

		$this->control = new EditControl($user, $this->serviceLocator->getByType('Librette\Doctrine\Forms\FormFactory'));
		$this->presenter = $presenter = PresenterMock::create();
		$presenter->onStartup[] = function (PresenterMock $presenter) {
			$presenter->addComponent($this->control, 'editControl');
		};
	}

	public function testBasic()
	{
		$eventInvoked = FALSE;
		$this->control->onSave[] = function() use(&$eventInvoked){
			$eventInvoked = TRUE;
		};
		$post = array('do' => 'editControl-form-submit', 'username' => 'john', 'email' => 'john@doe.com', 'password' => '456');
		$request = new Request('Foo', 'post', array(), $post);
		$this->presenter->run($request);

		Assert::true($eventInvoked);
		Assert::equal('john', $this->user->getUsername());
		Assert::equal('john@doe.com', $this->user->getEmail());
		Assert::true($this->user->validatePassword('123'));
	}

	public function testEditPassword()
	{
		$this->control->setAllowPasswordEdit(TRUE);
		$post = array('do' => 'editControl-form-submit', 'username' => 'john', 'email' => 'john@doe.com', 'password' => '456');
		$request = new Request('Foo', 'post', array(), $post);
		$this->presenter->run($request);
		Assert::true($this->user->validatePassword('456'));
	}

	public function testRender()
	{
		$this->control['form']->setAction('/');
		$this->serviceLocator->callInjects($this->presenter);
		$this->presenter->run(new Request('Foo', 'get', array()));

		ob_start();
		$this->control->render();
		$content = ob_get_clean();
		Assert::matchFile(__DIR__ . '/expected/editControl.html', $content);
	}

}

run(new EditControlTestCase());
