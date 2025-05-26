<!-- 0503 AQ 把公告的CRUD接完、修搜尋框-->

<?php
include('header.php');
include('db.php');
include('ann_edit.php');

// 處理搜尋功能
$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM announcements WHERE title LIKE '%$search_keyword%' OR content LIKE '%$search_keyword%' ORDER BY date DESC";
} else {
    $sql = "SELECT * FROM announcements ORDER BY date DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="new.css">
</head>

<body>

    <!-- 搜尋框 -->
    <form id="search-form" method="GET" class="index_search-block">
        <div class="index_search-box">
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <button type="submit" class="index_search-but">搜尋</button>
        </div>
    </form>


    <!-- 公告列表 -->
    <div class="index_main-news">
        <div class="index_main-news-title">最新消息</div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="index_main-news-mess">
                    <div class="index-mess-left" onclick="window.location.href='ann_post.php?id=<?php echo $row['id']; ?>'">
                        <div class="index_mess-date"><?php echo $row['date']; ?></div>
                        <div class="index_mess-title"><?php echo $row['title']; ?></div>
                    </div>
                    <div class="index-mess-right">
                    <?php
                        if (isset($_SESSION['acc']) && $row['accounts'] == $_SESSION['acc']) {
                            ?>
                            <?php render_edit_form($row); ?>
                            <div class='but-delete' onclick="window.location.href='ann_delete.php?id=<?php echo $row['id']; ?>'">刪除</div>
                        <?php } ?>
                    </div>
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

    <!-- 只有管理員身分才顯示新增公告按鈕 -->
   <?php
    if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 2) {
        include('ann_add.php');
    } ?>
    <!-- 只有學生身分才顯示新增建言按鈕 -->
    <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1): ?>
        <?php include('eve_add.php'); ?> 
    <?php endif; ?>

    <!-- JavaScript 載入 -->
    <script>
    function openForm() {
        document.getElementById("formContainer").style.display = "block";
    }
    function closeForm() {
        document.getElementById("formContainer").style.display = "none";
    }
    function openForm2(id) {
        document.getElementById("formContainer2_" + id).style.display = "block";
    }
    function closeForm2(id) {
        document.getElementById("formContainer2_" + id).style.display = "none";
    }
    </script>
</body>

</html>