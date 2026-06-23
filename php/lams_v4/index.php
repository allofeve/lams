<?php
include 'config.php';

if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// ดึงสิทธิ์การเข้าถึงเมนู
$allowed_pages = array();
$stmt_grant = $conn->prepare("SELECT grant_tb.page_name FROM grant_tb INNER JOIN level_tb ON level_tb.level_id = grant_tb.level_id WHERE level_tb.level_num <= ?");
$stmt_grant->execute(array($clevel));
while($row = $stmt_grant->fetch(PDO::FETCH_ASSOC)) {
    $allowed_pages[] = $row['page_name'];
}

function can_show($file_name, $allowed_pages) {
    global $conn;
    $stmt = $conn->prepare("SELECT gid FROM grant_tb WHERE page_name = ?");
    $stmt->execute(array($file_name));
    if (!$stmt->fetch()) { return true; }
    return in_array($file_name, $allowed_pages);
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>ระบบจัดเก็บสัญญา (หน้าหลัก)</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php
		include 'lib_head.php';
	?>
    <style>
        body { background-color: #f0f2f5; padding-top: 30px; }
        .container { max-width: 800px; }
        .main-card { border-radius: 1rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border: none; }
        .menu-item {
            display: block; padding: 15px 20px; margin-bottom: 10px;
            border-radius: 0.75rem; transition: all 0.3s ease;
            text-decoration: none; border: 1px solid #e0e0e0; background: white;
        }
        .menu-item:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.05); background: #fafafa; }
        .menu-icon { font-size: 1.8rem; margin-right: 15px; width: 40px; text-align: center; }
        .menu-title { font-size: 1.1rem; font-weight: bold; color: #333; }
        .online-dot { width: 10px; height: 10px; background-color: #28a745; border-radius: 50%; display: inline-block; margin-right: 5px; animation: blink 1.5s infinite; }
        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="card main-card">
            <div class="card-header bg-primary text-white text-center py-4" style="border-radius: 1rem 1rem 0 0;">
                <h1 class="h4 mb-0">ระบบจัดเก็บสัญญาเงินกู้ (Loan Agreement Management System)</h1>
                <div class="mt-2 small text-white-100">
					<i class="fas fa-user-circle"></i> <?php echo $_SESSION['username']; ?> (Level: <?php echo $clevel; ?>)
					<a href="change_password.php" class="text-white ms-3 text-decoration-none"><i class="fas fa-key"></i> เปลี่ยนรหัสผ่าน</a>
					<a href="logout.php" class="btn btn-sm btn-danger ms-2 shadow-sm"> <i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a>
				</div>
            </div>
            
            <div class="card-body p-4">
                <div class="row">
                    <?php 
                    $menus = [
                        ['file' => 'search.php', 'icon' => 'fa-search', 'color' => 'text-primary', 'title' => 'ค้นหาสัญญา'],
                        ['file' => 'upload_file.php', 'icon' => 'fa-cloud-upload-alt', 'color' => 'text-success', 'title' => 'อัพโหลดสัญญาใหม่'],
                        ['file' => 'edit_list.php', 'icon' => 'fa-edit', 'color' => 'text-warning', 'title' => 'แก้ไขข้อมูลสัญญา'],
                        ['file' => 'delete_list.php', 'icon' => 'fa-trash-alt', 'color' => 'text-danger', 'title' => 'ลบไฟล์สัญญา'],
						['file' => 'manage_level.php', 'icon' => 'fa-user-tag', 'color' => 'text-dark', 'title' => 'จัดการเลเวล'],
						['file' => 'manage_grants.php', 'icon' => 'fa-user-shield', 'color' => 'text-dark', 'title' => 'จัดการสิทธิ์เข้าถึงหน้าเว็บ'],
                        ['file' => 'manage_users.php', 'icon' => 'fa-users', 'color' => 'text-info', 'title' => 'จัดการผู้ใช้งาน'],
						['file' => 'optimize.php', 'icon' => 'fa-database', 'color' => 'text-info', 'title' => 'Optimize MySQL'],
						['file' => 'view_logs.php', 'icon' => 'fa-file-alt', 'color' => 'text-warning', 'title' => 'ประวัติการใช้งาน'],
						['file' => 'manage_unlock.php', 'icon' => 'fa-unlock', 'color' => 'text-danger', 'title' => 'ปลดแบน']
                    ];

                    foreach($menus as $m) {
                        if(can_show($m['file'], $allowed_pages)) {
                            echo '<div class="col-12">
                                <a href="'.$m['file'].'" class="menu-item border-'.$m['color'].'">
                                    <div class="d-flex align-items-center">
                                        <i class="fas '.$m['icon'].' menu-icon '.$m['color'].'"></i>
                                        <div class="menu-title">'.$m['title'].'</div>
                                    </div>
                                </a>
                            </div>';
                        }
                    }
                    ?>

                    <div class="col-12 mt-4">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-signal text-success"></i> ผู้ใช้ที่ออนไลน์ขณะนี้</h6>
                                <div id="online_list" class="d-flex flex-wrap gap-2">
                                    <span class="text-muted small">กำลังโหลด...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function updateOnlineStatus() {
        $.get('user_process.php?action=online_list', function(data) {
            let html = '';
            if(data.length > 0) {
                data.forEach(function(u) {
                    html += `<span class="badge bg-white text-dark border p-2">
                                <span class="online-dot"></span>${u.username} 
                                <small class="text-muted">(Lv.${u.level_num})</small>
                             </span>`;
                });
            } else {
                html = '<span class="text-muted small">ไม่มีใครออนไลน์</span>';
            }
            $('#online_list').html(html);
        }, 'json');
    }

    $(document).ready(function() {
        updateOnlineStatus();
        setInterval(updateOnlineStatus, 5000); // อัปเดตทุก 5 วินาที
    });
    </script>
</body>
</html>
<?php
	$conn = null;
?>