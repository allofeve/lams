<?php
include 'config.php';
include 'save_log.php'; // บันทึก Log ด้วย
header('Content-Type: application/json');

if(!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired']);
    exit;
}

$user = $_SESSION['username'];
$old_pass = md5($_POST['old_pass']);
$new_pass = $_POST['new_pass'];
$confirm_pass = $_POST['confirm_pass'];

try {
    // 1. ตรวจสอบรหัสผ่านเดิมก่อน
    $stmt = $conn->prepare("SELECT id FROM user_tb WHERE username = ? AND password = ?");
    $stmt->execute([$user, $old_pass]);
    
    if(!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง']);
        exit;
    }

    // 2. ตรวจสอบว่ารหัสใหม่ยาวพอหรือไม่ (ตัวอย่าง 4 ตัวอักษรขึ้นไป)
    if(strlen($new_pass) < 4) {
        echo json_encode(['success' => false, 'message' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 4 ตัวอักษร']);
        exit;
    }

    // 3. อัปเดตรหัสผ่านใหม่
    $update = $conn->prepare("UPDATE user_tb SET password = ? WHERE username = ?");
    if($update->execute([md5($new_pass), $user])) {
        write_log("เปลี่ยนรหัสผ่านใหม่สำเร็จ");
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่สามารถเปลี่ยนรหัสผ่านได้']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}