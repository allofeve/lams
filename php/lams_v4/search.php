<?php
// 1. ตรวจสอบความปลอดภัยและการเชื่อมต่อฐานข้อมูล
include 'config.php'; 
include 'check_permission.php';
// ตรวจสอบว่ามีการ Login หรือไม่
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ตั้งค่า Timezone
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>ค้นหาไฟล์สัญญา - Loan System</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    
    <style>
        body { background-color: #f0f2f5; padding-top: 20px; }
        .container { max-width: 900px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: none; }
        .user-info { font-size: 0.9rem; margin-bottom: 15px; }
        .table-responsive { border-radius: 0.5rem; }
        .card-header { border-radius: 1rem 1rem 0 0 !important; }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center user-info px-2">
            <div>
                <a href="index.php" class="btn btn-sm btn-outline-primary shadow-sm">
                    <i class="fas fa-home"></i> กลับหน้าหลัก
                </a>
            </div>
            <div class="text-muted">
                <i class="fas fa-user-circle"></i> ผู้ใช้งาน: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> 
                <a href="logout.php" class="btn btn-sm btn-danger ms-2 shadow-sm"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
            </div>
        </div>

        <div class="card main-card mb-4">
            <div class="card-header bg-success text-white text-center py-4">
                <i class="fas fa-search fa-3x mb-2"></i>
                <h1 class="h4 mb-0">ค้นหาไฟล์สัญญาเงินกู้</h1>
                <p class="mb-0 opacity-75">ค้นหาจาก เลขทะเบียน, ชื่อ-นามสกุล หรือ เลขที่สัญญา</p>
            </div>
            <div class="card-body p-4">
                <form id="search_form" method="GET" action="search.php">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" name="query" id="search_query" class="form-control" 
                               placeholder="กรอกข้อมูลที่ต้องการค้นหา..." 
                               value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
                        <button class="btn btn-success" type="submit">
                            <i class="fas fa-search"></i> ค้นหา
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        // ส่วนการประมวลผลการค้นหาด้วย PDO
        if (isset($_GET['query']) && $_GET['query'] != "") {
            try {
                $search = "%" . $_GET['query'] . "%";
                
                // ใช้ Prepared Statement เพื่อความปลอดภัยจาก SQL Injection
                $sql = "SELECT * FROM loan_tb 
                        WHERE member_id LIKE :q 
                        OR member_name LIKE :q 
                        OR loan_id LIKE :q 
						OR member_group LIKE :q
                        ORDER BY loan_date_en DESC";
                
                $stmt = $conn->prepare($sql);
                $stmt->execute(array(':q' => $search));
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                echo '<div class="card main-card border-0 shadow-sm">';
                echo '<div class="card-body p-0">';
                echo '<div class="table-responsive">';
                echo '<table class="table table-hover mb-0">';
                echo '<thead class="table-light text-center">
                        <tr>
                            <th>เลขทะเบียน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>เลขที่สัญญา</th>
                            <th>วันที่สัญญา</th>
							<th>สังกัด</th>
                            <th>จัดการ</th>
                        </tr>
                      </thead>';
                echo '<tbody>';

                if (count($results) > 0) {
                    foreach ($results as $row) {
                        // แปลงวันที่จาก ค.ศ. เป็น พ.ศ. สำหรับแสดงผล
                        $date_obj = new DateTime($row['loan_date_en']);
                        $display_date = $date_obj->format('d/m/') . ($date_obj->format('Y') + 543);

                        echo '<tr class="text-center align-middle">';
                        echo '<td>' . htmlspecialchars($row['member_id']) . '</td>';
                        echo '<td class="text-start">' . htmlspecialchars($row['member_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['loan_id']) . '</td>';
                        echo '<td>' . htmlspecialchars($display_date) . '</td>';
						echo '<td>' . htmlspecialchars($row['member_group']) . '</td>';
                        echo '<td>
                                <a href="uploads/' . htmlspecialchars($row['file_name']) . '" target="_blank" class="btn btn-sm btn-primary">
                                    <i class="fas fa-file-pdf"></i> เปิดดูไฟล์
                                </a>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center py-5 text-muted">ไม่พบข้อมูลที่ตรงกับการค้นหา</td></tr>';
                }
                
                echo '</tbody></table></div></div></div>';
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $e->getMessage() . '</div>';
            }
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // แสดง Loading เมื่อกดค้นหา
        $('#search_form').on('submit', function() {
            if($('#search_query').val() !== "") {
                Swal.fire({
                    title: 'กำลังค้นหาข้อมูล...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
<?php
	$conn = null;
?>