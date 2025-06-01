<?php
if (session_status() == PHP_SESSION_NONE) //session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $e_id = $_POST['e_id'];
    $user_id = $_SESSION['acc'];
    $sql = "insert vote(e_id,v_stu) values('$e_id','$user_id')";
    if (mysqli_query($conn, $sql)) {
        $targetPage = 'vote.php';
        header('Location: ' . $targetPage);
        exit();
        mysqli_close($conn);
    } else {
?>
        <script>
            alert("新增失敗");
            history.back();
        </script>
<?php
    }
}

?>