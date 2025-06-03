<?php
include('db.php');
//session_start();
$error_message = ""; // 初始化錯誤訊息變數

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acc = $_POST['acc'];
    $pw = $_POST['pw'];

    $link = mysqli_connect('localhost', 'root', '', 'sa');
    if (!$link) {
        die("資料庫連線錯誤: " . mysqli_connect_error());
    }

    // 使用準備語句查詢帳號
    $stmt = mysqli_prepare($link, "SELECT * FROM user WHERE accounts = ?");
    mysqli_stmt_bind_param($stmt, "s", $acc);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // 用 password_verify 比對輸入密碼和資料庫雜湊密碼
        if (password_verify($pw, $row['password'])) {
            $_SESSION['acc'] = $acc;
            $_SESSION['permissions'] = $row['permissions'];
            $_SESSION['name'] = $row['name'];
            header('Location: index.php');
            exit();
        } else {
            $error_message = "帳號或密碼錯誤";
        }
    } else {
        $error_message = "帳號或密碼錯誤";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>登入</title>
    <link rel="stylesheet" href="setting.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>

<body class="login_body">
    <div class="login_form">
        <div class="login_header">
            <img src="./images/school.png" alt="學校標誌" />
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
            <input type="text" name="acc" placeholder="帳號(學號)" required />
            <input type="password" name="pw" placeholder="密碼" required />
            <button type="submit" class="login_button">登入</button>
        </form>

        <div class="login_links">
            <a href="index.php">回首頁</a>
            <a href="register.php">註冊</a>
        </div>
    </div>
</body>

</html>