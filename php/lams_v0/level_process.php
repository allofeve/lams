<?php
include 'config.php';
header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบสิทธิ์ (ต้องเป็น Level 99 เท่านั้น)
if (!isset($clevel) || $clevel != 99) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

try {
    // รายการ Level ทั้งหมด
    if ($action == 'list_levels') {
        $stmt = $conn->query("SELECT * FROM level_tb ORDER BY level_num ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    // เพิ่ม Level ใหม่
    else if ($action == 'add_level') {
        $level_num = intval($_POST['level_num']);
        $grant_name = trim($_POST['grant_name']);

        // เช็คว่ามีตัวเลข level นี้หรือยัง
        $check = $conn->prepare("SELECT level_id FROM level_tb WHERE level_num = ?");
        $check->execute([$level_num]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'มีเลขระดับ (Level Num) นี้อยู่ในระบบแล้ว']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO level_tb (level_num, grant_name) VALUES (?, ?)");
        $stmt->execute([$level_num, $grant_name]);
        echo json_encode(['success' => true]);
    } 

    // อัปเดต Level
    else if ($action == 'update_level') {
        $level_id = intval($_POST['level_id']);
        $level_num = intval($_POST['level_num']);
        $grant_name = trim($_POST['grant_name']);

        // เช็คซ้ำกรณีเปลี่ยนเลข level_num
        $check = $conn->prepare("SELECT level_id FROM level_tb WHERE level_num = ? AND level_id != ?");
        $check->execute([$level_num, $level_id]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'มีเลขระดับนี้อยู่ในระบบแล้ว']);
            exit;
        }

        $stmt = $conn->prepare("UPDATE level_tb SET level_num = ?, grant_name = ? WHERE level_id = ?");
        $stmt->execute([$level_num, $grant_name, $level_id]);
        echo json_encode(['success' => true]);
    }

    // ลบ Level
    else if ($action == 'delete_level') {
        $level_id = intval($_POST['level_id']);
        
        // ตรวจสอบก่อนว่ามี User ไหนใช้ Level นี้อยู่หรือไม่
        $check_user = $conn->prepare("SELECT id FROM user_tb WHERE level_id = ? LIMIT 1");
        $check_user->execute([$level_id]);
        if ($check_user->fetch()) {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบได้ เนื่องจากมีผู้ใช้งานอยู่ในระดับนี้']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM level_tb WHERE level_id = ?");
        $stmt->execute([$level_id]);
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn = null;
// ลบปีกกาตัวสุดท้ายที่เกินมาออกแล้ว
?>