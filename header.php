<!-- 0503 AQ 導覽列依身分別有區別-->

<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['name']);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="new.css">
</head>

<body>

    <!-- 功能列 -->
    <div class="header_navbar">
        <!-- logo位置 -->
        <div class="header_logo">
            <div class="header_logo-image" onclick="window.location.href='index.php';"></div>

            <a href="index.php" class="header_logo-title">愛校建言系統</a>
        </div>

        <!-- 功能列表 -->
        <div class="header_links">
            <!--使用者-->
            <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1){ ?>
            <a href="vote.php" class="header_link">投票專區</a>
            <?php } ?>
            <!--管理者-->
            <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 2){ ?>
            <a href="event.php" class="header_link">審核建言</a>
            <?php } ?>
            <a href="fundraise.php" class="header_link">募資專區</a>

            <?php if ($is_logged_in): ?>
                <!-- 顯示學號並跳轉到個人資訊 -->
                <a href="self.php" class="header_link"><?php echo $_SESSION['name']; ?></a>
                <!-- 顯示登出圖示 -->
                <div class="header_logout-icon" onclick="showLogoutConfirm()"></div>
            <?php else: ?>
                <!-- 顯示登入 -->
                <a href="login.php" class="header_link">登入</a>
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
            window.location.href = 'logout.php';
        }
    </script>

</body>

</html>