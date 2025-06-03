-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-03 07:28:14
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.1.25

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
  `date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `accounts` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `date`, `created_at`, `accounts`) VALUES
(52, '公告6', '公告6', '2025-06-02 15:06:27', '2025-06-02 13:06:47', 8),
(53, '公告5', '公告5', '2025-06-02 15:07:22', '2025-06-02 13:07:38', 8),
(54, '公告4', '公告4', '2025-06-02 15:09:07', '2025-06-02 13:09:18', 9),
(55, '公告3', '公告3', '2025-06-02 15:09:20', '2025-06-02 13:09:34', 8),
(56, '公告2', '公告2', '2025-06-02 15:09:36', '2025-06-02 13:09:49', 8),
(57, '公告1', '公告1', '2025-06-02 15:09:53', '2025-06-02 13:10:03', 9);

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
(5, 48, 1, '123', '2025-06-01 10:25:30', 'rejected', 'ugiiuyiuy', NULL),
(6, 51, 1, '我覺得不行', '2025-06-03 00:32:33', 'resolved', '誰理你啊', NULL);

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
(34, 48, 2, 'uyj', 8),
(35, 50, 4, '3', 8),
(36, 53, 2, '但是我拒絕', 8),
(37, 52, 5, '我覺得可以', 8),
(38, 51, 2, '申訴測試', 8);

-- --------------------------------------------------------

--
-- 資料表結構 `event`
--

CREATE TABLE `event` (
  `e_id` int(50) NOT NULL,
  `e_title` varchar(100) NOT NULL COMMENT '標題',
  `e_text` varchar(500) NOT NULL COMMENT '內文',
  `e_time` datetime NOT NULL COMMENT '日期',
  `accounts` int(50) NOT NULL COMMENT '提案人帳號',
  `e_type` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `event`
--

INSERT INTO `event` (`e_id`, `e_title`, `e_text`, `e_time`, `accounts`, `e_type`) VALUES
(48, '123', '123', '2025-06-01 10:24:52', 1, 1),
(49, '234', '234', '2025-06-01 10:29:35', 1, 0),
(50, '3', '3', '2025-06-01 22:03:58', 1, 1),
(51, '測試', '測試', '2025-06-02 21:45:00', 1, 1),
(52, 'test2', '2', '2025-06-02 22:09:56', 2, 1),
(53, 'test3', '3', '2025-06-02 22:10:02', 2, 1),
(54, '123', '123', '2025-06-03 02:17:27', 1, 0),
(55, 'erg', 'gg', '2025-06-03 02:20:14', 1, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `fundraising`
--

CREATE TABLE `fundraising` (
  `f_id` int(50) NOT NULL COMMENT '時間序位',
  `e_id` int(50) NOT NULL COMMENT '引用資訊',
  `f_title` varchar(100) NOT NULL,
  `f_content` varchar(100) NOT NULL,
  `f_now` int(100) NOT NULL,
  `f_goal` int(100) NOT NULL,
  `f_date` datetime NOT NULL,
  `f_cate` int(100) NOT NULL,
  `f_type` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `fundraising`
--

INSERT INTO `fundraising` (`f_id`, `e_id`, `f_title`, `f_content`, `f_now`, `f_goal`, `f_date`, `f_cate`, `f_type`) VALUES
(10, 52, '測試', '這是修改後的內文', 0, 123456, '2025-07-04 00:00:00', 2, 1);

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
  `l_time` datetime NOT NULL COMMENT '捐贈時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, '1@1', '$2y$10$HaiZs4ZxsS2QCEBehOX/JORW0PhoZV2Q1ygXP52qamf4AWT7wtJ/G', '1', '1'),
(2, '2@2', '$2y$10$0XLvGsGAnd9PA0UfjFCkBOrDd95DBcVfxXf3.2j0DeD1n9llaaRDi', '1', '2'),
(8, '8@8', '$2y$10$OFar6SNf1aypk2X0KBR.XOadLXzKjlp.ZJLUpqZO0lYyv/zKmwwoK', '2', '8'),
(9, '9@9', '$2y$10$StqqIjLkaMkEG3NrvtwzdOHJw704xVwB/I8A0AJ0IGB/smv/d2n/2', '2', '9');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `appeals`
--
ALTER TABLE `appeals`
  MODIFY `appeal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `audit`
--
ALTER TABLE `audit`
  MODIFY `a_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `event`
--
ALTER TABLE `event`
  MODIFY `e_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `fundraising`
--
ALTER TABLE `fundraising`
  MODIFY `f_id` int(50) NOT NULL AUTO_INCREMENT COMMENT '時間序位', AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `log`
--
ALTER TABLE `log`
  MODIFY `l_id` int(100) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=19;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `vote`
--
ALTER TABLE `vote`
  MODIFY `v_id` int(100) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=28;

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
