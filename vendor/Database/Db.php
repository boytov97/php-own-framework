<?php

namespace Database;

use PDO;

class Db
{
  protected $mysqlConfig = [];

  public function __construct(array $dbConfig)
  {
    $this->mysqlConfig = array_key_exists('mysql', $dbConfig) ? $dbConfig['mysql'] : [];
  }

  public function mysqlConnect()
  {
    $dbHost = array_key_exists('host', $this->mysqlConfig) ? $this->mysqlConfig['host'] : '';
    $dbName = array_key_exists('name', $this->mysqlConfig) ? $this->mysqlConfig['name'] : '';
    $dbUser = array_key_exists('user', $this->mysqlConfig) ? $this->mysqlConfig['user'] : '';
    $dbPassword = array_key_exists('password', $this->mysqlConfig) ? $this->mysqlConfig['password'] : '';

    return new PDO("mysql:host=".$dbHost."; dbname=".$dbName, $dbUser, $dbPassword);
  }
}