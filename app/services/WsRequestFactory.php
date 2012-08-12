<?php

/**
 * WsRequestFactory
 *
 * @author Martin
 */
class WsRequestFactory extends \Nette\Http\RequestFactory
{
	private
		/** @var \Nette\Http\Url */
		$url
	;

	private function createUrl()
	{
		$this->url = new Nette\Http\UrlScript;
	}

	public function setUrl(\Nette\Http\Url $url)
	{
		$this->url = $url;
		return $this;
	}
	
	public function createHttpRequest()
	{
		// DETECTS URI, base path and script path of the request.
		if($this->url === null)
		{
			$this->createUrl();
		}
		$url = $this->url;

		$_SERVER = array();
		
		// GET, POST, COOKIE
		$useFilter = (!in_array(ini_get('filter.default'), array('', 'unsafe_raw')) || ini_get('filter.default_flags'));

		parse_str($url->query, $query);
		if (!$query) {
			$query = $useFilter ? filter_input_array(INPUT_GET, FILTER_UNSAFE_RAW) : (empty($_GET) ? array() : $_GET);
		}
		//$post = $useFilter ? filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW) : (empty($_POST) ? array() : $_POST);
		$cookies = $useFilter ? filter_input_array(INPUT_COOKIE, FILTER_UNSAFE_RAW) : (empty($_COOKIE) ? array() : $_COOKIE);

		$files = array();
		$headers = array(
			strtolower('X-Requested-With') => 'XMLHttpRequest'
		);
		$post = array();
		
		
		//var_dump('pica');
		
		return new \Nette\Http\Request($url, $query, $post, $files, $cookies, $headers,
			isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : NULL,
			isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : NULL,
			isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : NULL
		);
	}
}
