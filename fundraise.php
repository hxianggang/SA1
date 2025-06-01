<?php
include('header.php');
include('db.php');
include('fun_add.php');
include('fun_edit.php');

// 搜尋關鍵字
$search_keyword = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
}
$search_sql = '';
if ($search_keyword !== '') {
    $search_sql = " AND f_title LIKE '%$search_keyword%'";
}

// 排序方向
$allowed_orders = ['ASC', 'DESC'];
$sort_order = 'DESC'; // 預設最新→最舊
if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], $allowed_orders)) {
    $sort_order = $_GET['sort_order'];
}

// 分頁設定
$per_page = 5;
$in_page = isset($_GET['in_page']) ? (int)$_GET['in_page'] : 1;
$goal_page = isset($_GET['goal_page']) ? (int)$_GET['goal_page'] : 1;
$in_start = ($in_page - 1) * $per_page;
$goal_start = ($goal_page - 1) * $per_page;

// 查詢募資中
$sql_in = "SELECT * FROM fundraising WHERE f_type = '1' $search_sql ORDER BY f_date $sort_order LIMIT $in_start, $per_page";
$result_in = mysqli_query($conn, $sql_in);
$sql_in_count = "SELECT COUNT(*) AS total FROM fundraising WHERE f_type = '1' $search_sql";
$total_in = mysqli_fetch_assoc(mysqli_query($conn, $sql_in_count));
$total_pages_in = ceil($total_in['total'] / $per_page);

// 查詢已達標
$sql_goal = "SELECT * FROM fundraising WHERE f_type = '2' $search_sql ORDER BY f_date $sort_order LIMIT $goal_start, $per_page";
$result_goal = mysqli_query($conn, $sql_goal);
$sql_goal_count = "SELECT COUNT(*) AS total FROM fundraising WHERE f_type = '2' $search_sql";
$total_goal = mysqli_fetch_assoc(mysqli_query($conn, $sql_goal_count));
$total_pages_goal = ceil($total_goal['total'] / $per_page);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="setting.css" />
</head>

<body>
    <form method="GET" style="display:flex; align-items:center; gap:12px; max-width:900px; margin:20px auto 30px;">
        <input type="text" name="search" placeholder="請輸入關鍵字" value="<?= htmlspecialchars($search_keyword) ?>" style="flex-grow:1; padding:8px 12px; font-size:1rem; border:1px solid #ccc; border-radius:6px;" />
        <select name="sort_order" onchange="this.form.submit()" style="padding:8px 12px; border-radius:6px;">
            <option value="DESC" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>時間 ↓</option>
            <option value="ASC" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>時間 ↑</option>
        </select>
        <button type="submit" style="padding:8px 18px; background:#ff7f50; color:white; border:none; border-radius:6px;">搜尋</button>
    </form>

    <div class="index_main-news" style="max-width:900px; margin:auto;">
        <div class="index_main-news-title">
            <div class="index_main-news-title_func open" id="index_title_func_1" onclick="OpenFunc1()">募資中</div>
            <div class="index_main-news-title_func" id="index_title_func_2" onclick="OpenFunc2()">已達標</div>
        </div>

        <!-- 募資中 -->
        <div id="in_progress_area" style="display:flex; flex-direction: column;">
            <?php if ($result_in && mysqli_num_rows($result_in) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_in)): ?>
                    <div class="index_main-news-mess ftype-1">
                        <div class="fun-mess-left" onclick="window.location.href='fun_post.php?id=<?= $row['f_id'] ?>'">
                            <div class="index_mess-date"><?= $row['f_date'] ?></div>
                            <div class="index_mess-title"><?= htmlspecialchars($row['f_title']) ?></div>
                        </div>
                        <div class="fun-mess-mid">
                            <div class="index_mess-date"><?= "目前金額：" . $row['f_now'] . " / " . $row['f_goal'] ?></div>
                        </div>
                        <div class="fun-mess-right">
                            <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 2 && $row['f_now'] < $row['f_goal']) {
                                render_add_fun($row);
                                render_edit_fun($row);
                            } ?>
                        </div>
                    </div>
                <?php endwhile; ?>
                <!-- 分頁 -->
                <div class="mytopics_pagination">
                    <?php if ($in_page > 1): ?>
                        <a href="?in_page=<?= $in_page - 1 ?>" class="mytopics_page_link">上一頁</a>
                    <?php endif; ?>
                    <span class="mytopics_page_info">第 <?= $in_page ?> 頁 / 共 <?= $total_pages_in ?> 頁</span>
                    <?php if ($in_page < $total_pages_in): ?>
                        <a href="?in_page=<?= $in_page + 1 ?>" class="mytopics_page_link">下一頁</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p style="padding:20px; text-align:center; color:#666;">沒有符合條件的募資內容</p>
            <?php endif; ?>
        </div>

        <!-- 已達標 -->
        <div id="reached_goal_area" style="display:none; flex-direction: column;">
            <?php if ($result_goal && mysqli_num_rows($result_goal) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_goal)): ?>
                    <div class="index_main-news-mess ftype-2">
                        <div class="fun-mess-left" onclick="window.location.href='fun_post.php?id=<?= $row['f_id'] ?>'">
                            <div class="index_mess-date"><?= $row['f_date'] ?></div>
                            <div class="index_mess-title"><?= htmlspecialchars($row['f_title']) ?></div>
                        </div>
                        <div class="fun-mess-mid">
                            <div class="index_mess-date"><?= "目前金額：" . $row['f_now'] . " / " . $row['f_goal'] ?></div>
                        </div>
                        <div class="fun-mess-right">
                            <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 2 && $row['f_now'] < $row['f_goal']) {
                                render_add_fun($row);
                                render_edit_fun($row);
                            } ?>
                        </div>
                    </div>
                <?php endwhile; ?>
                <!-- 分頁 -->
                <div class="mytopics_pagination">
                    <?php if ($goal_page > 1): ?>
                        <a href="?goal_page=<?= $goal_page - 1 ?>" class="mytopics_page_link">上一頁</a>
                    <?php endif; ?>
                    <span class="mytopics_page_info">第 <?= $goal_page ?> 頁 / 共 <?= $total_pages_goal ?> 頁</span>
                    <?php if ($goal_page < $total_pages_goal): ?>
                        <a href="?goal_page=<?= $goal_page + 1 ?>" class="mytopics_page_link">下一頁</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <p style="padding:20px; text-align:center; color:#666;">沒有符合條件的募資內容</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1): ?>
        <?php include('eve_add.php'); ?>
    <?php endif; ?> 

    <script>
        function OpenFunc1() {
            document.getElementById('in_progress_area').style.display = 'flex';
            document.getElementById('reached_goal_area').style.display = 'none';
            document.getElementById('index_title_func_1').classList.add('open');
            document.getElementById('index_title_func_2').classList.remove('open');
        }

        function OpenFunc2() {
            document.getElementById('in_progress_area').style.display = 'none';
            document.getElementById('reached_goal_area').style.display = 'flex';
            document.getElementById('index_title_func_2').classList.add('open');
            document.getElementById('index_title_func_1').classList.remove('open');
        }

        const params = new URLSearchParams(window.location.search);
        if (params.has('goal_page')) {
            OpenFunc2();
        } else {
            OpenFunc1();
        }
    </script>
    <script src="123.js"></script>
</body>
</html>