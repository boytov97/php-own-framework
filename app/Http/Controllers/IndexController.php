<?php

namespace app\Http\Controllers;

use database\Mysql\MysqlDatabaseConnection;
use Http\Request\RequestInterface as Request;
use Http\Controller\Controller;
use PDO;

class IndexController extends Controller
{
  /** @var MysqlDatabaseConnection */
  protected $db;

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * @throws \Exception
   */
  public function initialize()
  {
    $this->db = $this->di->get('db');
  }

  /**
   * @param Request $request
   * @throws \Exceptions\ViewException
   */
  public function indexAction(Request $request)
  {
    $param1 = $request->get('get_param_name', 12);
    $param2 = $request->post('post_param_name', 12);
    $pdo = $this->db->mysqlConnect();
    $sql = "SELECT * FROM developers";
    $stm = $pdo->prepare($sql);
    $stm->execute();
    $rows = $stm->fetchAll(PDO::FETCH_ASSOC);

    $this->view->render('index', [
      'developers' => $rows
    ]);
  }

  public function usersAction()
  {
    return $this->response->setResponse([
      [
      'id' => 'id',
      'name' => 'Name',
      'email' => 'email',
      ],
      [
        'id' => 'id',
        'name' => 'Name 1',
        'email' => 'email 1',
      ]
    ]);
  }
}