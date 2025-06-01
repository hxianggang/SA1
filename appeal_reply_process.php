<?php
//session_start();
if (!isset($_SESSION['permissions']) || $_SESSION['permissions'] != 2) {
    header("Location: login.php");
    exit();
}

include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appeal_id = isset($_POST['appeal_id']) ? intval($_POST['appeal_id']) : 0;
    $reply_text = isset($_POST['reply_text']) ? $_POST['reply_text'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($appeal_id > 0 && in_array($status, ['pending', 'resolved', 'rejected'])) {
        $stmt = $conn->prepare("UPDATE appeals SET reply_text = ?, status = ? WHERE appeal_id = ?");
        $stmt->bind_param("ssi", $reply_text, $status, $appeal_id);
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: appeals_manage.php?appeal_id=" . $appeal_id);
            exit();
        } else {
            $stmt->close();
            echo "更新失敗，請稍後再試。";
        }
    } else {
        echo "資料不完整或格式錯誤。";
    }
} else {
    header("Location: appeals_manage.php");
    exit();
}
