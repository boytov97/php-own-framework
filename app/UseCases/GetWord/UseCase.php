<?php

namespace app\UseCases\GetWord;

use app\Entities\Words\Word;
use app\UseCases\UseCase as UseCaseInterface;
use app\Entities\Words\RepositoryInterface as WordsRepository;

class UseCase implements UseCaseInterface
{
  /** @var WordsRepository */
  protected $wordsRepository;

  public function __construct(WordsRepository $wordsRepository)
  {
    $this->wordsRepository = $wordsRepository;
  }

  /**
   * @param int $id
   * @return Word
   */
  public function execute(int $id): Word
  {
    return $this->wordsRepository->findOne($id);
  }
}