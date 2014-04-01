<?php
namespace LibretteTests\SecurityExtension;

use Tester\Assert;
use Tester\TestCase;

require __DIR__ . '/../bootstrap.php';
require __DIR__ . '/models/UserEntity.php';


class UserEntityTestCase extends TestCase
{
	public function testPasswordValidation()
	{
		$userEntity = new UserEntity();
		$userEntity->setPassword('foo');
		Assert::true($userEntity->validatePassword('foo'));
		Assert::false($userEntity->validatePassword('bar'));

	}
}

run(new UserEntityTestCase());
