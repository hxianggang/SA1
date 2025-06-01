<?php
if (session_status() == PHP_SESSION_NONE) {
    //session_start();
}
if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

include("db.php");
include("header.php");

$account = $_SESSION['name'];
$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// 查詢發布的議題
$sql = "SELECT e.e_id, e.e_title, e.e_text, e.e_time FROM event e
        WHERE e.accounts = ? 
        LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sii", $account, $items_per_page, $offset);
$stmt->execute();
$result = $stmt->get_result();

// 確認是否有資料
$topics = [];
while ($row = $result->fetch_assoc()) {
    $topics[] = $row;
}

$stmt->close();

// 確認總共的議題數
$sql_total = "SELECT COUNT(*) FROM event WHERE accounts = ?";
$stmt_total = $conn->prepare($sql_total);
$stmt_total->bind_param("s", $account);
$stmt_total->execute();
$stmt_total->bind_result($total_topics);
$stmt_total->fetch();
$stmt_total->close();

$total_pages = ceil($total_topics / $items_per_page);
$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM event WHERE e_title LIKE '%$search_keyword%' OR e_text LIKE '%$search_keyword%' ORDER BY e_time DESC";
} else {
    $sql = "SELECT * FROM event ORDER BY e_time DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <title>我的議題</title>
    <link rel="stylesheet" href="setting.css">
</head>

<body class="mytopics_body">

    <!-- 側邊欄 -->
    <div class="mytopics_sidebar">
        <h2 class="mytopics_sidebar_title">個人中心</h2>
        <a href="self.php" class="mytopics_sidebar_link">個人設置</a>
        <a href="view_voted_topics.php" class="mytopics_sidebar_link">查看已投票議題</a>
        <a href="my_topics.php" class="mytopics_sidebar_link">我的議題</a>
    </div>

    <!-- 右側內容區域 -->
    <div class="mytopics_container">
        <h3 class="mytopics_heading">我的議題</h3>



        <?php
if ($result->num_rows > 0) {
    $hasPosted = false; // 新增旗標

    while ($row = $result->fetch_assoc()) {
        $eventDate = new DateTime($row['e_time']);
        $now = new DateTime();
        $interval = $eventDate->diff($now);
        $isOverThreeMonths = ($interval->m + $interval->y * 12) >= 3;

        if ($_SESSION['acc'] == $row['accounts']) { 
            $hasPosted = true; 
?>
            <div class="index_main-news-mess">
                <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                    <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                    <div class="index_mess-title"><?php echo $row['e_title']; ?></div>
                </div>
                <div class="index-mess-right">
                    <?php
                    $e_id = $row['e_id'];
                    $sqla = "SELECT * FROM audit WHERE e_id = $e_id";
                    $resulta = mysqli_query($conn, $sqla);
                    $rowa = mysqli_fetch_assoc($resulta);

                    if ($isOverThreeMonths) {
                        if (isset($rowa['a_acc'])) {
                            switch ($rowa['situation']) {
                                case 1:
                                    echo "已審核通過";
                                    break;
                                case 2:
                                    echo "已否決建言";
                                    break;
                                case 3:
                                    echo "投票未通過";
                                    break;
                                case 4:
                                    echo "計畫制定中";
                                    break;
                                case 5:
                                    echo "正在募資中";
                                    break;
                                case 6:
                                    echo "計畫進行中";
                                    break;
                                case 7:
                                    echo "申訴期間";
                                    break;
                                case 8:
                                    echo "建言已結案";
                                    break;
                            }
                        } else {
                            echo "已結束待審核";
                        }
                    } else {
                        if (isset($rowa['a_acc'])) {
                            switch ($rowa['situation']) {
                                case 1:
                                    echo "已審核通過";
                                    break;
                                case 2:
                                    echo "已否決建言";
                                    break;
                                case 3:
                                    echo "投票未通過";
                                    break;
                                case 4:
                                    echo "計畫制定中";
                                    break;
                                case 5:
                                    echo "正在募資中";
                                    break;
                                case 6:
                                    echo "計畫進行中";
                                    break;
                                case 7:
                                    echo "申訴期間";
                                    break;
                                case 8:
                                    echo "建言已結案";
                                    break;
                            }
                        } else {
                            $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                            $result3 = mysqli_query($conn, $sql3);
                            $row3 = mysqli_fetch_assoc($result3);
                            echo "總投票數：", $row3['vote_count'];
                        }
                    }
                    ?>
                </div>
            </div>
<?php
        } 
    } 

    // 若沒有符合的資料，就顯示提示
    if (!$hasPosted) {
        echo "<p>您尚未發表過議題</p>";
    }

} else {
    echo "<p>您尚未發表過議題</p>";
}
?>

        
    </div>

    <!-- 只有學生身分才顯示新增建言按鈕 -->
    <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 1): ?>
        <?php include('eve_add.php'); ?>
    <?php endif; ?>
    <script src="123.js"></script>

</body>

</html>