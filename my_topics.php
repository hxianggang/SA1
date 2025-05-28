<?php
session_start();
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

// 查詢該使用者的議題
$sql = "SELECT e.e_id, e.e_title, e.e_text, e.e_time FROM event e
        WHERE e.accounts = ? 
        ORDER BY e.e_time DESC
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $account, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

$topics = [];
while ($row = $result->fetch_assoc()) {
    $topics[] = $row;
}
$stmt->close();

// 取得總數計算分頁
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
    <meta charset="UTF-8" />
    <title>我的議題</title>
    <link rel="stylesheet" href="style.css" />
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
            <table class="index_main-news-table mytopics_table">

                <thead style="background-color: var(--orange1); color: white;">
                    <tr>
                        <th style="padding: 12px;">議題標題</th>
                        <th style="padding: 12px;">內容</th>
                        <th style="padding: 12px;">時間</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topics as $topic): ?>
                        <tr style="border-bottom:1px solid #ddd; cursor:pointer;" onclick="window.location.href='eve_post.php?e_id=<?= $topic['e_id'] ?>'">
                            <td style="padding: 12px; font-weight: bold; color:#333;"><?= htmlspecialchars($topic['e_title']) ?></td>
                            <td style="padding: 12px;"><?= nl2br(htmlspecialchars($topic['e_text'])) ?></td>
                            <td style="padding: 12px; color: #666;"><?= htmlspecialchars($topic['e_time']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- 分頁 -->
            <div class="mytopics_pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?= $current_page - 1 ?>" class="mytopics_page_link">上一頁</a>
                <?php endif; ?>

                <span class="mytopics_page_info">第 <?= $current_page ?> 頁 / 共 <?= $total_pages ?> 頁</span>

                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?= $current_page + 1 ?>" class="mytopics_page_link">下一頁</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="color: gray; text-align:center; margin-top:40px;">
                您尚未發布過任何建言。
            </div>
        <?php endif; ?>
    </div>

</body>

</html>