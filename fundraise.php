<?php
include('header.php');
include('db.php');
include('fun_add.php');

// 處理搜尋功能
$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM fundraising WHERE f_title LIKE '%$search_keyword%' OR e_text LIKE '%$search_keyword%' ORDER BY e_time DESC";
} else {
    $sql = "SELECT * FROM fundraising ORDER BY f_date DESC";
}

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="setting.css">
</head>

<body>

    <!-- 搜尋框 -->
    <form id="search-form" method="GET" class="index_search-block">
        <div class="index_search-box">
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <button type="submit" class="index_search-but">搜尋</button>
        </div>
    </form>
    <div class="index_main-news">
        <div class="index_main-news-title">
            <div class="index_main-news-title_func open" id="index_title_func_1" onclick="OpenFunc1()">募資中</div>
            <div class="index_main-news-title_func" id="index_title_func_2" onclick="OpenFunc2()">已達標</div>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="index_main-news-mess">
                    <div class="fun-mess-left" onclick="window.location.href='fun_post.php?id=<?php echo $row['f_id']; ?>'">
                        <div class="index_mess-date"><?php echo $row['f_date'] ?></div>
                        <div class="index_mess-title"><?php echo $row['f_title']; ?></div>
                    </div>
                    <div class="fun-mess-mid">
                        <div class="index_mess-date"><?php echo "目前金額：".$row['f_now']." / ".$row['f_goal']; ?></div>
                    </div>
                    <div class="fun-mess-right">
                        <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 2) {
                            render_add_fun($row); 
                        } ?>
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

    <!-- 只有學生身分才顯示新增建言按鈕 -->
    <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1): ?>
        <?php include('eve_add.php'); ?> 
    <?php endif; ?>
    <script>
    function openForm3(id) {
        document.getElementById("formContainer3_" + id).style.display = "block";
    }
    function closeForm3(id) {
        document.getElementById("formContainer3_" + id).style.display = "none";
    }
    </script>
</body>