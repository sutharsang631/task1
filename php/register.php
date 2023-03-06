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

// Connect to MongoDB
$mongo = new MongoDB\Client("mongodb://localhost:27017");

// Handle signup request
if (isset($_POST['signup'])) {
  $email = $_POST['email'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $stmt = $mysql->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $email, $password);
  $stmt->execute();
  $user_id = $stmt->insert_id;
  $stmt->close();
  $mongo->selectDatabase("profile")->selectCollection("users")->insertOne(["user_id" => $user_id, "age" => 0, "dob" => "", "contact" => ""]);
  $session_id = md5(uniqid(rand(), true));
  $redis->set($session_id, $user_id);
  setcookie("session_id", $session_id, time() + 86400, "/");
  header("Location: /profile.php");
  exit();
}

// Close connections
$redis->close();
$mysql->close();

?>
