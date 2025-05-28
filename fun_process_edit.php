<?php
    $id=$_POST['id'];
    $title=$_POST['title'];
    $content=$_POST['content'];
    $money = $_POST['money'];
    include('db.php');
    $sql="update fundraising set f_title='$title', f_content='$content', f_goal='$money' where f_id='$id'";
    if(mysqli_query($conn,$sql)){
        $targetPage = 'fundraise.php';
        header('Location: ' . $targetPage);
    exit();
    }else{
?>
    <script> alert("修改失敗"); history.back(); </script>
<?php
    }
    mysqli_close($link);
?>