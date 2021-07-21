-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2021-07-21 15:21:04
-- 伺服器版本： 10.4.19-MariaDB
-- PHP 版本： 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫: `ticket_system`
--

-- --------------------------------------------------------

--
-- 資料表結構 `admin_authority`
--

CREATE TABLE `admin_authority` (
  `id` int(11) NOT NULL,
  `page` varchar(255) NOT NULL COMMENT '頁面名稱',
  `page_tw` varchar(255) NOT NULL COMMENT '頁面(中)',
  `user` text NOT NULL COMMENT '可使用頁面角色群組'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `admin_authority`
--

INSERT INTO `admin_authority` (`id`, `page`, `page_tw`, `user`) VALUES
(1, 'Issue List', '回報總覽', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";s:1:\"1\";s:2:\"RD\";s:1:\"1\";s:2:\"PM\";s:1:\"1\";}'),
(2, 'Admin Management', '帳號管理', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";i:0;s:2:\"RD\";i:0;s:2:\"PM\";i:0;}'),
(10, 'Auth Management', '權限管理', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";i:0;s:2:\"RD\";i:0;s:2:\"PM\";i:0;}');

-- --------------------------------------------------------

--
-- 資料表結構 `issue`
--

CREATE TABLE `issue` (
  `issue_id` int(11) NOT NULL COMMENT '問題ID',
  `issue_title` varchar(255) NOT NULL COMMENT '標題',
  `returner` int(11) NOT NULL COMMENT '提交人員',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '提交日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `issue`
--

INSERT INTO `issue` (`issue_id`, `issue_title`, `returner`, `create_time`) VALUES
(1, 'BUG回報', 1, '2021-07-21 16:49:06'),
(2, 'TEST', 2, '2021-07-21 16:49:31'),
(4, 'ttteest', 2, '2021-07-21 16:56:23'),
(5, 'ttteest', 2, '2021-07-21 16:56:43');

-- --------------------------------------------------------

--
-- 資料表結構 `issue_text`
--

CREATE TABLE `issue_text` (
  `text_id` int(11) NOT NULL COMMENT '回報內文ID',
  `issue_id` int(11) NOT NULL COMMENT '對應issue ID',
  `issue_text` text NOT NULL COMMENT '內文',
  `issue_type` int(11) NOT NULL COMMENT '問題種類',
  `issue_operator` int(11) NOT NULL COMMENT '處理者',
  `creater` int(11) NOT NULL COMMENT '建立者',
  `create_time` datetime NOT NULL DEFAULT current_timestamp() COMMENT '建立時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `issue_text`
--

INSERT INTO `issue_text` (`text_id`, `issue_id`, `issue_text`, `issue_type`, `issue_operator`, `creater`, `create_time`) VALUES
(1, 1, 'XXX頁面BUG\nWERWER', 1, 1, 0, '2021-07-21 16:49:06'),
(2, 2, 'aaaa', 1, 3, 0, '2021-07-21 16:49:31'),
(3, 5, 'etetet', 1, 3, 2, '2021-07-21 16:56:43'),
(4, 5, 'HIHI', 5, 2, 0, '2021-07-21 17:13:36');

-- --------------------------------------------------------

--
-- 資料表結構 `issue_type`
--

CREATE TABLE `issue_type` (
  `type_id` int(11) NOT NULL COMMENT '類型ID',
  `type_name` varchar(255) NOT NULL COMMENT '類型名稱',
  `user` text NOT NULL COMMENT '可使用成員'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `issue_type`
--

INSERT INTO `issue_type` (`type_id`, `type_name`, `user`) VALUES
(1, 'BUG', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";i:1;s:2:\"RD\";i:0;s:2:\"PM\";i:0;}'),
(2, '測試項目', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";i:1;s:2:\"RD\";i:0;s:2:\"PM\";i:0;}'),
(3, '新需求', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";i:0;s:2:\"RD\";i:0;s:2:\"PM\";i:1;}'),
(4, '已解決', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";i:0;s:2:\"RD\";i:1;s:2:\"PM\";i:0;}'),
(5, '回覆', 'a:4:{s:5:\"admin\";i:1;s:2:\"QA\";i:1;s:2:\"RD\";i:1;s:2:\"PM\";i:1;}');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '使用者帳號',
  `password` varchar(255) NOT NULL COMMENT '密碼',
  `phone` varchar(255) DEFAULT NULL COMMENT '電話',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp() COMMENT '建立日期',
  `updated_at` datetime DEFAULT current_timestamp() COMMENT '更新日期',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '狀態 0:停用; 1:啟用;',
  `authority` varchar(255) NOT NULL DEFAULT '1' COMMENT '帳號權限角色'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `phone`, `remember_token`, `created_at`, `updated_at`, `status`, `authority`) VALUES
(1, 'admin', '$2y$10$laLzJ/uAizNebjQ.QJjaYuMIzH3tyu.gKUP4l5Dcn5mdk.AQjee4y', NULL, '', '2019-06-11 13:12:34', '2021-04-09 19:30:36', 1, 'admin'),
(2, 'QA01', '$2y$10$u7iNo4AukbhmvzlQ28DqnONa0ByN3aRTTZYzM7kTEQ6P5.UtBjRnO', '0987654321', NULL, '2021-07-17 23:58:17', '2021-07-20 23:21:20', 1, 'QA'),
(3, 'RD01', '$2y$10$.F08JYrat2SuWnQeLWQXAuy5rRk/vAs7fxrv3IdxGORfC.COcrYIm', NULL, NULL, '2021-07-17 23:58:33', '2021-07-20 23:21:15', 1, 'RD'),
(4, 'PM01', '$2y$10$S4Uj1Qrm9eveFk4PYixElOwkHU02vATiMbAJRlJ5r5xp60EhaDi4u', NULL, NULL, '2021-07-17 23:58:43', '2021-07-20 23:21:17', 1, 'PM');

-- --------------------------------------------------------

--
-- 資料表結構 `user_permissions`
--

CREATE TABLE `user_permissions` (
  `id` int(11) NOT NULL COMMENT '流水號',
  `name` varchar(128) NOT NULL COMMENT '權限角色名稱',
  `premission_note` varchar(255) NOT NULL COMMENT '角色權限備註'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 傾印資料表的資料 `user_permissions`
--

INSERT INTO `user_permissions` (`id`, `name`, `premission_note`) VALUES
(1, 'admin', '系統管理員'),
(2, 'QA', '測試人員'),
(3, 'RD', '工程師'),
(4, 'PM', '專案管理員');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `admin_authority`
--
ALTER TABLE `admin_authority`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `issue`
--
ALTER TABLE `issue`
  ADD PRIMARY KEY (`issue_id`);

--
-- 資料表索引 `issue_text`
--
ALTER TABLE `issue_text`
  ADD PRIMARY KEY (`text_id`);

--
-- 資料表索引 `issue_type`
--
ALTER TABLE `issue_type`
  ADD PRIMARY KEY (`type_id`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 資料表索引 `user_permissions`
--
ALTER TABLE `user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `admin_authority`
--
ALTER TABLE `admin_authority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `issue`
--
ALTER TABLE `issue`
  MODIFY `issue_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '問題ID', AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `issue_text`
--
ALTER TABLE `issue_text`
  MODIFY `text_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回報內文ID', AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `issue_type`
--
ALTER TABLE `issue_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '類型ID', AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user_permissions`
--
ALTER TABLE `user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '流水號', AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
