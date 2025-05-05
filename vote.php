<!-- 0503 AQ 撰寫投票介面-->

<?php
include('header.php');
include('db.php');

// 處理搜尋功能
$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM event WHERE e_title LIKE '%$search_keyword%' OR e_text LIKE '%$search_keyword%' ORDER BY e_time DESC";
} else {
    $sql = "SELECT * FROM event ORDER BY e_time DESC";
}

$result = mysqli_query($conn, $sql);
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
    <form id="search-form" method="GET" class="index_search-block">
        <div class="index_search-box">
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <button type="submit" class="index_search-but">搜尋</button>
        </div>
    </form>


    <!-- 投票列表 -->
        <div class="index_main-news">
            <div class="index_main-news-title">投票專區</div>
                <?php if ($result->num_rows > 0){ ?>
                    <?php while ($row = $result->fetch_assoc()){ 
                        $sql4 = "SELECT * FROM audit WHERE e_id = '{$row['e_id']}'";
                        $result4 = mysqli_query($conn, $sql4);
                        $row4 = mysqli_fetch_assoc($result4);
                        if(isset($row4['a_acc']) == FALSE){?>
                        <div class="index_main-news-mess">
                            <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                                <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                                <div class="index_mess-title"><?php echo $row['e_title']; ?></div>
                            </div>
                            <div class="index-mess-right">
                            <?php
                            // 查投票人數
                            $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                            $result3 = mysqli_query($conn, $sql3);
                            $row3 = mysqli_fetch_assoc($result3);
                            ?>
                            <div class="index_mess-date">
                                投票數：<?php echo $row3['vote_count']; ?>
                            </div>
                            </div>
                        </div>
                    <?php }}  ?>
                <?php } ?>
            </div>       
        </div>


    <!-- 搜尋表單 (隱藏) -->
    <form id="search-form" method="GET" style="display:none;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
    </form>


    <!-- JavaScript 載入 -->
    <script src="script.js"></script>
</body>

</html>