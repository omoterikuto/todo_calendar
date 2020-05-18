<?php 

namespace MyApp\Controller;

class Index extends \MyApp\Controller {

  public function run() {
    if (!$this->isLoggedIn()) {
      // login
      header('location: '. SITE_URL . '/login.php');
    }

    // get users info

    $userModel = new \MyApp\Model\User();
    $this->setValues('users', $userModel->findAll());
  }

}