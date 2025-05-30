<?php
session_start();
if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] != 2) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $appeal_id = $_POST['appeal_id'];
    $reply_text = trim($_POST['reply_text']);
    $status = $_POST['status'];

    $sql = "UPDATE appeals SET reply_text = ?, reply_date = NOW(), status = ? WHERE appeal_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $reply_text, $status, $appeal_id);

    if ($stmt->execute()) {
        echo "<script>alert('回覆成功'); window.location.href='appeals_manage.php';</script>";
    } else {
        echo "<script>alert('更新失敗'); history.back();</script>";
    }
}
