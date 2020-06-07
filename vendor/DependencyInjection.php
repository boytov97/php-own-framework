<?php

use Exceptions\DiException;

class DependencyInjection
{
  protected $di = [];

  /**
   * @param string $name
   * @param $definition
   */
  public function set(string $name, $definition)
  {
    $this->di[$name] = $definition;
  }

  /**
   * @param string $name
   * @return mixed
   * @throws Exception
   */
  public function get(string $name)
  {
    if (array_key_exists($name, $this->di)) {
      if (is_callable($this->di[$name])) {
        return $this->di[$name]();
      } else {
        return $this->di[$name];
      }
    } else {
      throw new DiException("{$name} not found in DependencyInjection");
    }
  }

  public function has(string $name)
  {
    return array_key_exists($name, $this->di);
  }
}