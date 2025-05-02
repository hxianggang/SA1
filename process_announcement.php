<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['name']) || $_SESSION['permissions'] != 2) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['acc'];

    $stmt = $conn->prepare("INSERT INTO announcements (title, content, accounts, date) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param('sss', $title, $content, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: index.php?message=success");
        exit();
    } else {
        $stmt->close();
        $conn->close();
        header("Location: index.php?message=fail");
        exit();
    }
}
?>
