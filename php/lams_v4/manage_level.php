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
	<title>จัดการระดับสิทธิ์ - Loan System</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include 'lib_head.php'; ?>
    <style>
        body { background-color: #f0f2f5; padding-top: 20px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 800px;">
        <div class="d-flex justify-content-between mb-3">
            <a href="index.php" class="btn btn-outline-primary shadow-sm"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
            <button class="btn btn-success shadow-sm" id="btnAddLevel">
                <i class="fas fa-plus-circle"></i> เพิ่มระดับสิทธิ์ใหม่
            </button>
        </div>

        <div class="card main-card">
            <div class="card-header bg-dark text-white py-3">
                <h5 class="mb-0"><i class="fas fa-layer-group"></i> จัดการระดับผู้ใช้งาน (Level Management)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>ระดับ (Level Num)</th>
                            <th>ชื่อสิทธิ์ (Grant Name)</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="level_list">
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="levelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="level_form" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">จัดการระดับสิทธิ์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="level_id" id="level_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ตัวเลขระดับ (Level Number)</label>
                        <input type="number" name="level_num" id="level_num" class="form-control" placeholder="เช่น 1, 10, 99" required>
                        <small class="text-muted">* ตัวเลขที่สูงกว่ามักจะมีสิทธิ์มากกว่าในระบบ</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อเรียกสิทธิ์ (Grant Name)</label>
                        <input type="text" name="grant_name" id="grant_name" class="form-control" placeholder="เช่น พนักงาน, ผู้ดูแลระบบ" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        loadLevels();

        function loadLevels() {
            $.get('level_process.php?action=list_levels', function(data) {
                let html = '';
                data.forEach(function(item) {
                    html += `<tr>
                        <td>${item.level_id}</td>
                        <td><span class="badge bg-primary fs-6">${item.level_num}</span></td>
                        <td class="text-start">${item.grant_name}</td>
                        <td>
                            <button onclick="editLevel(${item.level_id}, ${item.level_num}, '${item.grant_name}')" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> แก้ไข
                            </button>
                            <button onclick="deleteLevel(${item.level_id})" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> ลบ
                            </button>
                        </td>
                    </tr>`;
                });
                $('#level_list').html(html);
            }, 'json');
        }

		$('#btnAddLevel').click(function() {
            $('#level_form')[0].reset();
            $('#level_id').val('');
            $('#modalTitle').text('เพิ่มระดับสิทธิ์ใหม่');
            $('#levelModal').modal('show');
        });

        window.editLevel = function(id, num, name) {
            $('#level_id').val(id);
            $('#level_num').val(num);
            $('#grant_name').val(name);
            $('#modalTitle').text('แก้ไขข้อมูลระดับสิทธิ์');
            $('#levelModal').modal('show');
        };

        $('#level_form').on('submit', function(e) {
            e.preventDefault();
            let action = $('#level_id').val() ? 'update_level' : 'add_level';
            $.post('level_process.php?action=' + action, $(this).serialize(), function(res) {
                if(res.success) {
                    Swal.fire('สำเร็จ', 'ดำเนินการเรียบร้อย', 'success');
                    $('#levelModal').modal('hide');
                    loadLevels();
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }, 'json');
        });

        window.deleteLevel = function(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "การลบระดับสิทธิ์อาจส่งผลต่อผู้ใช้งานที่ใช้ระดับนี้อยู่!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ยืนยันลบ'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('level_process.php?action=delete_level', {level_id: id}, function(res) {
                        if(res.success) loadLevels();
                        else Swal.fire('ผิดพลาด', res.message, 'error');
                    }, 'json');
                }
            });
        };
    });
    </script>
</body>
</html>