<?php
include('header.php');
include('db.php');

$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
}

$sort = $_GET['sort'] ?? 'time_desc';
$orderBy = "e.e_time DESC";

switch ($sort) {
    case 'time_asc':
        $orderBy = "e.e_time ASC";
        break;
    case 'vote_desc':
        $orderBy = "vote_count DESC, e.e_time DESC";
        break;
    case 'vote_asc':
        $orderBy = "vote_count ASC, e.e_time DESC";
        break;
    case 'time_desc':
    default:
        $orderBy = "e.e_time DESC";
}

$per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $per_page;

if ($_SESSION['permissions'] == 2) {
    if ($search_keyword !== '') {
        $sql_admin = "
            SELECT e.*, (SELECT COUNT(*) FROM vote v WHERE v.e_id = e.e_id) AS vote_count
            FROM event e
            WHERE e.e_title LIKE ? OR e.e_text LIKE ?
            ORDER BY $orderBy
            LIMIT ?, ?
        ";
        $stmt_admin = $conn->prepare($sql_admin);
        $like_keyword = "%$search_keyword%";
        $stmt_admin->bind_param('ssii', $like_keyword, $like_keyword, $start, $per_page);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        $count_sql = "SELECT COUNT(*) AS total FROM event WHERE e_title LIKE ? OR e_text LIKE ?";
        $stmt_count = $conn->prepare($count_sql);
        $stmt_count->bind_param('ss', $like_keyword, $like_keyword);
        $stmt_count->execute();
        $total_row = $stmt_count->get_result()->fetch_assoc();
    } else {
        $sql_admin = "
            SELECT e.*, (SELECT COUNT(*) FROM vote v WHERE v.e_id = e.e_id) AS vote_count
            FROM event e
            ORDER BY $orderBy
            LIMIT ?, ?
        ";
        $stmt_admin = $conn->prepare($sql_admin);
        $stmt_admin->bind_param('ii', $start, $per_page);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();

        $count_sql = "SELECT COUNT(*) AS total FROM event";
        $total_row = mysqli_fetch_assoc(mysqli_query($conn, $count_sql));
    }

    $total_pages = ceil($total_row['total'] / $per_page);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="setting.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        .event_main-news-mess {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f1f1;
            cursor: pointer;
        }

        .event_main-news-mess:hover {
            background-color: #f9f9f9;
        }

        .event_mess-left {
            width: 75%;
            height: 100%;
        }

        .event_mess-right {
            width: 25%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding-left: 10px;
            font-weight: 600;
            font-size: 14px;
            color: #444;
        }

        /* 新增搜尋框與排序選單的樣式 */
        .index_search-box {
            display: flex;
            width: 60%;
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            align-items: center;
        }

        .index_search-bar {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px 0 0 5px;
        }

        .index_search-but {
            width: 10%;
            border: none;
            background-color: #ff8000;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: auto;
        }

        .index_search-but:hover {
            background-color: #ea7500;
        }

        select[name="sort"] {
            margin-left: 10px;
            padding: 8px 12px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
            cursor: pointer;
            outline: none;
        }
    </style>
</head>

<body>

    <!-- 搜尋框 + 排序選單 -->
    <form id="search-form" method="GET" class="index_search-block" style="display:flex; align-items:center; gap:10px; max-width:900px; margin:20px auto 30px;">
        <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" style="flex-grow:1; padding:8px 12px; font-size:1rem; border:1px solid #ccc; border-radius:6px;" />

        <!-- 新增排序下拉，排在搜尋框與按鈕中間 -->
        <select name="sort" onchange="this.form.submit()" style="padding:8px 12px; border-radius:6px; border:1px solid #ccc; font-size:1rem; cursor:pointer; margin-left: 8px; margin-right: 8px;">
            <option value="time_desc" <?= ($sort === 'time_desc') ? 'selected' : '' ?>>時間 ↓</option>
            <option value="time_asc" <?= ($sort === 'time_asc') ? 'selected' : '' ?>>時間 ↑</option>
            <option value="vote_desc" <?= ($sort === 'vote_desc') ? 'selected' : '' ?>>投票數 ↓</option>
            <option value="vote_asc" <?= ($sort === 'vote_asc') ? 'selected' : '' ?>>投票數 ↑</option>
        </select>

        <button type="submit" class="index_search-but">搜尋</button>
    </form>
    <?php if ($_SESSION['permissions'] == 2): ?>
        <div class="index_main-news"  style="max-width:900px; margin:auto;">
            <div class="index_main-news-title">審核建言</div>
            <?php if ($result_admin->num_rows > 0): ?>
                <?php while ($row = $result_admin->fetch_assoc()): ?>
                    <div class="event_main-news-mess">
                        <div class="event_mess-left" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                            <div class="index_mess-date"><?= $row['e_time'] ?></div>
                            <div class="index_mess-title"><?= htmlspecialchars($row['e_title']) ?></div>
                        </div>
                        <div class="event_mess-right">
                            投票數：<?= $row['vote_count'] ?>
                            <?php
                            $status_text = "尚未審核";
                            $sql2 = "SELECT * FROM audit WHERE e_id = '{$row['e_id']}'";
                            $result2 = $conn->query($sql2);
                            $row2 = $result2->fetch_assoc();
                            if ($row2) {
                            switch ($row2['situation']) {
                                case 1: $status_text = "已接受"; break;
                                case 2: $status_text = "已拒絕"; break;
                                case 3: $status_text = "投票未通過"; break;
                                case 4: $status_text = "計畫制定中"; break;
                                case 5: $status_text = "正在募資中"; break;
                                case 6: $status_text = "計畫進行中"; break;
                                case 7: $status_text = "申訴期間"; break;
                                case 8: $status_text = "建言已結案"; break;
                            }
                        }
                            echo "　| 狀態：" . $status_text;
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <div class="mytopics_pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search_keyword) ?>&sort=<?= $sort ?>" class="mytopics_page_link"><i class="ri-arrow-left-line"></i></a>
                <?php endif; ?>
                <span class="mytopics_page_info">第 <?= $page ?> 頁 / 共 <?= $total_pages ?> 頁</span>
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search_keyword) ?>&sort=<?= $sort ?>" class="mytopics_page_link"><i class="ri-arrow-right-line"></i></a>
                <?php endif; ?>
            </div>
            <?php else: ?>
                <p style="text-align:center; padding:20px; color:#666;">目前沒有待審核的建言。</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</body>

</html>