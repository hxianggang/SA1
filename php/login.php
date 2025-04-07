<?php
session_start();  // 啟動 session

// 引入資料庫連線
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 取得使用者輸入的帳號和密碼
    $account = $_POST['account'];
    $password = $_POST['password'];

    // 防止 SQL 注入
    $account = $conn->real_escape_string($account);
    $password = $conn->real_escape_string($password);

    // 查詢使用者資料
    $sql = "SELECT * FROM user WHERE account='$account' AND password='$password'";
    $result = $conn->query($sql);

    // 檢查帳號密碼是否正確
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 設定 session 變數
        $_SESSION['account'] = $user['account'];
        $_SESSION['role'] = $user['role'];

        // 轉跳到主頁或其他頁面
        header('Location: index.php');
        exit;
    } else {
        // 若帳號或密碼錯誤
        echo "帳號或密碼錯誤";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>登入</title>
    <style>
        @charset "utf-8";
        /* 通用設定值 取消某些標籤的預設設定 */
        :root{
            --orange1: #ff8000;
            --orange2: #ea7500;
            --orange3: #ffdcb9;
            --color-white: #fefefe;
            --color-black: #000;
            --color-blue: #0080ff;
        }
        *{
            box-sizing: border-box;
            position: relative;
        }
        html,body{
            font-family: '微軟正黑體', arial;
            background-color: var(--color-white);
            height: 100%;
        }
        .main{
            height: 100%;
        }
        .wrapper{
            height: 100%;
        }
        body{
            top: 0;
            width: 100%;
            margin: 0;
        }
        h1,h2,h3,h4,h5,h6,p{
            margin: 0;
        }
        ul{
            padding: 0;
            margin: 0;
            list-style: none;
        }
        a{
            text-decoration: none;
        }

        /* 設定整個頁面版型 */
        .container{
            width: 100%;
            height: 50%;
            position: relative;
            top: 25%;
            padding-left: 16px;
            padding-right: 16px;
        }
        .row{
            margin-left: -16px;
            margin-right: -16px;
        }
        .row:after{
            content: ''; /*填空值*/
            display: block; /*設定display的block，預設為inline*/
            clear: both; /*清除左右浮動*/
        }
        .col-2,.col-3,.col-4{
            float: left;
            border: 1px solid red;
            padding-left: 16px;
            padding-right: 16px;
        }
        .col-2{
            width: 50%;
        }
        .col-3{
            width: 33.33333%;
        }
        .col-4{
            width: 25%;
        }

        /* mask-dark 遮罩元件 */
        .mask-dark{
            background-color: rgba(0,0,0,0.7);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        /* 登入介面相關 */
        .jumbotron{
            border: none;
            background-image: url('../images/focus2293.jpg');
            background-size: cover;
            background-attachment: fixed;
            height: 100%;
            /*使圖片是跟著視窗的位置而非設定的框線*/
        }
        .login-form{
            border: none;
            width: 30%;
            height: 60%;
            position: relative;
            top: 20%;
            left: 35%;
            background-color: var(--color-white);
            border-radius: 10px;
        }
        .login-title{
            background-color: var(--orange1);
            height: 18%;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px 10px 0 0;
        }
        .login-title-left{
            display: inline-block;
            background-image: url(../images/school.png);
            background-size: cover;
            background-repeat: no-repeat;
            width: 60px;
            height: 70%;
            margin-right: 20px;
        }
        .login-title-right{
            display: flex;
            flex-direction: column;
            text-align: center;
            height: 80%;
            margin-left: 20px;
            justify-content: center;
        }
        .login-title-right h3{
            font-size: 25px;
            font-weight: bolder;
            color: var(--color-white);
        }
        .login-title-right p{
            font-size: 18px;
            font-weight: 700;
            color: var(--color-white);
        }
        .login-input{
            width: 80%;
            height: 12%;
            border: none;
            border-radius: 5px;
            margin: 15px 10%;
            padding-left: 20px;
            background-color: #efefef;
            transition: all 0ms;
        }
        .login-input:focus{
            width: calc(80% - 2px);
            height: calc(12% - 2px);
            outline: none;
            box-shadow: none;
            border: 1px solid var(--orange1);
            border-color: var(--orange1);
        }
        .login-notice{
            margin-top: 20px;
            margin-left: 10%;
            font-size: 16px;

            font-weight: 700;
        }
        .login-button{
            width: 80%;
            height: 12%;
            margin: 10% 10% 0;
            border: none;
            border-radius: 5px;
            background-color: var(--orange1);
            color: var(--color-white);
            font-weight: 600;
            font-size: 18px;
            transition: all .3s;
        }
        .login-button:hover{
            background-color: var(--orange2);
            transform: translateY(-3px);
        }
        .back-to-main{
            position: absolute;
            z-index: 1000;
            top: 10px;
            left: 10px;
        }
        .back-to-main a{
            color: var(--color-white);
            font-size: 20px;
            font-weight: 500;
            transition: all .3s;
        }
        .back-to-main a:hover{
            color: var(--orange1);
            transform: translateY(-3px);
        }
        .login-choice{
            width: 80%;
            height: 10%;
            margin-top: 5%;
            left: 10%;
            display: flex;
            align-items: center;
            justify-content: space-around;
        }
        .choice-left{
            width: 30%;
            height: 80%;
            color: var(--orange1);
            font-size: 16px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 20px;
            transition: all .3s;
        }
        .choice-right{
            width: 30%;
            height: 80%;
            color: var(--orange1);
            font-size: 16px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 20px;
            transition: all .3s;
        }
        .choice-left:hover{
            background-color: var(--orange1);
            color: var(--color-white);
        }
        .choice-right:hover{
            background-color: var(--orange1);
            color: var(--color-white);
        }
        .choice-left.on{
            width: 30%;
            height: 80%;
            color: var(--color-white);
            font-size: 16px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 22px;
            background-color: var(--orange1);
        }
        .choice-right.on{
            width: 30%;
            height: 80%;
            color: var(--color-white);
            font-size: 16px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            border-radius: 22px;
            background-color: var(--orange1);
        }
        .choice-left.on:hover{
            background-color: var(--orange2);
        }
        .choice-right.on:hover{
            background-color: var(--orange2);
        }
        .login-div{
            width: 100%;
            height: 62%;
            margin-top: 5%;
        }
        .register-div{
            width: 100%;
            height: 62%;
            position: relative;
            top: -65%;
        }
        .close{
            opacity: 0;
            z-index: -100;
        }
        .login{
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="jumbotron">
        <div class="mask-dark"></div>
        <div class="login-form">
            <div class="login-title">
                <div class="login-title-left"></div>
                <div class="login-title-right">
                    <h3>登入</h3>
                    <p>歡迎使用系統</p>
                </div>
            </div>
            <form method="POST" action="">
                <input type="text" name="account" class="login-input" placeholder="帳號" required>
                <input type="password" name="password" class="login-input" placeholder="密碼" required>
                <div class="login-notice">請輸入您的帳號與密碼</div>
                <button type="submit" class="login-button">登入</button>
            </form>
        </div>
    </div>
</body>
</html>
