<?php
namespace Librette\SecurityExtension\DI;

use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\PhpLiteral;

/**
 * @author David Matejka
 */
class SecurityExtension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('editControlFactory'))
				->setImplement('\Librette\SecurityExtension\Components\EditForm\EditControlFactory')
				->setClass('\Librette\SecurityExtension\Components\EditForm\EditControl')
				->setArguments(array(new PhpLiteral('$user')));

		$builder->addDefinition($this->prefix('loginControlFactory'))
				->setImplement('\Librette\SecurityExtension\Components\LoginForm\LoginControlFactory');

		$builder->addDefinition($this->prefix('passwordControlFactory'))
				->setImplement('\Librette\SecurityExtension\Components\PasswordForm\PasswordControlFactory')
				->setClass('\Librette\SecurityExtension\Components\PasswordForm\PasswordControl')
				->setArguments(array(new PhpLiteral('$user')));
	}

}