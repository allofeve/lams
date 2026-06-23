<?php
include 'config.php';
include 'check_permission.php'; 
if(!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>ลบข้อมูลสัญญา - Loan System</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    <style>
        body { background-color: #f0f2f5; padding-top: 20px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
        .card-header { border-radius: 1rem 1rem 0 0 !important; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div class="d-flex justify-content-between mb-3 px-2">
            <a href="index.php" class="btn btn-sm btn-outline-primary shadow-sm"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
            <span class="text-muted">
				<i class="fas fa-user-circle"></i> ผู้ใช้งาน: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> 
                <a href="logout.php" class="btn btn-sm btn-danger ms-2 shadow-sm"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
			</span>
        </div>

        <div class="card main-card mb-4">
            <div class="card-header bg-danger text-white text-center py-4">
                <i class="fas fa-trash-alt fa-3x mb-2"></i>
                <h1 class="h4 mb-0">ลบข้อมูลสัญญาเงินกู้</h1>
                <p class="mb-0 opacity-75 text-white">ค้นหาและระบุรายการที่ต้องการลบถาวร</p>
            </div>
            <div class="card-body p-4">
                <form method="GET" action="delete_list.php">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" name="query" class="form-control" placeholder="เลขทะเบียน / ชื่อ / เลขที่สัญญา..." value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>" required>
                        <button class="btn btn-danger" type="submit"><i class="fas fa-search"></i> ค้นหา</button>
                    </div>
                </form>
            </div>
        </div>

        <?php
        if (isset($_GET['query']) && $_GET['query'] != "") {
            $search = "%" . $_GET['query'] . "%";
            $stmt = $conn->prepare("SELECT * FROM loan_tb WHERE member_id LIKE :q OR member_name LIKE :q OR loan_id LIKE :q OR member_group LIKE :q ORDER BY loan_date_en DESC");
            $stmt->execute(array(':q' => $search));
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<div class="card main-card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0">';
            echo '<thead class="table-light text-center"><tr><th>เลขทะเบียน</th><th>ชื่อ-นามสกุล</th><th>เลขที่สัญญา</th><th>วันที่สัญญา</th><th>สังกัด</th><th>จัดการ</th></tr></thead><tbody>';

            if (count($results) > 0) {
                foreach ($results as $row) {
					// แปลงวันที่จาก ค.ศ. เป็น พ.ศ. สำหรับแสดงผล
					$date_obj = new DateTime($row['loan_date_en']);
					$display_date = $date_obj->format('d/m/') . ($date_obj->format('Y') + 543);
					
                    echo '<tr class="text-center align-middle" id="row_' . $row['id'] . '">';
                    echo '<td>' . htmlspecialchars($row['member_id']) . '</td>';
                    echo '<td class="text-start">' . htmlspecialchars($row['member_name']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['loan_id']) . '</td>';
					echo '<td>' . htmlspecialchars($display_date) . '</td>';
					echo '<td>' . htmlspecialchars($row['member_group']) . '</td>';
                    echo '<td><button type="button" onclick="confirmDelete(' . $row['id'] . ', \'' . htmlspecialchars($row['loan_id']) . '\')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i> ลบ</button></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4" class="text-center py-5 text-muted">ไม่พบข้อมูลที่ต้องการลบ</td></tr>';
            }
            echo '</tbody></table></div></div>';
        }
        ?>
    </div>

    <script>
    function confirmDelete(id, loanId) {
        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณกำลังจะลบสัญญาเลขที่ " + loanId + " และไฟล์ PDF ออกจากระบบถาวร ไม่สามารถกู้คืนได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ส่งคำขอลบผ่าน AJAX
                $.ajax({
                    url: 'delete_process.php',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(res) {
                        if(res.success) {
                            Swal.fire('ลบแล้ว!', 'ข้อมูลและไฟล์ถูกลบออกจากระบบแล้ว', 'success').then(() => {
                                $('#row_' + id).fadeOut(); // ซ่อนแถวที่ลบไป
                            });
                        } else {
                            Swal.fire('ผิดพลาด!', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('ผิดพลาด!', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                    }
                });
            }
        });
    }
    </script>
</body>
</html>
<?php
	$conn = null;
?>