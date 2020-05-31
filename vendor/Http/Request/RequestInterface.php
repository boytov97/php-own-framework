<?php

namespace Http\Request;

interface RequestInterface
{
  public function get(string $name, $defaultValue);

  public function post(string $name, $defaultValue);
}