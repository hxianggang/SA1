<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    if (!isset($_POST['e_id'], $_POST['reason'], $_POST['audit_action'])) {
        die('錯誤：缺少必要欄位');
    }

    $e_id = $_POST['e_id'];
    $reason = $_POST['reason'];
    $action = $_POST['audit_action'];
    $user_id = $_SESSION['acc'];

    // 檢查 e_id 是否存在於 event 表
    $check_sql = "SELECT COUNT(*) FROM event WHERE e_id = ?";
    $stmt_check = $conn->prepare($check_sql);
    $stmt_check->bind_param("i", $e_id);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($count == 0) {
        die('錯誤：指定的建言ID不存在');
    }

    // 決定 situation
    if ($action === 'up') {
        $situation = 1;
    } elseif ($action === 'down') {
        $situation = 2;
    } else {
        die('請選擇有效的選項');
    }

    // 插入 audit 表
    $insert_sql = "INSERT INTO audit (e_id, situation, reason, a_acc) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("iiss", $e_id, $situation, $reason, $user_id);

    if ($stmt->execute()) {
        // 更新狀態
        $update = "UPDATE audit SET situation = 4 WHERE e_id = ? AND situation = 1";
        $stmt_update = $conn->prepare($update);
        $stmt_update->bind_param("i", $e_id);
        $stmt_update->execute();
        $stmt_update->close();

        $update2 = "UPDATE event SET e_type = 1 WHERE e_id = ?";
        $stmt_update2 = $conn->prepare($update2);
        $stmt_update2->bind_param("i", $e_id);
        $stmt_update2->execute();
        $stmt_update2->close();

        $stmt->close();
        $conn->close();

        header('Location: event.php');
        exit();
    } else {
        $stmt->close();
        $conn->close();
        echo "<script>alert('新增失敗'); history.back();</script>";
        exit();
    }
}
