<?php

use Http\Request\Request;
use Http\Response\Response;
use Http\Request\RequestInterface;

class Application
{
  /** @var DependencyInjection */
  protected $di;

  /** @var Router  */
  protected $router;

  public function __construct(Router $router)
  {
    $this->router = $router;
  }

  public function setDi(DependencyInjection $di)
  {
    $this->di = $di;
  }

  /**
   * @throws ReflectionException
   * @throws \Exceptions\RouterException
   */
  public function run()
  {
    $request = new Request($_GET, $_POST);
    $this->di->set(RequestInterface::class, $request);
    $this->router->setDi($this->di);

    $requestUri = $_SERVER['REQUEST_URI'];
    $requestUri = explode('?', $requestUri);
    $requestUri = $requestUri[0];

    $response = $this->router->execute($requestUri, $_SERVER['REQUEST_METHOD']);

    if ($response instanceof Response) {
      echo $response->getResponse();
    } else {
      echo $response;
    }
  }
}