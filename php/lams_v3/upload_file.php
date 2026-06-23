<?php
// 1. ตรวจสอบความปลอดภัย (Session & Cookie)
include 'config.php'; 
include 'check_permission.php';
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Bangkok");

// ดึงค่าขนาดไฟล์สูงสุดจาก Server เพื่อแสดงผล
$upload_max_size = ini_get("upload_max_filesize");
$max_file_size_display = $upload_max_size . (preg_match('/[MGK]/i', $upload_max_size) ? 'B' : '');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>อัพโหลดสัญญาเงินกู้ - Loan System</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    
    <style>
        body { background-color: #f0f2f5; padding-top: 20px; }
        .container { max-width: 700px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); border: none; }
        .user-info { font-size: 0.9rem; margin-bottom: 15px; }
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
                <i class="fas fa-user-circle"></i> ผู้ใช้งาน: <strong><?php echo $_SESSION['username']; ?></strong> 
                <a href="logout.php" class="btn btn-sm btn-danger ms-2 shadow-sm"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
            </div>
        </div>

        <div class="card main-card">
            <div class="card-header bg-primary text-white text-center py-4">
                <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                <h1 class="h4 mb-0">ระบบจัดเก็บสัญญาเงินกู้</h1>
                <p class="mb-0 opacity-75">แบบฟอร์มอัพโหลดสัญญาใหม่</p>
            </div>
            <div class="card-body p-4">
                <form id="upload_form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">เลขทะเบียนสมาชิก</label>
                            <input type="text" class="form-control" name="member_id" id="member_id" 
                                   onchange="this.value = this.value.toUpperCase()" required placeholder="S00001 หรือ 12345">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">เลขที่สัญญา</label>
                            <input type="text" class="form-control" name="loan_id" required placeholder="สม6800001">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control" name="member_name" required placeholder="นายสมชาย ใจดี">
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">วันที่สัญญา (พ.ศ.)</label>
                            <input type="text" class="form-control" name="loan_date" id="loan_date" 
                                   maxlength="10" placeholder="เช่น 01/01/2568" required>
                            <small class="text-muted">รูปแบบ: วว/ดด/ปปปป</small>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="form-label fw-bold">เลือกไฟล์สัญญา (PDF)</label>
                            <input type="file" class="form-control" name="loan_file" accept=".pdf" required>
                            <small class="text-danger">* ขนาดไฟล์ไม่เกิน <?php echo $max_file_size_display; ?></small>
                        </div>
                    </div>

                    <button type="submit" id="btn_upload" class="btn btn-success w-100 py-2 fs-5 shadow-sm">
                        <i class="fas fa-save"></i> บันทึกและอัพโหลดไฟล์
                    </button>
                </form>
            </div>
            <div class="card-footer text-center text-muted py-3">
                © <?php echo date('Y'); ?> Loan Contract System
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
    $(document).ready(function() {
        // จัดการฟอร์แมตวันที่ขณะพิมพ์ (Auto slash)
        $('#loan_date').on('input', function() {
            var val = $(this).val().replace(/\D/g, '');
            if (val.length > 2 && val.length <= 4) val = val.slice(0, 2) + '/' + val.slice(2);
            else if (val.length > 4) val = val.slice(0, 2) + '/' + val.slice(2, 4) + '/' + val.slice(4, 8);
            $(this).val(val);
        });

        $('#upload_form').on('submit', function(e) {
            e.preventDefault(); 
            
            // แสดง Loading
            Swal.fire({
                title: 'กำลังอัพโหลด...',
                text: 'โปรดรอสักครู่ ระบบกำลังจัดเก็บข้อมูลและไฟล์ PDF',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            var formData = new FormData(this);
            
            $.ajax({
                url: 'upload_file_process.php', 
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false, 
                processData: false, 
                success: function(response) {
                    if (response.success) {
                        Swal.fire('สำเร็จ!', response.message, 'success').then(() => {
                            $('#upload_form')[0].reset();
                        });
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด!', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถเชื่อมต่อ Server ได้', 'error');
                }
            });
        });
    });
    </script>
</body>
</html>
<?php
	$conn = null;
?>