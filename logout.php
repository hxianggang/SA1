<?php
session_start();

// 清除 session 變數
session_unset();

// 銷毀 session
session_destroy();

// 轉向回登入頁
header('Location: login.php');
exit;
