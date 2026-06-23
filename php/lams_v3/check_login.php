<?php
include 'config.php';

$user = isset($_POST['username']) ? $_POST['username'] : '';
$pass = isset($_POST['password']) ? md5($_POST['password']) : '';
$remember = isset($_POST['remember']) ? true : false;

$stmt = $conn->prepare("SELECT * FROM user_tb WHERE username = ? AND password = ?");
$stmt->execute(array($user, $pass));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row) {
    $_SESSION['username'] = $row['username'];

    // --- ส่วนที่เพิ่มใหม่: อัปเดตสถานะเป็น online ---
    $update_stmt = $conn->prepare("UPDATE user_tb SET user_status = 'online' WHERE id = ?");
    $update_stmt->execute(array($row['id']));
    // ------------------------------------------

    if($remember) {
        $token = md5($row['password']);
        $cookie_value = $user . ':' . $token;
        setcookie('remember_user', $cookie_value, time() + (86400 * 30), "/");
    }
	include 'save_log.php';
	write_log("เข้าสู่ระบบสำเร็จ");
    echo json_encode(array('status' => 'success'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Username หรือ Password ไม่ถูกต้อง'));
}
?>