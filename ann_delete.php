<?php
    $post_id=$_GET['id'];

    $link=mysqli_connect('localhost','root');
    mysqli_select_db($link,'sa');
    $sql="delete from announcements where id='$post_id'";
    if (mysqli_query($link, $sql)) {
        $sql="delete from announcements where id='$post_id'";
        if (mysqli_query($link, $sql)) {
            echo "<script> alert('刪除成功');  </script>";
            header('Location: index.php');
        } else {
            echo "<script> alert('刪除失敗'); </script>";
            header('Location: index.php');
        }
    } else {
        echo "<script> alert('刪除失敗'); </script>";
        header('Location: index.php');
    }
    mysqli_close($link);
?>