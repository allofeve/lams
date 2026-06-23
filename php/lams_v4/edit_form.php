<?php
include 'config.php';
// ตรวจสอบการ Login
if(!isset($_SESSION['username'])) { 
    header("Location: login.php"); 
    exit(); 
}

// รับค่า ID และดึงข้อมูลเดิม
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT * FROM loan_tb WHERE id = ?");
$stmt->execute(array($id));
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$data) { 
    die("<div class='alert alert-danger'>ไม่พบข้อมูลสัญญาที่ต้องการแก้ไข</div>"); 
}

// แปลงวันที่จากฐานข้อมูล (Y-m-d) เป็นรูปแบบไทย (วว/ดด/ปปปป)
$date_parts = explode('-', $data['loan_date_en']);
$date_th = $date_parts[2]."/".$date_parts[1]."/".($date_parts[0]+543);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <title>แก้ไขข้อมูลสัญญา - Loan System</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    <style>
        body { background-color: #f0f2f5; padding-top: 50px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <div class="card main-card">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="fas fa-edit"></i> แก้ไขข้อมูลสัญญา: <?php echo htmlspecialchars($data['loan_id']); ?></h5>
            </div>
            <div class="card-body p-4">
                <form id="update_form" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">เลขทะเบียนสมาชิก</label>
                        <input type="text" name="member_id" class="form-control" value="<?php echo htmlspecialchars($data['member_id']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ชื่อ-นามสกุล</label>
                        <input type="text" name="member_name" class="form-control" value="<?php echo htmlspecialchars($data['member_name']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">เลขที่สัญญา</label>
                        <input type="text" name="loan_id" class="form-control" value="<?php echo htmlspecialchars($data['loan_id']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">วันที่สัญญา (พ.ศ.)</label>
                        <input type="text" name="loan_date" class="form-control" id="loan_date" value="<?php echo $date_th; ?>" placeholder="วว/ดด/ปปปป" maxlength="10" required>
                    </div>
					
					<div class="mb-3">
                        <label class="form-label fw-bold">สังกัด</label>
                        <input type="text" name="member_group" class="form-control" value="<?php echo htmlspecialchars($data['member_group']); ?>" required>
                    </div>

                    <div class="mb-4 p-3 border rounded bg-light">
                        <label class="form-label fw-bold text-primary"><i class="fas fa-file-pdf"></i> เปลี่ยนไฟล์สัญญา PDF (ถ้ามี)</label>
                        <input type="file" name="loan_file" class="form-control" accept="application/pdf">
                        <div class="form-text mt-2">
                            ไฟล์ปัจจุบัน: <span class="badge bg-secondary"><?php echo htmlspecialchars($data['file_name']); ?></span>
                            <br><small class="text-danger">* หากไม่ต้องการเปลี่ยนไฟล์ ให้เว้นว่างไว้</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="edit_list.php" class="btn btn-secondary px-4">ยกเลิก</a>
                        <button type="submit" class="btn btn-success px-4">บันทึกการแก้ไข</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // ฟอร์แมตวันที่อัตโนมัติ (ใส่ / ให้อัตโนมัติ)
        $('#loan_date').on('input', function() {
            var val = $(this).val().replace(/\D/g, '');
            if (val.length > 2 && val.length <= 4) val = val.slice(0, 2) + '/' + val.slice(2);
            else if (val.length > 4) val = val.slice(0, 2) + '/' + val.slice(2, 4) + '/' + val.slice(4, 8);
            $(this).val(val);
        });

        $('#update_form').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'กำลังบันทึกข้อมูล...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // ใช้ FormData เพราะมีการส่งไฟล์
            var formData = new FormData(this);

            $.ajax({
                url: 'update_process.php',
                type: 'POST',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(res) {
                    if(res.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'แก้ไขข้อมูลสัญญาเรียบร้อยแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = 'edit_list.php';
                        });
                    } else {
                        Swal.fire('ผิดพลาด!', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('ผิดพลาด!', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                }
            });
        });
    });
    </script>
</body>
</html>