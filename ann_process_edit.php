<?php
$id = $_POST['id'];
$title = $_POST['title'];
$content = $_POST['content'];
include('db.php');
$sql = "update announcements set title='$title', content='$content' where id='$id'";
if (mysqli_query($conn, $sql)) {
    $targetPage = 'index.php';
    header('Location: ' . $targetPage);
    exit();
} else {
?>
    <script>
        alert("修改失敗");
        history.back();
    </script>
<?php
}
mysqli_close($link);
?>