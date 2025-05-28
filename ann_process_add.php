<!--0503 AQ 去AI化-->

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
    $sql="insert announcements(title,content,accounts,date) values('$title','$content','$user_id',NOW())";
    if(mysqli_query($conn,$sql)){
        $targetPage = 'index.php';
        header('Location: ' . $targetPage);
    exit();
    }else{
?>
    <script> alert("新增失敗"); history.back(); </script>
<?php
    }}
    mysqli_close($link);
?>
