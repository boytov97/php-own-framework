<?php

namespace app\Infrastructure;

use PDO;
use app\Entities\Words\RepositoryInterface;
use database\Mysql\MysqlDatabaseConnection;
use app\Entities\Words\Word;

class MysqlWordsRepository implements RepositoryInterface
{
  protected $db;

  public function __construct(MysqlDatabaseConnection $db)
  {
    $this->db = $db;
  }

  public function findAll(): array
  {
    $pdo = $this->db->mysqlConnect();
    $sql = "SELECT * FROM words";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    return $rows;
  }

  public function findOne(int $id): Word
  {
    $title = '';
    $image = '';
    $audio = '';

    $pdo = $this->db->mysqlConnect();
    $sql = "SELECT * FROM words WHERE id = {$id} LIMIT 1";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $row = $stm->fetch(PDO::FETCH_ASSOC);

    if (is_array($row)) {
      $title = array_key_exists('title', $row) ? $row['title'] : '';
      $image = array_key_exists('image', $row) ? $row['image'] : '';
      $audio = array_key_exists('audio', $row) ? $row['audio'] : '';
    }

    return new Word($title, $image, $audio);
  }
}