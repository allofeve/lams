<?php
include 'config.php';
include 'check_permission.php'; // ตรวจสอบสิทธิ์

// เช็คสิทธิ์เฉพาะ Admin (Level 99)
if (!isset($_SESSION['level']) || $_SESSION['level'] != 99) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>บันทึกประวัติการใช้งาน - Loan System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    <style>
        body { background-color: #f0f2f5; padding-top: 20px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
        .ua-text { 
            max-width: 200px; 
            overflow: hidden; 
            text-overflow: ellipsis; 
            white-space: nowrap; 
            display: inline-block;
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="max-width: 1200px;">
        <div class="d-flex justify-content-between mb-3">
            <a href="index.php" class="btn btn-outline-primary shadow-sm"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
            <button class="btn btn-secondary shadow-sm" onclick="window.location.reload();">
                <i class="fas fa-sync-alt"></i> รีเฟรชข้อมูล
            </button>
        </div>

        <div class="card main-card">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-history"></i> ประวัติการใช้งานระบบ (System Logs)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-center">
                            <tr>
                                <th>ID</th>
                                <th>วัน-เวลา</th>
                                <th>ผู้ใช้งาน</th>
                                <th>IP Address</th>
                                <th>กิจกรรม (Activity)</th>
                                <th>อุปกรณ์/บราวเซอร์</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            try {
                                // ดึงข้อมูลจาก log_tb โดยเรียงจากใหม่ไปเก่า
                                $stmt = $conn->query("SELECT * FROM log_tb ORDER BY log_id DESC LIMIT 200");
                                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td class='text-center text-muted'>{$row['log_id']}</td>";
                                    echo "<td class='text-center' style='white-space:nowrap;'>".date('d/m/Y H:i:s', strtotime($row['date_time']))."</td>";
                                    echo "<td class='text-center'><span class='badge bg-info text-dark'>{$row['username']}</span></td>";
                                    echo "<td class='text-center'><small class='fw-bold text-primary'>{$row['ip_address']}</small></td>";
                                    echo "<td>" . htmlspecialchars($row['activity']) . "</td>";
                                    echo "<td><span class='ua-text' title='".htmlspecialchars($row['ua'])."'>{$row['ua']}</span></td>";
                                    echo "</tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='6' class='text-center text-danger'>เกิดข้อผิดพลาด: {$e->getMessage()}</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-muted small text-center">
                แสดงบันทึก 200 รายการล่าสุด
            </div>
        </div>
    </div>

    <script src="lib/bootstrap-5.3.8/js/bootstrap.bundle.min.js"></script>
</body>
</html>