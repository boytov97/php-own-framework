<?php

namespace Library\Autowire;

use DependencyInjection;

class Autowire
{
  /**
   * @param DependencyInjection $di
   * @param $definitions
   * @throws \Exception
   */
  public static function addDefinitions(DependencyInjection $di, $definitions)
  {
    if (count($definitions) === 0) {
      return;
    }

    foreach ($definitions as $key => $definition) {
      if (is_string($definition)) {
        $autowireBuilder = new AutowireBuilder($di);
        $closure = $autowireBuilder->build($definition);
        $di->set($key, $closure);
      }
    }
  }
}