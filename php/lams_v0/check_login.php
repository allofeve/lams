<?php
include 'config.php';
include 'save_log.php';
$max_login_fail = 5;

$user = isset($_POST['username']) ? trim($_POST['username']) : '';
$pass = isset($_POST['password']) ? md5($_POST['password']) : '';
$remember = isset($_POST['remember']) ? true : false;

// 1. ตรวจสอบว่ามี Username นี้ในฐานข้อมูลหรือไม่
$stmt = $conn->prepare("SELECT * FROM user_tb WHERE username = ?");
$stmt->execute(array($user));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    // กรณีไม่มี Username ในระบบ
    echo json_encode(array('status' => 'error', 'message' => 'Username ไม่ได้ลงทะเบียนไว้'));
    exit;
}

// 2. ถ้ามี Username ให้เช็คก่อนว่าโดนแบนไปแล้วหรือยัง (login_fail > $max_login_fail)
if ($row['login_fail'] >= $max_login_fail) {
    write_log("พยายามเข้าสู่ระบบด้วยบัญชีที่ถูกระงับ: $user");
    echo json_encode(array('status' => 'error', 'message' => 'บัญชีของคุณถูกระงับ (เนื่องจากกรอกรหัสผิดเกิน 5 ครั้ง)'));
    exit;
}

// 3. ตรวจสอบรหัสผ่าน
if ($row['password'] === $pass) {
    // --- กรณีรหัสผ่านถูกต้อง ---
    $_SESSION['username'] = $row['username'];
    $_SESSION['level_id'] = $row['level_id'];

    // อัปเดตสถานะเป็น online และรีเซ็ต login_fail เป็น 0 เมื่อเข้าได้สำเร็จ
    $update_stmt = $conn->prepare("UPDATE user_tb SET user_status = 'online', login_fail = 0 WHERE id = ?");
    $update_stmt->execute(array($row['id']));

    if ($remember) {
        $token = md5($row['password']);
        $cookie_value = $user . ':' . $token;
        setcookie('remember_user', $cookie_value, time() + (86400 * 30), "/");
    }
	
    write_log("เข้าสู่ระบบสำเร็จ");
    echo json_encode(array('status' => 'success'));
} else {
    // --- กรณีรหัสผ่านไม่ถูกต้อง ---
    $new_fail = $row['login_fail'] + 1;
    
    // อัปเดตค่า login_fail +1 ลงฐานข้อมูล
    $update_fail = $conn->prepare("UPDATE user_tb SET login_fail = ? WHERE id = ?");
    $update_fail->execute(array($new_fail, $row['id']));

    write_log("รหัสผ่านไม่ถูกต้อง (Username: $user, ครั้งที่: $new_fail)");

    if ($new_fail >= $max_login_fail) {
        // ถ้าบวกครั้งล่าสุดแล้วเกิน $max_login_fail ให้แจ้งว่าถูกแบน
        echo json_encode(array('status' => 'error', 'message' => 'รหัสผ่านไม่ถูกต้อง คุณถูกระงับการเข้าใช้งานเนื่องจากกรอกผิดเกิน 5 ครั้ง'));
    } else {
        // ถ้ายังไม่ถึง $max_login_fail แจ้งเตือนปกติพร้อมบอกจำนวนครั้งที่เหลือ (ถ้าต้องการ)
        $remain = $max_login_fail - $new_fail;
        echo json_encode(array('status' => 'error', 'message' => "รหัสผ่านไม่ถูกต้อง (คุณเหลือโอกาสอีก $remain ครั้ง)"));
    }
}
?>