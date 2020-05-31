<?php

namespace Http\Request;

class Request implements RequestInterface
{
  protected $get;

  protected $post;

  public function __construct(array $get, array $post)
  {
    $this->get = $get;
    $this->post = $post;
  }

  public function get(string $name, $defaultValue)
  {
    return array_key_exists($name, $this->get) ? $this->get[$name] : $defaultValue;
  }

  public function post(string $name, $defaultValue)
  {
    return array_key_exists($name, $this->post) ? $this->post[$name] : $defaultValue;
  }
}