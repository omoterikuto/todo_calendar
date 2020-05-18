<?php

// ログイン

require_once(__DIR__ . '/../config/config.php');

$app = new MyApp\Controller\Login();

$app->run();



?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Log In</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="account.css">
</head>
<body>
  <div id="container">
  <h1>ログイン</h1>
    <form action="" method="post" id="login">
      <div class="input-form">

        <input type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email) : ''; ?>">
        <input type="password" name="password" placeholder="password">
      </div>
      <p class="err"><?= h($app->getErrors('login')); ?></p>
      <div class="btn-wrapper">
        <div class="btn" onclick="document.getElementById('login').submit();">Log In</div>
        <a class="btn"href="<?= HOME_DIR; ?>/signup.php">Sign Up</a>
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </div>
    </form>
  </div>
</body>
</html>