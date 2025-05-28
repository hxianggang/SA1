<!--0503 AQ 去AI化-->

<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['name']) || $_SESSION['permissions'] != 1) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $e_title = $_POST['e_title'];
    $e_text = $_POST['e_text'];
    $user_id = $_SESSION['acc'];
    $sql="insert event(e_title,e_text,accounts,e_time) values('$e_title','$e_text','$user_id',NOW())";
    if(mysqli_query($conn,$sql)){
        $targetPage = 'event.php';
        header('Location: ' . $targetPage);
    exit();
    }else{
?>
    <script> alert("新增失敗"); history.back(); </script>
<?php
    }}
    mysqli_close($link);
?>
