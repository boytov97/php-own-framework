<?php

namespace app\UseCases\GetWordsData;

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
   * @return array
   */
  public function execute(): array
  {
    return $this->wordsRepository->findAll();
  }
}