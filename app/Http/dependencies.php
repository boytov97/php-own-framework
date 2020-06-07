<?php

use database\Mysql\MysqlDatabaseConnection;

return function (DependencyInjection $di) {
  $database = require(__DIR__ . '/../../config/database.php');

  $di->set('db', function () use ($database) {
    return new MysqlDatabaseConnection($database);
  });

  $di->set(MysqlDatabaseConnection::class, function () use ($database) {
    return new MysqlDatabaseConnection($database);
  });
};