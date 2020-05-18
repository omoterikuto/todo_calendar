<?php

require_once(__DIR__ . '/../config/config.php');

if (!isset($_SESSION['me'])) {
  header('Location: ' . HOME_DIR . '/login.php');
}

// get todos
$todoApp = new \MyApp\Todo();
$todos = $todoApp->getAll();


$cal = new MyApp\Calendar();

$user_meta = $_SESSION['me'];

$date = new DateTime('now');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>やることカレンダー</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">

  <link href="https://fonts.googleapis.com/css?family=Kosugi&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <span>
      <?= $date->format('Y年n月d日 G:i'); ?>
      <?= '[ ' . $user_meta->email . ' ]'; ?>
    </span>
    <form action="./logout.php" method="post" id="logout">
      <input type="submit" value="Log Out">
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    </form>
  </header>
    <h1>やることリスト</h1>
  <div class="container">
    <table>
      <thead>
        <tr>
          <th><a href="<?= HOME_DIR; ?>/?t=<?php echo h($cal->prev);?>">&laquo;</a></th>
          <th id="year_month" colspan="5"><?php echo h($cal->year).'年'.h($cal->month).'月'; ?></th>
          <th><a href="<?= HOME_DIR; ?>/?t=<?php echo h($cal->next);?>">&raquo;</a></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Sun</td>
          <td>Mon</td>
          <td>Tue</td>
          <td>Wed</td>
          <td>Thu</td>
          <td>Fri</td>
          <td>Sat</td>
        </tr>
        <?php echo $cal->show(); ?>
      </tbody>
      <tfoot>
          <tr>
            <th colspan="7"><a href="<?= HOME_DIR; ?>">Today</a></th>
          </tr>
      </tfoot>
    </table>
    <div class="todo-container">
      <form action="" id="new-todo-form">
        <input type="text" id="new-todo" placeholder="今日やることは？">
        <input type="hidden" name="user_id" value="<?= $user_meta->id; ?>">
      </form>
      <p id="none_message">やることはありません</p>
      <ul id="todos">
        <li id="todo_template" data-id="">
          <input type="checkbox" class="update-todo">
          <span class="todo-title"></span>
          <div class="delete-todo">×</div>
        </li>
      </ul>
    </div>
  </div>
  <div class="all-todo-container">
  <h2 class="all-todos-title">全てのタスク</h2>
    <ul id="all_todos"><?php foreach ($todos as $todo) : ?>
    <li class="todo_<?= h($todo->id); ?>" data-id="<?= h($todo->id); ?>">
      <input type="checkbox" class="update-todo" <?php if ($todo->state === '1') { echo 'checked'; } ?>>
      <span class="todo-title <?php if ($todo->state === '1') { echo 'done'; } ?>"><?= h($todo->title); ?></span>
      <span class="todo-date">タスク日 <?= mb_substr(h($todo->date), 0, 10); ?></span>
      <span class="todo-date">作成日 <?= mb_substr(h($todo->created), 0, 10); ?></span>
      <div class="delete-todo">x</div>
    </li><?php endforeach; ?>
</ul>
    <li id="all_todo_template" data-id="">
      <input type="checkbox" class="update-todo">
      <span class="todo-title"></span>
      <span class="todo-date"></span>
      <span class="todo-created"></span>
      <div class="delete-todo">×</div>
    </li>
  </div>
  <input type="hidden" id="token" name="" value="<?= h($_SESSION['token']);  ?>">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="<?= HOME_DIR;?>/todo.js"></script>
</body>
</html>
