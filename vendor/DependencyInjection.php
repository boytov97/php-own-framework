<?php

class DependencyInjection
{
  protected $di = [];

  public function set(string $name, $instance)
  {
    $this->di[$name] = $instance;
  }

  public function get(string $name)
  {
    return $this->di[$name];
  }
}