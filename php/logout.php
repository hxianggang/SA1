<?php
    $_SESSION['acc']="";
    $_SESSION['type']="";
    $targetPage = 'main.php';
    header('Location: '. $targetPage);
    exit();
?>