<?php
$login = filter_var(
  trim($_POST['login']),
  FILTER_SANITIZE_STRING
);

$pass = filter_var(
  trim($_POST['pass']),
  FILTER_SANITIZE_STRING
);

$name = filter_var(
  trim($_POST['name']),
  FILTER_SANITIZE_STRING
);

$mysql = new mysqli('localhost', 'ADMIN', 'ADMIN', 'ADMIN');
$result = $mysql->query("SELECT * FROM `game_haven_users`
  WHERE `login` = '$login'");

$user = $result->fetch_assoc();
$mysql->close();
if (count($user) == 0) {
  $pass = md5($pass);

  $mysql = new mysqli('localhost', 'ADMIN', 'ADMIN', 'ADMIN');
  $mysql->query("INSERT INTO `game_haven_users` (`login`, `pass`, `name`)
    VALUES('$login', '$pass', '$name')");
  $mysql->close();
  $mysql = new mysqli('localhost', 'ADMIN', 'ADMIN', 'ADMIN');
  $result = $mysql->query("SELECT * FROM `game_haven_users`
    WHERE `login` = '$login' AND `pass` = '$pass'");

  $user = $result->fetch_assoc();

  setcookie('user', $user['name'], time() + 60 * 60 * 12, "/");
  setcookie('cookieLogin', $user['login'], time() + 60 * 60 * 12, "/");

  header('Location: ./main.php');
} else {
  header('Location: ./registration_form.php?error=user_is_exists');
  exit;
}
?>