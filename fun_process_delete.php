<?php
include('db.php');

$id = intval($_GET['id']);

$sql = "SELECT l_qua, f_id FROM log WHERE l_id = $id";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $l_qua = $row['l_qua'];
    $f_id = $row['f_id'];

    // 先扣除 f_now
    $update = "UPDATE fundraising SET f_now = f_now - $l_qua WHERE f_id = $f_id";
    if (mysqli_query($conn, $update)) {

        $check = "SELECT f_now, f_goal FROM fundraising WHERE f_id = $f_id";
        $check_result = mysqli_query($conn, $check);
        if ($row2 = mysqli_fetch_assoc($check_result)) {
            if ($row2['f_now'] < $row2['f_goal']) {
                $resetType = "UPDATE fundraising SET f_type = 1 WHERE f_id = $f_id";
                mysqli_query($conn, $resetType);
            }
        }

        $delete = "DELETE FROM log WHERE l_id = $id";
        if (mysqli_query($conn, $delete)) {
            echo "<script>alert('刪除成功');</script>";
            header("Location: fundraise.php");
            exit();
        } else {
            echo "<script>alert('刪除失敗');</script>";
            header("Location: fundraise.php");
            exit();
        }

    } else {
        echo "<script>alert('更新 fundraising 失敗');</script>";
        header("Location: fundraise.php");
        exit();
    }

} else {
    echo "<script>alert('查無 log 資料');</script>";
    header("Location: fundraise.php");
    exit();
}

mysqli_close($conn);
?>
