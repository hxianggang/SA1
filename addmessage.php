<?php
// 檢查是否已登入
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // 只在 session 尚未啟動時才呼叫 session_start()
}

if (!isset($_SESSION['username'])) {
    // 如果未登入，重定向到登入頁面
    header('Location: login.php');
    exit();
}

// 如果有提交表單
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 取得表單資料
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['username'];  // 假設 session 中保存了使用者帳號

    // 連接資料庫
    include('db.php');
    $stmt = $conn->prepare("INSERT INTO event (e_title, e_text, accounts, e_time) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('sss', $title, $content, $user_id);

    // 執行插入操作
    if ($stmt->execute()) {
        echo "建言發佈成功!";
    } else {
        echo "發佈失敗，請稍後再試。";
    }

    // 關閉連接
    $stmt->close();
    $conn->close();
}
?>

<!-- 這段HTML會顯示在右下角 -->
<button class="addmes_add-button-right-bottom" onclick="openForm()">+</button>

<!-- 用來顯示發佈建言的表單 -->
<div id="formContainer" class="addmes_form-container-popup">
    <form id="suggestionForm" method="POST">
        <label for="title">標題:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">內容:</label>
        <textarea id="content" name="content" required></textarea><br><br>

        <button type="submit">提交</button>
        <button type="button" onclick="closeForm()">取消</button>
    </form>
</div>