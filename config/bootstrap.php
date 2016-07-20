<?php
use DI\ContainerBuilder;

include __DIR__.'/../vendor/autoload.php';

function e() {
	echo '<pre>';
	$result = [];
	foreach(func_get_args() as $arg) {
		$result[] = json_encode($arg, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
	}
	echo join("\n\n", $result);
	echo '</pre>';
}


$builder = new ContainerBuilder();
$builder->useAnnotations(false);
$builder->addDefinitions(__DIR__.'/di.php');
$container = $builder->build();

return $container;
