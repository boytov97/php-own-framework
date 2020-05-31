<?php

use Database\Db;

return function (DependencyInjection $di) {
  $database = require(__DIR__ . '/../../config/database.php');

  $di->set('db', (function () use ($database) {
    return new Db($database);
  })());
};