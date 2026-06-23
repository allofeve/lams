<?php
include 'config.php'; 
include 'check_permission.php';
// 1. ตรวจสอบความปลอดภัย
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>Optimize Database - Loan System</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    
    <style>
        body { background-color: #f0f2f5; padding-top: 50px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
        .log-container { background: #212529; color: #0f0; padding: 15px; border-radius: 8px; font-family: 'Courier New', Courier, monospace; font-size: 0.9rem; max-height: 300px; overflow-y: auto; }
    </style>
</head>
<body>
    <div class="container" style="max-width: 700px;">
        <div class="d-flex justify-content-between mb-3 px-2">
            <a href="index.php" class="btn btn-sm btn-outline-primary"><i class="fas fa-home"></i> กลับหน้าหลัก</a>
            <span class="text-muted">ผู้ใช้: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
        </div>

        <div class="card main-card">
            <div class="card-header bg-dark text-white text-center py-4">
                <i class="fas fa-database fa-3x mb-2 text-info"></i>
                <h1 class="h4 mb-0">Database Optimization</h1>
                <p class="mb-0 opacity-75">จัดเรียงดัชนีและเพิ่มประสิทธิภาพการค้นหาข้อมูล</p>
            </div>
            <div class="card-body p-4 text-center">
                <div id="initial_state">
                    <p class="text-muted mb-4">การทำงานนี้จะทำการ `OPTIMIZE TABLE` ทุกตารางในฐานข้อมูลของคุณ เพื่อคืนพื้นที่และทำให้การ Query ทำงานได้รวดเร็วขึ้น</p>
                    <button id="start_optimize" class="btn btn-info btn-lg w-100 shadow-sm">
                        <i class="fas fa-bolt"></i> เริ่มการปรับแต่งฐานข้อมูล
                    </button>
                </div>

                <div id="processing_state" style="display:none;">
                    <h5 class="mb-3">กำลังดำเนินการ...</h5>
                    <div class="progress mb-3" style="height: 25px;">
                        <div id="progress_bar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%">0%</div>
                    </div>
                    <div class="log-container text-start" id="log_box">
                        > เตรียมความพร้อม...<br>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#start_optimize').on('click', function() {
            $('#initial_state').hide();
            $('#processing_state').show();
            runOptimize();
        });

        function runOptimize() {
            $.ajax({
                url: 'optimize_process.php',
                type: 'POST',
                dataType: 'json',
                success: function(res) {
                    if(res.success) {
                        $('#progress_bar').css('width', '100%').text('100%');
                        res.logs.forEach(function(msg) {
                            $('#log_box').append('> ' + msg + '<br>');
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'ปรับแต่งฐานข้อมูลทั้งหมดเรียบร้อยแล้ว',
                            confirmButtonText: 'ตกลง'
                        });
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้', 'error');
                }
            });
        }
    });
    </script>
</body>
</html>