<?php

namespace app\Http\Controllers;

use Http\Controller\Controller;
use app\UseCases\UseCaseFactory;
use Http\Request\RequestInterface as Request;

class WordsController extends Controller
{
  /** @var UseCaseFactory */
  protected $useCase;

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * @throws \Exception
   */
  public function initialize()
  {
    $this->useCase = $this->di->get(UseCaseFactory::class);
  }

  public function indexAction()
  {
    $words = $this->useCase->getWordsData();
    return $this->response->setResponse($words);
  }

  public function wordAction(Request $request, $id)
  {
    $word = $this->useCase->getWord((int)$id);
    return $this->response->setResponse($word->jsonSerialize());
  }
}