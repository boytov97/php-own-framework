<?php

namespace app\Http\Controllers;

use Http\Controller\Controller;
use app\UseCases\UseCaseFactory;

class WordsController extends Controller
{
  protected $useCase;

  public function __construct()
  {
    parent::__construct();

    $this->useCase = new UseCaseFactory();
  }

  public function indexAction()
  {
    return "words";
  }
}