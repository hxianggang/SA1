<!--有BUG-->

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
    <link rel="stylesheet" href="setting.css">
</head>

<body class="viewvoted_body">

    <!-- 側邊欄 -->
    <div class="viewvoted_sidebar">
        <h2>個人中心</h2>
        <a href="self.php">個人設置</a>
        <a href="view_voted_topics.php">查看已投票議題</a>
        <a href="my_topics.php">我的議題</a>
    </div>

    <!-- 右側內容區域 -->
    <div class="viewvoted_container">
        <h3>已投票議題</h3>
        <?php if (count($topics) > 0): ?>
            <?php foreach ($topics as $topic): ?>
                <div class="viewvoted_row">
                    <span class="viewvoted_label">議題標題：</span>
                    <span class="viewvoted_value"><?= htmlspecialchars($topic['e_title']) ?></span>
                    <div><strong>內容：</strong><?= htmlspecialchars($topic['e_text']) ?></div>
                    <div><strong>時間：</strong><?= htmlspecialchars($topic['e_time']) ?></div>
                </div>
            <?php endforeach; ?>

            <!-- 分頁 -->
            <div class="viewvoted_pagination">
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