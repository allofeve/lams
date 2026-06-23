<?php
// check_permission.php
// ตรวจสอบว่ามี Session หรือยัง (ถ้าไม่มีให้ include config.php)
if (!isset($_SESSION)) {
    include 'config.php';
}

// 1. ดึงชื่อไฟล์ปัจจุบันที่กำลังเรียกใช้งาน
$current_page = basename($_SERVER['PHP_SELF']);

// 2. ตรวจสอบ Level ของผู้ใช้ปัจจุบัน
$user_level = isset($clevel) ? intval($clevel) : 0;

// 3. ค้นหาในฐานข้อมูลว่าหน้านี้ต้องการ Level เท่าใด
$stmt_check = $conn->prepare("SELECT level_tb.level_num AS level_num FROM grant_tb INNER JOIN level_tb ON grant_tb.level_id = level_tb.level_id WHERE grant_tb.page_name = ?");
$stmt_check->execute(array($current_page));
$permission_data = $stmt_check->fetch(PDO::FETCH_ASSOC);

if ($permission_data) {
    // 4. ถ้ามีการกำหนดสิทธิ์ไว้ และ Level ของผู้ใช้ "น้อยกว่า" Level ที่ต้องการ
    if ($user_level < intval($permission_data['level_num'])) {
        // บันทึก Log (Optional) หรือแจ้งเตือนและเด้งไปหน้า index.php
        echo "<script src='lib/sweetalert2@11.js'></script>";
        echo "<script>
            setTimeout(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'สิทธิ์ไม่เพียงพอ',
                    text: 'คุณไม่มีสิทธิ์เข้าถึงหน้าเว็บนี้',
                    confirmButtonText: 'ตกลง'
                }).then(function() {
                    window.location.href = 'index.php';
                });
            }, 100);
        </script>";
        exit(); // หยุดการทำงานของหน้าเว็บทันที
    }
}
?>