<?php

class Loader
{
  protected $classes = [];

  public function setClasses(array $classes)
  {
    $this->classes = $classes;
  }

  public function register()
  {
    foreach ($this->classes as $class) {
      require_once __DIR__ . '/../' .$class . '.php';
    }
  }
}