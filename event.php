<!-- 0503 AQ 撰寫建言列表-->

<?php
include('header.php');
include('db.php');

// 處理搜尋功能
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <link rel="stylesheet" href="new.css">
</head>

<body>

    <!-- 搜尋框 -->
    <form id="search-form" method="GET" class="index_search-block">
        <div class="index_search-box">
            <input type="text" class="index_search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
            <button type="submit" class="index_search-but">搜尋</button>
        </div>
    </form>



    <!-- 學生建言列表 -->
    <?php if ($_SESSION['permissions'] == 1){ ?>
        <div class="index_main-news">
            <div class="index_main-news-title">歷史提出建言</div>
                <?php if ($result->num_rows > 0){ ?>
                    <?php while ($row = $result->fetch_assoc()){ 
                        $eventDate = new DateTime($row['e_time']);
                        $now = new DateTime();
                        $interval = $eventDate->diff($now);
                        $isOverThreeMonths = ($interval->m + $interval->y * 12) >= 3;?>
                        <?php if ($_SESSION['acc'] == $row['accounts']){ ?> <!--只有自己提的才能看到-->
                            <div class="index_main-news-mess">
                            <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                                <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                                <div class="index_mess-title"><?php echo $row['e_title']; ?></div>
                            </div>
                            <div class="index-mess-right">
                            <?php
                            // 查看現況
                            ?>
                                <div class="index_mess-date">
                                    <?php
                                    $e_id = $row['e_id'];
                                    $sqla = "SELECT * FROM audit WHERE e_id = $e_id";
                                    $resulta = mysqli_query($conn, $sqla);
                                    $rowa = mysqli_fetch_assoc($resulta);
                                    if ($isOverThreeMonths) {
                                        if (isset($rowa['a_acc'])){
                                            if ($rowa['situation'] == 1){
                                                echo "已審核通過";
                                            }else if ($rowa['situation'] == 2){
                                                echo "已否決建言";
                                            }else if ($rowa['situation'] == 3){
                                                echo "投票未通過";
                                            }else if ($rowa['situation'] == 4){
                                                echo "計畫制定中";
                                            }else if ($rowa['situation'] == 5){
                                                echo "正在募資中";
                                            }else if ($rowa['situation'] == 6){
                                                echo "計畫進行中";
                                            }else if ($rowa['situation'] == 7){
                                                echo "申訴期間";
                                            }else if ($rowa['situation'] == 8){
                                                echo "建言已結案";
                                            }
                                        }else{
                                            echo "已結束待審核";
                                        }
                                    }else{
                                        if (isset($rowa['a_acc'])){
                                            if ($rowa['situation'] == 1){
                                                echo "已審核通過";
                                            }else if ($rowa['situation'] == 2){
                                                echo "已否決建言";
                                            }else if ($rowa['situation'] == 3){
                                                echo "投票未通過";
                                            }else if ($rowa['situation'] == 4){
                                                echo "計畫制定中";
                                            }else if ($rowa['situation'] == 5){
                                                echo "正在募資中";
                                            }else if ($rowa['situation'] == 6){
                                                echo "計畫進行中";
                                            }else if ($rowa['situation'] == 7){
                                                echo "申訴期間";
                                            }else if ($rowa['situation'] == 8){
                                                echo "建言已結案";
                                            }
                                        }else if(!isset($rowa['a_acc'])){
                                            $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                                            $result3 = mysqli_query($conn, $sql3);
                                            $row3 = mysqli_fetch_assoc($result3);
                                            echo "總投票數：",$row3['vote_count'];
                                        }
                                    }?>
                                </div>
                            </div>
                        </div>
                        <?php }  ?>
                    <?php } ?>
                <?php } ?>
            </div>       
        </div>
    <?php } ?>


    <!-- 管理建言審核列表 -->
    <?php if ($_SESSION['permissions'] == 2){ ?>
        <div class="index_main-news">
            <div class="index_main-news-title">審核建言</div>
                <?php if ($result->num_rows > 0){ ?>
                    <?php while ($row = $result->fetch_assoc()){ ?>
                        <div class="index_main-news-mess">
                            <div class="index-mess-left" onclick="window.location.href='eve_post.php?e_id=<?php echo $row['e_id']; ?>'">
                                <div class="index_mess-date"><?php echo $row['e_time']; ?></div>
                                <div class="index_mess-title"><?php echo $row['e_title']; ?></div>
                            </div>
                            <div class="index-mess-right">
                            <?php
                            // 查投票人數
                            $sql3 = "SELECT COUNT(*) AS vote_count FROM vote WHERE e_id = '{$row['e_id']}'";
                            $result3 = mysqli_query($conn, $sql3);
                            $row3 = mysqli_fetch_assoc($result3);

                            // 查審核狀態（只撈一筆即可）
                            $sql2 = "SELECT * FROM audit WHERE e_id = '{$row['e_id']}'";
                            $result2 = mysqli_query($conn, $sql2);

                            if ($row2 = mysqli_fetch_assoc($result2)) {
                                $status = $row2['situation'];
                                if ($status == 1) {
                                    $status_text = "已接受";
                                } elseif ($status == 2) {
                                    $status_text = "已拒絕";
                                } elseif ($status == 3) {
                                    $status_text = "投票未通過";
                                } elseif ($status == 4) {
                                    $status_text = "計畫制定中";
                                } elseif ($status == 5) {
                                    $status_text = "正在募資中";
                                } elseif ($status == 6) {
                                    $status_text = "計畫進行中";
                                } elseif ($status == 7) {
                                    $status_text = "申訴期間";
                                } elseif ($status == 8) {
                                    $status_text = "建言已結案";
                                }
                            } else {
                                $status_text = "尚未審核";
                            }
                            ?>

                            <div class="index_mess-date">
                                投票數：<?php echo $row3['vote_count']; ?>　
                                <?php echo $status_text; ?>
                            </div>




                            </div>
                        </div>
                    <?php }  ?>
                <?php } ?>
            </div>       
        </div>
    <?php } ?>

    <!-- 搜尋表單 (隱藏) -->
    <form id="search-form" method="GET" style="display:none;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
    </form>

    <!-- 只有學生身分才顯示新增建言按鈕 -->
    <?php if ($_SESSION['permissions'] == 1): ?>
        <?php include('eve_add.php'); ?> 
    <?php endif; ?>

    <!-- JavaScript 載入 -->
    <script src="123.js"></script>
</body>

</html>