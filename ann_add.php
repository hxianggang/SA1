<?php
ob_start();
// 檢查是否已登入
if (session_status() == PHP_SESSION_NONE) {
    session_start();  // 只在 session 尚未啟動時才呼叫 session_start()
}

if (!isset($_SESSION['name'])) {
    // 如果未登入，重定向到登入頁面
    header('Location: login.php');
    exit();
} ?>

<!-- 這段HTML會顯示在右下角 -->
<button class="addmes_add-button-right-bottom" onclick="openForm()">+</button>

<!-- 新增公告的表單 -->
<div id="formContainer" class="addmes_form-container-popup" style="display:none;">
    <form id="suggestionForm" method="POST" action="ann_process_add.php">
        <label for="title">新增公告:</label>
        <input type="text" id="title" name="title" required><br><br>

        <label for="content">內文:</label>
        <textarea id="content" name="content" required></textarea><br><br>

        <button type="submit">提交</button>
        <button type="button" onclick="closeForm()">取消</button>
    </form>
</div>

<?php
ob_end_flush();
?>