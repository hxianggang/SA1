<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("header.php");

$account = $_SESSION['name'];
$acc = $_SESSION['acc'];
$items_per_page = max(1, 5);
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($current_page - 1) * $items_per_page;

// 查詢目前頁面的議題資料
$sql = "SELECT e.e_id, e.e_title, e.e_text, e.e_time 
        FROM vote v 
        JOIN event e ON v.e_id = e.e_id 
        WHERE v.v_stu = ? 
        ORDER BY e.e_time DESC 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $acc, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();
$topics = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// 查詢總投票數，計算總頁數
$sql_total = "SELECT COUNT(*) FROM vote WHERE v_stu = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("s", $acc);
$stmt_total->execute();
$stmt_total->bind_result($total_voted);
$stmt_total->fetch();
$stmt_total->close();

$total_pages = max(1, ceil($total_voted / $items_per_page));
$current_page = min($current_page, $total_pages);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>查看已投票議題</title>
    <link rel="stylesheet" href="setting.css">
</head>

<body class="mytopics_body">
    <div class="mytopics_sidebar">
        <h2 class="mytopics_sidebar_title">個人中心</h2>
        <a href="self.php" class="mytopics_sidebar_link">個人設置</a>
        <a href="my_topics.php" class="mytopics_sidebar_link">我的議題</a>
        <a href="view_voted_topics.php" class="mytopics_sidebar_link">查看已投票議題</a>
    </div>

    <div class="mytopics_container">
        <h3 class="mytopics_heading">已投票議題</h3>

        <?php if (count($topics) > 0): ?>
            <?php foreach ($topics as $row): ?>
                <?php
                $eventDate = new DateTime($row['e_time']);
                $now = new DateTime();
                $interval = $eventDate->diff($now);
                $isOverThreeMonths = ($interval->m + $interval->y * 12) >= 3;

                $e_id = $row['e_id'];
                $sqla = "SELECT * FROM audit WHERE e_id = $e_id";
                $resulta = mysqli_query($conn, $sqla);
                $rowa = mysqli_fetch_assoc($resulta);
                ?>
                <div class="index_main-news-mess">
                    <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                        <div class="index_mess-date"><?= $row['e_time'] ?></div>
                        <div class="index_mess-title"><?= htmlspecialchars($row['e_title']) ?></div>
                    </div>
                    <div class="index-mess-right">
                        <?php
                        if ($isOverThreeMonths) {
                            if (isset($rowa['a_acc'])) {
                                switch ($rowa['situation']) {
                                    case 1: echo "已審核通過"; break;
                                    case 2: echo "已否決建言"; break;
                                    case 3: echo "投票未通過"; break;
                                    case 4: echo "計畫制定中"; break;
                                    case 5: echo "正在募資中"; break;
                                    case 6: echo "計畫進行中"; break;
                                    case 7: echo "申訴期間"; break;
                                    case 8: echo "建言已結案"; break;
                                }
                            } else {
                                echo "已結束待審核";
                            }
                        } else {
                            if (isset($rowa['a_acc'])) {
                                switch ($rowa['situation']) {
                                    case 1: echo "已審核通過"; break;
                                    case 2: echo "已否決建言"; break;
                                    case 3: echo "投票未通過"; break;
                                    case 4: echo "計畫制定中"; break;
                                    case 5: echo "正在募資中"; break;
                                    case 6: echo "計畫進行中"; break;
                                    case 7: echo "申訴期間"; break;
                                    case 8: echo "建言已結案"; break;
                                }
                            } else {
                                $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                                $result3 = mysqli_query($conn, $sql3);
                                $row3 = mysqli_fetch_assoc($result3);
                                echo "總投票數：" . $row3['vote_count'];
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>您尚未投票過任何議題</p>
        <?php endif; ?>

        <!-- 分頁按鈕 -->
        <div class="mytopics_pagination">
            <?php if ($current_page > 1): ?>
                <a href="?page=<?= $current_page - 1 ?>" class="mytopics_page_link"><i class="ri-arrow-left-line"></i></a>
            <?php endif; ?>

            <span class="mytopics_page_info">第 <?= $current_page ?> 頁 / 共 <?= $total_pages ?> 頁</span>

            <?php if ($current_page < $total_pages): ?>
                <a href="?page=<?= $current_page + 1 ?>" class="mytopics_page_link"><i class="ri-arrow-right-line"></i></a>
            <?php endif; ?>
        </div>
    </div>

    <!-- 學生權限才顯示新增建言按鈕 -->
    <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1): ?>
        <?php include('eve_add.php'); ?>
    <?php endif; ?>
    <script src="123.js"></script>
</body>
</html>