<?php

namespace app\Entities\Words;

interface RepositoryInterface
{
  public function findAll(): array;

  public function findOne(int $id): Word;
}