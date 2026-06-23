<?php
include 'config.php';
include 'check_permission.php';

if (!isset($clevel) || $clevel != 99) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>จัดการผู้ใช้งาน - Loan System</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    <style>
        body { background-color: #f0f2f5; padding-top: 20px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 1000px;">
        <div class="d-flex justify-content-between mb-3">
            <a href="index.php" class="btn btn-outline-primary shadow-sm"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-user-plus"></i> เพิ่มผู้ใช้งานใหม่
            </button>
        </div>

        <div class="card main-card">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-users-cog"></i> รายการผู้ใช้งานในระบบ</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>ชื่อผู้ใช้งาน (Username)</th>
                            <th>ระดับสิทธิ์</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="user_list">
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="add_user_form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มผู้ใช้งานใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ระดับสิทธิ์ (Level)</label>
                        <select name="level" class="form-select" required>
                            <option value="1">Level 1 (พนักงาน)</option>
							<option value="5">Level 5 (เจ้าหน้าที่สินเชื่อ)</option>
                            <option value="10">Level 10 (หัวหน้า)</option>
                            <option value="99">Level 99 (Admin)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="edit_user_form" class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> แก้ไขผู้ใช้งาน</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" id="edit_username" class="form-control" readonly>
                        <small class="text-muted">* ไม่สามารถเปลี่ยนชื่อผู้ใช้ได้</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">รหัสผ่านใหม่ (ปล่อยว่างถ้าไม่เปลี่ยน)</label>
                        <input type="password" name="password" class="form-control" placeholder="กรอกรหัสผ่านใหม่เพื่อ Reset">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ระดับสิทธิ์ (Level)</label>
                        <select name="level" id="edit_level" class="form-select" required>
                            <option value="1">Level 1 (พนักงาน)</option>
							<option value="5">Level 5 (เจ้าหน้าที่สินเชื่อ)</option>
                            <option value="10">Level 10 (หัวหน้า)</option>
                            <option value="99">Level 99 (Admin)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-warning">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        loadUsers();

        function loadUsers() {
            $.get('user_process.php?action=list', function(data) {
                let html = '';
                data.forEach(function(item) {
                    let badge = item.level == 99 ? 'bg-danger' : 'bg-primary';
                    html += `<tr>
                        <td>${item.id}</td>
                        <td class="text-start fw-bold">${item.username}</td>
                        <td><span class="badge ${badge}">Level ${item.level}</span></td>
                        <td>
                            <button onclick="editUser(${item.id}, '${item.username}', ${item.level})" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> แก้ไข
                            </button>
                            <button onclick="deleteUser(${item.id}, '${item.username}')" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> ลบ
                            </button>
                        </td>
                    </tr>`;
                });
                $('#user_list').html(html);
            }, 'json');
        }

        // ฟังก์ชันแก้ไข
        window.editUser = function(id, username, level) {
            $('#edit_id').val(id);
            $('#edit_username').val(username);
            $('#edit_level').val(level);
            $('#editUserModal').modal('show');
        };

        $('#edit_user_form').on('submit', function(e) {
            e.preventDefault();
            $.post('user_process.php?action=update', $(this).serialize(), function(res) {
                if(res.success) {
                    Swal.fire('สำเร็จ', 'แก้ไขข้อมูลเรียบร้อย', 'success');
                    $('#editUserModal').modal('hide');
                    loadUsers();
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }, 'json');
        });

        // ฟังก์ชันเพิ่มผู้ใช้
        $('#add_user_form').on('submit', function(e) {
            e.preventDefault();
            $.post('user_process.php?action=add', $(this).serialize(), function(res) {
                if(res.success) {
                    Swal.fire('สำเร็จ', 'เพิ่มผู้ใช้เรียบร้อย', 'success');
                    $('#addUserModal').modal('hide');
                    $('#add_user_form')[0].reset();
                    loadUsers();
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }, 'json');
        });

        // ฟังก์ชันลบ
        window.deleteUser = function(id, name) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "ต้องการลบผู้ใช้ " + name + " ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบเลย'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('user_process.php?action=delete', {id: id}, function(res) {
                        if(res.success) {
                            Swal.fire('ลบเรียบร้อย', '', 'success');
                            loadUsers();
                        } else {
                            Swal.fire('ผิดพลาด', res.message, 'error');
                        }
                    }, 'json');
                }
            });
        };
    });
    </script>
</body>
</html>
<?php
	$conn = null;
?>