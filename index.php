<?php 

try {
  spl_autoload_register(function ($className) {
    $className = str_replace("\\", "/", $className);

    include __DIR__ . '/' .$className . '.php';
  });

  $routes = require(__DIR__ . '/config/routes.php');

  require_once __DIR__ . "/helpers.php";

  require_once __DIR__ . "/vendor/autoloader.php";

  require_once __DiR__ . '/config/loader.php';

  $di = new DependencyInjection();

  $dependencies = require __DIR__ . "/app/Http/dependencies.php";
  $dependencies($di);

  $repositories = require __DIR__ . '/app/Http/repositories.php';
  $repositories($di);

  $services = require __DIR__ . '/app/Http/services.php';
  $services($di);

  $routing = new Router($routes);

  $application = new Application($routing);
  $application->setDi($di);
  $application->run();
} catch (\Exception $exception) {
  echo $exception->getMessage() . ". {$exception->getFile()} on line " . $exception->getLine();
}
