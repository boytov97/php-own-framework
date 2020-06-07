<?php

use Http\Request\RequestInterface;
use Exceptions\RouterException;

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
   * @throws RouterException
   */
  public function execute(string $requestUri, string $method)
  {
    $foundRoute = [];
    if (count($this->routes) > 0) {
      foreach ($this->routes as $route) {
        if ("/" === $requestUri || "/index" === $requestUri || "/index/index" === $requestUri) {
          $foundRoute = [
            'controller' => 'index',
            'action' => 'index'
          ];
          break;
        }

        if ($route['uri'] === $requestUri) {
          if ($route['method'] === 'any') {
            $foundRoute = $route;
          } else {
            if ($route['method'] === strtolower($method)) {
              $foundRoute = $route;
            }
          }
        }
      }

      if (count($foundRoute) === 0) {
        $requestUriElements = explode('/', $requestUri);

        foreach ($this->routes as $route) {
          preg_match('/^.+{(.+)}$/', $route['uri'], $matches);

          if (count($matches) > 1) {
            $parameterRegex = $matches[1];
            $requestUriRegex = preg_replace('/{.+}$/', $parameterRegex, $route['uri']);
            $requestUriRegex = preg_replace('/\//', '\\/', $requestUriRegex);
            preg_match("/^{$requestUriRegex}$/", $requestUri, $requestUriMatches);

            if (count($requestUriMatches) > 0) {
              if ($route['method'] === 'any') {
                $foundRoute = $route;
                $foundRoute['parameter'] = array_pop($requestUriElements);
              } else {
                if ($route['method'] === strtolower($method)) {
                  $foundRoute = $route;
                  $foundRoute['parameter'] = array_pop($requestUriElements);
                }
              }
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
   * @throws RouterException
   */
  protected function build(array $foundRoute)
  {
    if (count($foundRoute) > 0) {
      $controllerPath = $this->getControllerPath($foundRoute);

      if ($controllerPath) {
        //include $controllerPath;

        $controller = $this->getController($foundRoute);
        $action = $this->getAction($foundRoute);

        try {
          $reflector = new ReflectionClass($controller);
        } catch (ReflectionException $e) {
          throw new RouterException("The controller {$controller} does not exist");
        }

        if (!$reflector->isInstantiable()) {
          throw new RouterException("The controller is not instantiable");
        }

        /** @var \Http\Controller\Controller $newController */
        $newController = new $controller;
        $currentMethodParams = [];
        $hasAction = $this->checkAction($action, $reflector);

        if ($hasAction) {
          $newController->view->setView($this->getControllerName($foundRoute));
          $newController->setDi($this->di);
          $newController->initialize();

          $reflectionClass = new ReflectionClass($controller);
          $currentMethodParams = $reflectionClass->getMethod($action)->getParameters();
        }

        if (count($currentMethodParams) > 0) {
          $request = null;
          $parameter = null;
          $instanceOfRequest = true;
          foreach ($currentMethodParams as $param) {
            $paramClass = $param->getClass();

            if (is_null($paramClass)) {
              $parameter = array_key_exists('parameter', $foundRoute) ? $foundRoute['parameter'] : null;
            } else {
              $parameterClassName = $paramClass->getName();
              $request = ($parameterClassName === 'Http\Request\RequestInterface')
                ? $this->di->get($parameterClassName)
                : $instanceOfRequest = false;
            }
          }

          if (!$instanceOfRequest) {
            throw new RouterException("First parameter class must be Http\Request\RequestInterface");
          }

          if ($request && $parameter) {
            return $newController->$action($request, $parameter);
          } elseif ($request) {
            return $newController->$action($request);
          } else {
            return $newController->$action($parameter);
          }
        }

        if ($hasAction) {
          return $newController->$action();
        } else {
          throw new RouterException("Action not found");
        }
      } else {
        throw new RouterException("Controller not found");
      }
    } else {
      throw new RouterException("Route not found");
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
    $controllerName = array_key_exists('controller', $route) ? $route['controller'] : "index";
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
    return array_key_exists('controller', $route) ? $route['controller'] : "index";
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