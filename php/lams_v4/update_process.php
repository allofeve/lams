<?php
include 'config.php';
include 'save_log.php';
header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบสิทธิ์เบื้องต้น
if(!isset($_SESSION['username'])) {
    echo json_encode(array('success' => false, 'message' => 'Session หมดอายุ กรุณา Login ใหม่'));
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $m_id = trim($_POST['member_id']);
    $m_name = trim($_POST['member_name']);
    $l_id = trim($_POST['loan_id']);
    $l_date_th = trim($_POST['loan_date']);
	$member_group = trim($_POST['member_group']);

    // 1. ตรวจสอบฟอร์แมตวันที่และแปลงเป็น ค.ศ. (Y-m-d)
    $dp = explode('/', $l_date_th);
    if(count($dp) != 3) {
        echo json_encode(array('success' => false, 'message' => 'รูปแบบวันที่ไม่ถูกต้อง'));
        exit;
    }
    $l_date_en = ($dp[2]-543)."-".$dp[1]."-".$dp[0];

    try {
        // 2. ดึงชื่อไฟล์เดิมจากฐานข้อมูล
        $stmt_old = $conn->prepare("SELECT file_name FROM loan_tb WHERE id = ?");
        $stmt_old->execute(array($id));
        $old_data = $stmt_old->fetch(PDO::FETCH_ASSOC);
        
        if(!$old_data) {
            echo json_encode(array('success' => false, 'message' => 'ไม่พบข้อมูลเดิมในระบบ'));
            exit;
        }

        $file_name_to_save = $old_data['file_name'];
        $path = "uploads/";

        // 3. ตรวจสอบว่ามีการอัปโหลดไฟล์ PDF ใหม่หรือไม่
        if (isset($_FILES['loan_file']) && $_FILES['loan_file']['error'] == 0) {
            $file_ext = strtolower(pathinfo($_FILES["loan_file"]["name"], PATHINFO_EXTENSION));

            if ($file_ext != "pdf") {
                echo json_encode(array('success' => false, 'message' => 'กรุณาอัปโหลดไฟล์เฉพาะนามสกุล .pdf เท่านั้น'));
                exit;
            }

            // ตั้งชื่อไฟล์ใหม่ตามข้อมูลที่แก้ไข
            $new_filename = $m_id . "_" . $l_id . "_" . str_replace("/", "-", $l_date_th) . "." . $file_ext;
            
            // จัดการเรื่องการบันทึกไฟล์ (รองรับ Windows Server ภาษาไทย)
            $path_copy = $path . iconv("UTF-8", "TIS-620", $new_filename);

            if (move_uploaded_file($_FILES['loan_file']['tmp_name'], $path_copy)) {
                // ลบไฟล์เก่าทิ้งถ้าชื่อไฟล์เปลี่ยนไป หรือมีการอัปโหลดใหม่สำเร็จ
                $old_file_path = $path . iconv("UTF-8", "TIS-620", $old_data['file_name']);
                if (file_exists($old_file_path)) {
                    @unlink($old_file_path);
                }
                $file_name_to_save = $new_filename;
            } else {
                echo json_encode(array('success' => false, 'message' => 'เกิดข้อผิดพลาดในการย้ายไฟล์ไปยังโฟลเดอร์ uploads/'));
                exit;
            }
        } else {
            // กรณีไม่ได้อัปโหลดไฟล์ใหม่ แต่ข้อมูลอื่นเปลี่ยน (เช่น เลขทะเบียนเปลี่ยน) 
            // อาจจะต้องการเปลี่ยนชื่อไฟล์เดิมให้ตรงกับข้อมูลใหม่ด้วย (Optional)
            $new_filename = $m_id . "_" . $l_id . "_" . str_replace("/", "-", $l_date_th) . ".pdf";
            if($file_name_to_save != $new_filename) {
                $old_path = $path . iconv("UTF-8", "TIS-620", $file_name_to_save);
                $new_path = $path . iconv("UTF-8", "TIS-620", $new_filename);
                if(file_exists($old_path)) {
                    rename($old_path, $new_path);
                }
                $file_name_to_save = $new_filename;
            }
        }

        // 4. อัปเดตข้อมูลลงฐานข้อมูลด้วย PDO
        $sql = "UPDATE loan_tb SET 
                    member_id = ?, 
                    member_name = ?, 
                    loan_id = ?, 
                    loan_date_en = ?, 
					member_group = ?,
                    file_name = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array($m_id, $m_name, $l_id, $l_date_en, $member_group, $file_name_to_save, $id));
		
		write_log("แก้ไขสัญญา " . $l_id . " สำเร็จ");
        echo json_encode(array('success' => true));

    } catch (PDOException $e) {
        echo json_encode(array('success' => false, 'message' => 'Database Error: ' . $e->getMessage()));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid Request Method'));
}