<?php
session_start();

$is_logged_in = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <style>
        /* 通用設定 */
        :root {
            --orange1: #ff8000;
            --color-white: #fefefe;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            font-family: '微軟正黑體', arial;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        /* 功能列相關 */
        .navbar {
            width: 100%;
            height: 64px;
            background-color: var(--orange1);
            position: fixed;
            z-index: 1000;
            display: flex;
            align-items: center;
            top: 0;
            padding: 0 20px;
            min-width: 1200px;
            justify-content: space-between;
        }

        /* logo區域 */
        .navbar-logo {
            display: flex;
            align-items: center;
            width: auto;
        }

        /* 這是插入圖片的位置，記得替換圖片路徑 */
        .navbar-logo-image {
            width: 40px; /* 設定圖片大小 */
            height: 40px;
            background-image: url('./images/school.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            cursor: pointer;
        }

        .navbar-logo-title {
            font-size: 20px;
            font-weight: 900;
            color: var(--color-white);
            margin-left: 15px;
            text-decoration: none;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: auto;
        }

        .navbar-link {
            color: var(--color-white);
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
            transition: all .3s;
            padding: 0 15px;
        }

        .navbar-link:hover {
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }

        .navbar-login {
            display: flex;
            align-items: center;
            justify-content: right;
            width: auto;
        }

        .navbar-login .navbar-link {
            color: var(--color-white);
            font-weight: 600;
            font-size: 18px;
            text-decoration: none;
        }

        /* 登出圖示 */
        .logout-icon {
            width: 30px;
            height: 30px;
            background-image: url('./images/logout.png');
            background-size: contain;
            background-repeat: no-repeat;
            margin-left: 10px;
            cursor: pointer;
        }

        /* 登出確認彈窗 */
        #logout-confirm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #logout-confirm button {
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <!-- 功能列 -->
    <div class="navbar">
        <!-- logo位置 -->
        <div class="navbar-logo">
            <!-- 插入圖片，點擊後跳轉到 index.php -->
            <div class="navbar-logo-image" onclick="window.location.href='index.php';"></div>

            <a href="index.php" class="navbar-logo-title">愛校建言系統</a>
        </div>

        <!-- 功能列表 -->
        <div class="navbar-links">
            <a href="main.html" class="navbar-link">最新消息</a>
            <a href="vote.html" class="navbar-link">最新議題</a>
            <a href="money.html" class="navbar-link">募資專區</a>

            <?php if ($is_logged_in): ?>
                <!-- 顯示學號並跳轉到個人資訊 -->
                <a href="self.php" class="navbar-link"><?php echo $_SESSION['username']; ?></a>
                <!-- 顯示登出圖示 -->
                <div class="logout-icon" onclick="showLogoutConfirm()"></div>
            <?php else: ?>
                <!-- 顯示登入 -->
                <a href="login.php" class="navbar-link">登入</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- 登出確認彈窗 -->
    <div id="logout-confirm">
        <p>確定要登出嗎？</p>
        <button onclick="logout()">確定</button>
        <button onclick="closeLogoutConfirm()">取消</button>
    </div>

    <script>
        // 顯示登出確認彈窗
        function showLogoutConfirm() {
            document.getElementById('logout-confirm').style.display = 'block';
        }

        // 關閉登出確認彈窗
        function closeLogoutConfirm() {
            document.getElementById('logout-confirm').style.display = 'none';
        }

        // 登出
        function logout() {
            // 清除 session 並重新導向到登錄頁面
            window.location.href = 'logout.php'; // 這裡可以根據需要跳轉到登出處理頁面
        }
    </script>

</body>

</html>
