<?php
include('header.php');
include('db.php');
include('ann_edit.php');

// 搜尋關鍵字
$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
}

// 排序方向
$sort_order = 'DESC';
if (isset($_GET['sort_order']) && ($_GET['sort_order'] === 'ASC' || $_GET['sort_order'] === 'DESC')) {
    $sort_order = $_GET['sort_order'];
}

// 分頁設定
$per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

// 主查詢
$sql = "SELECT * FROM announcements WHERE title LIKE '%$search_keyword%' OR content LIKE '%$search_keyword%' ORDER BY date $sort_order LIMIT $start, $per_page";
$result = mysqli_query($conn, $sql);

// 總筆數查詢
$sql_count = "SELECT COUNT(*) AS total FROM announcements WHERE title LIKE '%$search_keyword%' OR content LIKE '%$search_keyword%'";
$total_result = mysqli_fetch_assoc(mysqli_query($conn, $sql_count));
$total_pages = ceil($total_result['total'] / $per_page);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="setting.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
</head>
<body>
    <form id="search-form" method="GET" style="max-width:900px; margin: 20px auto 30px; display:flex; align-items:center; gap:12px;">
        <input type="text" name="search" value="<?= htmlspecialchars($search_keyword) ?>" placeholder="請輸入關鍵字"
            style="flex-grow:1; padding:8px 12px; font-size:1rem; border:1px solid #ccc; border-radius:6px;" />
        <select name="sort_order" onchange="this.form.submit()" style="padding:8px 12px; border-radius:6px;">
            <option value="DESC" <?= $sort_order === 'DESC' ? 'selected' : '' ?>>時間 ↓</option>
            <option value="ASC" <?= $sort_order === 'ASC' ? 'selected' : '' ?>>時間 ↑</option>
        </select>
        <button type="submit" style="padding:8px 18px; background:#ff7f50; color:white; border:none; border-radius:6px;">搜尋</button>
    </form>

    <div class="index_main-news" style="max-width:900px; margin:auto;">
        <div class="index_main-news-title">最新消息</div>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="index_main-news-mess">
                    <div class="index-mess-left" onclick="window.location.href='ann_post.php?id=<?= $row['id']; ?>'">
                        <div class="index_mess-date"><?= $row['date']; ?></div>
                        <div class="index_mess-title"><?= htmlspecialchars($row['title']); ?></div>
                    </div>
                    <div class="index-mess-right">
                        <?php if (isset($_SESSION['acc']) && $row['accounts'] == $_SESSION['acc']) {
                            render_edit_form($row);
                            echo "<div class='but-delete' onclick=\"window.location.href='ann_delete.php?id={$row['id']}'\">刪除</div>";
                        } ?>
                    </div>
                </div>
            <?php endwhile; ?>

            <!-- 分頁按鈕 -->
            <div class="mytopics_pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search_keyword) ?>&sort_order=<?= $sort_order ?>" class="mytopics_page_link"><i class="ri-arrow-left-line"></i></a>
                <?php endif; ?>

                <span class="mytopics_page_info">第 <?= $page ?> 頁 / 共 <?= $total_pages ?> 頁</span>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search_keyword) ?>&sort_order=<?= $sort_order ?>" class="mytopics_page_link"><i class="ri-arrow-right-line"></i></a>
                <?php endif; ?>
            </div>

            <?php else: ?>
                <div style="padding: 20px; text-align: center; color: #666;">沒有符合條件的公告。</div>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 2): ?>
            <?php include('ann_add.php'); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1): ?>
            <?php include('eve_add.php'); ?>
        <?php endif; ?>

        <script src="123.js"></script>
    </body>
</html>
