<?php
include 'config.php';

if(isset($_SESSION['username'])) {
    // เปลี่ยนสถานะเป็น offline ก่อนทำลาย session
    $stmt = $conn->prepare("UPDATE user_tb SET user_status = 'offline' WHERE username = ?");
    $stmt->execute(array($_SESSION['username']));
}

include 'save_log.php';
write_log("ออกจากระบบสำเร็จ");
	
// ลบ Session
session_destroy();

// ลบ Cookie โดยการตั้งเวลาให้หมดอายุไปแล้ว
if (isset($_COOKIE['remember_user'])) {
    setcookie('remember_user', '', time() - 3600, "/");
}

header("Location: login.php");
exit();

?>