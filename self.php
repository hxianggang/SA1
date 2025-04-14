<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("header.php");

$account = $_SESSION['username'];
$warning = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['update_gmail'])) {
        $new_gmail = $_POST['new_gmail'];
        $stmt = $conn->prepare("UPDATE user SET gmail=? WHERE accounts=?");
        $stmt->bind_param("si", $new_gmail, $account);
        $stmt->execute();
        $stmt->close();
        $success = "信箱已成功更新！";
    }

    if (isset($_POST['update_password'])) {
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE user SET password=? WHERE accounts=?");
            $stmt->bind_param("si", $hashed_password, $account);
            $stmt->execute();
            $stmt->close();
            $success = "密碼已成功更新！";
        } else {
            $warning = "密碼不一致，請重新輸入！";
        }
    }
}

$stmt = $conn->prepare("SELECT accounts, gmail FROM user WHERE accounts=?");
$stmt->bind_param("i", $account);
$stmt->execute();
$stmt->bind_result($accounts, $gmail);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>個人資料</title>
    <style>
        body {
            font-family: '微軟正黑體', arial;
            background-color: #fffdf7;
            margin: 0;
            padding-top: 80px;
            display: flex;
        }

        /* 側邊欄 (sidebar) 設計 */
        .sidebar {
            width: 200px;
            background-color: var(--orange1);
            padding: 20px;
            color: white;
            height: 100vh;
            position: fixed;
            top: 64px; /* 讓 sidebar 距離 navbar 64px */
            left: 0;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #ff5700;
        }

        /* 主內容區域 */
        .container {
            margin-left: 220px;
            max-width: 700px;
            padding: 30px;
        }

        .row {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            margin-right: 10px;
        }

        .value {
            display: inline-block;
            min-width: 150px;
        }

        .action-button {
            background-color: #ff8000;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        /* 其他已經設計好的模態視窗及功能 */
        /* 此處省略之前的 CSS 設計，保持原來樣式不變 */

    </style>
</head>

<body>

    <!-- 側邊欄 -->
    <div class="sidebar">
        <h2>個人中心</h2>
        <a href="self.php">個人設置</a>
        <a href="view_voted_topics.php">查看已投票議題</a>
        <a href="my_topics.php">我的議題</a>
    </div>

    <!-- 右側內容區域 -->
    <div class="container">
        <div class="row"><span class="label">學號:</span><span class="value"><?= htmlspecialchars($accounts) ?></span></div>
        <div class="row"><span class="label">信箱:</span><span class="value"><?= htmlspecialchars($gmail) ?></span><button class="action-button" onclick="document.getElementById('gmailModal').style.display='block'">修改</button></div>
        <div class="row"><span class="label">密碼:</span><span class="value">●●●●●●●●</span><button class="action-button" onclick="document.getElementById('passwordModal').style.display='block'">修改</button></div>
    </div>

    <!-- Gmail Modal -->
    <div id="gmailModal" class="modal">
        <!-- 這裡省略原本的模態視窗程式碼 -->
    </div>

    <!-- 密碼 Modal -->
    <div id="passwordModal" class="modal">
        <!-- 這裡省略原本的模態視窗程式碼 -->
    </div>

    <script>
        // 關閉模態視窗
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = "none";
            }
        }

        function togglePassword(fieldId, icon) {
            const field = document.getElementById(fieldId);
            if (field.type === "password") {
                field.type = "text";
                icon.textContent = "🙈";
            } else {
                field.type = "password";
                icon.textContent = "👁";
            }
        }
    </script>
</body>

</html>
