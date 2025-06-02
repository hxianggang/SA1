<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言2.0</title>
    <link rel="stylesheet" href="/setting.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=LXGW+WenKai+Mono+TC&family=LXGW+WenKai+TC:wght@300;400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include('header.php');
    include('db.php');

    $e_id = $_GET['e_id'];

    // 查詢建言及提案者資料
    $sql = "SELECT * FROM event e, user u WHERE e.e_id = '$e_id' AND u.accounts = e.accounts";
    $result = mysqli_query($conn, $sql);

    // 申訴狀態中文轉換函式，只針對三個狀態
    function appealStatusToChinese($status)
    {
        return match ($status) {
            'pending' => '待處理',
            'resolved' => '已處理',
            'rejected' => '駁回',
            default => htmlspecialchars($status),
        };
    }

    while ($row = mysqli_fetch_assoc($result)) {
        // 建言提出者帳號
        $proposer_acc = $row['accounts'];

        // 查詢該建言審核狀態
        $sqlAudit = "SELECT * FROM audit WHERE e_id = '$e_id'";
        $resultAudit = mysqli_query($conn, $sqlAudit);
        $rowAudit = mysqli_fetch_assoc($resultAudit);

        // 查詢申訴狀態（使用者是否已申訴）
        $appeal_sql = "SELECT * FROM appeals WHERE e_id = '$e_id' AND accounts = '{$_SESSION['acc']}'";
        $appeal_result = mysqli_query($conn, $appeal_sql);
        $hasAppealed = mysqli_num_rows($appeal_result) > 0;
    ?>

        <main>
            <div class="message-page">
                <div class="message-page-left">
                    <div class="back-href" onclick="window.history.back()">
                        <p><i class="ri-arrow-left-line"></i> 返回上一頁</p>
                    </div>
                    <div class="message-title"><?php echo htmlspecialchars($row['e_title']); ?></div>
                    <div class="date-person">
                        <div class="message-date"><?php echo htmlspecialchars($row['e_time']); ?></div>
                    </div>

                    <!--內文-->
                    <div class="message-info"><?php echo nl2br(htmlspecialchars($row['e_text'])); ?></div>
                </div>

                <!--管理員審核-->
                <div class="message-page-right">
                    <?php
                    if ($_SESSION['permissions'] == 2) {
                        // 如果尚未被審核，顯示審核表單
                        if (mysqli_num_rows($resultAudit) === 0) { ?>
                            <form method="POST" action="aud_process_add.php" class="aud-form">
                                <input type="hidden" name="e_id" value="<?php echo htmlspecialchars($e_id); ?>">
                                <label><input type="radio" name="audit_action" value="up" required class="aud-form-radio"> 接受</label>
                                <label><input type="radio" name="audit_action" value="down" class="aud-form-radio"> 拒絕</label>
                                <label for="reason">原因及處理方法:</label>
                                <textarea id="reason" name="reason" required class="aud-form-text"></textarea><br><br>
                                <button class="eve_post_but" type="submit">送出</button>
                            </form>
                            <?php
                        } else {
                            if ($rowAudit['situation'] == 4) {
                            ?>
                                <form method="POST" action="pro_process.php" class="aud-form">
                                    <input type="hidden" name="e_id" value="<?php echo htmlspecialchars($e_id); ?>">
                                    <label>標題：<input type="text" name="pro_title" value="" required class="aud-form-radio" required></label><br>
                                    <label for="reason">內文:</label>
                                    <textarea id="reason" name="pro_content" required class="aud-form-text" required></textarea><br>
                                    <label>檔案上傳：</label>
                                    <input type="file" id="file" name="pro_file"><br>
                                    <label>目標金額：<input type="text" name="pro_goal" value="" required class="aud-form-radio" required></label><br>
                                    <label>期限：</label>
                                    <input type="date" id="time" name="pro_date" class="form-date" required><br>
                                    <label>處理單位：</label>
                                    <select id="category" name="pro_cate" required class="form-date">
                                        <option value="">-- 請選擇 --</option>
                                        <option value="1">學務處</option>
                                        <option value="2">教務處</option>
                                        <option value="3">總務處</option>
                                    </select><br>
                                    <button class="eve_post_but" type="submit">送出</button>
                                </form>
                    <?php
                            } else {
                                echo "已制定計畫";
                            }
                        }
                    }
                    ?>
                    <!-- 申訴功能：只在「被拒絕」時，且使用者是建言提出者，且使用者沒申訴過，顯示申訴表單 -->
                    <?php if ($_SESSION['permissions'] == 1 && isset($rowAudit['situation']) && $rowAudit['situation'] == 2 && $_SESSION['acc'] === $proposer_acc): ?>
                        <div class="appeal-section" class="aud-form">
                            <h3>申訴此建言</h3>
                            <?php if (!$hasAppealed): ?>
                                <form method="POST" action="appeal_process_add.php">
                                    <input type="hidden" name="e_id" value="<?= htmlspecialchars($e_id) ?>">
                                    <label for="appeal_text">申訴理由：</label><br>
                                    <textarea id="appeal_text" name="appeal_text" required rows="5" style="width:100%;"></textarea><br><br>
                                    <button type="submit" class="eve_post_but">提交</button>
                                </form>
                            <?php else:
                                $appeal = mysqli_fetch_assoc($appeal_result);
                            ?>
                                <p><strong>您已提交申訴，狀態：<?= appealStatusToChinese($appeal['status']) ?></strong></p>
                                <?php if (!empty($appeal['reply_text'])): ?>
                                    <p><strong>管理者回覆：</strong><br><?= nl2br(htmlspecialchars($appeal['reply_text'])) ?></p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!--使用者附議建言 沒投過該建言的才出現-->
                    <?php
                    if ($_SESSION['permissions'] == 1) {
                        $user_acc = $_SESSION['acc'];

                        $sql2 = "SELECT * FROM vote WHERE e_id = '$e_id' AND v_stu = '$user_acc'";
                        $result2 = mysqli_query($conn, $sql2);

                        $sql3 = "SELECT * FROM audit WHERE e_id = '$e_id'";
                        $result3 = mysqli_query($conn, $sql3);
                        $row3 = mysqli_fetch_assoc($result3);

                        $eventDate = new DateTime($row['e_time']);
                        $now = new DateTime();
                        $interval = $eventDate->diff($now);
                        $isOverThreeMonths = ($interval->m + $interval->y * 12) >= 3;
                        if (!$isOverThreeMonths && !isset($row3['situation'])) {
                            if (mysqli_num_rows($result2) == 0) {
                                // 尚未投過票：顯示附議按鈕
                    ?>
                            <form id="suggestionForm" method="POST" action="vot_process_add.php">
                                <input type="hidden" id="e_id" name="e_id" required value="<?php echo htmlspecialchars($e_id); ?>">
                                <button class="eve_post_but" type="submit">附議</button>
                                </form>
                        <?php
                            } else {
                                // 已投過票：顯示灰色提示框
                        ?>
                                <div class="eve_post_but_disabled" onclick="confirmUnvote('<?php echo $e_id; ?>, this')">已附議</div>
                        <?php
                            }
                        }     
                    }     
                    ?>

                </div>
            </div>
        </main>

    <?php } 
    ?>
        <script>
            function confirmUnvote(e_id, element) {
    const confirmed = confirm("你確定要取消附議嗎？");

    if (confirmed) {
        fetch('unvote_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'e_id=' + encodeURIComponent(e_id)
        }).then(() => {
            // 無論結果是成功或失敗，都重新整理頁面
            location.reload();
        }).catch((error) => {
            console.error('錯誤:', error);
            location.reload(); // 即使出錯也刷新，讓資料同步
        });
    }
}

        </script>

    <script src="../php_test/js/setting.js"></script>
</body>