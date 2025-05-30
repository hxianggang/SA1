<?php
session_start();
if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] != 2) {
    header("Location: login.php");
    exit();
}

include('db.php');
include('header.php');

// 狀態中文對照函式
function statusToChinese($status)
{
    return match ($status) {
        'pending' => '待處理',
        'resolved' => '已處理',
        'rejected' => '駁回',
        default => htmlspecialchars($status),
    };
}

// 取得所有申訴列表
$sql = "SELECT a.appeal_id, e.e_title, u.name, a.appeal_date, a.status
        FROM appeals a
        JOIN event e ON a.e_id = e.e_id
        JOIN user u ON a.accounts = u.accounts
        ORDER BY a.appeal_date DESC";
$result = $conn->query($sql);

// 取得想查看的申訴ID (GET參數)
$view_id = isset($_GET['appeal_id']) ? intval($_GET['appeal_id']) : 0;
$detail = null;
if ($view_id > 0) {
    $stmt = $conn->prepare("SELECT a.*, e.e_title, u.name FROM appeals a 
                            JOIN event e ON a.e_id = e.e_id
                            JOIN user u ON a.accounts = u.accounts
                            WHERE a.appeal_id = ?");
    $stmt->bind_param("i", $view_id);
    $stmt->execute();
    $detail = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <title>申訴管理</title>
    <link rel="stylesheet" href="new.css" />
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: rgb(186, 184, 183);
        }

        tr:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }

        .detail-container {
            border: 1px solid #ccc;
            padding: 15px;
        }
    </style>
</head>

<body>
    <h1>申訴管理</h1>

    <!-- 申訴列表表格 -->
    <table>
        <thead>
            <tr>
                <th>建言標題</th>
                <th>申訴者</th>
                <th>申訴日期</th>
                <th>申訴狀態</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr onclick="window.location='appeals_manage.php?appeal_id=<?= $row['appeal_id'] ?>'">
                    <td><?= htmlspecialchars($row['e_title']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['appeal_date']) ?></td>
                    <td><?= statusToChinese($row['status']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- 顯示選擇的申訴詳細資料與回覆表單 -->
    <?php if ($detail): ?>
        <div class="detail-container">
            <h2>申訴詳情</h2>
            <p><strong>建言標題：</strong><?= htmlspecialchars($detail['e_title']) ?></p>
            <p><strong>申訴者：</strong><?= htmlspecialchars($detail['name']) ?></p>
            <p><strong>申訴內容：</strong><br><?= nl2br(htmlspecialchars($detail['appeal_text'])) ?></p>
            <p><strong>申訴狀態：</strong><?= statusToChinese($detail['status']) ?></p>
            <p><strong>申訴日期：</strong><?= htmlspecialchars($detail['appeal_date']) ?></p>

            <form method="POST" action="appeal_reply_process.php">
                <input type="hidden" name="appeal_id" value="<?= $detail['appeal_id'] ?>" />
                <label for="reply_text">回覆內容：</label><br>
                <textarea name="reply_text" id="reply_text" rows="5" style="width:100%;"><?= htmlspecialchars($detail['reply_text']) ?></textarea><br>
                <label for="status">更新狀態：</label>
                <select name="status" id="status">
                    <option value="pending" <?= $detail['status'] === 'pending' ? 'selected' : '' ?>>待處理</option>
                    <option value="resolved" <?= $detail['status'] === 'resolved' ? 'selected' : '' ?>>已處理</option>
                    <option value="rejected" <?= $detail['status'] === 'rejected' ? 'selected' : '' ?>>駁回</option>
                </select><br><br>
                <button type="submit">送出回覆</button>
            </form>
        </div>
    <?php else: ?>
        <p>請點選上方申訴列表查看詳細內容。</p>
    <?php endif; ?>

</body>

</html>