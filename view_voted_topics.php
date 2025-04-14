<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("header.php");

$account = $_SESSION['username'];
$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// 查詢已投票的議題
$sql = "SELECT e.e_id, e.e_title, e.e_text, e.e_time FROM vote v 
        JOIN event e ON v.e_id = e.e_id 
        WHERE v.v_stu = ? 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $account, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// 確認是否有資料
$topics = [];
while ($row = $result->fetch_assoc()) {
    $topics[] = $row;
}

$stmt->close();

// 確認總共的投票數
$sql_total = "SELECT COUNT(*) FROM vote WHERE v_stu = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("s", $account);
$stmt_total->execute();
$stmt_total->bind_result($total_voted);
$stmt_total->fetch();
$stmt_total->close();

$total_pages = ceil($total_voted / $items_per_page);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>查看已投票議題</title>
    <style>
        body {
            font-family: '微軟正黑體', arial;
            background-color: #fffdf7;
            margin: 0;
            padding-top: 80px;
            display: flex;
        }

        /* 側邊欄 (sidebar) 設計 */
        .sidebar {
            width: 200px;
            background-color: var(--orange1);
            padding: 20px;
            color: white;
            height: 100vh;
            position: fixed;
            top: 64px;
            left: 0;
            z-index: 10;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #ff5700;
        }

        /* 右側內容區域 */
        .container {
            margin-left: 220px;
            max-width: 700px;
            padding: 30px;
        }

        .row {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .label {
            font-weight: bold;
            margin-right: 10px;
        }

        .value {
            display: inline-block;
            min-width: 150px;
        }

        .action-button {
            background-color: #ff8000;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

    </style>
</head>

<body>

    <!-- 側邊欄 -->
    <div class="sidebar">
        <h2>個人中心</h2>
        <a href="self.php">個人設置</a>
        <a href="view_voted_topics.php">查看已投票議題</a>
        <a href="my_topics.php">我的議題</a>
    </div>

    <!-- 右側內容區域 -->
    <div class="container">
        <h3>已投票議題</h3>
        <?php if (count($topics) > 0): ?>
            <?php foreach ($topics as $topic): ?>
                <div class="row">
                    <span class="label">議題標題：</span>
                    <span class="value"><?= htmlspecialchars($topic['e_title']) ?></span>
                    <div><strong>內容：</strong><?= htmlspecialchars($topic['e_text']) ?></div>
                    <div><strong>時間：</strong><?= htmlspecialchars($topic['e_time']) ?></div>
                </div>
            <?php endforeach; ?>

            <!-- 分頁 -->
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?= $current_page - 1 ?>">上一頁</a>
                <?php endif; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?= $current_page + 1 ?>">下一頁</a>
                <?php endif; ?>
                <span>第 <?= $current_page ?> 頁 / 共 <?= $total_pages ?> 頁</span>
            </div>

        <?php else: ?>
            <div style="color: gray;">您尚未投票過任何議題。</div>
        <?php endif; ?>
    </div>

</body>

</html>
