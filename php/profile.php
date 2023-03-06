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
$db = $mongo->selectDatabase("database");
$collection = $db->selectCollection("profiles");

// Get user ID from session
$session_id = $_COOKIE['session_id'];
$user_id = $redis->get($session_id);
if (!$user_id) {
  header("Location: /login.php");
  exit();
}

// Handle profile update request
if (isset($_POST['update'])) {
  $age = $_POST['age'];
  $dob = $_POST['dob'];
  $contact = $_POST['contact'];
  $stmt = $mysql->prepare("UPDATE users SET age = ?, dob = ?, contact = ? WHERE id = ?");
  $stmt->bind_param("issi", $age, $dob, $contact, $user_id);
  $stmt->execute();
  $stmt->close();
  $document = [
    'user_id' => $user_id,
    'age' => $age,
    'dob' => $dob,
    'contact' => $contact
  ];
  $collection->updateOne(['user_id' => $user_id], ['$set' => $document], ['upsert' => true]);
  echo "Profile updated";
}

// Get profile data
$stmt = $mysql->prepare("SELECT age, dob, contact FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($age, $dob, $contact);
$stmt->fetch();
$stmt->close();
$document = $collection->findOne(['user_id' => $user_id]);

// Close connections
$redis->close();
$mysql->close();

?>