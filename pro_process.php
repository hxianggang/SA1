<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $e_id = $_POST['e_id'];
    $title = $_POST['pro_title'];
    $content = $_POST['pro_content'];
    $file = $_POST['pro_file'];
    $goal = $_POST['pro_goal'];
    $date = $_POST['pro_date'];
    $cate = $_POST['pro_cate'];
    $user_id = $_SESSION['acc'];
    $sql = "insert fundraising(e_id,f_title,f_content,f_file,f_now,f_goal,f_date,f_cate,f_type) values('$e_id','$title','$content','$file',0,'$goal','$date','$cate',1)";
    if(mysqli_query($conn,$sql)){
        $update = "UPDATE audit SET situation = 5 WHERE e_id = '$e_id' AND situation = 4";
        mysqli_query($conn, $update);
        $update2 = "UPDATE event SET e_type = 1 WHERE e_id = '$e_id'";
        mysqli_query($conn, $update2);
        $targetPage = 'event.php';
        header('Location: ' . $targetPage);
    exit();mysqli_close($conn);
    }else{
?>
    <script> alert("新增失敗"); history.back(); </script>
<?php
    }}
?>
