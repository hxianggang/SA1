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
    $sort_order = 'time_desc';
    if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], $allowed_orders)) {
        $sort_order = $_GET['sort_order'];
    }
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
        default:
            $order_by = "e_time DESC";
    }

    // 每頁顯示筆數
    $per_page = 5;

    // 投票區分頁處理
    $vote_page = isset($_GET['vote_page']) ? (int)$_GET['vote_page'] : 1;
    $vote_start = ($vote_page - 1) * $per_page;

    $sql_vote = "
    SELECT e.*,
    (SELECT COUNT(*) FROM vote v WHERE v.e_id = e.e_id) AS vote_count
    FROM event e
    WHERE e_type=0 $search_sql
    ORDER BY $order_by
    LIMIT $vote_start, $per_page";
    $result_vote = mysqli_query($conn, $sql_vote);

    $sql_vote_count = "
    SELECT COUNT(*) AS total
    FROM event e
    WHERE e_type=0 $search_sql";
    $total_vote = mysqli_fetch_assoc(mysqli_query($conn, $sql_vote_count));
    $total_pages_vote = ceil($total_vote['total'] / $per_page);

    // 已結束區分頁處理
    $end_page = isset($_GET['end_page']) ? (int)$_GET['end_page'] : 1;
    $end_start = ($end_page - 1) * $per_page;

    $sql_end = "
    SELECT e.*,
    (SELECT COUNT(*) FROM vote v WHERE v.e_id = e.e_id) AS vote_count,
    (SELECT a.situation FROM audit a WHERE a.e_id = e.e_id LIMIT 1) AS audit_situation
    FROM event e
    WHERE e_type=1 $search_sql
    ORDER BY $order_by
    LIMIT $end_start, $per_page";
    $result_end = mysqli_query($conn, $sql_end);

    $sql_end_count = "
    SELECT COUNT(*) AS total
    FROM event e
    WHERE e_type=1 $search_sql";
    $total_end = mysqli_fetch_assoc(mysqli_query($conn, $sql_end_count));
    $total_pages_end = ceil($total_end['total'] / $per_page);

    function auditSituationToChinese($situation) {
        if ($situation === null) return '尚未審核';
        return match ((int)$situation) {
            1 => '待審核',
            2 => '拒絕',
            3 => '通過',
            4 => '已制定計畫',
            5 => '正在募資中',
            6 => '計畫進行中',
            7 => '申訴期間',
            8 => '建言已結案',
            default => '未知狀態',
        };
    }
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="setting.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>
<body>

<!--搜尋與排序 -->
<form method="GET" style="display:flex; align-items:center; gap:10px; max-width:900px; margin:20px auto 30px;">
    <input type="text" name="search" value="<?= htmlspecialchars($search_keyword) ?>" placeholder="請輸入關鍵字"
           style="flex-grow:1; padding:8px 12px; font-size:1rem; border:1px solid #ccc; border-radius:6px;">
    <select name="sort_order" onchange="this.form.submit()" style="padding:8px 12px; border-radius:6px; border:1px solid #ccc;">
        <option value="time_desc" <?= $sort_order === 'time_desc' ? 'selected' : '' ?>>時間 ↓</option>
        <option value="time_asc" <?= $sort_order === 'time_asc' ? 'selected' : '' ?>>時間 ↑</option>
        <option value="vote_desc" <?= $sort_order === 'vote_desc' ? 'selected' : '' ?>>票數 ↓</option>
        <option value="vote_asc" <?= $sort_order === 'vote_asc' ? 'selected' : '' ?>>票數 ↑</option>
    </select>
    <button type="submit" style="padding:8px 18px; border-radius:6px; background:#ff7f50; color:white; border:none;">搜尋</button>
</form>

<!-- 主內容 -->
<div class="index_main-news" style="max-width:900px; margin:auto;">
    <div class="index_main-news-title">
        <div class="index_main-news-title_func open" id="index_title_func_1" onclick="OpenFunc1()" style="cursor:pointer;">投票區</div>
        <div class="index_main-news-title_func" id="index_title_func_2" onclick="OpenFunc2()" style="cursor:pointer;">已結束</div>
    </div>

    <!--投票區 -->
    <div id="vote-area">
        <?php if ($result_vote && $result_vote->num_rows > 0): ?>
            <?php while ($row = $result_vote->fetch_assoc()): ?>
                <div class="index_main-news-mess" data-etype="0">
                    <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                        <div class="index_mess-date"><?= $row['e_time'] ?></div>
                        <div class="index_mess-title"><?= htmlspecialchars($row['e_title']) ?></div>
                    </div>
                    <div class="index-mess-right">
                        <div class="index_mess-date">投票數：<?= $row['vote_count'] ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>沒有符合條件的投票區建言。</p>
        <?php endif; ?>

        <!--分頁 -->
        <div class="mytopics_pagination">
            <?php if ($vote_page > 1): ?>
                <a href="?vote_page=<?= $vote_page - 1 ?>" class="mytopics_page_link"><i class="ri-arrow-left-line"></i></a>
            <?php endif; ?>
            <span class="mytopics_page_info">第 <?= $vote_page ?> 頁 / 共 <?= $total_pages_vote ?> 頁</span>
            <?php if ($vote_page < $total_pages_vote): ?>
                <a href="?vote_page=<?= $vote_page + 1 ?>" class="mytopics_page_link"><i class="ri-arrow-right-line"></i></a>
            <?php endif; ?>
        </div>
    </div>

    <!--已結束區 -->
    <div id="end-area" style="display:none;">
        <?php if ($result_end && $result_end->num_rows > 0): ?>
            <?php while ($row = $result_end->fetch_assoc()): ?>
                <div class="index_main-news-mess" data-etype="1">
                    <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                        <div class="index_mess-date"><?= $row['e_time'] ?></div>
                        <div class="index_mess-title"><?= htmlspecialchars($row['e_title']) ?></div>
                    </div>
                    <div class="index-mess-right">
                        <div class="index_mess-date">案件狀態：<?= auditSituationToChinese($row['audit_situation']) ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>沒有符合條件的已結束建言。</p>
        <?php endif; ?>

        <!--分頁 -->
        <div class="mytopics_pagination">
            <?php if ($end_page > 1): ?>
                <a href="?end_page=<?= $end_page - 1 ?>" class="mytopics_page_link"><i class="ri-arrow-left-line"></i></a>
            <?php endif; ?>
            <span class="mytopics_page_info">第 <?= $end_page ?> 頁 / 共 <?= $total_pages_end ?> 頁</span>
            <?php if ($end_page < $total_pages_end): ?>
                <a href="?end_page=<?= $end_page + 1 ?>" class="mytopics_page_link"><i class="ri-arrow-right-line"></i></a>
            <?php endif; ?>
        </div>
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

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('end_page')) {
        OpenFunc2();
    } else {
        OpenFunc1();
    }
</script>
</body>
</html>
