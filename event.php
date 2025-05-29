<?php
include('header.php');
include('db.php');

$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
}

// 學生建言查詢
if ($_SESSION['permissions'] == 1) {
    $acc = $_SESSION['acc'];
    if ($search_keyword !== '') {
        $sql_student = "SELECT * FROM event WHERE accounts = ? AND (e_title LIKE ? OR e_text LIKE ?) ORDER BY e_time DESC";
        $stmt_student = $conn->prepare($sql_student);
        $like_keyword = "%$search_keyword%";
        $stmt_student->bind_param('sss', $acc, $like_keyword, $like_keyword);
        $stmt_student->execute();
        $result_student = $stmt_student->get_result();
    } else {
        $sql_student = "SELECT * FROM event WHERE accounts = ? ORDER BY e_time DESC";
        $stmt_student = $conn->prepare($sql_student);
        $stmt_student->bind_param('s', $acc);
        $stmt_student->execute();
        $result_student = $stmt_student->get_result();
    }
}

// 管理建言查詢
if ($_SESSION['permissions'] == 2) {
    if ($search_keyword !== '') {
        $sql_admin = "SELECT * FROM event WHERE e_title LIKE ? OR e_text LIKE ? ORDER BY e_time DESC";
        $stmt_admin = $conn->prepare($sql_admin);
        $like_keyword = "%$search_keyword%";
        $stmt_admin->bind_param('ss', $like_keyword, $like_keyword);
        $stmt_admin->execute();
        $result_admin = $stmt_admin->get_result();
    } else {
        $sql_admin = "SELECT * FROM event ORDER BY e_time DESC";
        $result_admin = $conn->query($sql_admin);
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="new.css">
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
    </style>
</head>

<body>

    <!-- 搜尋框 -->
    <form id="search-form" method="GET" class="index_search-block">
        <div class="index_search-box">
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?= htmlspecialchars($search_keyword) ?>">
            <button type="submit" class="index_search-but">搜尋</button>
        </div>
    </form>

    <?php if ($_SESSION['permissions'] == 1): ?>
        <div class="index_main-news">
            <div class="index_main-news-title">歷史提出建言</div>
            <?php if ($result_student->num_rows > 0): ?>
                <?php while ($row = $result_student->fetch_assoc()):
                    $eventDate = new DateTime($row['e_time']);
                    $now = new DateTime();
                    $interval = $eventDate->diff($now);
                    $isOverThreeMonths = ($interval->m + $interval->y * 12) >= 3;
                ?>
                    <div class="index_main-news-mess">
                        <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                            <div class="index_mess-date"><?= $row['e_time'] ?></div>
                            <div class="index_mess-title"><?= $row['e_title'] ?></div>
                        </div>
                        <div class="index-mess-right">
                            <?php
                            $e_id = $row['e_id'];
                            $sqla = "SELECT * FROM audit WHERE e_id = $e_id";
                            $resulta = $conn->query($sqla);
                            $rowa = $resulta->fetch_assoc();

                            if ($isOverThreeMonths) {
                                if (isset($rowa['a_acc'])) {
                                    // 你的狀態判斷邏輯
                                } else {
                                    echo "已結束待審核";
                                }
                            } else {
                                if (isset($rowa['a_acc'])) {
                                    // 你的狀態判斷邏輯
                                } else {
                                    $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                                    $result3 = $conn->query($sql3);
                                    $row3 = $result3->fetch_assoc();
                                    echo "總投票數：" . $row3['vote_count'];
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; padding:20px; color:#666;">你尚未提出任何符合條件的建言。</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if ($_SESSION['permissions'] == 2): ?>
        <div class="index_main-news">
            <div class="index_main-news-title">審核建言</div>
            <?php if ($result_admin->num_rows > 0): ?>
                <?php while ($row = $result_admin->fetch_assoc()):
                    $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                    $result3 = $conn->query($sql3);
                    $row3 = $result3->fetch_assoc();

                    $sql2 = "SELECT * FROM audit WHERE e_id = '{$row['e_id']}'";
                    $result2 = $conn->query($sql2);
                    $row2 = $result2->fetch_assoc();

                    $status_text = "尚未審核";
                    if ($row2) {
                        switch ($row2['situation']) {
                            case 1:
                                $status_text = "已接受";
                                break;
                            case 2:
                                $status_text = "已拒絕";
                                break;
                            case 3:
                                $status_text = "投票未通過";
                                break;
                            case 4:
                                $status_text = "計畫制定中";
                                break;
                            case 5:
                                $status_text = "正在募資中";
                                break;
                            case 6:
                                $status_text = "計畫進行中";
                                break;
                            case 7:
                                $status_text = "申訴期間";
                                break;
                            case 8:
                                $status_text = "建言已結案";
                                break;
                        }
                    }
                ?>
                    <div class="event_main-news-mess">
                        <div class="event_mess-left" onclick="window.location.href='eve_post.php?e_id=<?= $row['e_id'] ?>'">
                            <div class="index_mess-date"><?= $row['e_time'] ?></div>
                            <div class="index_mess-title"><?= $row['e_title'] ?></div>
                        </div>
                        <div class="event_mess-right">
                            投票數：<?= $row3['vote_count'] ?>　
                            <?= $status_text ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; padding:20px; color:#666;">目前沒有待審核的建言。</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</body>

</html>