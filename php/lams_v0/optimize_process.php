<?php
include 'config.php';
header('Content-Type: application/json; charset=utf-8');

if(!isset($_SESSION['username'])) {
    echo json_encode(array('success' => false, 'message' => 'Unauthorized'));
    exit;
}

try {
    $logs = array();
    
    // 1. ดึงชื่อตารางทั้งหมดในฐานข้อมูล
    $stmt = $conn->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        // 2. รันคำสั่ง Optimize สำหรับแต่ละตาราง
        $conn->query("OPTIMIZE TABLE `$table` ");
        $logs[] = "optimize ตาราง `$table` ... [OK]";
		$conn->query("REPAIR TABLE `$table` ");
        $logs[] = "repair ตาราง `$table` ... [OK]";
    }

    echo json_encode(array(
        'success' => true,
        'logs' => $logs
    ));

} catch (PDOException $e) {
    echo json_encode(array(
        'success' => false,
        'message' => 'Database Error: ' . $e->getMessage()
    ));
}