<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言2.0</title>
    <link rel="stylesheet" href="../php_test/setting.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=LXGW+WenKai+Mono+TC&family=LXGW+WenKai+TC:wght@300;400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    include('header.php');
    include('db.php'); 
    ?>

    <!--本體-->
    <main>
        <?php
        $id=$_GET['id'];
        $sql = "select * from fundraising where f_id=$id";
        $result=mysqli_query($conn,$sql);
        while($row=mysqli_fetch_assoc($result)){ ?>
        <div class="message-page">
            <div class="message-page-left">
                <div class="back-href" onclick="window.history.back()"><p><i class='bx bx-arrow-back'></i> 返回上一頁</div></p>
                <div class="message-title"><?php echo $row['f_title']; ?></div>
                <div class="date-person">
                    <div class="message-date"><?php echo $row['f_date']; ?></div>
                </div>

                <!--內文-->
                <div class="message-info">
                    <?php echo $row['f_content']; ?>
                </div>
                <div class="message-info">
                    <?php
                    if ($row['f_cate'] == 1){
                        echo "學務處：02-2905-3174";
                    }else if ($row['f_cate'] == 2){
                        echo "教務處：02-2905-2217";
                    }else {
                        echo "02-2905-2000";
                    }?>
                </div>
            </div>

            <!--之後可以放募資消息-->
            <div class="message-page-right">
                <?php
                echo "目標金額：";
                echo $row['f_now']."/".$row['f_goal'];
                $sql2 = "select * from log where f_id=$id";
                $result2=mysqli_query($conn,$sql2);
                if ($result2->num_rows > 0): ?>
                    <?php while ($row = $result2->fetch_assoc()): ?>
                        <div class="index_main-news-mess">
                            <div class="fun-mess-left">
                                <div class="index_mess-title"><?php echo $row['l_name']; ?></div>
                            </div>
                            <div class="fun-mess-mid">
                                <?php echo $row['l_qua']; ?>
                            </div>
                            <div class="fun-mess-right">
                                <?php if (isset($_SESSION['permissions']) && $_SESSION['permissions'] == 2) {?>
                                <div class='but-delete' onclick="window.location.href='fun_process_delete.php?id=<?php echo $row['l_id']; ?>'">刪除</div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div>目前還沒有募資紀錄</div>
                <?php endif; ?>
            </div>
        </div>
        <?php } ?>
    </main>
    <script src="../php_test/js/setting.js"></script>
</body>
