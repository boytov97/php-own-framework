<?php

use Http\Request\RequestInterface;

class Router
{
  /** @var DependencyInjection */
  protected $di;

  /** @var RequestInterface */
  protected $request;

  protected $routes = [];

  protected $bathDir = __DIR__ . "/../";

  protected $appDir = __DIR__ . "/../app/";

  protected $controllerDir = __DIR__ . "/../app/Http/Controllers/";

  protected $controllerNameSpace = "\app\Http\Controllers";

  public function __construct(array $routes)
  {
    $this->routes = $routes;
  }

  /**
   * @param DependencyInjection $di
   */
  public function setDi(DependencyInjection $di)
  {
    $this->di = $di;
  }

  /**
   * @param string $requestUri
   * @param string $method
   * @return string
   * @throws ReflectionException
   */
  public function execute(string $requestUri, string $method)
  {
    $foundRoute = [];
    if (count($this->routes) > 0) {
      foreach ($this->routes as $routeMethod => $route) {
        if ("/" === $requestUri || "/index" === $requestUri || "/index/index" === $requestUri) {
          $foundRoute = [
            'controller' => 'index',
            'action' => 'index'
          ];
          break;
        }

        if ($route['uri'] === $requestUri) {
          if ($routeMethod === 'any') {
            $foundRoute = $route;
          } else {
            if ($route['method'] === strtolower($method)) {
              $foundRoute = $route;
            }
          }
        }
      }
    }

    if (!count($foundRoute)) {
      if ("/" === $requestUri || "/index" === $requestUri || "/index/index" === $requestUri) {
        $foundRoute = [
          'controller' => 'index',
          'action' => 'index'
        ];
      }
    }

    return $this->build($foundRoute);
  }

  /**
   * @param array $foundRoute
   * @return string
   * @throws ReflectionException
   */
  protected function build(array $foundRoute)
  {
    if (count($foundRoute) > 0) {
      $controllerPath = $this->getControllerPath($foundRoute);

      if ($controllerPath) {
        include $controllerPath;

        $controller = $this->getController($foundRoute);
        $action = $this->getAction($foundRoute);

        try {
          $reflector = new ReflectionClass($controller);
        } catch (ReflectionException $e) {
          return "The controller {$controller} does not exist";
        }

        if (!$reflector->isInstantiable()) {
          return "The controller is not instantiable";
        }

        /** @var \Http\Controller\Controller $newController */
        $newController = new $controller;
        $currentMethodParams = [];
        $hasAction = $this->checkAction($action, $reflector);

        if ($hasAction) {
          $newController->view->setView($this->getControllerName($foundRoute));

          $reflectionClass = new ReflectionClass($controller);
          $currentMethodParams = $reflectionClass->getMethod($action)->getParameters();
        }

        if (count($currentMethodParams) > 0) {
          $firstParamClass = $currentMethodParams[0]->getClass()->name;

          if ($firstParamClass === 'Http\Request\RequestInterface') {
            $request = $this->di->get('request');
            return $newController->$action($request);
          }
        }

        if ($hasAction) {
          return $newController->$action();
        } else {
          return "Action not found";
        }
      } else {
        return "Controller not found";
      }
    } else {
      return "Route not found";
    }
  }

  /**
   * @param string $action
   * @param ReflectionClass $reflector
   * @return bool
   */
  protected function checkAction(string $action, ReflectionClass $reflector)
  {
    $result = false;

    foreach ($reflector->getMethods() as $method) {
      if ($action === $method->name) {
        $result = true;
      }
    }

    return $result;
  }

  /**
   * @param array $route
   * @return bool|string
   */
  protected function getControllerPath(array $route)
  {
    $controllerName = array_key_exists('controller', $route) ? $route['controller'] : "";
    $controller = ucfirst($controllerName) . "Controller.php";
    $controllerPath = $this->controllerDir . $controller;

    if (file_exists($controllerPath)) {
      return $controllerPath;
    }

    return false;
  }

  /**
   * @param array $route
   * @return mixed|string
   */
  protected function getControllerName(array $route)
  {
    return array_key_exists('controller', $route) ? $route['controller'] : "";
  }

  /**
   * @param array $route
   * @return string
   */
  protected function getController(array $route)
  {
    $controllerName = array_key_exists('controller', $route) ? $route['controller'] : "index";
    $controllerName = ucfirst($controllerName) . "Controller";

    return $this->controllerNameSpace . "\\" . $controllerName;
  }

  /**
   * @param array $route
   * @return string
   */
  protected function getAction(array $route)
  {
    $action = array_key_exists('action', $route) ? $route['action'] : "index";
    return $action . "Action";
  }

  /**
   * @param array $route
   * @return mixed|string
   */
  protected function getActionName(array $route)
  {
    return array_key_exists('action', $route) ? $route['action'] : "index";
  }
}