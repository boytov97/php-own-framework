<?php

use library\Autowire\Autowire;
use app\UseCases\UseCaseFactory;

return function (DependencyInjection $di) {
  Autowire::addDefinitions($di, [
    UseCaseFactory::class => UseCaseFactory::class
  ]);
};