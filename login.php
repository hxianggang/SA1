<?php
session_start();
 
// 引入資料庫連線
include('db.php');

$error_message = ""; // 初始化錯誤訊息變數

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 取得使用者輸入的帳號和密碼
    $account = $_POST['account'];
    $password = $_POST['password'];

    // 防止 SQL 注入
    $account = $conn->real_escape_string($account);
    $password = $conn->real_escape_string($password);

    $sql = "SELECT * FROM user WHERE accounts='$account'";
    $result = $conn->query($sql);

    // 檢查帳號是否存在
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 使用 password_verify() 檢查密碼是否正確
        if (password_verify($password, $user['password'])) {
            // 密碼正確，設定 session 變數
            $_SESSION['username'] = $user['accounts']; // 修改成 accounts
            $_SESSION['role'] = $user['role'];

            // 轉跳到主頁或其他頁面
            header('Location: index.php');
            exit;
        } else {
            // 密碼錯誤
            $error_message = "帳號或密碼錯誤";
        }
    } else {
        // 帳號不存在
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
    <style>
        @charset "utf-8";

        /* 設定顏色變數 */
        :root {
            --orange1: #ff8000;
            --orange2: #ea7500;
            --color-white: #fefefe;
            --color-black: #000;
            --error-red: #ff4d4d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* 設定全頁面背景 */
        body {
            font-family: '微軟正黑體', arial;
            background-image: url('./images/focus2293.jpg');
            background-size: cover;
            background-attachment: fixed;
            background-position: center center;/*照片置中*/
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--color-white);
        }

        /* 主要登入區塊 */
        .login-form {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
        }

        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-header img {
            width: 50px;
            margin-bottom: 20px;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: bold;
            color: var(--orange1);
        }

        /* 文字標題 */
        .login-header p {
            font-size: 16px;
            margin-top: 10px;
        }

        /* 輸入框 */
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            font-size: 16px;
            background-color: #fff;
            color: #333;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--orange1);
        }

        /* 登入按鈕 */
        .login-button {
            width: 100%;
            padding: 12px;
            background-color: var(--orange1);
            color: var(--color-white);
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 10px 0;
        }

        .login-button:hover {
            background-color: var(--orange2);
        }

        /* 註冊與回首頁 */
        .login-links {
            text-align: center;
            margin-top: 15px;
        }

        .login-links a {
            color: var(--color-white);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin: 0 10px;
        }

        .login-links a:hover {
            color: var(--orange1);
        }

        /* 錯誤訊息的樣式 */
        .error-message {
            color: var(--error-red);
            text-align: center;
            margin-bottom: 20px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <div class="login-form">
        <div class="login-header">
            <img src="./images/school.png" alt="學校標誌">
            <h2>輔仁大學 愛校建言系統</h2>
            <p>請輸入帳號(學號)和密碼登入系統</p>
        </div>

        <!-- 顯示錯誤訊息 -->
        <?php if ($error_message): ?>
        <div class="error-message">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="account" placeholder="帳號(學號)" required>
            <input type="password" name="password" placeholder="密碼" required>
            <button type="submit" class="login-button">登入</button>
        </form>

        <div class="login-links">
            <a href="index.php">回首頁</a>
            <a href="register.php">註冊</a>
        </div>
    </div>
</body>

</html>
