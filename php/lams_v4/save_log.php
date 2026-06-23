<?php
function write_log($action) {
    global $conn; // ใช้ตัวแปรเชื่อมต่อฐานข้อมูลจาก config.php
    
    // ตรวจสอบว่ามีการ Login หรือยัง ถ้ายังให้บันทึกเป็น 'Guest'
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
	$user_ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    // เตรียมข้อมูล Action
    $action_detail = trim($action);
    $current_date = date('Y-m-d H:i:s'); // รูปแบบวันที่และเวลา

    try {
        $stmt = $conn->prepare("INSERT INTO log_tb (username, ip_address, ua, date_time, activity) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(array($user, $user_ip, $user_agent, $current_date, $action_detail));
    } catch (PDOException $e) {
        // ในกรณีที่ต้องการ Debug สามารถเปิดบรรทัดล่างได้
        //error_log("Log Error: " . $e->getMessage());
    }
}
?>