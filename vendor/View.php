<?php

use Exceptions\ViewException;

class View
{
  protected $viewDir = __DIR__ . "/../app/views/";

  protected $layoutsDir = __DIR__ . "/../app/views/layouts";

  protected $layout = 'index.phtml';

  protected $view = 'index';

  protected $contentViewName = 'index';

  protected $data = [];

  /**
   * @param string $viewName
   * @param array $data
   * @throws ViewException
   */
  public function render(string $viewName, array $data)
  {
    $this->data = $data;
    extract($data);

    $this->contentViewName = $viewName;
    $layoutPath = $this->layoutsDir . "/" . $this->layout;

    if (file_exists($layoutPath)) {
      require($layoutPath);
    } else {
      throw new ViewException("The layout {$layoutPath} not found");
    }

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

  /**
   * @throws ViewException
   */
  public function getContent()
  {
    extract($this->data);
    $contentViewPath = $this->viewDir . "/" . $this->view . "/" . $this->contentViewName . ".phtml";

    if (file_exists($contentViewPath)) {
      require($contentViewPath);
    } else {
     throw new ViewException("The content {$contentViewPath} not found");
    }
  }
}