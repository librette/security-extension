<?php
namespace Librette\SecurityExtension\Models;

/**
 * @author David Matejka
 */
interface IUser
{

	public function getId();


	public function setUsername($username);


	public function getUsername();


	public function setEmail($email);


	public function getEmail();


	public function setPassword($password);


	public function validatePassword($password);
}