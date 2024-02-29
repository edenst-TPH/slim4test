<?php

use DI\ContainerBuilder;
use Slim\App;

require_once __DIR__ . '/../vendor/autoload.php';

// Build DI container instance
$container = (new ContainerBuilder())
    ->addDefinitions(__DIR__ . '/container.php')
    ->build();

$container->set('upload_directory', __DIR__ . '/uploads');

// Create App instance
return $container->get(App::class);
