<?php

namespace MyApp;

class Todo {
  private $db;

  public function __construct() {
    try {
      $this->db = new \PDO(DSN, DB_USERNAME, DB_PASSWORD);
      $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {
      echo $e->getMessage();
      exit;
    }
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = sha1(uniqid(mt_rand(), true));
    }
  }

  public function getAll() {
    $id = $_SESSION['me']->id;
    $sql = "select * from tasks where user_id = $id order by date ASC";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(\PDO::FETCH_OBJ);
  }

  private function validateToken() {
    if (!isset($_SESSION['token']) ||
    !isset($_POST['token']) ||
    $_SESSION['token'] !== $_POST['token']
  ) {
    throw new \Exception('invalid token');
  }
  }
  public function post() {
    $this->validateToken();
    try {
      if (!isset($_POST['mode'])) {
        throw new \Exception('mode not set!');
      }
      switch ($_POST['mode']) {
        case 'update':
          return $this->update();
        case 'create':
          return $this->create();
        case 'delete':
          return $this->delete();
        case 'display':
          return $this->display();
      }

    } catch(\Exception $e) {
      $_SESSION['error'] = $e->getMessage();
    }
  }
  private function update() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[update] id not set!');
    }

    $this->db->beginTransaction();

    $sql = sprintf("update tasks set state = (state + 1) %% 2 where id = %d", $_POST['id']);
    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    $sql = sprintf("select state from tasks where id = %d", $_POST['id']);
    $stmt = $this->db->query($sql);
    $state = $stmt->fetchColumn();

    $this->db->commit();

    return [
      'state' => $state
    ];

  }

  private function create() {
    if (!isset($_POST['title']) || $_POST['title'] === '') {
      throw new \Exception('[create] title not set!');
    }
    if (!isset($_POST['day']) || $_POST['day'] === '') {
      throw new \Exception('[create] day not set!');
    }
    if (!isset($_POST['year_month']) || $_POST['year_month'] === '') {
      throw new \Exception('[year_month] day not set!');
    }
    $date = new \DateTime('now');
    $hms = $date->format(' H:m:s');
    $day = substr($_POST['day'], 4, 2);
    $year = mb_substr($_POST['year_month'],0 ,4);
    $month = mb_substr($_POST['year_month'],5, -1);
    if (mb_strlen($_POST['day']) == 13) {
      $month -= 1;
    } elseif(mb_strlen($_POST['day']) == 11) {
      $month += 1;
    }
    $month = str_pad($month, 2, 0, STR_PAD_LEFT);
    $save_date = $year. '-' . $month. '-' .$day . $hms;

    $sql = "insert into tasks (user_id, title, date, created) values (:user_id, :title, :date, :created)";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
      ':title' => $_POST['title'],
      ':user_id' => $_SESSION['me']->id,
      ':date' => $save_date,
      ':created' => $date->format('Y-m-d H:m:s')
      ]);
    return [
      'id' => $this->db->lastInsertId(),
      'date' => $save_date,
      'created' => $date->format('Y-m-d')
    ];
  }

  private function delete() {
    if (!isset($_POST['id'])) {
      throw new \Exception('[delete] id not set!');
    }


    $sql = sprintf("delete from tasks where id = %d", $_POST['id']);
    $stmt = $this->db->prepare($sql);
    $stmt->execute();


    return [
    ];
  }

  public function display() {
    if (!isset($_POST['year_month']) || $_POST['year_month'] === '') {
      throw new \Exception('year_monthがセットされていません');
    }
    if (!isset($_POST['day']) || $_POST['day'] === '') {
      throw new \Exception('dayがセットされていません');
    }
    $day = substr($_POST['day'], 4, 2);
    
    $year = mb_substr($_POST['year_month'],0 ,4);
    $month = mb_substr($_POST['year_month'],5, -1);
    if (mb_strlen($_POST['day']) == 13) {
      $month -= 1;
    } elseif(mb_strlen($_POST['day']) == 11) {
      $month += 1;
    }
    
    $display_day = $year. '-' . $month . '-' . $day;
    $input_day = $year . '年' . $month . '月' . $day . '日にやることは？';
    
    $id = $_SESSION['me']->id;
    
    $sql = "select * from tasks where user_id = $id AND DATE_FORMAT(date, '%m%d') = DATE_FORMAT('$display_day', '%m%d') order by date DESC";
    $stmt = $this->db->query($sql);
    $res = $stmt->fetchAll(\PDO::FETCH_OBJ);
    if (empty($res)) {
      $res = null;
    }
    return [
      'obj' => $res,
      'date' => $input_day
    ];
  }
}
