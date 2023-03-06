<?php

session_start();

// Connect to Redis
$redis = new Redis();
$redis->connect('localhost', 6379);

// Connect to MySQL
$mysql = new mysqli("host", "username", "password", "database");
if ($mysql->connect_errno) {
  die("Failed to connect to MySQL: " . $mysql->connect_error);
}

// Handle login request
if (isset($_POST['login'])) {
  $email = $_POST['email'];
  $password = $_POST['password'];
  $stmt = $mysql->prepare("SELECT id, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->bind_result($user_id, $hash);
  if ($stmt->fetch() && password_verify($password, $hash)) {
    $session_id = md5(uniqid(rand(), true));
    $redis->set($session_id, $user_id);
    setcookie("session_id", $session_id, time() + 86400, "/");
    header("Location: /profile.php");
    exit();
  } else {
    echo "Wrong email or password";
  }
  $stmt->close();
}

// Close connection
$redis->close();
$mysql->close();

?>