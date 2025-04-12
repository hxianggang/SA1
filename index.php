<?php
// 引入 header.php
include('header.php');
include('db.php');

// 處理搜尋功能
$search_keyword = '';
if (isset($_GET['search'])) {
    $search_keyword = $_GET['search'];
}

// 查詢公告
$sql = "SELECT * FROM announcements WHERE title LIKE ? OR content LIKE ? ORDER BY date DESC";
$stmt = $conn->prepare($sql);
$search_term = "%" . $search_keyword . "%";
$stmt->bind_param('ss', $search_term, $search_term);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>愛校建言系統</title>
    <style>
        /* 網頁基本樣式 */
        body {
            font-family: '微軟正黑體', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* 搜尋框樣式 */
        .search-block {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .search-box {
            display: flex;
            width: 60%;
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .search-bar {
            width: 90%;
            padding: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px 0 0 5px;
        }

        .search-but {
            width: 10%;
            background-color: #ff8000;
            color: #ffffff;
            text-align: center;
            padding: 12px;
            cursor: pointer;
            border-radius: 0 5px 5px 0;
        }

        .search-but:hover {
            background-color: #ea7500;
        }

        /* 公告列表樣式 */
        .main-news {
            margin-top: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            margin-left: auto;
            margin-right: auto;
        }

        .main-news-title {
            font-size: 24px;
            font-weight: bold;
            color: #ff8000;
            margin-bottom: 20px;
        }

        .main-news-mess {
            padding: 10px;
            margin: 10px 0;
            border-bottom: 1px solid #f1f1f1;
            cursor: pointer;
        }

        .main-news-mess:hover {
            background-color: #f9f9f9;
        }

        .mess-date {
            font-size: 14px;
            color: #888888;
        }

        .mess-title {
            font-size: 18px;
            color: #333333;
            font-weight: bold;
        }

        /* 為頁面內容添加上邊距，避免被固定的 navbar 擋住 */
        body {
            padding-top: 80px; /* 調整為 navbar 的高度 */
        }
    </style>
</head>
<body>

    <!-- 搜尋框 -->
    <div class="search-block">
        <div class="search-box">
            <input type="text" class="search-bar" placeholder="請輸入關鍵字" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>" form="search-form">
            <div class="search-but" onclick="document.getElementById('search-form').submit();">搜尋</div>
        </div>
    </div>

    <!-- 公告列表 -->
    <div class="main-news">
        <div class="main-news-title">最新消息</div>
        
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="main-news-mess" onclick="window.location.href='post.php?id=<?php echo $row['id']; ?>'">
                    <div class="mess-date"><?php echo $row['date']; ?></div>
                    <div class="mess-title"><?php echo $row['title']; ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div>沒有符合條件的公告。</div>
        <?php endif; ?>
    </div>

    <!-- 搜尋表單 (隱藏) -->
    <form id="search-form" method="GET" style="display:none;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_keyword); ?>">
    </form>

</body>
</html>
