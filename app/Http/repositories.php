<?php

use library\Autowire\Autowire;
use app\Entities\Words\RepositoryInterface as WordsRepositoryInterface;
use app\Infrastructure\MysqlWordsRepository;

return function (DependencyInjection $di) {
  Autowire::addDefinitions($di, [
    WordsRepositoryInterface::class => MysqlWordsRepository::class
  ]);
};