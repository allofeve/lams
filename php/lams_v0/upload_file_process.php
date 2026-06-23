<?php
// upload_file_process.php
include 'config.php';
include 'save_log.php';
set_time_limit(0);
header('Content-Type: application/json; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจาก Form
    $member_id    = isset($_POST["member_id"]) ? trim($_POST["member_id"]) : "";
    $member_name  = isset($_POST["member_name"]) ? trim($_POST["member_name"]) : "";
    $loan_id      = isset($_POST["loan_id"]) ? trim($_POST["loan_id"]) : "";
    $loan_date_th = isset($_POST["loan_date"]) ? trim($_POST["loan_date"]) : "";

    // ตรวจสอบเบื้องต้น
    if(empty($member_id) || empty($member_name) || empty($loan_id) || empty($loan_date_th)) {
        $response['message'] = "กรุณากรอกข้อมูลให้ครบถ้วน";
    } else {
        // แปลงวันที่ วว/ดด/ปปปป (พ.ศ.) เป็น Y-m-d (ค.ศ.)
        $date_parts = explode('/', $loan_date_th);
        $loan_date_en = ($date_parts[2] - 543) . "-" . $date_parts[1] . "-" . $date_parts[0];

        // จัดการไฟล์
        $path = "uploads/";
        if (!is_dir($path)) { mkdir($path, 0777, true); }

        $file_ext = strtolower(pathinfo($_FILES["loan_file"]["name"], PATHINFO_EXTENSION));
        $filename_in_db = $member_id . "_" . $loan_id . "_" . str_replace("/", "-", $loan_date_th) . "." . $file_ext;
        
        // สำหรับ Server Windows บางตัวที่ต้องการ TIS-620 ในการบันทึกไฟล์จริง
        $path_copy = $path . iconv("UTF-8", "TIS-620", $filename_in_db);

        if ($file_ext != "pdf") {
            $response['message'] = "อนุญาตเฉพาะไฟล์ PDF เท่านั้น";
        } elseif (move_uploaded_file($_FILES['loan_file']['tmp_name'], $path_copy)) {
            try {
                // บันทึกข้อมูลด้วย PDO Prepare Statement
                $sql = "INSERT INTO loan_tb (member_id, member_name, loan_id, loan_date_en, file_name) 
                        VALUES (:mid, :mname, :lid, :ldate, :fname)";
                $stmt = $conn->prepare($sql);
                $stmt->execute(array(
                    ':mid'   => $member_id,
                    ':mname' => $member_name,
                    ':lid'   => $loan_id,
                    ':ldate' => $loan_date_en,
                    ':fname' => $filename_in_db
                ));

                $response['success'] = true;
                $response['message'] = 'อัพโหลดและบันทึกข้อมูลเรียบร้อยแล้ว';
				write_log("อัปโหลดไฟล์สัญญา " . $filename_in_db . " สำเร็จ");
            } catch(PDOException $e) {
                if (file_exists($path_copy)) { @unlink($path_copy); }
                $response['message'] = "Error: " . $e->getMessage();
            }
        } else {
            $response['message'] = "ไม่สามารถอัพโหลดไฟล์ได้";
        }
    }
}
echo json_encode($response);