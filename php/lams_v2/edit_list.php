<?php
include 'config.php'; 
include 'check_permission.php';
if(!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>แก้ไขข้อมูลสัญญา - Loan System</title>
    <meta charset="UTF-8" />
    <?php
		include 'lib_head.php';
	?>
    <style>
        body { background-color: #f0f2f5; padding-top: 20px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div class="d-flex justify-content-between mb-3">
            <a href="index.php" class="btn btn-sm btn-outline-primary"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
            <span class="text-muted">
				<i class="fas fa-user-circle"></i> ผู้ใช้งาน: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> 
                <a href="logout.php" class="btn btn-sm btn-danger ms-2 shadow-sm"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
			</span>
        </div>

        <div class="card main-card mb-4">
            <div class="card-header bg-warning text-dark text-center py-4" style="border-radius: 1rem 1rem 0 0;">
                <i class="fas fa-edit fa-3x mb-2"></i>
                <h1 class="h4 mb-0">แก้ไขข้อมูลสัญญาเงินกู้</h1>
            </div>
            <div class="card-body p-4">
                <form method="GET">
                    <div class="input-group input-group-lg">
                        <input type="text" name="query" class="form-control" placeholder="ค้นหาเพื่อแก้ไข..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
                        <button class="btn btn-warning" type="submit">ค้นหา</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        if (isset($_GET['query']) && $_GET['query'] != "") {
            $search = "%" . $_GET['query'] . "%";
            $stmt = $conn->prepare("SELECT * FROM loan_tb WHERE member_id LIKE :q OR member_name LIKE :q OR loan_id LIKE :q ORDER BY loan_date_en DESC");
            $stmt->execute(array(':q' => $search));
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<div class="card main-card border-0"><div class="table-responsive"><table class="table table-hover mb-0">';
            echo '<thead class="table-light text-center"><tr><th>เลขทะเบียน</th><th>ชื่อ-นามสกุล</th><th>เลขที่สัญญา</th><th>วันที่สัญญา</th><th>จัดการ</th></tr></thead><tbody>';

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
                    echo '<td><a href="edit_form.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning"><i class="fas fa-tools"></i> แก้ไข</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4" class="text-center py-5 text-muted">ไม่พบข้อมูล</td></tr>';
            }
            echo '</tbody></table></div></div>';
        }
        ?>
    </div>
</body>
</html>