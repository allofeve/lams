<?php
include 'config.php';
include 'check_permission.php'; // ตรวจสอบสิทธิ์เข้าถึง

if (!isset($clevel) || $clevel != 99) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>จัดการสิทธิ์การเข้าถึง - Loan System</title>
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
    <div class="container" style="max-width: 900px;">
        <div class="d-flex justify-content-between mb-3">
            <a href="index.php" class="btn btn-outline-primary shadow-sm"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#addGrantModal">
                <i class="fas fa-plus-circle"></i> เพิ่มสิทธิ์ใหม่
            </button>
        </div>

        <div class="card main-card">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-user-shield"></i> รายการสิทธิ์การเข้าถึงหน้าเว็บ </h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ชื่อหน้าเว็บ (File Name)</th>
                            <th>Level ที่อนุญาต</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="grant_list">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="grantModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="grant_form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">เพิ่มสิทธิ์ใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="gid" id="gid">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อไฟล์หน้าเว็บ</label>
                        <input type="text" name="page_name" id="page_name" class="form-control" placeholder="เช่น upload_file.php" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ระดับสิทธิ์ (Level ขั้นต่ำ)</label>
                        <select name="level_id" id="level" class="form-select" required>
                            <?php
								$stmt = $conn->prepare("SELECT * FROM level_tb");
								$stmt->execute();
								while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
									echo '<option value="'.$row["level_id"].'">Level '.$row["level_num"].' ('.$row["grant_name"].')</option>';
								}
							?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        loadGrants();

        function loadGrants() {
            $.get('grant_process.php?action=list', function(data) {
                let html = '';
                data.forEach(function(item) {
                    let badge = item.level_num == 99 ? 'bg-danger' : (item.level_num >= 10 ? 'bg-warning text-dark' : 'bg-success');
                    html += `<tr>
                        <td class="text-start fw-bold">${item.page_name}</td>
                        <td><span class="badge ${badge}">Level ${item.level_num}</span></td>
                        <td>
                            <button onclick="editGrant(${item.gid}, '${item.page_name}', ${item.level_id})" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> แก้ไข
                            </button>
                            <button onclick="deleteGrant(${item.gid}, '${item.page_name}')" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> ลบ
                            </button>
                        </td>
                    </tr>`;
                });
                $('#grant_list').html(html);
            }, 'json');
        }

        // เปิด Modal เพิ่มใหม่
        $('[data-bs-target="#addGrantModal"]').click(function() {
            $('#grant_form')[0].reset();
            $('#gid').val('');
            $('#page_name').prop('readonly', false);
            $('#modalTitle').text('เพิ่มสิทธิ์ใหม่');
            $('#grantModal').modal('show');
        });

        // เปิด Modal แก้ไข
        window.editGrant = function(gid, page, level_id) {
            $('#gid').val(gid);
            $('#page_name').val(page).prop('readonly', true); // ไม่ให้แก้ชื่อไฟล์
            $('#level_id').val(level_id);
            $('#modalTitle').text('แก้ไขระดับสิทธิ์');
            $('#grantModal').modal('show');
        };

        $('#grant_form').on('submit', function(e) {
            e.preventDefault();
            let action = $('#gid').val() ? 'update' : 'add';
            $.post('grant_process.php?action=' + action, $(this).serialize(), function(res) {
                if(res.success) {
                    Swal.fire('สำเร็จ', 'ดำเนินการเรียบร้อย', 'success');
                    $('#grantModal').modal('hide');
                    loadGrants();
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }, 'json');
        });

        window.deleteGrant = function(gid, name) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "ต้องการลบสิทธิ์ของหน้า " + name + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ลบเลย'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('grant_process.php?action=delete', {gid: gid}, function(res) {
                        if(res.success) loadGrants();
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