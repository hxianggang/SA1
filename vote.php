<?php
include('header.php');
include('db.php');

// 處理搜尋關鍵字
$search_keyword = '';
$search_sql = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
    $search_sql = " AND (e_title LIKE '%$search_keyword%' OR e_text LIKE '%$search_keyword%')";
}

// 投票區 (e_type=0)
$sql_vote = "SELECT * FROM event WHERE e_type=0 $search_sql ORDER BY e_time DESC";
$result_vote = mysqli_query($conn, $sql_vote);

// 已結束 (e_type=1)
$sql_end = "SELECT * FROM event WHERE e_type=1 $search_sql ORDER BY e_time DESC";
$result_end = mysqli_query($conn, $sql_end);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="new.css" />
</head>

<body>

    <!-- 搜尋框 -->
    <form id="search-form" method="GET" class="index_search-block">
        <div class="index_search-box">
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" />
            <button type="submit" class="index_search-but">搜尋</button>
        </div>
    </form>

    <!-- 投票列表 -->
    <div class="index_main-news">
        <div class="index_main-news-title">
            <div class="index_main-news-title_func open" id="index_title_func_1" onclick="OpenFunc1()">投票區</div>
            <div class="index_main-news-title_func" id="index_title_func_2" onclick="OpenFunc2()">已結束</div>
        </div>

        <!-- 投票區 -->
        <div id="vote-area">
            <?php if ($result_vote && $result_vote->num_rows > 0) {
                while ($row = $result_vote->fetch_assoc()) {
                    // 計算投票數
                    $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                    $result3 = mysqli_query($conn, $sql3);
                    $row3 = mysqli_fetch_assoc($result3);
            ?>
                    <div class="index_main-news-mess" data-etype="0">
                        <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                            <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                            <div class="index_mess-title"><?php echo htmlspecialchars($row['e_title']); ?></div>
                        </div>
                        <div class="index-mess-right">
                            <div class="index_mess-date">投票數：<?php echo $row3['vote_count']; ?></div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p>沒有符合條件的投票區建言。</p>';
            } ?>
        </div>

        <!-- 已結束 -->
        <div id="end-area" style="display:none;">
            <?php if ($result_end && $result_end->num_rows > 0) {
                while ($row = $result_end->fetch_assoc()) {
                    // 計算投票數
                    $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                    $result3 = mysqli_query($conn, $sql3);
                    $row3 = mysqli_fetch_assoc($result3);
            ?>
                    <div class="index_main-news-mess" data-etype="1">
                        <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                            <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                            <div class="index_mess-title"><?php echo htmlspecialchars($row['e_title']); ?></div>
                        </div>
                        <div class="index-mess-right">
                            <div class="index_mess-date">投票數：<?php echo $row3['vote_count']; ?></div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p>沒有符合條件的已結束建言。</p>';
            } ?>
        </div>
    </div>

    <script>
        function OpenFunc1() {
            // 顯示投票區，隱藏已結束
            document.getElementById('vote-area').style.display = 'block';
            document.getElementById('end-area').style.display = 'none';

            document.getElementById('index_title_func_1').classList.add('open');
            document.getElementById('index_title_func_2').classList.remove('open');
        }

        function OpenFunc2() {
            // 顯示已結束，隱藏投票區
            document.getElementById('vote-area').style.display = 'none';
            document.getElementById('end-area').style.display = 'block';

            document.getElementById('index_title_func_2').classList.add('open');
            document.getElementById('index_title_func_1').classList.remove('open');
        }

        // 預設打開投票區
        OpenFunc1();
    </script>

</body>

</html>