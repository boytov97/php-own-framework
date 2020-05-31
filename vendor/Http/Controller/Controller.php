<?php

namespace Http\Controller;

use Http\Response\Response;
use View;

class Controller
{
  /** @var View */
  public $view;
  /** @var Response */
  public $response;

  public function __construct()
  {
    $this->view = new View();
    $this->response = new Response();
  }
}