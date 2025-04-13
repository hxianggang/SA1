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
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
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

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 25px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-content h3 {
            margin-bottom: 20px;
        }

        .modal-content input {
            width: calc(100% - 40px);
            padding: 10px;
            font-size: 16px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .password-field {
            display: flex;
            align-items: center;
        }

        .toggle-password {
            margin-left: 10px;
            cursor: pointer;
            font-size: 18px;
            user-select: none;
        }

        .modal-actions {
            text-align: right;
        }

        .modal-actions button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
        }

        .confirm-button {
            background-color: #4CAF50;
            color: white;
        }

        .cancel-button {
            background-color: #e53935;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row"><span class="label">學號:</span><span class="value"><?= htmlspecialchars($accounts) ?></span></div>
        <div class="row"><span class="label">Gmail:</span><span class="value"><?= htmlspecialchars($gmail) ?></span><button class="action-button" onclick="document.getElementById('gmailModal').style.display='block'">修改</button></div>
        <div class="row"><span class="label">密碼:</span><span class="value">●●●●●●●●</span><button class="action-button" onclick="document.getElementById('passwordModal').style.display='block'">修改</button></div>
    </div>

    <!-- Gmail Modal -->
    <div id="gmailModal" class="modal">
        <div class="modal-content">
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
                <div class="modal-actions">
                    <button type="button" class="cancel-button" onclick="document.getElementById('gmailModal').style.display='none'">取消</button>
                    <button type="submit" class="confirm-button">確認</button>
                </div>
            </form>
        </div>
    </div>


    <!-- Password Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <h3>修改密碼</h3>
            <form method="POST">
                <input type="hidden" name="update_password" value="1">
                <div class="password-field">
                    <input type="password" name="new_password" id="new_password" placeholder="新密碼" required>
                    <span class="toggle-password" onclick="togglePassword('new_password', this)">👁</span>
                </div>
                <div class="password-field">
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="確認新密碼" required>
                    <span class="toggle-password" onclick="togglePassword('confirm_password', this)">👁</span>
                </div>
                <div class="modal-actions">
                    <button type="button" class="cancel-button" onclick="document.getElementById('passwordModal').style.display='none'">取消</button>
                    <button type="submit" class="confirm-button">確認</button>
                </div>
            </form>
        </div>
    </div>

    <script>
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