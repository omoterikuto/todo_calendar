<?php
namespace MyApp;

class Token {
  public function validateToken() {
    if (!isset($_SESSION['token']) || $_SESSION['token'] !== $_POST['token']) {
      throw new \Exception('不正なアクセスです');
    }
  }
}
