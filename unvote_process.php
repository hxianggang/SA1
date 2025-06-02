<?php
session_start();
include('db.php');

if (!isset($_SESSION['acc']) || !isset($_POST['e_id'])) {
    echo 'error';
    exit;
}

$acc = $_SESSION['acc'];
$e_id = $_POST['e_id'];

$sql = "DELETE FROM vote WHERE e_id = ? AND v_stu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $e_id, $acc);
$success = $stmt->execute();
$stmt->close();

echo $success ? 'success' : 'error';
?>
