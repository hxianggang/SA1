<?php
session_start();  // 啟動 session

// 引入資料庫連線
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 取得使用者輸入的註冊資料
    $account = $_POST['account'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // 防止 SQL 注入
    $account = $conn->real_escape_string($account);
    $password = $conn->real_escape_string($password);
    $email = $conn->real_escape_string($email);

    // 密碼加密
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 插入資料庫
    $sql = "INSERT INTO user (accounts, password, gmail) VALUES ('$account', '$password_hash', '$email')";

    if ($conn->query($sql) === TRUE) {
        // 註冊成功後，重定向至登入頁面
        header('Location: login.php');
        exit;
    } else {
        echo "註冊失敗: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊</title>
    <style>
        @charset "utf-8";

        /* 設定顏色變數 */
        :root {
            --orange1: #ff8000;
            --orange2: #ea7500;
            --color-white: #fefefe;
            --color-black: #000;
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
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: var(--color-white);
        }

        /* 主要註冊區塊 */
        .register-form {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
        }

        .register-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .register-header img {
            width: 50px;
            margin-bottom: 20px;
        }

        .register-header h2 {
            font-size: 28px;
            font-weight: bold;
            color: var(--orange1);
        }

        /* 文字標題 */
        .register-header p {
            font-size: 16px;
            margin-top: 10px;
        }

        /* 輸入框 */
        input[type="text"],
        input[type="password"],
        input[type="email"] {
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
        input[type="password"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: var(--orange1);
        }

        /* 註冊按鈕 */
        .register-button {
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


        .register-button:hover {
            background-color: var(--orange2);
        }

        /* 登入與回首頁 */
        .register-links {
            text-align: center;
            margin-top: 15px;
        }

        .register-links a {
            color: var(--color-white);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin: 0 10px;
        }

        .register-links a:hover {
            color: var(--orange1);
        }
    </style>
</head>

<body>
    <div class="register-form">
        <div class="register-header">
            <img src="./images/school.png" alt="學校標誌">
            <h2>輔仁大學 愛校建言系統</h2>
            <p>請填寫帳號(學號)、密碼和電子信箱註冊</p>
        </div>

        <form method="POST" action="">
            <input type="text" name="account" placeholder="帳號(學號)" required>
            <input type="password" name="password" placeholder="密碼" required>
            <input type="email" name="email" placeholder="電子信箱" required>
            <button type="submit" class="register-button">註冊</button>
        </form>

        <div class="register-links">
            <a href="index.php">回首頁</a>
            <a href="login.php">已經有帳號？登入</a>
        </div>
    </div>
</body>

</html>