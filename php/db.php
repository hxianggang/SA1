<?php
// db.php - 用來建立與資料庫的連線

$host = 'localhost';  // 資料庫主機
$username = 'root';   // 資料庫使用者名稱
$password = '';       // 資料庫密碼
$dbname = 'sa';       // 資料庫名稱

// 建立資料庫連線
$conn = new mysqli($host, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}
?>
