<?php
session_start();
include('header.php');
include('db.php');

$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM event WHERE e_title LIKE '%$search_keyword%' OR e_text LIKE '%$search_keyword%' ORDER BY e_time DESC";
} else {
    $sql = "SELECT * FROM event ORDER BY e_time DESC";
}

$result = mysqli_query($conn, $sql);

$vote_area = [];
$ended_area = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['e_type'] === '0') {
            $vote_area[] = $row;
        } elseif ($row['e_type'] === '1') {
            $ended_area[] = $row;
        }
    }
}
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
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?= htmlspecialchars($search_keyword) ?>" />
            <button type="submit" class="index_search-but">搜尋</button>
        </div>
    </form>

    <!-- 投票列表 -->
    <div class="index_main-news">
        <div class="index_main-news-title">
            <div class="index_main-news-title_func open" id="index_title_func_1" onclick="OpenFunc1()">投票區</div>
            <div class="index_main-news-title_func" id="index_title_func_2" onclick="OpenFunc2()">已結束</div>
        </div>

        <div id="vote_area" style="display:flex; flex-direction: column;">
            <?php if (!empty($vote_area)) : ?>
                <?php foreach ($vote_area as $row): ?>
                    <div class="index_main-news-mess" data-etype="0" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                        <div class="index-mess-left">
                            <div class="index_mess-date"><?= $row['e_time'] ?></div>
                            <div class="index_mess-title"><?= htmlspecialchars($row['e_title']) ?></div>
                        </div>
                        <div class="index-mess-right">
                            <?php
                            $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                            $result3 = mysqli_query($conn, $sql3);
                            $row3 = mysqli_fetch_assoc($result3);
                            ?>
                            <div class="index_mess-date">投票數：<?= $row3['vote_count'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding:20px; text-align:center; color:#666;">沒有符合條件的議題</p>
            <?php endif; ?>
        </div>

        <div id="ended_area" style="display:none; flex-direction: column;">
            <?php if (!empty($ended_area)) : ?>
                <?php foreach ($ended_area as $row): ?>
                    <div class="index_main-news-mess" data-etype="1" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                        <div class="index-mess-left">
                            <div class="index_mess-date"><?= $row['e_time'] ?></div>
                            <div class="index_mess-title"><?= htmlspecialchars($row['e_title']) ?></div>
                        </div>
                        <div class="index-mess-right">
                            <?php
                            $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                            $result3 = mysqli_query($conn, $sql3);
                            $row3 = mysqli_fetch_assoc($result3);
                            ?>
                            <div class="index_mess-date">投票數：<?= $row3['vote_count'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding:20px; text-align:center; color:#666;">沒有符合條件的議題</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 只有學生身分才顯示新增建言按鈕 -->
    <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1): ?>
        <?php include('eve_add.php'); ?>
    <?php endif; ?>

    <script src="123.js"></script>


</body>

</html>