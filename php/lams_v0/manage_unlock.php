<?php
include 'config.php';
include 'check_permission.php';

// ตรวจสอบสิทธิ์เฉพาะ Admin Level 99
if (!isset($clevel) || isset($clevel) != 99) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <title>ปลดล็อกผู้ใช้งาน - Loan System</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    <style>
        body { background-color: #f8f9fa; padding-top: 30px; }
        .main-card { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .table-locked { background-color: #fff1f1; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 900px;">
        <div class="d-flex justify-content-between mb-4">
            <a href="index.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left"></i> กลับหน้าหลัก</a>
            <h4 class="fw-bold text-danger"><i class="fas fa-user-lock"></i> รายชื่อผู้ใช้ที่ถูกระงับ/เข้าผิด</h4>
        </div>

        <div class="card main-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th>ชื่อผู้ใช้</th>
                                <th>จำนวนครั้งที่ผิด</th>
                                <th>สถานะ</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody id="locked_user_list">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    function loadLockedUsers() {
        $.get('user_process.php?action=list_locked', function(data) {
            let html = '';
            if (data.length === 0) {
                html = '<tr><td colspan="4" class="text-center py-4 text-muted">ไม่มีผู้ใช้งานที่ถูกระงับในขณะนี้</td></tr>';
            } else {
                data.forEach(function(user) {
                    let statusBadge = user.login_fail > 5 
                        ? '<span class="badge bg-danger">ถูกระงับ (Banned)</span>' 
                        : '<span class="badge bg-warning text-dark">เฝ้าระวัง</span>';
                    
                    html += `<tr class="text-center ${user.login_fail > 5 ? 'table-locked' : ''}">
                        <td class="fw-bold">${user.username}</td>
                        <td class="text-danger">${user.login_fail} ครั้ง</td>
                        <td>${statusBadge}</td>
                        <td>
                            <button onclick="unlockUser(${user.id}, '${user.username}')" class="btn btn-sm btn-success shadow-sm">
                                <i class="fas fa-unlock"></i> ปลดล็อก
                            </button>
                        </td>
                    </tr>`;
                });
            }
            $('#locked_user_list').html(html);
        }, 'json');
    }

    window.unlockUser = function(id, username) {
        Swal.fire({
            title: 'ยืนยันการปลดล็อก?',
            text: `ต้องการล้างประวัติการเข้าผิดของ ${username} ใช่หรือไม่?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            confirmButtonText: 'ยืนยันปลดล็อก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('user_process.php?action=unlock', { id: id }, function(res) {
                    if (res.success) {
                        Swal.fire('สำเร็จ', 'ปลดล็อกผู้ใช้งานเรียบร้อยแล้ว', 'success');
                        loadLockedUsers();
                    } else {
                        Swal.fire('ผิดพลาด', res.message, 'error');
                    }
                }, 'json');
            }
        });
    }

    $(document).ready(function() {
        loadLockedUsers();
    });
    </script>
</body>
</html>