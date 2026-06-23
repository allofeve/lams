<?php
include 'config.php';
include 'save_log.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'มีการเข้าถึงโดยไม่ได้รับอนุญาต']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$current_level = intval($clevel);

try {
    if ($action == 'online_list') {
        $stmt = $conn->query("SELECT user_tb.username AS username, level_tb.level_num AS level_num FROM user_tb INNER JOIN level_tb ON user_tb.level_id = level_tb.level_id WHERE user_tb.user_status = 'online' ORDER BY user_tb.username ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($current_level != 99) {
        echo json_encode(['success' => false, 'message' => 'สิทธิ์ไม่เพียงพอ']);
        exit;
    }

    if ($action == 'list') {
        $stmt = $conn->query("SELECT user_tb.id AS id, user_tb.username AS username, user_tb.level_id AS level_id, level_tb.level_num AS level_num FROM user_tb INNER JOIN level_tb ON user_tb.level_id = level_tb.level_id ORDER BY level_tb.level_num DESC, user_tb.username ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } 
    
    // เพิ่มผู้ใช้งานใหม่
	else if ($action == 'add') {
        $user = trim($_POST['username']);
        $pass = $_POST['password'];
        $level_id = intval($_POST['level_id']);

        $check = $conn->prepare("SELECT id FROM user_tb WHERE username = ?");
        $check->execute([$user]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username นี้มีอยู่ในระบบแล้ว']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO user_tb (username, password, level_id, user_status) VALUES (?, ?, ?, 'offline')");
        if ($stmt->execute([$user, md5($pass), $level_id])) {
            write_log("เพิ่มผู้ใช้งานใหม่: $user");
            echo json_encode(['success' => true]);
        }
    }

    // update ข้อมูลผู้ใช้
	else if ($action == 'update') {
        $id = intval($_POST['id']);
        $level_id = intval($_POST['level_id']);
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (!empty($password)) {
            // แก้ไขทั้งรหัสผ่านและระดับสิทธิ์
            $stmt = $conn->prepare("UPDATE user_tb SET password = ?, level_id = ? WHERE id = ?");
            $stmt->execute([md5($password), $level_id, $id]);
        } else {
            // แก้ไขเฉพาะระดับสิทธิ์
            $stmt = $conn->prepare("UPDATE user_tb SET level_id = ? WHERE id = ?");
            $stmt->execute([$level_id, $id]);
        }
        write_log("แก้ไขข้อมูลผู้ใช้งาน ID: $id");
        echo json_encode(['success' => true]);
    }

    else if ($action == 'delete') {
        $id = intval($_POST['id']);
        $stmt_find = $conn->prepare("SELECT username FROM user_tb WHERE id = ?");
        $stmt_find->execute([$id]);
        $target = $stmt_find->fetch(PDO::FETCH_ASSOC);

        if (!$target || $target['username'] == 'admin' || $target['username'] == $_SESSION['username']) {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบผู้ใช้นี้ได้']);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM user_tb WHERE id = ?");
        if ($stmt->execute([$id])) {
            write_log("ลบผู้ใช้งาน: " . $target['username']);
            echo json_encode(['success' => true]);
        }
    }
	
	// --- 1. ดึงรายชื่อผู้ใช้ที่ login_fail > 0 ---
    if ($action == 'list_locked') {
        $stmt = $conn->query("SELECT id, username, login_fail FROM user_tb WHERE login_fail > 0 ORDER BY login_fail DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    // --- 2. อัปเดต login_fail ให้เป็น 0 (ปลดล็อก) ---
    else if ($action == 'unlock') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        $stmt = $conn->prepare("UPDATE user_tb SET login_fail = 0 WHERE id = ?");
        if ($stmt->execute([$id])) {
            // บันทึก Log การปลดล็อก
            if (function_exists('write_log')) {
                write_log("ปลดล็อกผู้ใช้งาน ID: $id ให้กลับมาใช้งานได้ปกติ");
            }
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถปลดล็อกได้']);
        }
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}