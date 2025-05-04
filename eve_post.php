<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言2.0</title>
    <link rel="stylesheet" href="../php_test/style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
        $e_id=$_GET['e_id'];
        $sql = "select * from event e,user u where e.e_id='$e_id' and u.accounts=e.accounts";
        $result=mysqli_query($conn,$sql);
        while($row=mysqli_fetch_assoc($result)){ ?>
        <div class="message-page">
            <div class="message-page-left">
                <div class="back-href" onclick="window.history.back()"><p><i class='bx bx-arrow-back'></i> 返回上一頁</div></p>
                <div class="message-title"><?php echo $row['e_title']; ?></div>
                <div class="date-person">
                    <div class="message-date"><?php echo $row['e_time']; ?></div>
                </div>

                <!--內文-->
                <div class="message-info"><?php echo $row['e_text']; ?></div>

                <!--檔案上傳-->
                <div class="message-docu">
                    <a href="C:\Users\User\Downloads\img_main_pc (1).png" download="main.png"><i class='bx bx-download' ></i>下載附件</a>
                </div>

                <!--使用者附議建言 沒投過該建言的才出現-->
                <?php 
                if ($_SESSION['permissions'] == 1){
                $user_acc = $_SESSION['acc'];
                $sql2 = "SELECT * FROM vote WHERE e_id = '$e_id' AND v_stu = '$user_acc'";
                $result2 = mysqli_query($conn, $sql2);
                if (mysqli_num_rows($result2) === 0) { ?>
                    <form id="suggestionForm" method="POST" action="vot_process_add.php">
                        <input type="hidden" id="e_id" name="e_id" required value="<?php echo $e_id;?>"><br><br>
                        <button type="submit">附議</button>
                    </form>
                <?php }} ?>

                <!--管理者審核建言 沒投過該建言的才出現-->
                <?php 
                if ($_SESSION['permissions'] == 2) {
                    // 檢查這筆建言是否已經被審核過（任何人）
                    $sql2 = "SELECT * FROM audit WHERE e_id = '$e_id'";
                    $result2 = mysqli_query($conn, $sql2);

                    // 如果找不到代表尚未被任何人審核，就顯示審核表單
                    if (mysqli_num_rows($result2) === 0) { ?>
                    <form method="POST" action="aud_process_add.php">
                        <input type="hidden" name="e_id" value="<?php echo $e_id; ?>">
                        <label><input type="radio" name="audit_action" value="up" required> 接受</label>
                        <label><input type="radio" name="audit_action" value="down"> 拒絕</label>
                        <label for="reason">原因及處理方法:</label>
                        <textarea id="reason" name="reason" required></textarea><br><br> 
                        <button type="submit">送出</button>
                    </form>
                <?php 
                    }
                }
                ?>
            </div>
            

            <!--之後可以放募資消息-->
            <div class="message-page-right">待更新</div>
        </div>
        <?php } ?>
    </main>
    <script src="../php_test/js/setting.js"></script>
</body>
