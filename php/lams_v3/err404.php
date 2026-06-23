<?php
header("Refresh: 5; url=index.php");
exit();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<title>เกิดข้อผิดพลาด</title>
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
                <h1 class="h4 mb-0">เกิดข้อผิดพลาด</h1>
				</div>
            </div>

            <div class="card-body p-4">
				<div align="center">
					<h2>เกิดข้อผิดพลาดรหัส 404 : ไม่พบหน้าเว็บ</h2>
				</div>
            </div>
        </div>
    </div>
</body>
</html>