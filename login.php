<?php
session_start();

// 引入資料庫連線
include('db.php');

$error_message = ""; // 初始化錯誤訊息變數

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account = $_POST['account'];
    $password = $_POST['password'];

    $account = $conn->real_escape_string($account);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT * FROM user WHERE accounts='$account'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['accounts'];
            $_SESSION['role'] = $user['role'];
            header('Location: index.php');
            exit;
        } else {
            $error_message = "帳號或密碼錯誤";
        }
    } else {
        $error_message = "帳號或密碼錯誤";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="login_body">
    <div class="login_form">
        <div class="login_header">
            <img src="./images/school.png" alt="學校標誌">
            <h2>輔仁大學 愛校建言系統</h2>
            <p>請輸入帳號(學號)和密碼登入系統</p>
        </div>

        <!-- 顯示錯誤訊息 -->
        <?php if ($error_message): ?>
            <div class="login_error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="account" placeholder="帳號(學號)" required>
            <input type="password" name="password" placeholder="密碼" required>
            <button type="submit" class="login_button">登入</button>
        </form>

        <div class="login_links">
            <a href="index.php">回首頁</a>
            <a href="register.php">註冊</a>
        </div>
    </div>
</body>

</html>