<?php
if (session_status() == PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('db.php');

    $f_id = $_POST['id'];
    $num = $_POST['f_num'];
    $name = $_POST['f_name'];
    $date = date("Y-m-d H:i:s");
    $sql = "insert log(f_id,l_name,l_qua,l_time) values('$f_id','$name','$num','$date')";
    if (mysqli_query($conn, $sql)) {
        $update = "UPDATE fundraising SET f_now = f_now+$num WHERE f_id = '$f_id'";
        mysqli_query($conn, $update);

        $check = "SELECT f_now, f_goal FROM fundraising WHERE f_id = $f_id";
        $result = mysqli_query($conn, $check);
        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['f_now'] >= $row['f_goal']) {
                // 若達標，更新 f_type = 2
                $setType = "UPDATE fundraising SET f_type = 2 WHERE f_id = $f_id";
                mysqli_query($conn, $setType);
            }
        }

        $targetPage = 'fundraise.php';
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