<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<meta charset="UTF-8">
    <title>เข้าสู่ระบบจัดเก็บสัญญา</title>
    <?php
		include 'lib_head.php';
	?>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="text-center mb-4">เข้าสู่ระบบจัดเก็บสัญญา</h4>
                    <form id="loginForm">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
						<div class="mb-3 form-check">
							<input type="checkbox" class="form-check-input" name="remember" id="remember" checked>
							<label class="form-check-label" for="remember">จดจำฉันในระบบ (1 เดือน)</label>
						</div>
                        <button type="submit" class="btn btn-primary w-100" id="btnLogin">เข้าสู่ระบบ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        
        // แสดงผล Loading
        Swal.fire({
            title: 'กำลังตรวจสอบ...',
            allowOutsideClick: false,
            didOpen: function() { Swal.showLoading(); }
        });

        $.ajax({
            url: 'check_login.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'สำเร็จ', text: 'กำลังเปลี่ยนหน้า...', timer: 1500, showConfirmButton: false });
                    setTimeout(function() { window.location.href = 'index.php'; }, 1500);
                } else {
                    Swal.fire('ข้อผิดพลาด', response.message, 'error');
                }
            }
        });
    });
});
</script>
</body>
</html>