<?php
include 'config.php';
include 'save_log.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['username']) || !isset($_SESSION['level'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized Access']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$current_level = intval($_SESSION['level']);

try {
    if ($action == 'online_list') {
        $stmt = $conn->query("SELECT username, level FROM user_tb WHERE user_status = 'online' ORDER BY username ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        exit;
    }

    if ($current_level != 99) {
        echo json_encode(['success' => false, 'message' => 'สิทธิ์ไม่เพียงพอ']);
        exit;
    }

    if ($action == 'list') {
        $stmt = $conn->query("SELECT id, username, level FROM user_tb ORDER BY level DESC, username ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    } 
    
    else if ($action == 'add') {
        $user = trim($_POST['username']);
        $pass = $_POST['password'];
        $level = intval($_POST['level']);

        $check = $conn->prepare("SELECT id FROM user_tb WHERE username = ?");
        $check->execute([$user]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Username นี้มีอยู่ในระบบแล้ว']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO user_tb (username, password, level, user_status) VALUES (?, ?, ?, 'offline')");
        if ($stmt->execute([$user, md5($pass), $level])) {
            write_log("เพิ่มผู้ใช้งานใหม่: $user");
            echo json_encode(['success' => true]);
        }
    }

    else if ($action == 'update') {
        $id = intval($_POST['id']);
        $level = intval($_POST['level']);
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (!empty($password)) {
            // แก้ไขทั้งรหัสผ่านและระดับสิทธิ์
            $stmt = $conn->prepare("UPDATE user_tb SET password = ?, level = ? WHERE id = ?");
            $stmt->execute([md5($password), $level, $id]);
        } else {
            // แก้ไขเฉพาะระดับสิทธิ์
            $stmt = $conn->prepare("UPDATE user_tb SET level = ? WHERE id = ?");
            $stmt->execute([$level, $id]);
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
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}