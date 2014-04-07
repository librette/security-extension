<?php
namespace Librette\SecurityExtension;

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
