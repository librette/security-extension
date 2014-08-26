<?php
namespace Librette\SecurityExtension\Models;

/**
 * @author David Matejka
 */
interface IUser
{

	/**
	 * @return int
	 */
	public function getId();


	/**
	 * @param string
	 * @return void
	 */
	public function setEmail($email);


	/**
	 * @return string
	 */
	public function getEmail();


	/**
	 * @param string
	 * @return void
	 */
	public function setPassword($password);


	/**
	 * @param string
	 * @return bool
	 */
	public function validatePassword($password);


	/**
	 * @return string
	 */
	public function getPresentableName();

}
