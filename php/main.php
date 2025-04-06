<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言2.0</title>
    <link rel="stylesheet" href="../css/new.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=LXGW+WenKai+Mono+TC&family=LXGW+WenKai+TC:wght@300;400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
<?php
    $link=mysqli_connect('localhost','root');
    mysqli_select_db($link,'sa');
    $sql = "select * from user where accounts='$accounts'";
    $result=mysqli_query($link,$sql);
    ?>
    <div class="navbar">
        <div class="logo-group">
            <a href="main.php" class="logo"></a>
            <a href="main.php" class="logo-title">愛校建言系統</a>
        </div>
        <?php
        if($_SESSION['type'] == "1"){
            echo "<div class='nav-user'>
            <p>歡迎，",$_SESSION['acc'],"</p>
            <a href='logout.php' class='nav-login'>登出</a>
            </div>";
        }elseif($_SESSION['type'] == "2"){
            echo "<div class='nav-user'>
            <p>歡迎，",$_SESSION['acc'],"</p>
            <a href='logout.php' class='nav-login'>登出</a>
            </div>";
        }else{
            echo "<div class='nav-user'>
            <a href='../login.html' class='nav-login'>登入</a>
            </div>";
        }?>
    </div>
    <?php
        if($_SESSION['type'] == ""){
            echo "<div class='func-list'>
            <div class='func1 open' id='f1' onclick='OpenF1()'>","最新消息","</div>
        </div> 
        <div class='no-login' id='notice-page'>
        <div class='need-login'>
            <i class='bx bxs-lock-alt'></i>使用其他功能前請先<a href='../login.html' class='main-login-notice'>","登入系統","</a>
        </div>
        <div class='main-news'>
            <div class='main-news-title'>
                <div class='main-title-title'>最新消息 <i class='bx bx-book-open'></i></div>
                <div class='main-news-mess' onclick=window.location.href='../post.html'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>","發布募資相關規則","</div>
                </div>
                <div class='main-news-mess' onclick='window.location.href=../post.html'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>","公告2","</div>
                </div>
                <div class='main-news-mess' onclick='window.location.href=../post.html'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>","公告3","</div>
                </div>
                <div class='main-news-mess' onclick='window.location.href=../post.html'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>","公告4","</div>
                </div>
                <div class='main-news-mess' onclick='window.location.href=../post.html'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>","公告5","</div>
                </div>
            </div>
        </div>
        <div class='main-page-num'>
            <ul>
                <li><i class='bx bx-chevrons-left'></i></li>
                <li>","1","</li>
                <li>","2","</li>
                <li>","3","</li>
                <li>","4","</li>
                <li>","5","</li>
                <li><i class='bx bx-chevrons-right'></i></li>
            </ul>
        </div>
    </div>";}?>
    <?php
        if($_SESSION['type'] == "2"){
            echo "<div class='func-list'>
            <div class='func1 open' id='f1' onclick='OpenF1()'>","最新消息","</div>
            <div class='func2' id='f2' onclick='OpenF2()'>","愛校建言","</div>
            <div class='func3' id='f3' onclick='OpenF3()'>","募資專區","</div>
        </div> 
        <div class='no-login' id='notice-page'>
            <div class='search-block'>
                <div class='search-box'>
                    <input type='text' class='search-bar' placeholder='請輸入關鍵字'>
                    <div class='search-but'><i class='bx bx-search'></i></div>
                </div>
            </div>
            <div class='main-news'>
                <div class='main-news-title'>
                    <div class='main-title-title'>最新消息 <i class='bx bx-book-open'></i></div>
                    <div class='main-news-mess' onclick=window.location.href='../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","發布募資相關規則","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告2","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告3","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告4","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告5","</div>
                    </div>
                </div>
            </div>
            <div class='main-page-num'>
                <ul>
                    <li><i class='bx bx-chevrons-left'></i></li>
                    <li>","1","</li>
                    <li>","2","</li>
                    <li>","3","</li>
                    <li>","4","</li>
                    <li>","5","</li>
                    <li><i class='bx bx-chevrons-right'></i></li>
                </ul>
            </div>
        </div>
        <div class='after-login' id='vote-page'>
            <div class='search-block'>
                <div class='search-box'>
                    <input type='text' class='search-bar' placeholder='請輸入關鍵字'>
                    <div class='search-but'><i class='bx bx-search'></i></div>
                </div>
            </div>
            <div class='main-news'><div class='main-news-title'>
            <div class='main-title-title'>最新投票議題 <i class='bx bx-book-open'></i></div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件1</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件2</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件3</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件4</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件5</div>
                </div>
            </div>
            <div class='main-page-num-2'>
                <ul>
                    <li><i class='bx bx-chevrons-left'></i></li>
                    <li>","1","</li>
                    <li>","2","</li>
                    <li>","3","</li>
                    <li>","4","</li>
                    <li>","5","</li>
                    <li><i class='bx bx-chevrons-right' ></i></li>
                </ul>
            </div> 
        </div>
        <div class='login-money' id='money-page'>
            <div class='search-block'>
                <div class='search-box'>
                    <input type='text' class='search-bar' placeholder='請輸入關鍵字'>
                    <div class='search-but'><i class='bx bx-search'></i></div>
                </div>
            </div>
            <div class='main-news'><div class='main-news-title'>
            <div class='main-title-title'>最新募資消息 <i class='bx bx-book-open'></i></div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件1</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件2</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件3</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件4</div>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件5</div>
                </div>
            </div>
            <div class='main-page-num-2'>
                <ul>
                    <li><i class='bx bx-chevrons-left'></i></li>
                    <li>","1","</li>
                    <li>","2","</li>
                    <li>","3","</li>
                    <li>","4","</li>
                    <li>","5","</li>
                    <li><i class='bx bx-chevrons-right' ></i></li>
                </ul>
            </div> 
        </div>"
    ;}?>
        <?php
        if($_SESSION['acc'] == "123"){
            echo "<div class='func-list'>
            <div class='func1 open' id='f1' onclick='OpenF1()'>","最新消息","</div>
            <div class='func2' id='f2' onclick='OpenF2()'>","愛校建言","</div>
            <div class='func3' id='f3' onclick='OpenF3()'>","募資專區","</div>
        </div>
        <div class='no-login' id='notice-page'>
            <div class='search-block'>
                <div class='search-box'>
                    <input type='text' class='search-bar' placeholder='請輸入關鍵字'>
                    <div class='search-but'><i class='bx bx-search'></i></div>
                </div>
            </div>
            <div class='main-news'>
                <div class='main-news-title'>
                    <div class='main-title-title'>最新消息 <i class='bx bx-book-open'></i></div>
                    <div class='main-news-mess' onclick=window.location.href='../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","發布募資相關規則","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告2","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告3","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告4","</div>
                    </div>
                    <div class='main-news-mess' onclick='window.location.href=../post.html'>
                        <div class='mess-date'>2025/3/14</div>
                        <div class='mess-title'>","公告5","</div>
                    </div>
                </div>
            </div>
            <div class='main-page-num'>
                <ul>
                    <li><i class='bx bx-chevrons-left'></i></li>
                    <li>","1","</li>
                    <li>","2","</li>
                    <li>","3","</li>
                    <li>","4","</li>
                    <li>","5","</li>
                    <li><i class='bx bx-chevrons-right'></i></li>
                </ul>
            </div>
        </div>
        <div class='after-login' id='vote-page'>
            <div class='search-block'>
                <div class='search-box'>
                    <input type='text' class='search-bar' placeholder='請輸入關鍵字'>
                    <div class='search-but'><i class='bx bx-search'></i></div>
                </div>
            </div>
            <div class='main-news'><div class='main-news-title'>
            <div class='main-title-title'>最新投票議題 <i class='bx bx-book-open'></i></div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件1</div>
                </div>
                <div class='mnmr' onclick='OpenVote()'>
                    <i class='bx bx-archive-in'></i>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件2</div>
                </div>
                <div class='mnmr'>
                    <i class='bx bx-archive-in'></i>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件3</div>
                </div>
                <div class='mnmr'>
                    <i class='bx bx-archive-in'></i>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件4</div>
                </div>
                <div class='mnmr'>
                    <i class='bx bx-archive-in'></i>
                </div>
            </div>
            <div class='main-vote-mess'>
                <div class='mnml'>
                    <div class='mess-date'>2025/3/14</div>
                    <div class='mess-title'>案件5</div>
                </div>
                <div class='mnmr'>
                    <i class='bx bx-archive-in'></i>
                </div>
            </div>
            <div class='main-page-num-2'>
                <ul>
                    <li><i class='bx bx-chevrons-left'></i></li>
                    <li>","1","</li>
                    <li>","2","</li>
                    <li>","3","</li>
                    <li>","4","</li>
                    <li>","5","</li>
                    <li><i class='bx bx-chevrons-right' ></i></li>
                </ul>
            </div> 
        </div>" ;}?>
    <div class="vote-box" id="vote-box">
        <div class="back-href" onclick="CloseVote()"><p><i class='bx bx-arrow-back'></i> 離開投票</p></div>
        <div class="vote-box-title">輔大游泳池需要清潔</div>
        <div class="num-date">
            <div class="vote-box-num">No.114514</div>
            <div class="vote-box-date">2025-3-18</div>
        </div>
        <div class="vote-box-info">
            輔仁大學游泳池近期出現水質混濁、底部沉積物增加等情況，影響使用者的游泳體驗。特別是在高峰時段，
            池水透明度明顯降低，部分區域甚至有異味，可能影響衛生與安全。經觀察，泳池邊緣及過濾設備周圍有明
            顯的污垢與沉積物，推測可能與近期清潔頻率降低或過濾系統運作異常有關。為維護游泳環境，建議立即安
            排清潔與水質檢測，確保泳池符合衛生標準，並降低可能對使用者健康造成的影響。
        </div>
        <div class="vote-about">
            <div class="vote-pnum">目前投票人數：79人</div>
            <div class="vote-but" onclick="VoteFin()">投票</div>
        </div>
    </div>
    <script src="../js/new.js"></script>
</body>