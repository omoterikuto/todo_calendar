<?php

// 新規登録

require_once(__DIR__ . '/../config/config.php');

$app = new MyApp\Controller\Signup();

$app->run();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Sign Up</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="account.css">
</head>
<body>
  <div id="container">
    <h1>新規登録</h1>
    <form action="" method="post" id="signup">
    <div class="input-form">

        <input type="text" name="email" placeholder="email" value="<?= isset($app->getValues()->email) ? h($app->getValues()->email): ''; ?>">
        <input type="password" name="password" placeholder="password">
      </div>
      <p class="err"><?= h($app->getErrors('email')); ?></p>
    <p class="err"><?= h($app->getErrors('password')); ?></p>
    <div class="btn-wrapper">
      <div class="btn" onclick="document.getElementById('signup').submit();">Sign Up</div>
      <a class="btn"href="<?= HOME_DIR; ?>/login.php">Log In</a>
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">

    </div>
    </form>
  </div>
</body>
</html>