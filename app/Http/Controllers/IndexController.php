<?php

namespace app\Http\Controllers;

use Http\Request\RequestInterface as Request;
use Http\Controller\Controller;

class IndexController extends Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function indexAction(Request $request)
  {
    $param = $request->get('param', 12);

    $this->view->render('index', [
      'param' => $param
    ]);
  }

  public function usersAction()
  {
    return $this->response->setResponse([
      'test' => 123
    ]);
  }

  public function customersAction()
  {
    return "customers";
  }
}