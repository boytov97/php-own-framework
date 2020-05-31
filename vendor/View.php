<?php

class View
{
  protected $viewDir = __DIR__ . "/../app/views/";

  protected $layoutsDir = __DIR__ . "/../app/views/layouts";

  protected $layout = 'index.phtml';

  protected $view = 'index';

  public function render(string $viewName, array $data)
  {
    extract($data);
    require($this->layoutsDir . "/" . $this->layout);
    require($this->viewDir . "/" . $this->view . "/" . $viewName . ".phtml");
    require($this->layoutsDir . "/footer.phtml");

    return;
  }

  public function setLayout(string $layoutName)
  {
    $this->layout = strtolower($layoutName) . ".phtml";
  }

  public function setView(string $viewName)
  {
    $this->view = strtolower($viewName);
  }
}