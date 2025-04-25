-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-04-25 17:43:44
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `sa`
--

-- --------------------------------------------------------

--
-- 資料表結構 `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `event`
--

CREATE TABLE `event` (
  `e_id` int(255) NOT NULL,
  `e_title` varchar(100) NOT NULL COMMENT '標題',
  `e_text` varchar(500) NOT NULL COMMENT '內文',
  `e_time` date NOT NULL COMMENT '日期',
  `e_picture` blob NOT NULL COMMENT '圖片',
  `accounts` int(50) NOT NULL COMMENT '提案人帳號'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `event`
--

INSERT INTO `event` (`e_id`, `e_title`, `e_text`, `e_time`, `e_picture`, `accounts`) VALUES
(1, '1', '1', '2025-04-21', '', 1),
(2, '22222222222222222222', '2222222222222222222222222222222222222222', '2025-04-21', '', 1),
(3, '3', '3', '2025-04-21', '', 1);

-- --------------------------------------------------------

--
-- 資料表結構 `fundraising`
--

CREATE TABLE `fundraising` (
  `f_id` int(50) NOT NULL COMMENT '時間序位',
  `e_id` int(50) NOT NULL COMMENT '引用資訊',
  `f_bar` int(50) NOT NULL COMMENT '顯示進程(階段)',
  `f_doc` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '企劃書(支援PDF、圖片)' CHECK (json_valid(`f_doc`)),
  `f_contect` varchar(50) NOT NULL COMMENT '聯繫校方',
  `f_line` date NOT NULL COMMENT '處理期限',
  `f_depart` varchar(20) NOT NULL COMMENT '處理單位'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `history`
--

CREATE TABLE `history` (
  `h_id` int(100) NOT NULL COMMENT '流水號',
  `f_id` int(50) NOT NULL COMMENT '連接募資公告',
  `h_state` int(100) NOT NULL COMMENT '結案原因(完成/駁回)',
  `h_time` date NOT NULL COMMENT '專案開始/結束',
  `h_intime` date NOT NULL COMMENT '錄入時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `log`
--

CREATE TABLE `log` (
  `l_id` int(100) NOT NULL COMMENT '流水號',
  `f_id` int(50) NOT NULL COMMENT '募資編號',
  `l_name` varchar(10) NOT NULL COMMENT '募款者姓名',
  `l_item` varchar(50) NOT NULL COMMENT '捐贈物品/金錢',
  `l_qua` int(100) NOT NULL COMMENT '捐贈數量',
  `l_time` date NOT NULL COMMENT '捐贈時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `accounts` int(50) NOT NULL COMMENT '帳號',
  `gmail` varchar(50) NOT NULL COMMENT 'gmail',
  `password` varchar(255) NOT NULL COMMENT '密碼',
  `permissions` varchar(10) NOT NULL COMMENT '權限'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`accounts`, `gmail`, `password`, `permissions`) VALUES
(1, '1@1', '$2y$10$2biC42NS2NKu2f1wKuKsZeFO0jn8Sa02mDaxI6cY.zBljL/q2ndNu', ''),
(2, '2@2', '$2y$10$oF1O4j4sXYSxmUuTH/RvWeWIif74upB.QGxGKm7DPCMj2d3MSMV/C', 'vip'),
(3, '3@3', '$2y$10$YYJkErVFqEf74QXwTc2YHe5/X8OLL02AXcqK1hQFvO4yw3.I2sOYy', ''),
(411401234, '1@1', '$2y$10$JXfVpK4M6alBlnJsQjXlyOwgcVP.j0GocOFSp/NZwFLpkWrtaZYNy', '');

-- --------------------------------------------------------

--
-- 資料表結構 `vote`
--

CREATE TABLE `vote` (
  `v_id` int(100) NOT NULL COMMENT '流水號',
  `e_id` int(50) NOT NULL COMMENT '文章編號',
  `v_stu` int(50) NOT NULL COMMENT '學號'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`e_id`),
  ADD KEY `accounts` (`accounts`);

--
-- 資料表索引 `fundraising`
--
ALTER TABLE `fundraising`
  ADD PRIMARY KEY (`f_id`),
  ADD KEY `a_id` (`e_id`);

--
-- 資料表索引 `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`h_id`),
  ADD KEY `f_id` (`f_id`);

--
-- 資料表索引 `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`l_id`),
  ADD KEY `f_id` (`f_id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`accounts`);

--
-- 資料表索引 `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`v_id`),
  ADD KEY `e_id` (`e_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `event`
--
ALTER TABLE `event`
  MODIFY `e_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`accounts`) REFERENCES `user` (`accounts`);

--
-- 資料表的限制式 `fundraising`
--
ALTER TABLE `fundraising`
  ADD CONSTRAINT `fundraising_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `event` (`e_id`);

--
-- 資料表的限制式 `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `fundraising` (`f_id`);

--
-- 資料表的限制式 `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `fundraising` (`f_id`);

--
-- 資料表的限制式 `vote`
--
ALTER TABLE `vote`
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `event` (`e_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
