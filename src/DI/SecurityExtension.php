<?php
namespace Librette\SecurityExtension\DI;

use Kdyby\Console\DI\ConsoleExtension;
use Kdyby\Doctrine\DI\IEntityProvider;
use Librette\SecurityNamespaces\DI\SecurityNamespacesExtension;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\PhpLiteral;

/**
 * @author David Matejka
 */
class SecurityExtension extends CompilerExtension implements IEntityProvider
{

	public $defaults = [
		'namespaces' => [],
	];


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('editControlFactory'))
				->setImplement('\Librette\SecurityExtension\Components\EditForm\EditControlFactory')
				->setClass('\Librette\SecurityExtension\Components\EditForm\EditControl')
				->setArguments([new PhpLiteral('$user')]);

		$builder->addDefinition($this->prefix('loginControlFactory'))
				->setImplement('\Librette\SecurityExtension\Components\LoginForm\LoginControlFactory');

		$builder->addDefinition($this->prefix('passwordControlFactory'))
				->setImplement('\Librette\SecurityExtension\Components\PasswordForm\PasswordControlFactory')
				->setClass('\Librette\SecurityExtension\Components\PasswordForm\PasswordControl')
				->setArguments([new PhpLiteral('$user')]);

		/** @var INamespaceProvider $ext */
		foreach ($this->compiler->getExtensions('Librette\SecurityExtension\DI\INamespaceProvider') as $ext) {
			$config['namespaces'] += $ext->getSecurityNamespaces();
		}
		foreach ($config['namespaces'] as $name => $class) {
			$builder->addDefinition($this->prefix($name . '.dao'))
					->setFactory('@doctrine.dao', [$class]);

			$builder->addDefinition($this->prefix($name . '.authenticator'))
					->setClass('\Librette\SecurityExtension\Authenticator')
					->setAutowired(FALSE);

			$builder->addDefinition($this->prefix($name . '.identityInitializer'))
					->setClass('\Librette\SecurityExtension\Identity\EntityIdentityInitializer');

			$builder->addDefinition($this->prefix($name . '.securityNamespace'))
					->setClass('\Librette\SecurityExtension\SecurityNamespace')
					->setArguments([$name, $this->prefix("@$name.authenticator"), NULL, $this->prefix("@$name.identityInitializer")])
					->addSetup('setDao', [$this->prefix("@$name.dao")])
					->addTag(SecurityNamespacesExtension::SECURITY_NAMESPACE_TAG, ['name' => $name]);

/*			$builder->addDefinition($this->prefix($name . '.createUserCommand'))
					->setClass('\Librette\SecurityExtension\Commands\CreateUserCommand', [$this->prefix("@$name.securityNamespace")])
					->addTag(ConsoleExtension::COMMAND_TAG);*/
		}
	}


	function getEntityMappings()
	{
		return [
			'Librette\SecurityExtension' => __DIR__ . '/../',
		];
	}

}
