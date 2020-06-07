<?php

namespace Library\Autowire;

use DependencyInjection;
use library\Exceptions\BindingResolutionException;
use ReflectionClass;
use ReflectionException;

class AutowireBuilder
{
  /** @var DependencyInjection */
  protected $di;

  public function __construct(DependencyInjection $di)
  {
    $this->di = $di;
  }

  /**
   * @param $concrete
   * @return object|string
   * @throws \Exception
   */
  public function build($concrete)
  {
    try {
      $reflector = new ReflectionClass($concrete);
    } catch (ReflectionException $e) {
      throw new BindingResolutionException("The controller {$concrete} does not exist");
    }

    if (!$reflector->isInstantiable()) {
      throw new BindingResolutionException("The controller is not instantiable");
    }

    $constructor = $reflector->getConstructor();

    // If there are no constructors, that means there are no dependencies then
    // we can just resolve the instances of the objects right away, without
    // resolving any other types or dependencies out of these containers.
    if (is_null($constructor)) {
      return new $concrete;
    }

    $dependencies = $constructor->getParameters();

    $instances = $this->resolveDependencies($dependencies);

    return $reflector->newInstanceArgs($instances);
  }

  /**
   * @param array $dependencies
   * @return array
   * @throws \Exception
   */
  protected function resolveDependencies(array $dependencies)
  {
    $results = [];

    foreach ($dependencies as $dependency) {
      $results[] = is_null($dependency->getClass())
        ? $this->resolvePrimitive($dependency)
        : $this->resolveClass($dependency);
    }

    return $results;
  }

  protected function resolvePrimitive(\ReflectionParameter $parameter)
  {
    if ($parameter->isDefaultValueAvailable()) {
      return $parameter->getDefaultValue();
    } else {
      $message = "Unresolvable dependency resolving [$parameter] in class {$parameter->getDeclaringClass()->getName()}";
      throw new BindingResolutionException($message);
    }
  }

  /**
   * @param \ReflectionParameter $parameter
   * @return mixed
   * @throws \Exception
   */
  protected function resolveClass(\ReflectionParameter $parameter)
  {
    try {
      $concrete = $parameter->getClass()->getName();

      if ($this->di->has($concrete)) {
        return $this->di->get($concrete);
      }

      throw new BindingResolutionException("The class {$concrete} does not exist");
    } catch (BindingResolutionException $exception) {
      if ($parameter->isDefaultValueAvailable()) {
        return $parameter->getDefaultValue();
      }
      throw $exception;
    }
  }
}