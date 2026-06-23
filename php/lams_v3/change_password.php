<?php
include 'config.php';
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <title>เปลี่ยนรหัสผ่าน - Loan System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f0f2f5; padding-top: 50px; }
        .password-card { max-width: 450px; margin: auto; border-radius: 1rem; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="card password-card">
            <div class="card-header bg-primary text-white text-center py-3" style="border-radius: 1rem 1rem 0 0;">
                <h5 class="mb-0"><i class="fas fa-key me-2"></i> เปลี่ยนรหัสผ่านใหม่</h5>
            </div>
            <div class="card-body p-4">
                <form id="changePassForm">
                    <div class="mb-3">
                        <label class="form-label fw-bold">รหัสผ่านปัจจุบัน</label>
                        <input type="password" name="old_pass" class="form-control" required>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รหัสผ่านใหม่</label>
                        <input type="password" name="new_pass" id="new_pass" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ยืนยันรหัสผ่านใหม่อีกครั้ง</label>
                        <input type="password" name="confirm_pass" class="form-control" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">บันทึกการเปลี่ยนแปลง</button>
                        <a href="index.php" class="btn btn-outline-secondary">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    $('#changePassForm').on('submit', function(e) {
        e.preventDefault();
        
        // ตรวจสอบเบื้องต้นว่ารหัสใหม่ตรงกันไหม
        let n = $('#new_pass').val();
        let c = $('input[name="confirm_pass"]').val();
        
        if(n !== c) {
            Swal.fire('ผิดพลาด', 'รหัสผ่านใหม่ไม่ตรงกัน', 'error');
            return;
        }

        $.post('password_process.php', $(this).serialize(), function(res) {
            if(res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ',
                    text: 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว กรุณาเข้าสู่ระบบใหม่',
                }).then(() => {
                    window.location.href = 'logout.php';
                });
            } else {
                Swal.fire('ผิดพลาด', res.message, 'error');
            }
        }, 'json');
    });
    </script>
</body>
</html>