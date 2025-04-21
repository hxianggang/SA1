<?php
session_start();
include('header.php');
include('db.php');

// 處理搜尋功能
$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = $_GET['search'];
}

// 查詢公告
$sql = "SELECT * FROM announcements WHERE title LIKE ? OR content LIKE ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search_keyword . "%";
$stmt->bind_param('ss', $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- 搜尋框 -->
    <div class="index_search-block">
        <div class="index_search-box">
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" form="search-form">
            <div class="index_search-but" onclick="document.getElementById('search-form').submit();">搜尋</div>
        </div>
    </div>

    <!-- 公告列表 -->
    <div class="index_main-news">
        <div class="index_main-news-title">最新消息</div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="index_main-news-mess" onclick="window.location.href='post.php?id=<?php echo $row['id']; ?>'">
                    <div class="index_mess-date"><?php echo $row['date']; ?></div>
                    <div class="index_mess-title"><?php echo $row['title']; ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div>沒有符合條件的公告。</div>
        <?php endif; ?>
    </div>

    <!-- 搜尋表單 (隱藏) -->
    <form id="search-form" method="GET" style="display:none;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
    </form>

    <!-- 檢查是否登入，若已登入則顯示 + 按鈕 -->
    <?php if (isset($_SESSION['username'])): ?>
        <?php include('addmessage.php'); ?> <!-- 顯示發佈建言按鈕 -->
    <?php endif; ?>

    <!-- JavaScript 載入 -->
    <script src="script.js"></script>
</body>

</html>