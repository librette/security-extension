<?php
namespace LibretteTests\SecurityExtension;

use Nette\Configurator;
use Nette\DI\Container;
use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/mocks/FormFactoryMockFactory.php';
require __DIR__ . '/models/UserEntity.php';


class ExtensionTestCase extends TestCase
{

	/** @var Container */
	protected $container;

	public function setUp()
	{
		$configurator = new Configurator();
		$configurator->addConfig(__DIR__ . '/config/securityExtension.neon');
		$configurator->setTempDirectory(TEMP_DIR);
		$this->container = $configurator->createContainer();
	}


	public function testComponentFactories()
	{
		$user = new UserEntity();
		/** @var \Librette\SecurityExtension\Components\EditForm\EditControlFactory $editControlFactory */
		$editControlFactory = $this->container->getByType('\Librette\SecurityExtension\Components\EditForm\EditControlFactory');
		Assert::type('\Librette\SecurityExtension\Components\EditForm\EditControl', $editControlFactory->create($user));

		/** @var \Librette\SecurityExtension\Components\LoginForm\LoginControlFactory $loginControlFactory */
		$loginControlFactory = $this->container->getByType('\Librette\SecurityExtension\Components\LoginForm\LoginControlFactory');
		Assert::type('\Librette\SecurityExtension\Components\LoginForm\LoginControl', $loginControlFactory->create());

		/** @var \Librette\SecurityExtension\Components\PasswordForm\PasswordControlFactory $passwordControlFactory */
		$passwordControlFactory = $this->container->getByType('\Librette\SecurityExtension\Components\PasswordForm\PasswordControlFactory');
		Assert::type('\Librette\SecurityExtension\Components\PasswordForm\PasswordControl', $passwordControlFactory->create($user));
	}
}

run(new ExtensionTestCase());
