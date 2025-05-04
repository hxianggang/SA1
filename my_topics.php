<?php
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("header.php");

$account = $_SESSION['name'];
$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// 查詢發布的議題
$sql = "SELECT e.e_id, e.e_title, e.e_text, e.e_time FROM event e
        WHERE e.accounts = ? 
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

// 確認總共的議題數
$sql_total = "SELECT COUNT(*) FROM event WHERE accounts = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("s", $account);
$stmt_total->execute();
$stmt_total->bind_result($total_topics);
$stmt_total->fetch();
$stmt_total->close();

$total_pages = ceil($total_topics / $items_per_page);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>我的議題</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="mytopics_body">

    <!-- 側邊欄 -->
    <div class="mytopics_sidebar">
        <h2 class="mytopics_sidebar_title">個人中心</h2>
        <a href="self.php" class="mytopics_sidebar_link">個人設置</a>
        <a href="view_voted_topics.php" class="mytopics_sidebar_link">查看已投票議題</a>
        <a href="my_topics.php" class="mytopics_sidebar_link">我的議題</a>
    </div>

    <!-- 右側內容區域 -->
    <div class="mytopics_container">
        <h3 class="mytopics_heading">我的議題</h3>
        <?php if (count($topics) > 0): ?>
            <?php foreach ($topics as $topic): ?>
                <div class="mytopics_row">
                    <span class="mytopics_label">議題標題：</span>
                    <span class="mytopics_value"><?= htmlspecialchars($topic['e_title']) ?></span>
                    <div><strong>內容：</strong><?= htmlspecialchars($topic['e_text']) ?></div>
                    <div><strong>時間：</strong><?= htmlspecialchars($topic['e_time']) ?></div>
                </div>
            <?php endforeach; ?>

            <!-- 分頁 -->
            <div class="mytopics_pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?= $current_page - 1 ?>" class="mytopics_page_link">上一頁</a>
                <?php endif; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?= $current_page + 1 ?>" class="mytopics_page_link">下一頁</a>
                <?php endif; ?>
                <span class="mytopics_page_info">第 <?= $current_page ?> 頁 / 共 <?= $total_pages ?> 頁</span>
            </div>

        <?php else: ?>
            <div style="color: gray;">您尚未發布過任何建言。</div>
        <?php endif; ?>
    </div>

</body>

</html>