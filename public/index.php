<?php
namespace DV\UrlHelpers;

use DI\Container;
use Elastica\Client;
use ElasticSearch\IndexRepository;
use ElasticSearch\TestRequest;
use Kir\MySQL\Database;
use View\ViewFactory;

require __DIR__.'/../vendor/autoload.php';


/** @var Container $container */
$container = require __DIR__.'/../config/bootstrap.php';
error_reporting(E_ALL);

$container->call(function (Client $client, Database $db, ViewFactory $viewFactory) {
	$results = null;

	$_REQUEST = array_merge(['type' => [], 'q' => ''], $_REQUEST);

	$indexRepository = new IndexRepository($client);
	$index = $indexRepository->getIndex('shop');
	$products = $index->getType('products');
	$request = $products->createRequest(TestRequest::class, ['de', $db]);
	$results = $request->search(array_merge($_REQUEST, ['query' => $_REQUEST['q'], 'domain' => 5]), ['limit' => 50]);

	echo $viewFactory->create('search')
	->add('results', $results)
	->add('search', $_REQUEST['q'])
	->render('search');
});
