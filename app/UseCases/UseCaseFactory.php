<?php

namespace app\UseCases;

use app\Entities\Words\RepositoryInterface;

class UseCaseFactory
{
  /** @var RepositoryInterface */
  protected $wordsRepository;

  public function __construct(RepositoryInterface $wordsRepository)
  {
    $this->wordsRepository = $wordsRepository;
  }

  /**
   * @return array
   */
  public function getWordsData()
  {
    $wordsUseCase = new GetWordsData\UseCase($this->wordsRepository);
    return $wordsUseCase->execute();
  }

  /**
   * @param int $id
   * @return \app\Entities\Words\Word
   */
  public function getWord(int $id)
  {
    $wordsUseCase = new GetWord\UseCase($this->wordsRepository);
    return $wordsUseCase->execute($id);
  }
}