<?php
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("header.php");

$account = $_SESSION['name'];
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
    <link rel="stylesheet" href="new.css">
</head>

<body class="self_body">
    <!-- 側邊欄 -->
    <div class="self_sidebar">
        <h2>個人中心</h2>
        <a href="self.php">個人設置</a>
        <a href="view_voted_topics.php">查看已投票議題</a>
        <a href="my_topics.php">我的議題</a>
    </div>

    <!-- 右側內容區域 -->
    <div class="self_container">
        <div class="self_row"><span class="self_label">學號:</span><span class="self_value"><?= htmlspecialchars($accounts) ?></span></div>
        <div class="self_row"><span class="self_label">信箱:</span><span class="self_value"><?= htmlspecialchars($gmail) ?></span><button class="self_action-button" onclick="document.getElementById('gmailModal').style.display='block'">修改</button></div>
        <div class="self_row"><span class="self_label">密碼:</span><span class="self_value">●●●●●●●●</span><button class="self_action-button" onclick="document.getElementById('passwordModal').style.display='block'">修改</button></div>
    </div>

    <!-- Gmail Modal -->
    <div id="gmailModal" class="self_modal">
        <div class="self_modal-content">
            <h3>修改 Gmail</h3>
            <form method="POST">
                <input type="hidden" name="update_gmail" value="1">
                <div style="margin-bottom: 15px;">
                    <label>原信箱：</label><br>
                    <span style="font-size: 16px;"><?= htmlspecialchars($gmail) ?></span>
                </div>
                <div>
                    <label for="new_gmail">新信箱：</label>
                    <input type="email" name="new_gmail" id="new_gmail" placeholder="請輸入新信箱" required>
                </div>
                <div class="self_modal-actions">
                    <button type="button" class="self_cancel-button" onclick="document.getElementById('gmailModal').style.display='none'">取消</button>
                    <button type="submit" class="self_confirm-button">確認</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 密碼 Modal -->
    <div id="passwordModal" class="self_modal">
        <div class="self_modal-content">
            <h3>修改密碼</h3>
            <form method="POST">
                <input type="hidden" name="update_password" value="1">
                <div class="self_password-field">
                    <input type="password" name="new_password" id="new_password" placeholder="新密碼" required>
                    <span class="self_toggle-password" onclick="togglePassword('new_password', this)">👁</span>
                </div>
                <div class="self_password-field">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="確認新密碼" required>
                    <span class="self_toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
                </div>
                <div class="self_modal-actions">
                    <button type="button" class="self_cancel-button" onclick="document.getElementById('passwordModal').style.display='none'">取消</button>
                    <button type="submit" class="self_confirm-button">確認</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // 顯示/隱藏彈窗
        window.onclick = function(event) {
            if (event.target.classList.contains('self_modal')) {
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