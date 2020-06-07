<?php

namespace Http\Response;

class Response
{
  protected $response = [];

  public function setResponse(array $response)
  {
    $this->response = $response;
    return $this;
  }

  public function getResponse()
  {
    header('Content-Type: application/json');
    return json_encode($this->response);
  }
}