<?php
namespace Librette\SecurityExtension;

use Librette\SecurityNamespaces\ISecurityNamespace;

interface Exception
{

}


class InvalidStateException extends \RuntimeException implements Exception
{

}


class NotSupportedException extends \LogicException implements Exception
{

}


class InvalidArgumentException extends \InvalidArgumentException implements Exception
{

}


class UnexpectedValueException extends \UnexpectedValueException implements Exception
{

}


class InvalidSecurityNamespaceException extends InvalidArgumentException
{

	public static function validate(ISecurityNamespace $namespace)
	{
		if (!$namespace instanceof SecurityNamespace) {
			throw new UnexpectedValueException("Invalid namespace, Librette\\SecurityExtension\\SecurityNamespace expected, " . get_class($namespace) . ' given');
		}
	}
}