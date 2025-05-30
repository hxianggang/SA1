-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-05-30 10:02:36
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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `accounts` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `date`, `created_at`, `accounts`) VALUES
(49, 'asd', 'qwe', '2025-05-26', '2025-05-26 01:24:09', 567),
(50, '123', '123', '2025-05-26', '2025-05-26 01:26:34', 567),
(51, '555', '555', '2025-05-30', '2025-05-29 19:05:16', 8);

-- --------------------------------------------------------

--
-- 資料表結構 `appeals`
--

CREATE TABLE `appeals` (
  `appeal_id` int(11) NOT NULL,
  `e_id` int(50) NOT NULL,
  `accounts` int(50) NOT NULL,
  `appeal_text` text NOT NULL,
  `appeal_date` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `reply_text` text DEFAULT NULL,
  `reply_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `appeals`
--

INSERT INTO `appeals` (`appeal_id`, `e_id`, `accounts`, `appeal_text`, `appeal_date`, `status`, `reply_text`, `reply_date`) VALUES
(1, 41, 1, '1j7', '2025-05-30 13:39:23', 'rejected', '1', '2025-05-30 14:45:32'),
(2, 42, 2, '88', '2025-05-30 14:52:12', 'pending', NULL, NULL),
(3, 38, 1, '1', '2025-05-30 15:12:30', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `audit`
--

CREATE TABLE `audit` (
  `a_id` int(11) NOT NULL,
  `e_id` int(50) NOT NULL,
  `situation` int(10) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `a_acc` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `audit`
--

INSERT INTO `audit` (`a_id`, `e_id`, `situation`, `reason`, `a_acc`) VALUES
(26, 35, 5, '567', 567),
(28, 40, 4, '1', 8),
(29, 38, 2, '不要', 8),
(30, 41, 2, '不要', 8),
(31, 42, 2, '88', 8);

-- --------------------------------------------------------

--
-- 資料表結構 `event`
--

CREATE TABLE `event` (
  `e_id` int(50) NOT NULL,
  `e_title` varchar(100) NOT NULL COMMENT '標題',
  `e_text` varchar(500) NOT NULL COMMENT '內文',
  `e_time` date NOT NULL COMMENT '日期',
  `e_picture` blob NOT NULL COMMENT '圖片',
  `accounts` int(50) NOT NULL COMMENT '提案人帳號',
  `e_type` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `event`
--

INSERT INTO `event` (`e_id`, `e_title`, `e_text`, `e_time`, `e_picture`, `accounts`, `e_type`) VALUES
(33, 'ruby醬', 'はい', '2025-01-01', '', 123, 1),
(35, '1', '123', '2025-05-13', '', 123, 1),
(36, '2', '234', '2025-05-13', '', 234, 0),
(37, '123', '123', '2025-05-13', '', 123, 0),
(38, 'asdlkjdslkasdjlsad', 'adgf a gdf', '2025-05-26', '', 123, 1),
(39, '可愛小貓咪', 'weewf', '2025-05-26', '', 123, 0),
(40, '1', '1', '2025-05-29', '', 1, 1),
(41, '22', '22', '2025-05-29', '', 1, 1),
(42, '33', '333', '2025-05-30', '', 2, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `fundraising`
--

CREATE TABLE `fundraising` (
  `f_id` int(50) NOT NULL COMMENT '時間序位',
  `e_id` int(50) NOT NULL COMMENT '引用資訊',
  `f_title` varchar(100) NOT NULL,
  `f_content` varchar(100) NOT NULL,
  `f_file` varchar(100) NOT NULL,
  `f_now` int(100) NOT NULL,
  `f_goal` int(100) NOT NULL,
  `f_date` date NOT NULL,
  `f_cate` int(100) NOT NULL,
  `f_type` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `fundraising`
--

INSERT INTO `fundraising` (`f_id`, `e_id`, `f_title`, `f_content`, `f_file`, `f_now`, `f_goal`, `f_date`, `f_cate`, `f_type`) VALUES
(8, 35, '測試', '測試用', '', 100000, 100000, '2025-05-31', 2, 2),
(9, 35, '測試2', 'test', '', 1000, 1000000, '2025-06-07', 2, 1);

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
  `l_qua` int(100) NOT NULL COMMENT '金額',
  `l_time` date NOT NULL COMMENT '捐贈時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `log`
--

INSERT INTO `log` (`l_id`, `f_id`, `l_name`, `l_qua`, `l_time`) VALUES
(11, 8, 'AQ', 100000, '2025-05-26'),
(12, 9, 'AQ', 1000, '2025-05-26');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `accounts` int(50) NOT NULL COMMENT '帳號',
  `gmail` varchar(50) NOT NULL COMMENT 'gmail',
  `password` varchar(255) NOT NULL COMMENT '密碼',
  `permissions` varchar(10) NOT NULL COMMENT '權限',
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`accounts`, `gmail`, `password`, `permissions`, `name`) VALUES
(1, '1@1', '$2y$10$yfjI3bAAMTUpnV/8NGGul.zFzWjzeHyRSCxNh.maa5D.KUqIanHmu', '1', '1'),
(2, '2@2', '$2y$10$0XLvGsGAnd9PA0UfjFCkBOrDd95DBcVfxXf3.2j0DeD1n9llaaRDi', '1', '2'),
(8, '8@8', '$2y$10$OFar6SNf1aypk2X0KBR.XOadLXzKjlp.ZJLUpqZO0lYyv/zKmwwoK', '2', '8'),
(123, '123', '123', '1', '爆豪勝己'),
(234, '234', '234', '1', '綠谷出久'),
(456, '456', '456', '2', '相澤消太'),
(567, '567', '567', '2', '歐爾麥特');

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
-- 傾印資料表的資料 `vote`
--

INSERT INTO `vote` (`v_id`, `e_id`, `v_stu`) VALUES
(16, 36, 234),
(17, 37, 123),
(18, 40, 1),
(19, 41, 1),
(20, 36, 1),
(21, 38, 1),
(22, 42, 2),
(23, 41, 2),
(24, 35, 2);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts` (`accounts`);

--
-- 資料表索引 `appeals`
--
ALTER TABLE `appeals`
  ADD PRIMARY KEY (`appeal_id`),
  ADD KEY `fk_appeals_event` (`e_id`),
  ADD KEY `fk_appeals_user` (`accounts`);

--
-- 資料表索引 `audit`
--
ALTER TABLE `audit`
  ADD PRIMARY KEY (`a_id`),
  ADD KEY `a_acc` (`a_acc`),
  ADD KEY `e_id` (`e_id`);

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
  ADD KEY `e_id` (`e_id`),
  ADD KEY `v_stu` (`v_stu`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `appeals`
--
ALTER TABLE `appeals`
  MODIFY `appeal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `audit`
--
ALTER TABLE `audit`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `event`
--
ALTER TABLE `event`
  MODIFY `e_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `fundraising`
--
ALTER TABLE `fundraising`
  MODIFY `f_id` int(50) NOT NULL AUTO_INCREMENT COMMENT '時間序位', AUTO_INCREMENT=10;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log`
--
ALTER TABLE `log`
  MODIFY `l_id` int(100) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=16;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `vote`
--
ALTER TABLE `vote`
  MODIFY `v_id` int(100) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=25;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`accounts`) REFERENCES `user` (`accounts`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `appeals`
--
ALTER TABLE `appeals`
  ADD CONSTRAINT `fk_appeals_event` FOREIGN KEY (`e_id`) REFERENCES `event` (`e_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_appeals_user` FOREIGN KEY (`accounts`) REFERENCES `user` (`accounts`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `audit`
--
ALTER TABLE `audit`
  ADD CONSTRAINT `audit_ibfk_1` FOREIGN KEY (`a_acc`) REFERENCES `user` (`accounts`),
  ADD CONSTRAINT `audit_ibfk_2` FOREIGN KEY (`e_id`) REFERENCES `event` (`e_id`);

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
  ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`e_id`) REFERENCES `event` (`e_id`),
  ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`v_stu`) REFERENCES `user` (`accounts`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
