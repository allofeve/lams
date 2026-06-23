<?php
// config.php
$host     = "localhost";
$db_name  = "loan_db";
$username = "root";
$password = ""; 
$clevel = 0;

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch(PDOException $exception) {
    die("Connection error: " . $exception->getMessage());
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ระบบ Auto Login ด้วย Cookie
if (!isset($_SESSION['username']) && isset($_COOKIE['remember_user'])) {
    $cookie_data = explode(':', $_COOKIE['remember_user']);
    if (count($cookie_data) == 2) {
        $user  = $cookie_data[0];
        $token = $cookie_data[1];

        $stmt = $conn->prepare("SELECT user_tb.*, level_tb.level_num AS level_num FROM user_tb INNER JOIN level_tb ON user_tb.level_id = level_tb.level_id WHERE user_tb.username = ?");
        $stmt->execute(array($user));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && md5($row['password']) === $token) {
            $_SESSION['username'] = $row['username'];
            $clevel    = $row['level_num'];
        }
    }
}

if (isset($_SESSION['username']) && !isset($_COOKIE['remember_user'])) {
        $stmt = $conn->prepare("SELECT user_tb.*, level_tb.level_num AS level_num FROM user_tb INNER JOIN level_tb ON user_tb.level_id = level_tb.level_id WHERE user_tb.username = ?");
        $stmt->execute(array($_SESSION['username']));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $clevel    = $row['level_num'];
        }
}

if (isset($_SESSION['username']) && isset($_COOKIE['remember_user'])) {
    $cookie_data = explode(':', $_COOKIE['remember_user']);
    if (count($cookie_data) == 2) {
        $user  = $cookie_data[0];
        $token = $cookie_data[1];

        $stmt = $conn->prepare("SELECT user_tb.*, level_tb.level_num AS level_num FROM user_tb INNER JOIN level_tb ON user_tb.level_id = level_tb.level_id WHERE user_tb.username = ?");
        $stmt->execute(array($user));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && md5($row['password']) === $token) {
            $clevel    = $row['level_num'];
        }
    }
}
?>