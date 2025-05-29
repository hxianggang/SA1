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

// SQL 查詢募資公告並排序
$sql = "SELECT * FROM fundraising WHERE 1=1 $search_sql ORDER BY f_date $sort_order";
$result = mysqli_query($conn, $sql);

$fundraising_in_progress = [];
$fundraising_reached_goal = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ($row['f_type'] === '1') {
            $fundraising_in_progress[] = $row;
        } elseif ($row['f_type'] === '2') {
            $fundraising_reached_goal[] = $row;
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

    <!-- 搜尋框 + 排序選單 -->
    <form id="search-form" method="GET" class="index_search-block" style="display:flex; align-items:center; gap:12px; max-width:900px; margin:20px auto 30px;">
        <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?= htmlspecialchars($search_keyword) ?>" style="flex-grow:1; padding:8px 12px; font-size:1rem; border:1px solid #ccc; border-radius:6px;" />

        <select name="sort_order" onchange="this.form.submit()" style="padding:8px 12px; border-radius:6px; border:1px solid #ccc; font-size:1rem; cursor:pointer;">
            <option value="DESC" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>時間 ↓</option>
            <option value="ASC" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>時間 ↑</option>
        </select>

        <button type="submit" class="index_search-but" style="padding:8px 18px; border-radius:6px; background:#ff7f50; color:#fff; border:none; cursor:pointer; font-size:1rem;">搜尋</button>
    </form>

    <!-- 以下維持你原本的募資公告區塊 -->
    <div class="index_main-news">
        <div class="index_main-news-title">
            <div class="index_main-news-title_func open" id="index_title_func_1" onclick="OpenFunc1()">募資中</div>
            <div class="index_main-news-title_func" id="index_title_func_2" onclick="OpenFunc2()">已達標</div>
        </div>

        <div id="in_progress_area" style="display:flex; flex-direction: column;">
            <?php if (!empty($fundraising_in_progress)): ?>
                <?php foreach ($fundraising_in_progress as $row): ?>
                    <div class="index_main-news-mess ftype-1" data-ftype="1">
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
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding:20px; text-align:center; color:#666;">沒有符合條件的募資公告</p>
            <?php endif; ?>
        </div>

        <div id="reached_goal_area" style="display:none; flex-direction: column;">
            <?php if (!empty($fundraising_reached_goal)): ?>
                <?php foreach ($fundraising_reached_goal as $row): ?>
                    <div class="index_main-news-mess ftype-2" data-ftype="2">
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
                <?php endforeach; ?>
            <?php else: ?>
                <p style="padding:20px; text-align:center; color:#666;">沒有符合條件的募資公告</p>
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