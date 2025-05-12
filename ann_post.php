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
        $id = $_GET['id'];
        $sql = "select * from announcements a,user u where a.id='$id' and u.accounts=a.accounts";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="message-page">
                <div class="message-page-left">
                    <div class="back-href" onclick="window.history.back()">
                        <p><i class='bx bx-arrow-back'></i> 返回上一頁
                    </div>
                    </p>
                    <div class="message-title"><?php echo $row['title']; ?></div>
                    <div class="date-person">
                        <div class="message-date"><?php echo $row['date']; ?> ‧</div>
                        <div class="message-person"> <?php echo $row['name']; ?></div>
                    </div>

                    <!--內文-->
                    <div class="message-info"><?php echo $row['content']; ?></div>

                    <!--檔案上傳-->
                    <div class="message-docu">
                        <a href="C:\Users\User\Downloads\img_main_pc (1).png" download="main.png"><i class='bx bx-download'></i>下載附件</a>
                    </div>
                </div>

                <!--之後可以放募資消息-->
                <div class="message-page-right">待更新</div>
            </div>
        <?php } ?>
    </main>
    <script src="../php_test/js/setting.js"></script>
</body>