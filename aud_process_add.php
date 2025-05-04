<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $e_id = $_POST['e_id'];
    $reason = $_POST['reason'];
    $action = $_POST['audit_action'];
    $user_id = $_SESSION['acc'];
    if ($action === 'up') {
        $sql = "insert audit(e_id,situation,reason,a_acc) values('$e_id','1','$reason','$user_id')";
    } elseif ($action === 'down') {
        $sql = "insert audit(e_id,situation,reason,a_acc) values('$e_id','2','$reason','$user_id')";
    } else {
        die('請選擇有效的選項');
    }
    if(mysqli_query($conn,$sql)){
        $targetPage = 'event.php';
        header('Location: ' . $targetPage);
    exit();mysqli_close($conn);
    }else{
?>
    <script> alert("新增失敗"); history.back(); </script>
<?php
    }}
    
?>
