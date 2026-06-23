<?php
include 'config.php';
include 'save_log.php';
header('Content-Type: application/json; charset=utf-8');

// 1. ตรวจสอบสิทธิ์
if(!isset($_SESSION['username'])) {
    echo json_encode(array('success' => false, 'message' => 'Session หมดอายุ'));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        // 2. ดึงชื่อไฟล์จากฐานข้อมูลก่อนเพื่อนำไปลบในโฟลเดอร์
        $stmt = $conn->prepare("SELECT file_name FROM loan_tb WHERE id = ?");
        $stmt->execute(array($id));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            $path = "uploads/";
            // จัดการเรื่องภาษาไทยในชื่อไฟล์ (Windows Server)
            $file_path = $path . iconv("UTF-8", "TIS-620", $data['file_name']);

            // 3. ลบไฟล์ PDF (ถ้ามีไฟล์อยู่จริง)
            if (file_exists($file_path)) {
                @unlink($file_path);
            }

            // 4. ลบข้อมูลจากฐานข้อมูล
            $del_stmt = $conn->prepare("DELETE FROM loan_tb WHERE id = ?");
            $del_stmt->execute(array($id));
			
			write_log("ลบไฟล์สัญญา " . $data['file_name'] . " สำเร็จ");
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'message' => 'ไม่พบข้อมูลที่ต้องการลบ'));
        }

    } catch (PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Database Error: ' . $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid Request'));
}