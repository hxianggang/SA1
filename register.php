<?php
//session_start();  // 啟動 session

include('db.php');

$error_message = "";  // 儲存錯誤訊息
$account = $email = "";  // 初始化變數

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account = $_POST['account'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // 防止 SQL 注入
    $account = $conn->real_escape_string($account);
    $password = $conn->real_escape_string($password);
    $email = $conn->real_escape_string($email);

    // 檢查帳號是否已註冊
    $sql_account_check = "SELECT * FROM user WHERE accounts = ?";
    $stmt = $conn->prepare($sql_account_check);
    $stmt->bind_param('s', $account);
    $stmt->execute();
    $result_account = $stmt->get_result();

    // 檢查電子信箱是否已註冊
    $sql_email_check = "SELECT * FROM user WHERE gmail = ?";
    $stmt = $conn->prepare($sql_email_check);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result_email = $stmt->get_result();

    // 設置錯誤訊息
    if ($result_account->num_rows > 0) {
        // 如果帳號已經註冊
        $error_message = "該帳號已被註冊";
    } elseif ($result_email->num_rows > 0) {
        // 如果信箱已經註冊
        $error_message = "該電子信箱已被註冊";
    } else {
        // 密碼加密
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // 權限固定為 '1'，name 設為帳號(學號)
        $permissions = '1';
        $name = $account;

        // 使用 prepared statement 寫入資料庫
        $sql = "INSERT INTO user (accounts, password, gmail, permissions, name) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $account, $password_hash, $email, $permissions, $name);

        if ($stmt->execute()) {
            header('Location: login.php');
            exit;
        } else {
            echo "註冊失敗: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>註冊</title>
    <link rel="stylesheet" href="setting.css">
</head>

<body class="register_body">
    <div class="register_form">
        <div class="register_header">
            <img src="./images/school.png" alt="學校標誌">
            <h2>輔仁大學 愛校建言系統</h2>
            <p>請填寫帳號(學號)、密碼和電子信箱註冊</p>
        </div>

        <!-- 顯示錯誤訊息 -->
        <?php if ($error_message): ?>
            <div class="register_error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="account" placeholder="帳號(學號)" value="<?php echo htmlspecialchars($account); ?>" class="<?php echo ($error_message && strpos($error_message, '帳號') !== false) ? 'register_error' : ''; ?>" required>
            <input type="password" name="password" placeholder="密碼" required>
            <input type="email" name="email" placeholder="電子信箱" value="<?php echo htmlspecialchars($email); ?>" class="<?php echo ($error_message && strpos($error_message, '信箱') !== false) ? 'register_error' : ''; ?>" required>
            <button type="submit" class="register_button">註冊</button>
        </form>

        <div class="register_links">
            <a href="index.php">回首頁</a>
            <a href="login.php">已經有帳號？登入</a>
        </div>
    </div>
</body>

</html>