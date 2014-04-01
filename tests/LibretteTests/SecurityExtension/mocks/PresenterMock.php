<?php
namespace LibretteTests\SecurityExtension;

use Nette\Application\UI\Presenter;
use Nette\Application;
use Nette\Http\IResponse;
use Nette\Http\Request;
use Nette\Http\UrlScript;

class HttpResponseMock implements IResponse
{

	function setCode($code)
	{
	}


	function getCode()
	{
	}


	function setHeader($name, $value)
	{
	}



	function addHeader($name, $value)
	{
	}


	function setContentType($type, $charset = NULL)
	{
	}



	function redirect($url, $code = self::S302_FOUND)
	{
	}


	function setExpiration($seconds)
	{
	}

	function isSent()
	{
		return TRUE;
	}

	function getHeaders()
	{
		return array();
	}



	function setCookie($name, $value, $expire, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL)
	{
	}

	function deleteCookie($name, $path = NULL, $domain = NULL, $secure = NULL)
	{
	}

}


class PresenterMock extends Presenter
{

	public $onStartup = array();

	public function run(Application\Request $request)
	{
		$this->autoCanonicalize = FALSE;

		return parent::run($request);
	}


	public function startup()
	{
		parent::startup();
		$this->onStartup($this);
	}

	public function afterRender()
	{
		$this->terminate();
	}

	public function isAjax()
	{
		return FALSE;
	}

	public static function create(UrlScript $url = NULL, $method = 'GET')
	{
		$url = $url ? : new UrlScript('http://localhost/');
		$presenter = new self;
		$rc = $presenter->getReflection()->getParentClass();
		$rp = $rc->getProperty('httpResponse');
		$rp->setAccessible(TRUE);
		$rp->setValue($presenter, new HttpResponseMock());

		$rp = $rc->getProperty('httpRequest');
		$rp->setAccessible(TRUE);
		$rp->setValue($presenter, new Request($url, NULL, NULL, NULL, NULL, NULL, $method, NULL, NULL));

		return $presenter;
	}
}