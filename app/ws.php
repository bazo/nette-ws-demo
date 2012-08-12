<?php
use WS\Controller;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Session\SessionProvider;
use Ratchet\Wamp\WampServer;
use Nette\Application\Routers\Route;

// absolute filesystem path to the application root
define('APP_DIR', __DIR__);

// absolute filesystem path to the libraries
define('LIBS_DIR', APP_DIR . '/../libs');

// Load Nette Framework
define('VENDOR_DIR', APP_DIR.'/../vendor');
require_once VENDOR_DIR . '/nette/nette/Nette/loader.php';
require VENDOR_DIR.'/autoload.php';

// Configure application
$configurator = new Nette\Config\Configurator;

// Enable Nette Debugger for error visualisation & logging
$configurator->setDebugMode($configurator::DEVELOPMENT);
$configurator->enableDebugger(__DIR__ . '/../log');

// Enable RobotLoader - this will load all classes automatically
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon', 'ws');
$container = $configurator->createContainer();

// Setup router
$container->router[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
$container->router[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

$port = 8000;

//$wsServer = new WsServer(new WampServer(new MyApp));

#ini_set('session.name', 'websockets');
$session = new SessionProvider(
        new Controller($container)
      , $container->symfonyStorageHandler,
		array(/*'name' => 'websockets',*/ 'auto_start' => true)
    );

$session = new Bazo\Ratchet\NetteSessionProvider(new Controller($container), $container->session);

$wsServer = new WsServer($session);

// Make sure to run as root
$server = IoServer::factory($wsServer, $port);
$server->run();