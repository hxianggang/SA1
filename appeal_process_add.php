<?php
//session_start();
if (!isset($_SESSION['name']) || $_SESSION['permissions'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $e_id = $_POST['e_id'];
    $accounts = $_SESSION['acc'];
    $appeal_text = trim($_POST['appeal_text']);

    // 檢查是否已申訴過
    $check_sql = "SELECT * FROM appeals WHERE e_id = ? AND accounts = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param('ii', $e_id, $accounts);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('您已對此建言提出申訴，請勿重複提交。'); history.back();</script>";
        exit();
    }

    // 插入申訴資料
    $insert_sql = "INSERT INTO appeals (e_id, accounts, appeal_text, status) VALUES (?, ?, ?, 'pending')";
    $stmt_insert = $conn->prepare($insert_sql);
    $stmt_insert->bind_param('iis', $e_id, $accounts, $appeal_text);

    if ($stmt_insert->execute()) {
        echo "<script>alert('申訴已提交，請等待管理者回覆。'); window.location.href='eve_post.php?e_id=$e_id';</script>";
    } else {
        echo "<script>alert('提交失敗，請稍後再試。'); history.back();</script>";
    }
}
?>
