<?php
if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] != 2) {
    header("Location: login.php");
    exit();
}

include('db.php');
include('header.php');

// 狀態中文對照函式
function statusToChinese($status)
{
    return match ($status) {
        'pending' => '待處理',
        'resolved' => '已處理',
        'rejected' => '駁回',
        default => htmlspecialchars($status),
    };
}

// 取得目前頁碼與排序參數
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

$allowed_sorts = ['date_asc', 'date_desc'];
$sort_order = 'date_desc';
if (isset($_GET['sort_order']) && in_array($_GET['sort_order'], $allowed_sorts)) {
    $sort_order = $_GET['sort_order'];
}

$order_by = ($sort_order === 'date_asc') ? 'a.appeal_date ASC' : 'a.appeal_date DESC';

// 取得申訴總筆數
$count_sql = "SELECT COUNT(*) AS total FROM appeals";
$count_result = $conn->query($count_sql);
$total_items = 0;
if ($count_result) {
    $total_items = $count_result->fetch_assoc()['total'];
}
$total_pages = ceil($total_items / $items_per_page);

// 取得分頁資料
$sql = "SELECT a.appeal_id, e.e_title, u.name, a.appeal_date, a.status
        FROM appeals a
        JOIN event e ON a.e_id = e.e_id
        JOIN user u ON a.accounts = u.accounts
        ORDER BY $order_by
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// 取得想查看的申訴ID (GET參數)
$view_id = isset($_GET['appeal_id']) ? intval($_GET['appeal_id']) : 0;
$detail = null;
if ($view_id > 0) {
    $stmt2 = $conn->prepare("SELECT a.*, e.e_title, u.name FROM appeals a 
                            JOIN event e ON a.e_id = e.e_id
                            JOIN user u ON a.accounts = u.accounts
                            WHERE a.appeal_id = ?");
    $stmt2->bind_param("i", $view_id);
    $stmt2->execute();
    $detail = $stmt2->get_result()->fetch_assoc();
    $stmt2->close();
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8" />
    <title>申訴管理</title>
    <link rel="stylesheet" href="setting.css" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:nth-child(odd) {
            background-color: rgb(186, 184, 183);
        }

        tr:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }

        .detail-container {
            border: 1px solid #ccc;
            padding: 15px;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a,
        .pagination span {
            display: inline-block;
            margin: 0 5px;
            padding: 6px 12px;
            border: 1px solid #ccc;
            text-decoration: none;
            color: #333;
            border-radius: 4px;
        }

        .pagination a:hover {
            background-color: #eee;
        }

        .pagination .current-page {
            background-color: #666;
            color: #fff;
            border-color: #666;
            pointer-events: none;
        }

        .p15{
            padding: 15px;
        }
    </style>
</head>

<body>
    <main>
            <div class="message-page">
                <div class="message-page-left p15">
                    <h1>申訴管理</h1>

                    <!-- 排序選單 -->
                    <div style="margin-bottom: 10px;" >
                        <label for="sort_order">申訴日期排序：</label>
                        <select id="sort_order" name="sort_order" style="margin-left: 6px;">
                            <option value="date_desc" <?= $sort_order === 'date_desc' ? 'selected' : '' ?>>最新到最舊</option>
                            <option value="date_asc" <?= $sort_order === 'date_asc' ? 'selected' : '' ?>>最舊到最新</option>
                        </select>
                    </div>
                    <!-- 申訴列表表格 -->
                    <table>
                        <thead>
                            <tr>
                                <th>建言標題</th>
                                <th>申訴者</th>
                                <th>申訴日期</th>
                                <th>申訴狀態</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr onclick="window.location='appeals_manage.php?appeal_id=<?= $row['appeal_id'] ?>&page=<?= $page ?>&sort_order=<?= $sort_order ?>'">
                                    <td><?= htmlspecialchars($row['e_title']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['appeal_date']) ?></td>
                                    <td><?= statusToChinese($row['status']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>&sort_order=<?= $sort_order ?>">&laquo; 上一頁</a>
                        <?php endif; ?>

                        <?php
                        $start_page = max(1, $page - 3);
                        $end_page = min($total_pages, $page + 3);

                        if ($start_page > 1) {
                            echo '<a href="?page=1&sort_order=' . $sort_order . '">1</a>';
                            if ($start_page > 2) {
                                echo '<span>...</span>';
                            }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $page) {
                                echo '<span class="current-page">' . $i . '</span>';
                            } else {
                                echo '<a href="?page=' . $i . '&sort_order=' . $sort_order . '">' . $i . '</a>';
                            }
                        }

                        if ($end_page < $total_pages) {
                            if ($end_page < $total_pages - 1) {
                                echo '<span>...</span>';
                            }
                            echo '<a href="?page=' . $total_pages . '&sort_order=' . $sort_order . '">' . $total_pages . '</a>';
                        }
                        ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>&sort_order=<?= $sort_order ?>">下一頁 &raquo;</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!--管理員審核-->
                <div class="message-page-right">
                    <!-- 申訴詳細資料與回覆表單 -->
                    <?php if ($detail): ?>
                        <div class="detail-container">
                            <h2>申訴詳情</h2>
                            <p><strong>建言標題：</strong><?= htmlspecialchars($detail['e_title']) ?></p>
                            <p><strong>申訴者：</strong><?= htmlspecialchars($detail['name']) ?></p>
                            <p><strong>申訴內容：</strong><br><?= nl2br(htmlspecialchars($detail['appeal_text'])) ?></p>
                            <p><strong>申訴狀態：</strong><?= statusToChinese($detail['status']) ?></p>
                            <p><strong>申訴日期：</strong><?= htmlspecialchars($detail['appeal_date']) ?></p>

                            <form method="POST" action="appeal_reply_process.php">
                                <input type="hidden" name="appeal_id" value="<?= $detail['appeal_id'] ?>" />
                                <label for="reply_text">回覆內容：</label><br>
                                <textarea name="reply_text" id="reply_text" rows="5" style="width:100%;"><?= htmlspecialchars($detail['reply_text']) ?></textarea><br>
                                <label for="status">更新狀態：</label>
                                <select name="status" id="status">
                                    <option value="pending" <?= $detail['status'] === 'pending' ? 'selected' : '' ?>>待處理</option>
                                    <option value="resolved" <?= $detail['status'] === 'resolved' ? 'selected' : '' ?>>已處理</option>
                                    <option value="rejected" <?= $detail['status'] === 'rejected' ? 'selected' : '' ?>>駁回</option>
                                </select><br><br>
                                <button type="submit">送出回覆</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <p>請點選上方申訴列表查看詳細內容。</p>
                    <?php endif; ?>
                </div>
            </div>
        </main>

    <script>
        document.getElementById('sort_order').addEventListener('change', function() {
            const selectedSort = this.value;
            const urlParams = new URLSearchParams(window.location.search);
            const currentPage = urlParams.get('page') || '1';
            urlParams.set('sort_order', selectedSort);
            urlParams.set('page', currentPage);
            window.location.search = urlParams.toString();
        });
    </script>
</body>

</html>