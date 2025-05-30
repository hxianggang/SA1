<?php
session_start();
if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] != 2) {
    header("Location: login.php");
    exit();
}

include('db.php');

$sql = "SELECT a.*, e.e_title, u.name FROM appeals a
        JOIN event e ON a.e_id = e.e_id
        JOIN user u ON a.accounts = u.accounts
        ORDER BY a.appeal_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <title>申訴管理</title>
    <link rel="stylesheet" href="new.css" />
</head>

<body>
    <h1>申訴管理</h1>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
            <p><strong>建言標題：</strong><?= htmlspecialchars($row['e_title']) ?></p>
            <p><strong>申訴者：</strong><?= htmlspecialchars($row['name']) ?></p>
            <p><strong>申訴內容：</strong><br><?= nl2br(htmlspecialchars($row['appeal_text'])) ?></p>
            <p><strong>申訴狀態：</strong><?= htmlspecialchars($row['status']) ?></p>
            <p><strong>申訴日期：</strong><?= htmlspecialchars($row['appeal_date']) ?></p>

            <form method="POST" action="appeal_reply_process.php">
                <input type="hidden" name="appeal_id" value="<?= $row['appeal_id'] ?>" />
                <label for="reply_text">回覆內容：</label><br>
                <textarea name="reply_text" id="reply_text" rows="5" style="width:100%;"><?= htmlspecialchars($row['reply_text']) ?></textarea><br>
                <label for="status">更新狀態：</label>
                <select name="status" id="status">
                    <option value="pending" <?= $row['status'] === 'pending' ? 'selected' : '' ?>>待處理</option>
                    <option value="resolved" <?= $row['status'] === 'resolved' ? 'selected' : '' ?>>已處理</option>
                    <option value="rejected" <?= $row['status'] === 'rejected' ? 'selected' : '' ?>>駁回</option>
                </select><br><br>
                <button type="submit">送出回覆</button>
            </form>
        </div>
    <?php endwhile; ?>
</body>

</html>