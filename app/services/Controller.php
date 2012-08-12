<?php
namespace WS;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Controller implements MessageComponentInterface
{

	protected $clients;

	private
		/** @var \SystemContainer */
		$container,

		/** @var \Nette\Http\Session */			
		$session
	;

	public function __construct($container)
	{
		$this->clients = new \SplObjectStorage;
		$this->container = $container;
	}

	public function onOpen(ConnectionInterface $conn)
	{
		// Store the new connection to send messages to later
		$this->clients->attach($conn);
		$this->session = $conn->session;
		$this->session->start();
		$_SERVER = $this->session->getSection('env')->server;
		$this->session->close();
	}

	public function onMessage(ConnectionInterface $from, $msg)
	{
		$httpRequest = $this->container->nette->httpRequestFactory->setUrl(new \Nette\Http\UrlScript($msg))->createHttpRequest();
		$application = $this->container->application;
		
		$this->session->start();
		$_SERVER = $this->session->getSection('env')->server;
		ob_start();
		//$application->run();
		$application->run($httpRequest);
		$response = ob_get_clean();
		
		$from->send($response);
		
		$this->session->close();
	}

	public function onClose(ConnectionInterface $conn)
	{
		// The connection is closed, remove it, as we can no longer send it messages
		$this->clients->detach($conn);
	}

	public function onError(ConnectionInterface $conn, \Exception $e)
	{
		echo "An error has occurred: {$e->getMessage()}\n";

		$conn->close();
	}

}