<?php

namespace Http\Controller;

use Http\Response\Response;
use DependencyInjection;
use View;

class Controller
{
  /** @var View */
  public $view;
  /** @var Response */
  public $response;
  /** @var DependencyInjection */
  public $di;

  public function __construct()
  {
    $this->view = new View();
    $this->response = new Response();
  }
  
  public function setDi(DependencyInjection  $di)
  {
    $this->di = $di;
  }

  public function initialize()
  {

  }
}