<?php
include('header.php');
include('db.php');

// 取得搜尋關鍵字
$search_keyword = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
}
$search_sql = '';
if ($search_keyword !== '') {
    $search_sql = " AND (e_title LIKE '%$search_keyword%' OR e_text LIKE '%$search_keyword%')";
}

// 取得排序選項
$allowed_orders = ['time_desc', 'time_asc', 'vote_desc', 'vote_asc'];
$sort_order = 'time_desc'; // 預設

if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], $allowed_orders)) {
    $sort_order = $_GET['sort_order'];
}

// 根據排序選項設定 SQL ORDER BY
switch ($sort_order) {
    case 'time_asc':
        $order_by = "e_time ASC";
        break;
    case 'vote_desc':
        $order_by = "vote_count DESC";
        break;
    case 'vote_asc':
        $order_by = "vote_count ASC";
        break;
    case 'time_desc':
    default:
        $order_by = "e_time DESC";
}

// SQL 查詢語句：投票區 e_type=0
$sql_vote = "
SELECT e.*,
  (SELECT COUNT(*) FROM vote v WHERE v.e_id = e.e_id) AS vote_count
FROM event e
WHERE e_type=0 $search_sql
ORDER BY $order_by
";
$result_vote = mysqli_query($conn, $sql_vote);

// SQL 查詢語句：已結束 e_type=1
$sql_end = "
SELECT e.*,
  (SELECT COUNT(*) FROM vote v WHERE v.e_id = e.e_id) AS vote_count
FROM event e
WHERE e_type=1 $search_sql
ORDER BY $order_by
";
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
    <form id="search-form" method="GET" class="index_search-block" style="display:flex; align-items:center; gap:10px; max-width:900px; margin:20px auto 30px;">
        <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" style="flex-grow:1; padding:8px 12px; font-size:1rem; border:1px solid #ccc; border-radius:6px;" />

        <select name="sort_order" onchange="this.form.submit()" style="padding:8px 12px; border-radius:6px; border:1px solid #ccc; font-size:1rem; cursor:pointer;">
            <option value="time_desc" <?php if ($sort_order === 'time_desc') echo 'selected'; ?>>時間 ↓</option>
            <option value="time_asc" <?php if ($sort_order === 'time_asc') echo 'selected'; ?>>時間 ↑</option>
            <option value="vote_desc" <?php if ($sort_order === 'vote_desc') echo 'selected'; ?>>票數 ↓</option>
            <option value="vote_asc" <?php if ($sort_order === 'vote_asc') echo 'selected'; ?>>票數 ↑</option>
        </select>

        <button type="submit" class="index_search-but" style="padding:8px 18px; border-radius:6px; background:#ff7f50; color:#fff; border:none; cursor:pointer; font-size:1rem;">搜尋</button>
    </form>

    <!-- 投票列表 -->
    <div class="index_main-news" style="max-width:900px; margin:auto;">
        <div class="index_main-news-title">
            <div class="index_main-news-title_func open" id="index_title_func_1" onclick="OpenFunc1()" style="cursor:pointer;">投票區</div>
            <div class="index_main-news-title_func" id="index_title_func_2" onclick="OpenFunc2()" style="cursor:pointer;">已結束</div>
        </div>

        <!-- 投票區 -->
        <div id="vote-area">
            <?php if ($result_vote && $result_vote->num_rows > 0) {
                while ($row = $result_vote->fetch_assoc()) {
            ?>
                    <div class="index_main-news-mess" data-etype="0">
                        <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                            <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                            <div class="index_mess-title"><?php echo htmlspecialchars($row['e_title']); ?></div>
                        </div>
                        <div class="index-mess-right">
                            <div class="index_mess-date">投票數：<?php echo $row['vote_count']; ?></div>
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
            ?>
                    <div class="index_main-news-mess" data-etype="1">
                        <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                            <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                            <div class="index_mess-title"><?php echo htmlspecialchars($row['e_title']); ?></div>
                        </div>
                        <div class="index-mess-right">
                            <div class="index_mess-date">投票數：<?php echo $row['vote_count']; ?></div>
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
            document.getElementById('vote-area').style.display = 'block';
            document.getElementById('end-area').style.display = 'none';

            document.getElementById('index_title_func_1').classList.add('open');
            document.getElementById('index_title_func_2').classList.remove('open');
        }

        function OpenFunc2() {
            document.getElementById('vote-area').style.display = 'none';
            document.getElementById('end-area').style.display = 'block';

            document.getElementById('index_title_func_2').classList.add('open');
            document.getElementById('index_title_func_1').classList.remove('open');
        }

        OpenFunc1();
    </script>

</body>

</html>