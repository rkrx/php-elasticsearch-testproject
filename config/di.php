<?php
namespace DI;

use Elastica;
use Elastica\Client;
use Kir\MySQL\Database;
use Kir\MySQL\Databases\MySQL;
use PDO;
use View\Factories\CallbackViewFactory;
use View\Renderer;
use View\ViewFactory;
use View\Workers\FileWorker;

$config = [
	'system.rootdir' => dirname(__DIR__),

	'elasticsearch.bind' => '127.0.0.1',
	'elasticsearch.port' => '9200',
	
	'pdo.dsn' => 'mysql:host=127.0.0.1;port=3306;charset=utf8;dbname=es-test',
	'pdo.user' => 'root',
	'pdo.pass' => '',

	PDO::class => factory(function (Container  $container) {
		$pdo = new PDO(
			$container->get('pdo.dsn'),
			$container->get('pdo.user'),
			$container->get('pdo.pass'),
			[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
		);
		$pdo->exec('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED');
		return $pdo;
	}),

	Database::class => factory(function (Container  $container) {
		$pdo = $container->get(PDO::class);
		$mysql = new MySQL($pdo);
		$mysql->getAliasRegistry()->add('shop', 'shop__');
		return $mysql;
	}),

	Elastica\Client::class => factory(function (Container $container) {
		return new Elastica\Client(array(
			'host' => $container->get('elasticsearch.bind'),
			'port' => $container->get('elasticsearch.port')
		));
	}),

	ViewFactory::class => factory(function (Container $container) {
		$rootDir = $container->get('system.rootdir');
		return new CallbackViewFactory(function ($baseDir, array $vars) {
			return new Renderer(new FileWorker($baseDir, null, $vars));
		}, "{$rootDir}/templates");
	}),
];

if(file_exists(__DIR__.'/di-local.php')) {
	$config = array_merge($config, require 'di-local.php');
}

return $config;
