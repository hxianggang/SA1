<?php
$acc=$_POST['acc'];
$pw=$_POST['pw'];
$link=mysqli_connect('localhost','root');
mysqli_select_db($link,'sa');
$sql = "select * from user where accounts='$acc' and password='$pw'";
$result=mysqli_query($link,$sql);
if($row=mysqli_fetch_assoc($result)){
    $_SESSION['acc']=$acc;
    $_SESSION['type']=$row['permissions'];
    $type=$row['permissions'];
    if($type =="1"){
        $targetPage = 'main.php';
        header('Location: '. $targetPage);
        exit();
    }elseif($type == "2"){
        $targetPage = '../backend.html';
        header('Location: '. $targetPage);
        exit();
    }
}else{
?>
    <script> alert("登入失敗"); history.back(); </script>
<?php
    }
    mysqli_close($link);
?>