<?php

$host = '127.0.0.1';
$db   = 'mydb3';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$user = 'db_user';
$pass = 'S5BEIzXK7XjwszW8';

$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
$options[PDO::ATTR_DEFAULT_FETCH_MODE] = PDO::FETCH_ASSOC;
$options[PDO::ATTR_EMULATE_PREPARES] = false;

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
  throw new PDOException($e->getMessage(), (int)$e->getCode());
}

$sql = 'SELECT * FROM page WHERE pageKey = ?';
$page_query = $pdo->prepare($sql);

$page = "home";
$page_data;

if (isset($_GET['p'])) {
  $tmp = trim(strtolower(strip_tags($_GET['p'])));
  $page_query->execute([$tmp]);

  if ($page_query->rowCount() == 1) {
    $page = $tmp;
    $page_data = $page_query->fetch();
  } else {
    $page_query->execute(['home']);
    $page_data = $page_query->fetch();
  }
} else {
  $page_query->execute(['home']);
  $page_data = $page_query->fetch();
}

$site_user;
if (isset($_GET['logout'])){
    setcookie('user', '', time()-1);
    setcookie('code', '', time()-1);
} else {

if((isset($_POST['login']))&&($_POST['username'])&&($_POST['pass'])){
  $result = User::loginWithPassword(($_POST['username']),($_POST['pass']));
  if ($result['status']){
    $site_user = $result['user'];

    setcookie('user', $site_user['userName']);
    setcookie('code', $result['cookie']);
  } else {
    if ((isset($_COOKIE['code']))&&(isset($_COOKIE['user'])))
    {
    $result = User::loginWithCookies(($_COOKIE['user']),($_COOKIE['code']));
    if($result['status']){
     $site_user = $result['user'];
    }
  } 
}

}
}