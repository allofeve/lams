<?php
include 'config.php';
header('Content-Type: application/json; charset=utf-8');

if (!isset($clevel) || $clevel != 99) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';

try {
    if ($action == 'list') {
        $stmt = $conn->query("SELECT grant_tb.*, level_tb.level_num AS level_num FROM grant_tb INNER JOIN level_tb ON grant_tb.level_id = level_tb.level_id ORDER BY page_name ASC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    
    else if ($action == 'add') {
        $page = trim($_POST['page_name']);
        $level_id = intval($_POST['level_id']);

        $check = $conn->prepare("SELECT gid FROM grant_tb WHERE page_name = ?");
        $check->execute([$page]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'ชื่อหน้านี้ถูกกำหนดสิทธิ์ไว้แล้ว']);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO grant_tb (page_name, level_id) VALUES (?, ?)");
        $stmt->execute([$page, $level_id]);
        echo json_encode(['success' => true]);
    } 

    else if ($action == 'update') {
        $gid = intval($_POST['gid']);
        $level_id = intval($_POST['level_id']);

        $stmt = $conn->prepare("UPDATE grant_tb SET level_id = ? WHERE gid = ?");
        $stmt->execute([$level_id, $gid]);
        echo json_encode(['success' => true]);
    }

    else if ($action == 'delete') {
        $gid = intval($_POST['gid']);
        $stmt = $conn->prepare("DELETE FROM grant_tb WHERE gid = ?");
        $stmt->execute([$gid]);
        echo json_encode(['success' => true]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}