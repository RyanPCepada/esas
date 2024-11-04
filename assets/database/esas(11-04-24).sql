-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 04, 2024 at 04:53 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esas`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_activity_logs`
--

CREATE TABLE `tbl_activity_logs` (
  `activity_id` int(20) NOT NULL,
  `activity` text NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin_id` int(20) DEFAULT NULL,
  `moderator_id` int(20) DEFAULT NULL,
  `student_id` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_activity_logs`
--

INSERT INTO `tbl_activity_logs` (`activity_id`, `activity`, `dateAdded`, `admin_id`, `moderator_id`, `student_id`) VALUES
(1, 'You logged in to your account', '2024-10-14 01:33:50', NULL, 22230001, NULL),
(2, 'You logged out of your account', '2024-10-14 01:37:39', NULL, 22230001, NULL),
(3, 'You logged in to your account', '2024-10-14 01:41:43', NULL, 22230001, NULL),
(4, 'You updated NBSC Quick Response Teams information', '2024-10-13 19:41:54', NULL, 22230001, NULL),
(5, 'You updated NBSC Quick Response Team information', '2024-10-13 19:42:11', NULL, 22230001, NULL),
(6, 'You updated Mountaineering Societys information', '2024-10-13 19:42:15', NULL, 22230001, NULL),
(7, 'You updated Mountaineering Society information', '2024-10-13 19:42:20', NULL, 22230001, NULL),
(8, 'You changed your password', '2024-10-13 19:44:39', NULL, 22230001, NULL),
(9, 'You changed your password', '2024-10-13 19:44:46', NULL, 22230001, NULL),
(10, 'You updated your profile', '2024-10-13 19:47:10', NULL, 22230001, NULL),
(11, 'You updated your profile', '2024-10-13 19:47:20', NULL, 22230001, NULL),
(12, 'You created a post in NBSC Quick Response Team', '2024-10-14 01:56:26', NULL, 22230001, NULL),
(13, 'You added an event for NBSC Quick Response Team', '2024-10-14 02:01:38', NULL, 22230001, NULL),
(14, 'You added an event for NBSC Quick Response Team', '2024-10-14 02:04:08', NULL, 22230001, NULL),
(15, 'You added an event for NBSC Quick Response Team', '2024-10-14 02:04:35', NULL, 22230001, NULL),
(22, 'You logged out of your account', '2024-10-14 02:36:17', NULL, 22230001, NULL),
(23, 'You logged in to your account', '2024-10-14 02:36:21', NULL, 22230001, NULL),
(24, 'You disapproved Andrie Jose Ipulan Macas\'s application in NBSC Quick Response Team', '2024-10-14 02:37:56', NULL, NULL, 20201270),
(27, 'You logged out of your account', '2024-10-14 02:42:50', NULL, 22230001, NULL),
(28, 'You logged in to your account', '2024-10-14 02:42:53', NULL, 24250010, NULL),
(34, 'You logged out of your account', '2024-10-14 02:52:42', NULL, 24250010, NULL),
(35, 'You logged in to your account', '2024-10-14 02:52:45', NULL, 22230001, NULL),
(38, 'You logged in to your account', '2024-10-14 02:57:17', NULL, 22230001, NULL),
(39, 'You logged out of your account', '2024-10-14 03:01:43', NULL, 22230001, NULL),
(77, 'You updated NBSC Quick Response Team information', '2024-10-14 05:46:50', NULL, 22230001, NULL),
(78, 'You updated NBSC Quick Response Team information', '2024-10-14 05:47:44', NULL, 22230001, NULL),
(79, 'You updated NBSC Quick Response Team information', '2024-10-14 05:49:33', NULL, 22230001, NULL),
(80, 'You updated NBSC Quick Response Team information', '2024-10-14 05:50:50', NULL, 22230001, NULL),
(81, 'You updated NBSC Quick Response Team information', '2024-10-14 05:51:28', NULL, 22230001, NULL),
(84, 'You logged in to your account', '2024-10-14 06:00:18', NULL, 22230003, NULL),
(86, 'You logged in to your account', '2024-10-14 06:16:43', NULL, 24250006, NULL),
(88, 'You logged in to your account', '2024-10-14 06:23:48', NULL, 24250003, NULL),
(89, 'You logged out of your account', '2024-10-14 06:23:54', NULL, 24250003, NULL),
(90, 'You logged in to your account', '2024-10-14 06:24:16', NULL, 24250002, NULL),
(92, 'You logged in to your account', '2024-10-14 06:33:29', NULL, 24250002, NULL),
(95, 'You assigned Cliff Amadeus Evangelio as a moderator of NBSC Band Sound Space', '2024-10-14 06:49:07', NULL, 22230001, NULL),
(97, 'You approved Allan Cenia\'s club request', '2024-10-14 06:55:36', NULL, NULL, 20211860),
(98, 'You approved Allan Cenia\'s club request', '2024-10-14 06:56:43', NULL, NULL, 20211860),
(100, 'You logged in to your account', '2024-10-14 07:01:30', NULL, 24250008, NULL),
(102, 'You logged in to your account', '2024-10-14 07:02:25', NULL, 24250008, NULL),
(103, 'You created a post in NBSC Dance Troup', '2024-10-14 07:02:41', NULL, 24250008, NULL),
(104, 'You logged in to your account', '2024-10-14 07:27:52', NULL, 24250006, NULL),
(105, 'You logged in to your account', '2024-10-14 07:39:37', NULL, 24250006, NULL),
(114, 'You logged in to your account', '2024-10-14 08:57:08', NULL, 23240002, NULL),
(115, 'You logged out of your account', '2024-10-14 08:57:21', NULL, 23240002, NULL),
(116, 'You logged in to your account', '2024-10-14 08:57:35', NULL, 23240002, NULL),
(118, 'You added an event for Muslim Student\'s Society', '2024-10-14 09:02:25', NULL, 23240002, NULL),
(120, 'You assigned Cliff Amadeus Evangelio as a moderator of NBSC Band Sound Space', '2024-10-14 10:08:56', NULL, 22230001, NULL),
(162, 'You logged in to your account', '2024-10-14 12:00:53', NULL, 22230001, NULL),
(163, 'You logged out of your account', '2024-10-14 12:07:35', NULL, 22230001, NULL),
(164, 'You logged in to your account', '2024-10-15 01:23:24', NULL, 22230001, NULL),
(166, 'You logged out of your account', '2024-10-15 01:33:49', NULL, 22230001, NULL),
(167, 'You logged in to your account', '2024-10-15 01:35:45', NULL, 22230001, NULL),
(168, 'You created a post in NBSC Quick Response Team', '2024-10-15 01:37:17', NULL, 22230001, NULL),
(169, 'You added an event for NBSC Quick Response Team', '2024-10-15 01:39:33', NULL, 22230001, NULL),
(170, 'You logged in to your account', '2024-10-15 01:44:51', NULL, 22230001, NULL),
(171, 'You created a post in NBSC Quick Response Team', '2024-10-15 01:45:03', NULL, 22230001, NULL),
(172, 'You created a post in NBSC Quick Response Team', '2024-10-15 01:45:38', NULL, 22230001, NULL),
(185, 'You updated your profile', '2024-10-14 20:01:02', NULL, 22230001, NULL),
(186, 'You changed your password', '2024-10-14 20:01:30', NULL, 22230001, NULL),
(187, 'You changed your password', '2024-10-14 20:01:37', NULL, 22230001, NULL),
(188, 'You updated your profile', '2024-10-14 20:02:39', NULL, 22230001, NULL),
(189, 'You logged in to your account', '2024-10-15 02:20:11', NULL, 22230001, NULL),
(190, 'You updated your profile', '2024-10-14 20:20:33', NULL, 22230001, NULL),
(191, 'You updated your profile', '2024-10-14 20:20:51', NULL, 22230001, NULL),
(192, 'You updated your profile', '2024-10-14 20:21:07', NULL, 22230001, NULL),
(193, 'You logged in to your account', '2024-10-17 08:48:31', NULL, 22230001, NULL),
(194, 'You created a post in NBSC Quick Response Team', '2024-10-17 08:48:44', NULL, 22230001, NULL),
(195, 'You created a post in NBSC Quick Response Team', '2024-10-17 08:56:02', NULL, 22230001, NULL),
(196, 'You created a post in NBSC Quick Response Team', '2024-10-17 08:56:23', NULL, 22230001, NULL),
(197, 'You created a post in NBSC Quick Response Team', '2024-10-17 09:26:18', NULL, 22230001, NULL),
(198, 'You created a post in NBSC Quick Response Team', '2024-10-17 09:30:08', NULL, 22230001, NULL),
(199, 'You logged out of your account', '2024-10-17 09:34:05', NULL, 22230001, NULL),
(200, 'You logged in to your account', '2024-10-17 09:34:08', NULL, 24250010, NULL),
(201, 'You created a post in Infotech Club', '2024-10-17 09:34:13', NULL, 24250010, NULL),
(202, 'You logged in to your account', '2024-10-17 10:51:20', NULL, 24250010, NULL),
(203, 'You created a post in Infotech Club', '2024-10-17 10:51:26', NULL, 24250010, NULL),
(204, 'You created a post in Infotech Club', '2024-10-17 10:51:50', NULL, 24250010, NULL),
(205, 'You created a post in Infotech Club', '2024-10-17 10:53:45', NULL, 24250010, NULL),
(206, 'You created a post in Infotech Club', '2024-10-17 11:02:58', NULL, 24250010, NULL),
(207, 'You created a post in Infotech Club', '2024-10-17 11:06:00', NULL, 24250010, NULL),
(208, 'You created a post in Infotech Club', '2024-10-17 11:06:20', NULL, 24250010, NULL),
(209, 'You created a post in Infotech Club', '2024-10-17 11:26:54', NULL, 24250010, NULL),
(210, 'You logged in to your account', '2024-10-17 12:50:14', NULL, 22230001, NULL),
(220, 'You logged in to your account', '2024-10-18 03:29:13', NULL, 22230001, NULL),
(221, 'You logged in to your account', '2024-10-18 09:56:02', NULL, 22230001, NULL),
(222, 'You updated your profile', '2024-10-18 05:52:46', NULL, 22230001, NULL),
(223, 'You updated your profile', '2024-10-18 05:53:54', NULL, 22230001, NULL),
(224, 'You updated your profile', '2024-10-18 05:55:42', NULL, 22230001, NULL),
(225, 'You updated your profile', '2024-10-18 05:56:17', NULL, 22230001, NULL),
(227, 'You created a post in NBSC Quick Response Team', '2024-10-18 12:49:14', NULL, 22230001, NULL),
(228, 'You created a post in NBSC Quick Response Team', '2024-10-18 12:50:40', NULL, 22230001, NULL),
(229, 'You created a post in Mountaineering Society', '2024-10-18 12:50:58', NULL, 22230001, NULL),
(231, 'You logged out of your account', '2024-10-18 14:13:13', NULL, 22230001, NULL),
(232, 'You logged in to your account', '2024-10-18 14:13:16', NULL, 24250010, NULL),
(233, 'You updated your profile', '2024-10-18 08:13:49', NULL, 24250010, NULL),
(234, 'You updated your profile', '2024-10-18 08:14:05', NULL, 24250010, NULL),
(235, 'You updated Infotech Clubz information', '2024-10-18 08:14:10', NULL, 24250010, NULL),
(236, 'You updated Infotech Club information', '2024-10-18 08:14:29', NULL, 24250010, NULL),
(237, 'You updated the officers for the club', '2024-10-18 10:25:49', NULL, 24250010, NULL),
(238, 'You updated the officers for the club', '2024-10-18 10:25:56', NULL, 24250010, NULL),
(239, 'You updated the officers for the club', '2024-10-18 10:31:13', NULL, 24250010, NULL),
(240, 'You updated the officers for the club', '2024-10-18 10:33:05', NULL, 24250010, NULL),
(241, 'You updated the officers for the club', '2024-10-18 10:35:04', NULL, 24250010, NULL),
(242, 'You updated the officers for the club', '2024-10-18 10:39:47', NULL, 24250010, NULL),
(243, 'You updated the officers for the club', '2024-10-18 10:40:09', NULL, 24250010, NULL),
(244, 'You updated the officers for the club', '2024-10-18 10:40:26', NULL, 24250010, NULL),
(245, 'You updated the officers for the club', '2024-10-18 10:40:38', NULL, 24250010, NULL),
(246, 'You updated the officers for the club', '2024-10-18 10:42:13', NULL, 24250010, NULL),
(247, 'You updated the officers for the club', '2024-10-18 10:42:23', NULL, 24250010, NULL),
(248, 'You updated the officers for the club', '2024-10-18 10:42:32', NULL, 24250010, NULL),
(249, 'You updated the officers for the club', '2024-10-18 10:51:11', NULL, 24250010, NULL),
(250, 'You updated the officers for the club', '2024-10-18 10:51:25', NULL, 24250010, NULL),
(251, 'You updated the officers for the club', '2024-10-18 10:52:09', NULL, 24250010, NULL),
(252, 'You updated the officers for the club', '2024-10-18 10:56:50', NULL, 24250010, NULL),
(253, 'You logged out of your account', '2024-10-18 16:56:59', NULL, 24250010, NULL),
(254, 'You logged in to your account', '2024-10-18 16:57:02', NULL, 22230001, NULL),
(255, 'You logged out of your account', '2024-10-18 16:58:43', NULL, 22230001, NULL),
(256, 'You logged in to your account', '2024-10-18 16:58:46', NULL, 22230001, NULL),
(257, 'You logged out of your account', '2024-10-18 16:59:01', NULL, 22230001, NULL),
(258, 'You logged in to your account', '2024-10-18 16:59:03', NULL, 24250010, NULL),
(259, 'You logged in to your account', '2024-10-19 01:35:57', NULL, 24250010, NULL),
(260, 'You logged out of your account', '2024-10-19 01:40:48', NULL, 24250010, NULL),
(261, 'You logged in to your account', '2024-10-19 01:40:51', NULL, 22230001, NULL),
(267, 'You updated the club officers for Mountaineering Society', '2024-10-18 20:06:50', NULL, 22230001, NULL),
(268, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 20:07:45', NULL, 22230001, NULL),
(269, 'You updated the club officers for Mountaineering Society', '2024-10-18 20:08:49', NULL, 22230001, NULL),
(270, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 20:09:05', NULL, 22230001, NULL),
(271, 'You updated the club officers for Mountaineering Society', '2024-10-18 20:09:33', NULL, 22230001, NULL),
(272, 'You updated the club officers for Mountaineering Society', '2024-10-18 20:41:22', NULL, 22230001, NULL),
(274, 'You updated the club officers for Mountaineering Society', '2024-10-18 20:50:37', NULL, 22230001, NULL),
(275, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 20:51:15', NULL, 22230001, NULL),
(276, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 21:25:16', NULL, 22230001, NULL),
(277, 'You updated the club officers for Mountaineering Society', '2024-10-18 21:25:25', NULL, 22230001, NULL),
(278, 'You updated the club officers for Mountaineering Society', '2024-10-18 21:25:40', NULL, 22230001, NULL),
(279, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 21:25:46', NULL, 22230001, NULL),
(280, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 21:26:03', NULL, 22230001, NULL),
(281, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 21:27:32', NULL, 22230001, NULL),
(282, 'You updated the club officers for NBSC Quick Response Team', '2024-10-18 21:30:20', NULL, 22230001, NULL),
(283, 'You updated the club officers for Mountaineering Society', '2024-10-18 21:30:40', NULL, 22230001, NULL),
(284, 'You logged out of your account', '2024-10-19 03:31:39', NULL, 22230001, NULL),
(285, 'You logged in to your account', '2024-10-19 03:31:42', NULL, 24250010, NULL),
(286, 'You updated the club officers for Infotech Club', '2024-10-18 21:31:53', NULL, 24250010, NULL),
(287, 'You logged out of your account', '2024-10-19 03:34:18', NULL, 24250010, NULL),
(288, 'You logged in to your account', '2024-10-19 03:34:21', NULL, 24250010, NULL),
(321, 'You added an event for NBSC Quick Response Team', '2024-10-19 09:51:14', NULL, 22230001, NULL),
(323, 'You added an event for NBSC Quick Response Team', '2024-10-19 10:04:05', NULL, 22230001, NULL),
(328, 'You created a post in NBSC Quick Response Team', '2024-10-19 10:24:30', NULL, 22230001, NULL),
(339, 'You updated the event \'Earthquake Drill\' information in NBSC Quick Response Team', '2024-10-19 11:07:20', NULL, 22230001, NULL),
(340, 'You edited your post \'Welcome to the NBSC Mountaineering Society! 🏞️ Hello Adventure...\' in NBSC Quick Response Team', '2024-10-19 11:12:10', NULL, 22230001, NULL),
(341, 'You edited your post \'!Welcome to the NBSC Mountaineering Society! 🏞️ Hello Adventur...\' in NBSC Quick Response Team', '2024-10-19 11:12:43', NULL, 22230001, NULL),
(342, 'You edited your post \'sample\' in NBSC Quick Response Team', '2024-10-19 11:18:19', NULL, 22230001, NULL),
(343, 'You created a post in NBSC Quick Response Team', '2024-10-19 11:19:04', NULL, 22230001, NULL),
(344, 'You logged in to your account', '2024-10-19 11:20:25', NULL, 22230001, NULL),
(347, 'You approved Andrie Jose Ipulan Macas\'s application in NBSC Quick Response Team', '2024-10-19 11:30:22', NULL, 22230001, NULL),
(349, 'You updated Ryan Cepada status into inactive', '2024-10-19 11:37:46', NULL, 22230001, NULL),
(350, 'You updated Ryan Cepada status into active', '2024-10-19 11:38:15', NULL, 22230001, NULL),
(388, 'You logged in to your account', '2024-10-19 13:09:33', NULL, 22230001, NULL),
(389, 'You logged out of your account', '2024-10-19 13:09:37', NULL, 22230001, NULL),
(390, 'You logged in to your account', '2024-10-19 13:18:20', NULL, NULL, 111),
(391, 'You logged in to your account', '2024-10-19 13:19:06', NULL, 23240002, NULL),
(392, 'You approved hey\'s departure request in Muslim Student\'s Society', '2024-10-19 13:19:13', NULL, 23240002, NULL),
(393, 'You submitted an application to join NBSC Quick Response Team', '2024-10-19 13:20:08', NULL, NULL, 111),
(394, 'You logged out of your account', '2024-10-19 13:22:50', NULL, NULL, 111),
(396, 'You logged in to your account', '2024-10-19 13:34:25', NULL, 22230001, NULL),
(397, 'You deleted your post \'hiiiiiii\' in NBSC Quick Response Team', '2024-10-19 13:34:57', NULL, 22230001, NULL),
(398, 'You created a post in NBSC Quick Response Team', '2024-10-19 13:35:05', NULL, 22230001, NULL),
(400, 'You logged in to your account', '2024-10-19 13:45:34', NULL, 22230001, NULL),
(401, 'You updated NBSC Quick Response Team information', '2024-10-19 07:47:02', NULL, 22230001, NULL),
(402, 'You updated NBSC Quick Response Team information', '2024-10-19 08:39:11', NULL, 22230001, NULL),
(403, 'You updated NBSC Quick Response Team information', '2024-10-19 08:39:30', NULL, 22230001, NULL),
(404, 'You updated NBSC Quick Response Team information', '2024-10-19 08:39:42', NULL, 22230001, NULL),
(405, 'You updated Mountaineering Society information', '2024-10-19 08:54:31', NULL, 22230001, NULL),
(406, 'You updated NBSC Quick Response Team information', '2024-10-19 09:05:52', NULL, 22230001, NULL),
(407, 'You updated NBSC Quick Response Team information', '2024-10-19 09:06:14', NULL, 22230001, NULL),
(426, 'You updated Mountaineering Society information', '2024-10-19 10:24:25', NULL, 22230001, NULL),
(427, 'You updated Mountaineering Society information', '2024-10-19 10:24:33', NULL, 22230001, NULL),
(428, 'You updated Mountaineering Society information', '2024-10-19 10:24:38', NULL, 22230001, NULL),
(431, 'You updated the club officers for Mountaineering Society', '2024-10-19 10:33:48', NULL, 22230001, NULL),
(432, 'You updated the club officers for Mountaineering Society', '2024-10-19 10:33:51', NULL, 22230001, NULL),
(439, 'You updated NBSC Quick Response Team information', '2024-10-19 11:48:51', NULL, 22230001, NULL),
(440, 'You updated NBSC Quick Response Team information', '2024-10-19 11:49:00', NULL, 22230001, NULL),
(441, 'You updated Mountaineering Society information', '2024-10-19 11:49:54', NULL, 22230001, NULL),
(442, 'You updated Mountaineering Society information', '2024-10-19 11:50:00', NULL, 22230001, NULL),
(443, 'You updated Mountaineering Society information', '2024-10-19 11:50:18', NULL, 22230001, NULL),
(444, 'You updated Mountaineering Society information', '2024-10-19 11:50:36', NULL, 22230001, NULL),
(445, 'You updated NBSC Quick Response Team information', '2024-10-19 11:50:41', NULL, 22230001, NULL),
(446, 'You updated NBSC Quick Response Team information', '2024-10-19 11:51:27', NULL, 22230001, NULL),
(447, 'You updated Mountaineering Society information', '2024-10-19 12:00:10', NULL, 22230001, NULL),
(448, 'You updated Mountaineering Society information', '2024-10-19 12:00:41', NULL, 22230001, NULL),
(449, 'You updated NBSC Quick Response Team information', '2024-10-19 12:00:46', NULL, 22230001, NULL),
(450, 'You updated the club officers for NBSC Quick Response Team', '2024-10-19 12:09:28', NULL, 22230001, NULL),
(451, 'You updated the club officers for NBSC Quick Response Team', '2024-10-19 12:09:53', NULL, 22230001, NULL),
(452, 'You updated the club officers for NBSC Quick Response Team', '2024-10-19 12:10:08', NULL, 22230001, NULL),
(453, 'You updated the club officers for NBSC Quick Response Team', '2024-10-19 12:15:19', NULL, 22230001, NULL),
(454, 'You updated the club officers for NBSC Quick Response Team', '2024-10-19 12:15:28', NULL, 22230001, NULL),
(455, 'You deleted your post \'Good evening!\' in NBSC Quick Response Team', '2024-10-19 18:22:08', NULL, 22230001, NULL),
(456, 'You deleted your post \'sample2\' in NBSC Quick Response Team', '2024-10-19 18:22:13', NULL, 22230001, NULL),
(457, 'You deleted your post \'sample1\' in NBSC Quick Response Team', '2024-10-19 18:22:18', NULL, 22230001, NULL),
(458, 'You deleted your post \'Good evening!\' in NBSC Quick Response Team', '2024-10-19 18:22:22', NULL, 22230001, NULL),
(459, 'You deleted your post \'quick response post 7\' in NBSC Quick Response Team', '2024-10-19 18:22:33', NULL, 22230001, NULL),
(460, 'You deleted your post \'quick response post 6\' in NBSC Quick Response Team', '2024-10-19 18:22:39', NULL, 22230001, NULL),
(461, 'You deleted your post \'quick response post 5\' in NBSC Quick Response Team', '2024-10-19 18:22:48', NULL, 22230001, NULL),
(462, 'You deleted your post \'quick response post 3\' in NBSC Quick Response Team', '2024-10-19 18:22:59', NULL, 22230001, NULL),
(463, 'You deleted your post \'quick response post 2\' in NBSC Quick Response Team', '2024-10-19 18:23:04', NULL, 22230001, NULL),
(464, 'You logged out of your account', '2024-10-19 18:23:18', NULL, 22230001, NULL),
(465, 'You logged in to your account', '2024-10-19 18:23:23', NULL, 22230002, NULL),
(466, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-10-19 18:23:31', NULL, 22230002, NULL),
(467, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-10-19 18:23:50', NULL, 22230002, NULL),
(468, 'You deleted your post \'Hi everyone!\' in NBSC Quick Response Team', '2024-10-19 18:23:54', NULL, 22230002, NULL),
(470, 'You logged out of your account', '2024-10-19 18:25:25', NULL, 22230002, NULL),
(471, 'You logged in to your account', '2024-10-19 18:25:28', NULL, 22230001, NULL),
(474, 'You logged in to your account', '2024-10-19 18:29:52', NULL, 22230002, NULL),
(475, 'You deleted your post \'quick response post 4\' in NBSC Quick Response Team', '2024-10-19 18:29:58', NULL, 22230002, NULL),
(476, 'You created a post in NBSC Quick Response Team', '2024-10-19 18:30:01', NULL, 22230002, NULL),
(530, 'You logged in to your account', '2024-10-20 06:57:53', NULL, 22230001, NULL),
(532, 'You created a post in NBSC Quick Response Team', '2024-10-20 07:01:19', NULL, 22230001, NULL),
(533, 'You created a post in NBSC Quick Response Team', '2024-10-20 07:02:00', NULL, 22230001, NULL),
(534, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-10-20 07:02:25', NULL, 22230001, NULL),
(535, 'You deleted your post \'Good day!\' in NBSC Quick Response Team', '2024-10-20 07:02:30', NULL, 22230001, NULL),
(536, 'You created a post in NBSC Quick Response Team', '2024-10-20 07:02:36', NULL, 22230001, NULL),
(537, 'You deleted your post \'Good day!\' in NBSC Quick Response Team', '2024-10-20 07:03:16', NULL, 22230001, NULL),
(538, 'You created a post in NBSC Quick Response Team', '2024-10-20 07:03:21', NULL, 22230001, NULL),
(539, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-10-20 07:03:41', NULL, 22230001, NULL),
(540, 'You created a post in NBSC Quick Response Team', '2024-10-20 07:03:46', NULL, 22230001, NULL),
(547, 'You logged out of your account', '2024-10-20 11:33:08', NULL, NULL, 20191124),
(548, 'You logged in to your account', '2024-10-20 11:33:26', NULL, NULL, 20191124),
(549, 'You logged out of your account', '2024-10-20 12:01:16', NULL, NULL, 20191124),
(550, 'You logged in to your account', '2024-10-20 12:01:19', NULL, NULL, 111),
(551, 'You logged out of your account', '2024-10-20 12:08:24', NULL, NULL, 111),
(552, 'You logged in to your account', '2024-10-20 12:08:28', NULL, NULL, 20191124),
(553, 'You logged in to your account', '2024-10-20 12:19:32', NULL, 22230001, NULL),
(554, 'You updated NBSC Quick Response Team information', '2024-10-20 06:19:54', NULL, 22230001, NULL),
(555, 'You updated NBSC Quick Response Team information', '2024-10-20 06:20:03', NULL, 22230001, NULL),
(556, 'You updated NBSC Quick Response Team information', '2024-10-20 06:20:19', NULL, 22230001, NULL),
(557, 'You updated NBSC Quick Response Team information', '2024-10-20 06:20:31', NULL, 22230001, NULL),
(558, 'You updated NBSC Quick Response Team information', '2024-10-20 06:21:40', NULL, 22230001, NULL),
(559, 'You updated NBSC Quick Response Team information', '2024-10-20 06:21:59', NULL, 22230001, NULL),
(560, 'You updated NBSC Quick Response Team information', '2024-10-20 06:22:13', NULL, 22230001, NULL),
(561, 'You withdrew your departure request in Infotech Club', '2024-10-20 12:32:19', NULL, NULL, 20191124),
(562, 'You logged out of your account', '2024-10-20 12:54:56', NULL, NULL, 20191124),
(565, 'You logged in to your account', '2024-10-20 12:55:13', NULL, NULL, 20191115),
(566, 'You logged out of your account', '2024-10-20 12:55:49', NULL, NULL, 20191115),
(567, 'You logged in to your account', '2024-10-20 12:55:53', NULL, NULL, 20191115),
(568, 'You logged out of your account', '2024-10-20 12:56:20', NULL, NULL, 20191115),
(572, 'You logged out of your account', '2024-10-20 13:19:33', NULL, NULL, 20201179),
(573, 'You logged in to your account', '2024-10-20 13:19:36', NULL, NULL, 20191124),
(574, 'You logged out of your account', '2024-10-20 14:00:07', NULL, NULL, 20191124),
(575, 'You logged in to your account', '2024-10-20 14:00:12', NULL, NULL, 111),
(576, 'You logged out of your account', '2024-10-20 14:01:45', NULL, NULL, 111),
(577, 'You logged in to your account', '2024-10-20 14:01:48', NULL, NULL, 20191124),
(578, 'You logged in to your account', '2024-10-20 14:08:11', NULL, 22230001, NULL),
(579, 'You logged in to your account', '2024-10-21 11:03:00', NULL, NULL, 20191124),
(580, 'You logged out of your account', '2024-10-21 11:29:21', NULL, NULL, 20191124),
(581, 'You logged in to your account', '2024-10-21 11:29:27', NULL, NULL, 111),
(582, 'You logged out of your account', '2024-10-21 11:32:30', NULL, NULL, 111),
(583, 'You logged in to your account', '2024-10-21 11:32:33', NULL, NULL, 20191124),
(584, 'You logged out of your account', '2024-10-21 11:50:00', NULL, NULL, 20191124),
(585, 'You logged in to your account', '2024-10-21 11:50:04', NULL, NULL, 111),
(586, 'You logged out of your account', '2024-10-21 11:54:20', NULL, NULL, 111),
(587, 'You logged in to your account', '2024-10-21 11:54:23', NULL, NULL, 20191124),
(588, 'You logged out of your account', '2024-10-21 12:13:14', NULL, NULL, 20191124),
(589, 'You logged in to your account', '2024-10-21 12:13:17', NULL, NULL, 111),
(590, 'You logged out of your account', '2024-10-21 13:35:33', NULL, NULL, 111),
(591, 'You logged in to your account', '2024-10-21 13:35:36', NULL, NULL, 20191124),
(592, 'You logged in to your account', '2024-10-23 00:33:30', NULL, NULL, 20191124),
(593, 'You logged in to your account', '2024-10-23 00:45:36', 24251014, NULL, NULL),
(594, 'You added ArsyArts Club to the clubs list', '2024-10-23 01:05:10', 24251014, NULL, NULL),
(595, 'You deleted ArsyArts from the clubs list', '2024-10-23 01:05:23', 24251014, NULL, NULL),
(596, 'You updated NBSC Quick Response Team details', '2024-10-23 01:17:46', 24251014, NULL, NULL),
(597, 'You updated ArsyArts Club details', '2024-10-23 01:18:49', 24251014, NULL, NULL),
(598, 'You updated NBSC Quick Response Team details', '2024-10-23 01:48:47', 24251014, NULL, NULL),
(599, 'You updated NBSC Quick Response Team details', '2024-10-23 01:49:14', 24251014, NULL, NULL),
(600, 'You updated NBSC Quick Response Team details', '2024-10-23 01:54:04', 24251014, NULL, NULL),
(601, 'You updated NBSC Band Sound Space details', '2024-10-23 02:21:03', 24251014, NULL, NULL),
(602, 'You updated MAS-AMICUS details', '2024-10-23 02:22:19', 24251014, NULL, NULL),
(603, 'You updated Muslim Student\'s Society details', '2024-10-23 02:23:23', 24251014, NULL, NULL),
(604, 'You updated Dramatic Society details', '2024-10-23 02:24:31', 24251014, NULL, NULL),
(605, 'You updated NBSC Band Sound Space details', '2024-10-23 02:24:49', 24251014, NULL, NULL),
(606, 'You updated MAS-AMICUS details', '2024-10-23 02:25:29', 24251014, NULL, NULL),
(607, 'You updated Muslim Student\'s Society details', '2024-10-23 02:25:54', 24251014, NULL, NULL),
(608, 'You updated Young Historians Club details', '2024-10-23 02:27:38', 24251014, NULL, NULL),
(609, 'You updated English Club details', '2024-10-23 02:29:36', 24251014, NULL, NULL),
(610, 'You updated Math-Sci Club details', '2024-10-23 02:30:29', 24251014, NULL, NULL),
(611, 'You updated KAMFIL Club details', '2024-10-23 02:31:27', 24251014, NULL, NULL),
(612, 'You updated Mountaineering Society details', '2024-10-23 02:33:26', 24251014, NULL, NULL),
(613, 'You updated Mountaineering Society details', '2024-10-23 02:34:07', 24251014, NULL, NULL),
(614, 'You updated Mountaineering Society details', '2024-10-23 02:34:37', 24251014, NULL, NULL),
(615, 'You updated Debate Club details', '2024-10-23 02:35:26', 24251014, NULL, NULL),
(616, 'You updated Arts Society details', '2024-10-23 02:36:52', 24251014, NULL, NULL),
(617, 'You updated Arts Society details', '2024-10-23 02:37:52', 24251014, NULL, NULL),
(618, 'You updated Indigenous People Society details', '2024-10-23 02:39:05', 24251014, NULL, NULL),
(619, 'You updated Young Catholic Servants of Christ details', '2024-10-23 02:40:14', 24251014, NULL, NULL),
(620, 'You updated Peer Counselor\'s Club details', '2024-10-23 02:41:55', 24251014, NULL, NULL),
(621, 'You updated Sports details', '2024-10-23 02:42:44', 24251014, NULL, NULL),
(622, 'You updated Environmental Club details', '2024-10-23 02:44:52', 24251014, NULL, NULL),
(623, 'You updated NBSC Dance Troup details', '2024-10-23 02:46:54', 24251014, NULL, NULL),
(624, 'You logged in to your account', '2024-10-23 10:20:49', NULL, NULL, 20191124),
(625, 'You logged in to your account', '2024-10-23 12:51:32', NULL, 22230001, NULL),
(626, 'You updated NBSC Quick Response Team details', '2024-10-23 06:58:13', NULL, 22230001, NULL),
(627, 'You updated NBSC Quick Response Team details', '2024-10-23 06:58:35', NULL, 22230001, NULL),
(628, 'You logged in to your account', '2024-10-23 13:18:41', 24251014, NULL, NULL),
(629, 'You added Sample Club to the clubs list', '2024-10-23 13:21:52', 24251014, NULL, NULL),
(630, 'You updated Sample Club description', '2024-10-23 13:22:07', 24251014, NULL, NULL),
(631, 'You updated Sample Clubaaa description', '2024-10-23 13:22:18', 24251014, NULL, NULL),
(632, 'You updated Sample Club information', '2024-10-23 13:24:04', 24251014, NULL, NULL),
(633, 'You deleted Sample Club from the clubs list', '2024-10-23 13:24:16', 24251014, NULL, NULL),
(634, 'You deleted ArsyArts Club from the clubs list', '2024-10-23 13:24:20', 24251014, NULL, NULL),
(635, 'You updated NBSC Quick Response Team description', '2024-10-23 07:27:23', NULL, 22230001, NULL),
(636, 'You updated NBSC Quick Response Team description', '2024-10-23 07:27:36', NULL, 22230001, NULL),
(637, 'You logged in to your account', '2024-10-24 08:54:34', NULL, 22230001, NULL),
(638, 'You approved sample sample sample\'s application in NBSC Quick Response Team', '2024-10-24 09:00:53', NULL, 22230001, NULL),
(639, 'You approved Aya  Alim\'s application in NBSC Quick Response Team', '2024-10-24 09:26:50', NULL, 22230001, NULL),
(640, 'You logged in to your account', '2024-10-24 10:03:25', NULL, 22230001, NULL),
(641, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-24 19:14:12', NULL, 22230001, NULL),
(642, 'You logged in to your account', '2024-10-25 01:46:09', NULL, 22230001, NULL),
(643, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 01:48:19', NULL, 22230001, NULL),
(644, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 01:50:23', NULL, 22230001, NULL),
(645, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 02:13:57', NULL, 22230001, NULL),
(646, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 02:20:15', NULL, 22230001, NULL),
(647, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 02:25:25', NULL, 22230001, NULL),
(648, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 02:25:57', NULL, 22230001, NULL),
(649, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 03:12:53', NULL, 22230001, NULL),
(650, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 03:13:52', NULL, 22230001, NULL),
(651, 'You logged in to your account', '2024-10-25 03:18:12', NULL, NULL, 20191124),
(652, 'You updated NBSC Quick Response Team description', '2024-10-24 22:08:57', NULL, 22230001, NULL),
(653, 'You updated NBSC Quick Response Team description', '2024-10-24 22:09:18', NULL, 22230001, NULL),
(654, 'You updated Mountaineering Society description', '2024-10-24 22:09:30', NULL, 22230001, NULL),
(655, 'You updated Mountaineering Society description', '2024-10-24 22:09:56', NULL, 22230001, NULL),
(656, 'You updated Mountaineering Society description', '2024-10-24 22:10:15', NULL, 22230001, NULL),
(657, 'You logged out of your account', '2024-10-25 04:21:03', NULL, 22230001, NULL),
(658, 'You logged in to your account', '2024-10-25 04:21:06', NULL, 24250010, NULL),
(659, 'You logged out of your account', '2024-10-25 04:21:21', NULL, 24250010, NULL),
(660, 'You logged in to your account', '2024-10-25 04:21:24', NULL, 22230001, NULL),
(661, 'You logged in to your account', '2024-10-25 04:24:17', NULL, NULL, 20191115),
(662, 'You logged out of your account', '2024-10-25 04:24:33', NULL, NULL, 20191115),
(663, 'You logged in to your account', '2024-10-25 04:24:37', NULL, NULL, 20201179),
(664, 'You logged in to your account', '2024-10-25 04:25:29', NULL, 23240001, NULL),
(665, 'You logged out of your account', '2024-10-25 04:28:07', NULL, 23240001, NULL),
(666, 'You logged in to your account', '2024-10-25 04:28:11', NULL, 22230001, NULL),
(667, 'You logged in to your account', '2024-10-25 04:28:54', NULL, NULL, 20191124),
(668, 'You commented in a post in NBSC Quick Response Team', '2024-10-25 04:42:00', NULL, NULL, 20191124),
(669, 'You edited your comment in a post in NBSC Quick Response Team', '2024-10-25 04:42:07', NULL, NULL, 20191124),
(670, 'You deleted your comment in a post in NBSC Quick Response Team', '2024-10-25 04:42:13', NULL, NULL, 20191124),
(671, 'You logged in to your account', '2024-10-25 04:42:27', NULL, 22230001, NULL),
(672, 'You created a post in NBSC Quick Response Team', '2024-10-25 04:42:41', NULL, 22230001, NULL),
(673, 'You edited your post \'helloooo\' in NBSC Quick Response Team', '2024-10-25 04:42:59', NULL, 22230001, NULL),
(674, 'You deleted your post \'h\' in NBSC Quick Response Team', '2024-10-25 04:43:04', NULL, 22230001, NULL),
(675, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 06:07:51', NULL, 22230001, NULL),
(676, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 06:11:19', NULL, 22230001, NULL),
(677, 'You disapproved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 06:13:25', NULL, 22230001, NULL),
(678, 'You created a post in NBSC Quick Response Team', '2024-10-25 06:14:39', NULL, 22230001, NULL),
(679, 'You logged in to your account', '2024-10-25 06:48:27', 24251014, NULL, NULL),
(680, 'You logged in to your account', '2024-10-25 06:53:36', 24251014, NULL, NULL),
(681, 'You approved Ryan Cepada\'s club request', '2024-10-25 06:53:52', 24251014, NULL, 20191124),
(682, 'You logged out of your account', '2024-10-25 06:55:59', NULL, 22230001, NULL),
(683, 'You logged in to your account', '2024-10-25 06:56:02', NULL, 24250010, NULL),
(684, 'You logged in to your account', '2024-10-25 06:57:31', NULL, NULL, 20191124),
(685, 'You withdrew your departure request in Infotech Club', '2024-10-25 06:57:40', NULL, NULL, 20191124),
(686, 'You logged out of your account', '2024-10-25 06:57:48', NULL, NULL, 20191124),
(687, 'You logged in to your account', '2024-10-25 06:57:52', NULL, NULL, 20201179),
(688, 'You logged in to your account', '2024-10-25 06:58:13', NULL, 24250010, NULL),
(689, 'You approved Angela Naive Libay\'s departure request in Infotech Club', '2024-10-25 06:58:23', NULL, 24250010, NULL),
(690, 'You withdrew your departure request in Infotech Club', '2024-10-25 06:58:51', NULL, NULL, 20201179),
(691, 'You logged out of your account', '2024-10-25 06:59:02', NULL, NULL, 20201179),
(692, 'You logged in to your account', '2024-10-25 06:59:05', NULL, NULL, 20191124),
(693, 'You logged in to your account', '2024-10-25 06:59:29', NULL, 24250010, NULL),
(694, 'You approved Ryan Palmares Cepada\'s departure request in Infotech Club', '2024-10-25 06:59:36', NULL, 24250010, NULL),
(695, 'You withdrew your departure request in Infotech Club', '2024-10-25 07:00:04', NULL, NULL, 20191124),
(696, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-10-25 07:06:30', NULL, 24250010, NULL),
(697, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-10-25 07:08:28', NULL, 24250010, NULL),
(698, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-10-25 07:13:54', NULL, 24250010, NULL),
(699, 'You withdrew your departure request in Infotech Club', '2024-10-25 07:15:24', NULL, NULL, 20191124),
(700, 'You logged out of your account', '2024-10-25 07:24:47', NULL, NULL, 20191124),
(701, 'You logged in to your account', '2024-10-25 07:24:57', NULL, NULL, 201911244),
(702, 'You submitted an application to join NBSC Quick Response Team', '2024-10-25 07:25:29', NULL, NULL, 201911244),
(703, 'You logged in to your account', '2024-10-25 07:25:57', NULL, 24250001, NULL),
(704, 'You logged out of your account', '2024-10-25 07:26:04', NULL, 24250001, NULL),
(705, 'You logged in to your account', '2024-10-25 07:26:08', NULL, 22230001, NULL),
(706, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 07:26:41', NULL, 22230001, NULL),
(707, 'You logged in to your account', '2024-10-25 08:17:52', NULL, NULL, 20191124),
(708, 'You logged in to your account', '2024-10-25 10:03:45', NULL, 22230001, NULL),
(709, 'You updated NBSC Quick Response Team description', '2024-10-25 04:28:46', NULL, 22230001, NULL),
(710, 'You updated NBSC Quick Response Team description', '2024-10-25 04:29:00', NULL, 22230001, NULL),
(711, 'You updated the club officers for NBSC Quick Response Team', '2024-10-25 04:29:05', NULL, 22230001, NULL),
(712, 'You updated the club officers for NBSC Quick Response Team', '2024-10-25 04:39:29', NULL, 22230001, NULL),
(713, 'You logged in to your account', '2024-10-25 10:46:35', 24251014, NULL, NULL),
(714, 'You logged in to your account', '2024-10-25 11:44:42', NULL, 22230001, NULL),
(715, 'You logged out of your account', '2024-10-25 12:41:36', NULL, 22230001, NULL),
(716, 'You logged in to your account', '2024-10-25 12:41:40', NULL, 24250010, NULL),
(717, 'You logged out of your account', '2024-10-25 12:41:57', NULL, 24250010, NULL),
(718, 'You logged in to your account', '2024-10-25 12:42:00', NULL, 22230001, NULL),
(719, 'You logged in to your account', '2024-10-25 12:50:17', NULL, NULL, 20201179),
(720, 'You submitted an application to join Infotech Club', '2024-10-25 12:50:49', NULL, NULL, 20201179),
(721, 'You logged out of your account', '2024-10-25 12:51:05', NULL, 22230001, NULL),
(722, 'You logged in to your account', '2024-10-25 12:51:09', NULL, 24250010, NULL),
(723, 'You approved Angela Naive Libay\'s application in Infotech Club', '2024-10-25 12:52:39', NULL, 24250010, NULL),
(724, 'You logged out of your account', '2024-10-25 12:55:40', NULL, 24250010, NULL),
(725, 'You logged in to your account', '2024-10-25 12:55:43', NULL, 22230001, NULL),
(726, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-25 12:56:37', NULL, 22230001, NULL),
(727, 'You logged out of your account', '2024-10-25 13:40:48', NULL, 22230001, NULL),
(728, 'You logged in to your account', '2024-10-25 13:40:51', NULL, 24250010, NULL),
(729, 'You approved Angela Naive Libay\'s application in Infotech Club', '2024-10-25 13:41:10', NULL, 24250010, NULL),
(730, 'You logged in to your account', '2024-10-26 01:52:45', NULL, 22230001, NULL),
(731, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-10-26 01:53:03', NULL, 22230001, NULL),
(732, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-10-26 01:53:09', NULL, 22230001, NULL),
(733, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 03:02:55', NULL, 22230001, NULL),
(734, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 03:24:37', NULL, 22230001, NULL),
(735, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 03:25:43', NULL, 22230001, NULL),
(736, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 03:37:36', NULL, 22230001, NULL),
(737, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 03:44:29', NULL, 22230001, NULL),
(738, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 03:56:56', NULL, 22230001, NULL),
(739, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:17:45', NULL, 22230001, NULL),
(740, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:19:12', NULL, 22230001, NULL),
(741, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:20:08', NULL, 22230001, NULL),
(742, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:21:05', NULL, 22230001, NULL),
(743, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:21:54', NULL, 22230001, NULL),
(744, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:22:31', NULL, 22230001, NULL),
(745, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:23:11', NULL, 22230001, NULL),
(746, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:23:42', NULL, 22230001, NULL),
(747, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:24:22', NULL, 22230001, NULL),
(748, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:32:25', NULL, 22230001, NULL),
(749, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:33:30', NULL, 22230001, NULL),
(750, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:34:57', NULL, 22230001, NULL),
(751, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:35:38', NULL, 22230001, NULL),
(752, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 04:36:24', NULL, 22230001, NULL),
(753, 'You logged in to your account', '2024-10-26 09:41:15', NULL, 22230001, NULL),
(754, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 09:47:17', NULL, 22230001, NULL),
(755, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 09:49:01', NULL, 22230001, NULL),
(756, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 09:49:18', NULL, 22230001, NULL),
(757, 'You logged in to your account', '2024-10-26 10:16:35', NULL, NULL, 20191124),
(758, 'You logged out of your account', '2024-10-26 10:29:06', NULL, NULL, 20191124),
(759, 'You logged in to your account', '2024-10-26 10:29:10', NULL, NULL, 20191124),
(760, 'You logged in to your account', '2024-10-26 10:36:22', NULL, 23240004, NULL),
(761, 'You logged in to your account', '2024-10-26 10:41:39', NULL, NULL, 20191124),
(762, 'You logged out of your account', '2024-10-26 10:42:19', NULL, 23240004, NULL),
(763, 'You logged in to your account', '2024-10-26 10:43:40', NULL, 22230001, NULL),
(764, 'You logged in to your account', '2024-10-26 10:47:20', 24251014, NULL, NULL),
(765, 'You logged in to your account', '2024-10-26 10:54:43', NULL, NULL, 20191124),
(766, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 11:01:30', NULL, 22230001, NULL),
(767, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-26 11:02:58', NULL, 22230001, NULL),
(768, 'You updated Sample Sample status into departed', '2024-10-26 11:42:37', NULL, 22230001, NULL),
(769, 'You updated Joe Account status into inactive', '2024-10-26 11:57:46', NULL, 22230001, NULL),
(770, 'You updated Joe Account status into active', '2024-10-26 12:00:45', NULL, 22230001, NULL),
(771, 'You updated Joe Account status into inactive', '2024-10-26 12:01:09', NULL, 22230001, NULL),
(772, 'You updated Joe Account status into active', '2024-10-26 12:12:37', NULL, 22230001, NULL),
(773, 'You updated Joe Account status into inactive', '2024-10-26 12:13:10', NULL, 22230001, NULL),
(774, 'You updated Joe Account status into active', '2024-10-26 12:14:09', NULL, 22230001, NULL),
(775, 'You updated Joe Account status into inactive', '2024-10-26 12:17:18', NULL, 22230001, NULL),
(776, 'You updated Joe Account status into inactive', '2024-10-26 12:19:02', NULL, 22230001, NULL),
(777, 'You updated Joe Account status into active', '2024-10-26 12:19:19', NULL, 22230001, NULL),
(778, 'You updated Joe Account status into inactive', '2024-10-26 12:32:04', NULL, 22230001, NULL),
(779, 'You updated Joe Account status into active', '2024-10-26 12:32:19', NULL, 22230001, NULL),
(780, 'You updated Joe Account status into inactive', '2024-10-26 12:33:26', NULL, 22230001, NULL),
(781, 'You updated Joe Account status into active', '2024-10-26 12:33:39', NULL, 22230001, NULL),
(782, 'You logged out of your account', '2024-10-26 12:36:50', NULL, NULL, 20191124),
(783, 'You logged in to your account', '2024-10-26 12:36:55', NULL, NULL, 20190000),
(784, 'You logged in to your account', '2024-10-26 12:37:27', NULL, 22230001, NULL),
(785, 'Departure request for student \'Sample Sample Sample\' was approved.', '2024-10-26 12:38:02', NULL, 22230001, NULL),
(786, 'You logged out of your account', '2024-10-26 12:39:15', NULL, NULL, 20190000),
(787, 'You logged in to your account', '2024-10-26 12:39:18', NULL, NULL, 20191124),
(788, 'You logged in to your account', '2024-10-26 12:39:59', NULL, 22230001, NULL),
(789, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-10-26 12:40:17', NULL, 22230001, NULL),
(790, 'You withdrew your departure request in NBSC Quick Response Team', '2024-10-26 12:43:44', NULL, NULL, 20191124),
(791, 'You logged in to your account', '2024-10-26 12:46:36', 24251014, NULL, NULL),
(792, 'You updated Joe Account status into inactive', '2024-10-26 13:43:22', NULL, 22230001, NULL),
(793, 'You updated Joe Account status into active', '2024-10-26 13:43:53', NULL, 22230001, NULL),
(794, 'You updated Joe Account status into inactive', '2024-10-26 14:04:59', NULL, 22230001, NULL),
(795, 'You updated Joe Account status into active', '2024-10-26 14:05:45', NULL, 22230001, NULL),
(796, 'You updated Carl Account status into inactive', '2024-10-26 14:05:59', NULL, 22230001, NULL),
(797, 'You updated Carl Account status into active', '2024-10-26 14:06:11', NULL, 22230001, NULL),
(798, 'You updated Joe Account status into departed', '2024-10-26 14:07:54', NULL, 22230001, NULL),
(799, 'You updated Joe Account status into departed', '2024-10-26 14:09:40', NULL, 22230001, NULL),
(800, 'You updated Joe Account status into active', '2024-10-26 15:37:54', NULL, 22230001, NULL),
(801, 'You logged in to your account', '2024-10-27 00:34:35', NULL, 22230001, NULL),
(802, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 02:28:17', NULL, 22230001, NULL),
(835, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 05:26:10', NULL, 22230001, NULL),
(836, 'You logged in to your account', '2024-10-27 05:28:14', NULL, NULL, 20191124),
(837, 'You logged in to your account', '2024-10-27 10:06:04', NULL, 22230001, NULL),
(838, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 10:34:27', NULL, 22230001, NULL),
(839, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 10:34:55', NULL, 22230001, NULL),
(840, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 10:47:32', NULL, 22230001, NULL),
(841, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 11:07:01', NULL, 22230001, NULL),
(842, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 11:10:23', NULL, 22230001, NULL),
(843, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 11:13:05', NULL, 22230001, NULL),
(844, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 11:14:38', NULL, 22230001, NULL),
(845, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-10-27 11:16:55', NULL, 22230001, NULL),
(846, 'You logged in to your account', '2024-10-28 13:02:52', NULL, 22230001, NULL),
(847, 'You added an event for NBSC Quick Response Team', '2024-10-28 13:13:11', NULL, 22230001, NULL),
(848, 'You deleted the event \'Sample Event\' in NBSC Quick Response Team.', '2024-10-28 13:14:06', NULL, 22230001, NULL),
(849, 'You added an event for NBSC Quick Response Team', '2024-10-28 13:14:41', NULL, 22230001, NULL),
(854, 'You added an event for NBSC Quick Response Team', '2024-10-28 13:20:24', NULL, 22230001, NULL),
(855, 'You deleted the event \'Sample Event\' in NBSC Quick Response Team.', '2024-10-28 13:26:19', NULL, 22230001, NULL),
(856, 'You added an event for NBSC Quick Response Team', '2024-10-28 13:26:39', NULL, 22230001, NULL),
(857, 'You deleted the event \'Sample Event\' in NBSC Quick Response Team.', '2024-10-28 13:30:08', NULL, 22230001, NULL),
(858, 'You added an event for NBSC Quick Response Team', '2024-10-28 13:30:26', NULL, 22230001, NULL),
(859, 'You logged in to your account', '2024-10-28 13:41:04', 24251014, NULL, NULL),
(866, 'You created a post in NBSC Quick Response Team', '2024-10-28 14:33:45', NULL, 22230001, NULL),
(867, 'You logged in to your account', '2024-11-01 00:59:21', NULL, 22230001, NULL),
(868, 'You changed your password', '2024-10-31 18:08:43', NULL, 22230001, NULL),
(869, 'You changed your password', '2024-10-31 18:14:18', NULL, 22230001, NULL),
(870, 'You changed your password', '2024-10-31 18:17:09', NULL, 22230001, NULL),
(871, 'You changed your password', '2024-10-31 18:27:06', NULL, 22230001, NULL),
(872, 'You changed your password', '2024-10-31 18:29:38', NULL, 22230001, NULL),
(873, 'You changed your password', '2024-10-31 18:30:22', NULL, 22230001, NULL),
(874, 'You changed your password', '2024-10-31 18:30:52', NULL, 22230001, NULL),
(875, 'You changed your password', '2024-10-31 18:31:21', NULL, 22230001, NULL),
(876, 'You changed your password', '2024-10-31 18:33:28', NULL, 22230001, NULL),
(877, 'You changed your password', '2024-10-31 18:34:03', NULL, 22230001, NULL),
(878, 'You changed your password', '2024-10-31 18:36:21', NULL, 22230001, NULL),
(879, 'You changed your password', '2024-10-31 18:38:11', NULL, 22230001, NULL);
INSERT INTO `tbl_activity_logs` (`activity_id`, `activity`, `dateAdded`, `admin_id`, `moderator_id`, `student_id`) VALUES
(880, 'You changed your password', '2024-10-31 18:38:52', NULL, 22230001, NULL),
(881, 'You changed your password', '2024-10-31 18:41:52', NULL, 22230001, NULL),
(882, 'You changed your password', '2024-10-31 18:42:46', NULL, 22230001, NULL),
(883, 'You changed your password', '2024-10-31 18:44:54', NULL, 22230001, NULL),
(884, 'You changed your password', '2024-10-31 18:45:26', NULL, 22230001, NULL),
(885, 'You changed your password', '2024-10-31 18:46:26', NULL, 22230001, NULL),
(886, 'You logged in to your account', '2024-11-01 02:21:41', 24251014, NULL, NULL),
(887, 'You logged in to your account', '2024-11-01 02:29:40', NULL, NULL, 20211521),
(888, 'You deleted Merlinda Yepes Magno\'s club request', '2024-11-01 04:25:08', 24251014, NULL, NULL),
(889, 'You deleted Song Writers Clubs from the clubs list', '2024-11-01 03:15:23', 24251014, NULL, NULL),
(890, 'You approved Ryan Cepada\'s club request', '2024-11-01 03:23:02', 24251014, NULL, 20191124),
(891, 'You deleted Merlinda Yepes Magno\'s club request', '2024-11-01 04:24:30', 24251014, NULL, NULL),
(892, 'You approved Ryan Cepada\'s club request', '2024-11-01 04:28:25', 24251014, NULL, NULL),
(918, 'You added Ryan Cepada\'s club request to the clubs list', '2024-11-01 05:16:33', 24251014, NULL, NULL),
(925, 'You added Ryan Cepada\'s club request to the clubs list', '2024-11-01 05:20:10', 24251014, NULL, NULL),
(926, 'You deleted Multimedia Productions from the clubs list', '2024-11-01 05:20:31', 24251014, NULL, NULL),
(927, 'You logged out of your account', '2024-11-01 05:27:33', NULL, NULL, 20211521),
(928, 'You logged in to your account', '2024-11-01 05:27:38', NULL, NULL, 20191124),
(929, 'You logged in to your account', '2024-11-01 05:38:57', NULL, 22230001, NULL),
(930, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-11-01 05:39:19', NULL, 22230001, NULL),
(931, 'You logged out of your account', '2024-11-01 05:59:38', NULL, 22230001, NULL),
(932, 'You logged in to your account', '2024-11-01 06:01:10', NULL, 24250010, NULL),
(933, 'You logged in to your account', '2024-11-01 06:09:51', NULL, NULL, 111),
(934, 'You logged in to your account', '2024-11-01 06:10:57', NULL, 22230001, NULL),
(935, 'You logged out of your account', '2024-11-01 06:11:04', NULL, 22230001, NULL),
(936, 'You logged in to your account', '2024-11-01 06:11:09', NULL, 24250010, NULL),
(937, 'You logged in to your account', '2024-11-01 06:16:57', NULL, NULL, 20191124),
(938, 'You logged in to your account', '2024-11-01 06:18:00', NULL, 22230001, NULL),
(939, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-11-01 06:18:26', NULL, 22230001, NULL),
(940, 'You logged out of your account', '2024-11-01 06:36:05', NULL, 22230001, NULL),
(941, 'You logged in to your account', '2024-11-01 06:36:59', NULL, 23240002, NULL),
(942, 'You logged in to your account', '2024-11-01 06:39:06', NULL, NULL, 20191124),
(943, 'You logged in to your account', '2024-11-01 06:40:07', NULL, 22230001, NULL),
(944, 'You logged out of your account', '2024-11-01 06:40:19', NULL, 22230001, NULL),
(945, 'You logged in to your account', '2024-11-01 06:40:36', NULL, 23240002, NULL),
(946, 'You logged out of your account', '2024-11-01 06:52:36', NULL, 23240002, NULL),
(947, 'You logged in to your account', '2024-11-01 06:52:41', NULL, 22230001, NULL),
(948, 'You logged in to your account', '2024-11-01 07:03:00', NULL, NULL, 20191124),
(949, 'You logged in to your account', '2024-11-01 07:08:32', 24251014, NULL, NULL),
(950, 'You logged in to your account', '2024-11-01 08:33:30', NULL, 22230001, NULL),
(953, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-11-01 09:07:04', NULL, 22230001, NULL),
(954, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-11-01 09:10:13', NULL, 22230001, NULL),
(955, 'You logged in to your account', '2024-11-01 09:11:04', 24251014, NULL, NULL),
(956, 'You logged in to your account', '2024-11-01 09:35:56', NULL, NULL, 20191124),
(957, 'You logged in to your account', '2024-11-01 09:37:28', 24251014, NULL, NULL),
(958, 'You updated your club request \'Song Writers Clubs\'', '2024-11-01 09:41:06', NULL, NULL, 20191124),
(959, 'You submitted a club request \'a\'', '2024-11-01 10:34:44', NULL, NULL, 20191124),
(960, 'You deleted your club request \'a\'', '2024-11-01 10:36:13', NULL, NULL, 20191124),
(961, 'You logged in to your account', '2024-11-01 10:38:58', 24251014, NULL, NULL),
(962, 'You updated your club request \'Song Writers Clubs\'', '2024-11-01 10:39:31', NULL, NULL, 20191124),
(963, 'You updated your club request \'Song Writers Clubs\'', '2024-11-01 10:44:30', NULL, NULL, 20191124),
(964, 'You updated your club request \'Song Writers Clubs\'', '2024-11-01 10:44:52', NULL, NULL, 20191124),
(965, 'You updated your club request \'Song Writers Clubs\'', '2024-11-01 10:45:10', NULL, NULL, 20191124),
(966, 'You updated your club request \'Song Writers Clubs\'', '2024-11-01 10:45:27', NULL, NULL, 20191124),
(967, 'You submitted a club request \'a\'', '2024-11-01 11:01:01', NULL, NULL, 20191124),
(968, 'You submitted a club request \'b\'', '2024-11-01 11:02:34', NULL, NULL, 20191124),
(969, 'You deleted your club request \'b\'', '2024-11-01 11:02:58', NULL, NULL, 20191124),
(970, 'You deleted your club request \'a\'', '2024-11-01 11:03:02', NULL, NULL, 20191124),
(971, 'You logged in to your account', '2024-11-01 13:06:20', NULL, NULL, 20191124),
(972, 'You logged in to your account', '2024-11-02 01:35:01', NULL, 22230001, NULL),
(973, 'You added an event for NBSC Quick Response Team', '2024-11-02 01:36:03', NULL, 22230001, NULL),
(974, 'You logged in to your account', '2024-11-02 01:57:21', NULL, NULL, 20191124),
(975, 'You submitted a club request \'Fur Club\'', '2024-11-02 01:58:52', NULL, NULL, 20191124),
(976, 'You submitted a club request \'Motobikes Club\'', '2024-11-02 02:03:23', NULL, NULL, 20191124),
(977, 'You updated your club request \'Fur Club\'', '2024-11-02 02:04:36', NULL, NULL, 20191124),
(978, 'You updated your club request \'Motobikes Club\'', '2024-11-02 02:04:48', NULL, NULL, 20191124),
(979, 'You updated your club request \'Fur Club\'', '2024-11-02 02:05:01', NULL, NULL, 20191124),
(980, 'You logged out of your account', '2024-11-02 02:05:22', NULL, NULL, 20191124),
(981, 'You logged in to your account', '2024-11-02 02:05:33', NULL, NULL, 20191115),
(982, 'You submitted a club request \'Teacher\'s Club\'', '2024-11-02 02:06:28', NULL, NULL, 20191115),
(983, 'You submitted a club request \'Culinary Club\'', '2024-11-02 02:08:32', NULL, NULL, 20191115),
(984, 'You updated your club request \'Culinary Club\'', '2024-11-02 02:08:48', NULL, NULL, 20191115),
(985, 'You submitted a club request \'Chefs Club\'', '2024-11-02 02:09:53', NULL, NULL, 20191115),
(986, 'You logged out of your account', '2024-11-02 02:10:14', NULL, NULL, 20191115),
(987, 'You logged in to your account', '2024-11-02 02:10:21', NULL, NULL, 20201179),
(988, 'You logged in to your account', '2024-11-02 02:41:40', NULL, 22230001, NULL),
(989, 'You logged out of your account', '2024-11-02 02:47:07', NULL, 22230001, NULL),
(990, 'You logged in to your account', '2024-11-02 02:47:20', NULL, 22230001, NULL),
(991, 'You logged out of your account', '2024-11-02 02:50:03', NULL, 22230001, NULL),
(992, 'You logged in to your account', '2024-11-02 02:50:09', NULL, 24250010, NULL),
(993, 'You logged out of your account', '2024-11-02 02:50:28', NULL, 24250010, NULL),
(994, 'You logged in to your account', '2024-11-02 02:50:34', NULL, 22230001, NULL),
(995, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-02 03:05:58', NULL, 22230001, NULL),
(996, 'You created a post in NBSC Quick Response Team', '2024-11-02 03:06:04', NULL, 22230001, NULL),
(997, 'You edited your post \'heelo\' in NBSC Quick Response Team', '2024-11-02 03:06:13', NULL, 22230001, NULL),
(998, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-11-02 03:06:19', NULL, 22230001, NULL),
(999, 'You created a post in NBSC Quick Response Team', '2024-11-02 03:09:34', NULL, 22230001, NULL),
(1000, 'You created a post in NBSC Quick Response Team', '2024-11-02 03:09:43', NULL, 22230001, NULL),
(1001, 'You created a post in NBSC Quick Response Team', '2024-11-02 03:09:49', NULL, 22230001, NULL),
(1002, 'You added an event for NBSC Quick Response Team', '2024-11-02 03:10:51', NULL, 22230001, NULL),
(1003, 'You updated the event \'Sample NBSC Quick Response Team Event \' information in NBSC Quick Response Team', '2024-11-02 03:11:11', NULL, 22230001, NULL),
(1004, 'You added an event for NBSC Quick Response Team', '2024-11-02 03:11:42', NULL, 22230001, NULL),
(1005, 'You deleted your post \'Hello\' in Mountaineering Society', '2024-11-02 03:12:02', NULL, 22230001, NULL),
(1006, 'You created a post in Mountaineering Society', '2024-11-02 03:12:11', NULL, 22230001, NULL),
(1007, 'You created a post in Mountaineering Society', '2024-11-02 03:12:21', NULL, 22230001, NULL),
(1008, 'You created a post in Mountaineering Society', '2024-11-02 03:12:33', NULL, 22230001, NULL),
(1009, 'You added an event for Mountaineering Society', '2024-11-02 03:13:24', NULL, 22230001, NULL),
(1010, 'You added an event for Mountaineering Society', '2024-11-02 03:14:16', NULL, 22230001, NULL),
(1011, 'You updated the event \'Sample NBSC Quick Response Team Event\' information in NBSC Quick Response Team', '2024-11-02 03:15:05', NULL, 22230001, NULL),
(1012, 'You updated the event \'Sample Quick Response Team Event \' information in NBSC Quick Response Team', '2024-11-02 03:15:15', NULL, 22230001, NULL),
(1013, 'You added an event for Mountaineering Society', '2024-11-02 03:15:58', NULL, 22230001, NULL),
(1014, 'You added an event for Mountaineering Society', '2024-11-02 03:17:27', NULL, 22230001, NULL),
(1015, 'You logged out of your account', '2024-11-02 03:18:04', NULL, 22230001, NULL),
(1016, 'You logged in to your account', '2024-11-02 03:18:14', NULL, NULL, 111),
(1017, 'You logged in to your account', '2024-11-02 03:18:50', NULL, 22230001, NULL),
(1018, 'You approved hey\'s application in NBSC Quick Response Team', '2024-11-02 03:19:11', NULL, 22230001, NULL),
(1019, 'You logged out of your account', '2024-11-02 03:20:22', NULL, NULL, 111),
(1020, 'You logged in to your account', '2024-11-02 03:20:25', NULL, NULL, 20191124),
(1021, 'You withdrew your departure request in NBSC Quick Response Team', '2024-11-02 03:20:48', NULL, NULL, 20191124),
(1022, 'You logged in to your account', '2024-11-02 03:21:14', NULL, 22230001, NULL),
(1023, 'You logged out of your account', '2024-11-02 03:21:57', NULL, NULL, 20191124),
(1024, 'You logged in to your account', '2024-11-02 03:22:00', NULL, NULL, 20191115),
(1025, 'You submitted an application to join Infotech Club', '2024-11-02 03:26:24', NULL, NULL, 20191115),
(1026, 'You submitted an application to join English Club', '2024-11-02 03:28:11', NULL, NULL, 20191115),
(1027, 'You submitted an application to join Campus Seekers of Christ', '2024-11-02 03:28:50', NULL, NULL, 20191115),
(1028, 'You submitted an application to join Math-Sci Club', '2024-11-02 03:29:09', NULL, NULL, 20191115),
(1029, 'You submitted an application to join Dramatic Society', '2024-11-02 03:29:49', NULL, NULL, 20191115),
(1030, 'You logged in to your account', '2024-11-02 03:30:51', NULL, 22230002, NULL),
(1031, 'You created a post in NBSC Quick Response Team', '2024-11-02 03:31:03', NULL, 22230002, NULL),
(1032, 'You created a post in NBSC Quick Response Team', '2024-11-02 03:31:15', NULL, 22230002, NULL),
(1033, 'You created a post in NBSC Quick Response Team', '2024-11-02 03:31:25', NULL, 22230002, NULL),
(1034, 'You logged out of your account', '2024-11-02 03:32:15', NULL, 22230002, NULL),
(1035, 'You logged in to your account', '2024-11-02 03:32:25', NULL, NULL, 20201270),
(1036, 'You logged in to your account', '2024-11-02 03:51:13', NULL, 22230001, NULL),
(1037, 'You logged in to your account', '2024-11-02 05:30:37', NULL, NULL, 20191115),
(1038, 'You submitted an application to join Muslim Student\'s Society', '2024-11-02 06:29:03', NULL, NULL, 20191115),
(1039, 'You logged out of your account', '2024-11-02 06:49:16', NULL, NULL, 20191115),
(1040, 'You logged in to your account', '2024-11-02 06:49:19', NULL, NULL, 20191124),
(1041, 'You logged out of your account', '2024-11-02 06:49:36', NULL, NULL, 20191124),
(1042, 'You logged in to your account', '2024-11-02 06:49:44', NULL, NULL, 20191115),
(1043, 'You submitted an application to join Muslim Student\'s Society', '2024-11-02 07:35:52', NULL, NULL, 20191115),
(1044, 'You logged in to your account', '2024-11-03 09:22:01', NULL, 22230001, NULL),
(1045, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-11-03 09:23:54', NULL, 22230001, NULL),
(1046, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-11-03 09:25:43', NULL, 22230001, NULL),
(1047, 'You logged in to your account', '2024-11-03 09:26:32', 24251014, NULL, NULL),
(1048, 'You approved Ryan Cepada\'s club request', '2024-11-03 09:26:58', 24251014, NULL, NULL),
(1049, 'You logged in to your account', '2024-11-03 09:40:48', 24251014, NULL, NULL),
(1050, 'You approved Ryan Cepada\'s club request', '2024-11-03 09:41:02', 24251014, NULL, NULL),
(1052, 'You added an event for NBSC Quick Response Team', '2024-11-03 09:54:42', NULL, 22230001, NULL),
(1054, 'You added an event for NBSC Quick Response Team', '2024-11-03 10:02:21', NULL, 22230001, NULL),
(1055, 'You deleted the event \'Sample Quick Response Team Event\' in NBSC Quick Response Team.', '2024-11-03 10:07:10', NULL, 22230001, NULL),
(1056, 'You added an event for NBSC Quick Response Team', '2024-11-03 10:07:42', NULL, 22230001, NULL),
(1057, 'You logged out of your account', '2024-11-03 10:11:44', NULL, 22230001, NULL),
(1058, 'You logged in to your account', '2024-11-03 10:17:07', NULL, 22230001, NULL),
(1059, 'You added an event for NBSC Quick Response Team', '2024-11-03 10:18:04', NULL, 22230001, NULL),
(1060, 'You created a post in NBSC Quick Response Team', '2024-11-03 10:58:33', NULL, 22230001, NULL),
(1061, 'You created a post in NBSC Quick Response Team', '2024-11-03 11:06:19', NULL, 22230001, NULL),
(1062, 'You created a post in NBSC Quick Response Team', '2024-11-03 11:39:45', NULL, 22230001, NULL),
(1063, 'You created a post in the club with ID 1', '2024-11-03 11:45:30', NULL, 22230001, NULL),
(1064, 'You edited your post \'a\' in NBSC Quick Response Team', '2024-11-03 11:45:57', NULL, 22230001, NULL),
(1065, 'You deleted your post \'aaa\' in NBSC Quick Response Team', '2024-11-03 11:46:04', NULL, 22230001, NULL),
(1066, 'You created a post in the club with ID 1', '2024-11-03 11:47:42', NULL, 22230001, NULL),
(1067, 'You deleted your post \'eyyy\' in NBSC Quick Response Team', '2024-11-03 11:51:00', NULL, 22230001, NULL),
(1068, 'You deleted your post \'hey\' in NBSC Quick Response Team', '2024-11-03 11:51:04', NULL, 22230001, NULL),
(1069, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-11-03 11:51:08', NULL, 22230001, NULL),
(1070, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-03 11:51:13', NULL, 22230001, NULL),
(1071, 'You created a post in NBSC Quick Response Team', '2024-11-03 11:53:18', NULL, 22230001, NULL),
(1072, 'You created a post in the club with ID 1', '2024-11-03 11:53:18', NULL, 22230001, NULL),
(1073, 'You created a post in the club with ID 1', '2024-11-03 11:53:29', NULL, 22230001, NULL),
(1074, 'You created a post in the club with ID 1', '2024-11-03 11:55:41', NULL, 22230001, NULL),
(1075, 'You created a post in the club with ID 1', '2024-11-03 11:56:03', NULL, 22230001, NULL),
(1076, 'You created a post in the club with ID 1', '2024-11-03 12:00:10', NULL, 22230001, NULL),
(1077, 'You deleted your post \'a\' in NBSC Quick Response Team', '2024-11-03 12:00:15', NULL, 22230001, NULL),
(1078, 'You deleted your post \'e\' in NBSC Quick Response Team', '2024-11-03 12:00:18', NULL, 22230001, NULL),
(1079, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-03 12:00:23', NULL, 22230001, NULL),
(1080, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-11-03 12:00:27', NULL, 22230001, NULL),
(1081, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-03 12:00:30', NULL, 22230001, NULL),
(1082, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-03 12:00:35', NULL, 22230001, NULL),
(1083, 'You created a post in the club with ID 1', '2024-11-03 12:00:39', NULL, 22230001, NULL),
(1084, 'You edited your post \'hey\' in NBSC Quick Response Team', '2024-11-03 12:00:47', NULL, 22230001, NULL),
(1085, 'You deleted your post \'hey!\' in NBSC Quick Response Team', '2024-11-03 12:00:52', NULL, 22230001, NULL),
(1086, 'You logged in to your account', '2024-11-03 12:06:48', NULL, NULL, 20191124),
(1087, 'You deleted your post \'Hi Everyone!\' in NBSC Quick Response Team', '2024-11-03 12:09:53', NULL, 22230001, NULL),
(1088, 'You deleted your post \'Good day!\' in NBSC Quick Response Team', '2024-11-03 12:09:59', NULL, 22230001, NULL),
(1089, 'You deleted your post \'Hello everyone!\' in NBSC Quick Response Team', '2024-11-03 12:10:06', NULL, 22230001, NULL),
(1090, 'You logged out of your account', '2024-11-03 12:10:12', NULL, 22230001, NULL),
(1091, 'You logged in to your account', '2024-11-03 12:10:18', NULL, 22230002, NULL),
(1092, 'You deleted your post \'Good day everyone!\' in NBSC Quick Response Team', '2024-11-03 12:10:27', NULL, 22230002, NULL),
(1093, 'You deleted your post \'Hi everyone!\' in NBSC Quick Response Team', '2024-11-03 12:10:31', NULL, 22230002, NULL),
(1094, 'You deleted your post \'Hello everyone!\' in NBSC Quick Response Team', '2024-11-03 12:10:34', NULL, 22230002, NULL),
(1095, 'You logged in to your account', '2024-11-03 12:10:48', NULL, NULL, 20191124),
(1096, 'You logged in to your account', '2024-11-03 12:17:31', NULL, 22230001, NULL),
(1097, 'You created a post in the club with ID 1', '2024-11-03 12:17:44', NULL, 22230001, NULL),
(1098, 'You deleted your post \'Good evening everyone!\' in NBSC Quick Response Team', '2024-11-03 12:17:56', NULL, 22230001, NULL),
(1099, 'You created a post in the club with ID 1', '2024-11-03 12:18:04', NULL, 22230001, NULL),
(1100, 'You edited your post \'Hi!\' in NBSC Quick Response Team', '2024-11-03 12:18:18', NULL, 22230001, NULL),
(1101, 'You deleted your post \'Hello!\' in NBSC Quick Response Team', '2024-11-03 12:18:25', NULL, 22230001, NULL),
(1102, 'You created a post in the club with ID 1', '2024-11-03 12:18:35', NULL, 22230001, NULL),
(1103, 'You created a post in the club with ID 1', '2024-11-03 12:18:48', NULL, 22230001, NULL),
(1104, 'You created a post in the club with ID 1', '2024-11-03 12:18:56', NULL, 22230001, NULL),
(1105, 'You added an event for NBSC Quick Response Team', '2024-11-03 12:19:41', NULL, 22230001, NULL),
(1106, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-11-03 12:24:10', NULL, 22230001, NULL),
(1107, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-03 12:24:13', NULL, 22230001, NULL),
(1108, 'You deleted your post \'hey\' in NBSC Quick Response Team', '2024-11-03 12:24:18', NULL, 22230001, NULL),
(1109, 'You created a post in the club with ID 1', '2024-11-03 12:24:29', NULL, 22230001, NULL),
(1110, 'You added an event for NBSC Quick Response Team', '2024-11-03 12:25:20', NULL, 22230001, NULL),
(1111, 'You created a post in the club with ID 1', '2024-11-03 12:27:19', NULL, 22230001, NULL),
(1112, 'You created a post in the club with ID 1', '2024-11-03 12:31:21', NULL, 22230001, NULL),
(1113, 'You created a post in the club with ID 1', '2024-11-03 13:06:11', NULL, 22230001, NULL),
(1114, 'You created a post in the club with ID 1', '2024-11-03 13:11:28', NULL, 22230001, NULL),
(1115, 'You created a post in the club with ID 1', '2024-11-03 13:12:00', NULL, 22230001, NULL),
(1116, 'You created a post in the club with ID 1', '2024-11-03 13:12:37', NULL, 22230001, NULL),
(1117, 'You created a post in the club with ID 1', '2024-11-03 13:13:42', NULL, 22230001, NULL),
(1118, 'You created a post in the club with ID 1', '2024-11-03 13:14:17', NULL, 22230001, NULL),
(1119, 'You created a post in the club with ID 1', '2024-11-03 13:14:50', NULL, 22230001, NULL),
(1120, 'You created a post in the club with ID 1', '2024-11-03 13:26:16', NULL, 22230001, NULL),
(1121, 'You created a post in the club with ID 1', '2024-11-03 13:26:52', NULL, 22230001, NULL),
(1122, 'You created a post in the club with ID 1', '2024-11-03 13:27:28', NULL, 22230001, NULL),
(1123, 'You created a post in the club with ID 1', '2024-11-03 13:28:02', NULL, 22230001, NULL),
(1124, 'You created a post in the club with ID 1', '2024-11-03 13:29:26', NULL, 22230001, NULL),
(1125, 'You created a post in the club with ID 1', '2024-11-03 13:31:54', NULL, 22230001, NULL),
(1126, 'You created a post in the club with ID 1', '2024-11-03 13:36:23', NULL, 22230001, NULL),
(1127, 'You created a post in the club with ID 1', '2024-11-03 13:40:54', NULL, 22230001, NULL),
(1128, 'You created a post in the club with ID 1', '2024-11-03 13:45:46', NULL, 22230001, NULL),
(1129, 'You logged in to your account', '2024-11-03 13:46:12', NULL, 22230001, NULL),
(1130, 'You created a post in the club with ID 1', '2024-11-03 13:48:05', NULL, 22230001, NULL),
(1131, 'You logged in to your account', '2024-11-03 14:20:35', 24251014, NULL, NULL),
(1132, 'You approved Ryan Cepada\'s club request', '2024-11-03 14:20:54', 24251014, NULL, NULL),
(1133, 'You approved Ryan Palmares Cepada\'s club request', '2024-11-03 14:29:40', 24251014, NULL, NULL),
(1134, 'You deleted the event \'Sample Quick Response Team Event\' in NBSC Quick Response Team.', '2024-11-03 14:34:04', NULL, 22230001, NULL),
(1135, 'You deleted the event \'Sample Quick Response Team Event\' in NBSC Quick Response Team.', '2024-11-03 14:34:08', NULL, 22230001, NULL),
(1136, 'You deleted the event \'Sample NBSC Quick Response Team Event\' in NBSC Quick Response Team.', '2024-11-03 14:34:14', NULL, 22230001, NULL),
(1137, 'You added an event for NBSC Quick Response Team', '2024-11-03 14:34:42', NULL, 22230001, NULL),
(1138, 'You added an event for NBSC Quick Response Team', '2024-11-03 14:37:54', NULL, 22230001, NULL),
(1139, 'You deleted your post \'announcement test\' in NBSC Quick Response Team', '2024-11-03 14:42:51', NULL, 22230001, NULL),
(1140, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-03 14:43:02', NULL, 22230001, NULL),
(1141, 'You deleted your post \'test with club name\' in NBSC Quick Response Team', '2024-11-03 14:43:19', NULL, 22230001, NULL),
(1142, 'You deleted your post \'test for 9:35 pm sa\' in NBSC Quick Response Team', '2024-11-03 14:44:07', NULL, 22230001, NULL),
(1143, 'You deleted your post \'sa test 2\' in NBSC Quick Response Team', '2024-11-03 14:44:29', NULL, 22230001, NULL),
(1144, 'You deleted your post \'sa blancia test\' in NBSC Quick Response Team', '2024-11-03 14:44:33', NULL, 22230001, NULL),
(1145, 'You deleted your post \'Hello New update test\' in NBSC Quick Response Team', '2024-11-03 14:44:37', NULL, 22230001, NULL),
(1146, 'You deleted your post \'Hello New update test\' in NBSC Quick Response Team', '2024-11-03 14:44:41', NULL, 22230001, NULL),
(1147, 'You deleted your post \'Hello New update test\' in NBSC Quick Response Team', '2024-11-03 14:44:46', NULL, 22230001, NULL),
(1148, 'You deleted your post \'sa Test\' in NBSC Quick Response Team', '2024-11-03 14:44:49', NULL, 22230001, NULL),
(1149, 'You deleted your post \'Hello New update test\' in NBSC Quick Response Team', '2024-11-03 14:44:52', NULL, 22230001, NULL),
(1150, 'You deleted your post \'sa Test\' in NBSC Quick Response Team', '2024-11-03 14:44:58', NULL, 22230001, NULL),
(1151, 'You deleted your post \'sa Test\' in NBSC Quick Response Team', '2024-11-03 14:45:01', NULL, 22230001, NULL),
(1152, 'You deleted your post \'sa Test\' in NBSC Quick Response Team', '2024-11-03 14:45:04', NULL, 22230001, NULL),
(1153, 'You deleted your post \'sa Test\' in NBSC Quick Response Team', '2024-11-03 14:45:08', NULL, 22230001, NULL),
(1154, 'You deleted your post \'test\' in NBSC Quick Response Team', '2024-11-03 14:45:11', NULL, 22230001, NULL),
(1155, 'You deleted your post \'sa Test\' in NBSC Quick Response Team', '2024-11-03 14:45:15', NULL, 22230001, NULL),
(1156, 'You deleted your post \'Email notification test 2\' in NBSC Quick Response Team', '2024-11-03 14:45:19', NULL, 22230001, NULL),
(1157, 'You deleted your post \'Email notification test\' in NBSC Quick Response Team', '2024-11-03 14:45:23', NULL, 22230001, NULL),
(1158, 'You created a post in the club with ID 1', '2024-11-03 14:47:00', NULL, 22230001, NULL),
(1159, 'You created a post in the club with ID 1', '2024-11-03 14:49:55', NULL, 22230001, NULL),
(1160, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-11-03 14:51:20', NULL, 22230001, NULL),
(1161, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-03 14:51:25', NULL, 22230001, NULL),
(1162, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-11-03 14:52:21', NULL, 22230001, NULL),
(1163, 'You created a post in the club with ID 1', '2024-11-03 15:01:44', NULL, 22230001, NULL),
(1164, 'You logged in to your account', '2024-11-04 04:36:51', NULL, NULL, 20191124),
(1165, 'You logged out of your account', '2024-11-04 04:37:06', NULL, NULL, 20191124),
(1166, 'You logged in to your account', '2024-11-04 04:37:09', NULL, NULL, 20191115),
(1167, 'You logged in to your account', '2024-11-04 06:47:27', NULL, 24250004, NULL),
(1168, 'You approved Merlinda Yepes Magno\'s application in Young Catholic Servants of Christ', '2024-11-04 06:48:24', NULL, 24250004, NULL),
(1169, 'You logged out of your account', '2024-11-04 07:55:46', NULL, 24250004, NULL),
(1170, 'You logged in to your account', '2024-11-04 07:56:06', 24251014, NULL, NULL),
(1171, 'You approved Ryan Palmares Cepada\'s club request', '2024-11-04 07:56:40', 24251014, NULL, NULL),
(1172, 'You approved Ryan Palmares Cepada\'s club request', '2024-11-04 08:09:25', 24251014, NULL, NULL),
(1173, 'You approved Ryan Palmares Cepada\'s club request', '2024-11-04 08:10:50', 24251014, NULL, NULL),
(1174, 'You approved Ryan Palmares Cepada\'s club request', '2024-11-04 08:11:47', 24251014, NULL, NULL),
(1175, 'You approved Ryan Palmares Cepada\'s club request', '2024-11-04 08:12:01', 24251014, NULL, NULL),
(1176, 'You approved Ryan Palmares Cepada\'s club request', '2024-11-04 08:12:25', 24251014, NULL, NULL),
(1177, 'You logged in to your account', '2024-11-04 08:13:40', NULL, 22230001, NULL),
(1178, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-11-04 08:14:19', NULL, 22230001, NULL),
(1179, 'You logged in to your account', '2024-11-04 08:15:12', NULL, NULL, 20191124),
(1180, 'Departure request for student \'Ryan Palmares Cepada\' was approved.', '2024-11-04 08:15:36', NULL, 22230001, NULL),
(1182, 'You deleted your post \'hello\' in NBSC Quick Response Team', '2024-11-04 08:17:27', NULL, 22230001, NULL),
(1183, 'You deleted your post \'hi\' in NBSC Quick Response Team', '2024-11-04 08:17:31', NULL, 22230001, NULL),
(1184, 'You added an event for NBSC Quick Response Team', '2024-11-04 08:18:25', NULL, 22230001, NULL),
(1185, 'You logged out of your account', '2024-11-04 08:27:06', NULL, NULL, 20191124),
(1186, 'You logged in to your account', '2024-11-04 08:27:09', NULL, NULL, 20191115),
(1187, 'You submitted an application to join the club with ID 5', '2024-11-04 08:30:03', NULL, NULL, 20191115),
(1188, 'You submitted an application to join club with ID 5', '2024-11-04 08:39:38', NULL, NULL, 20191115),
(1189, 'You logged in to your account', '2024-11-04 09:37:41', NULL, NULL, 20191124),
(1190, 'You logged out of your account', '2024-11-04 09:38:44', NULL, NULL, 20191124),
(1191, 'You logged in to your account', '2024-11-04 09:38:47', NULL, NULL, 20191115),
(1192, 'You logged out of your account', '2024-11-04 09:39:39', NULL, NULL, 20191115),
(1193, 'You logged in to your account', '2024-11-04 09:39:53', NULL, NULL, 20191115),
(1194, 'You logged out of your account', '2024-11-04 09:40:52', NULL, NULL, 20191115),
(1195, 'You logged in to your account', '2024-11-04 09:40:54', NULL, NULL, 20191124),
(1196, 'You logged in to your account', '2024-11-04 09:42:58', NULL, 24250010, NULL),
(1197, 'You logged out of your account', '2024-11-04 09:45:35', NULL, NULL, 20191124),
(1198, 'You logged in to your account', '2024-11-04 09:45:39', NULL, NULL, 20191115),
(1199, 'You logged out of your account', '2024-11-04 09:49:47', NULL, NULL, 20191115),
(1200, 'You logged in to your account', '2024-11-04 09:49:50', NULL, NULL, 20191124),
(1201, 'You logged in to your account', '2024-11-04 09:57:43', NULL, 22230001, NULL),
(1202, 'You logged in to your account', '2024-11-04 10:05:27', 24251014, NULL, NULL),
(1203, 'You logged out of your account', '2024-11-04 10:38:20', NULL, 22230001, NULL),
(1204, 'You logged in to your account', '2024-11-04 10:38:31', NULL, NULL, 20191124),
(1205, 'You logged out of your account', '2024-11-04 10:38:43', NULL, NULL, 20191124),
(1206, 'You logged in to your account', '2024-11-04 10:39:17', NULL, 23240005, NULL),
(1207, 'You logged out of your account', '2024-11-04 10:39:42', NULL, 23240005, NULL),
(1208, 'You logged in to your account', '2024-11-04 10:39:50', NULL, 23240003, NULL),
(1209, 'You logged out of your account', '2024-11-04 10:44:44', NULL, 23240003, NULL),
(1210, 'You logged in to your account', '2024-11-04 10:44:52', NULL, 22230001, NULL),
(1211, 'You logged out of your account', '2024-11-04 10:45:04', NULL, 22230001, NULL),
(1212, 'You logged in to your account', '2024-11-04 10:45:10', NULL, 23240003, NULL),
(1213, 'You logged out of your account', '2024-11-04 10:58:51', NULL, 23240003, NULL),
(1214, 'You logged in to your account', '2024-11-04 10:58:56', NULL, 22230001, NULL),
(1215, 'You logged out of your account', '2024-11-04 10:59:05', NULL, 22230001, NULL),
(1216, 'You logged in to your account', '2024-11-04 10:59:13', NULL, 24250010, NULL),
(1217, 'You logged in to your account', '2024-11-04 10:59:36', NULL, NULL, 20201270),
(1218, 'You logged out of your account', '2024-11-04 11:01:08', NULL, 24250010, NULL),
(1219, 'You logged in to your account', '2024-11-04 11:01:14', NULL, 22230001, NULL),
(1220, 'You approved Ryan Palmares Cepada\'s application in NBSC Quick Response Team', '2024-11-04 11:06:15', NULL, 22230001, NULL),
(1221, 'You logged in to your account', '2024-11-04 11:08:25', 24251014, NULL, NULL),
(1222, 'You logged in to your account', '2024-11-04 11:42:59', NULL, 22230001, NULL),
(1223, 'You logged in to your account', '2024-11-04 12:10:50', NULL, 22230001, NULL),
(1224, 'You logged out of your account', '2024-11-04 12:11:17', NULL, 22230001, NULL),
(1225, 'You logged in to your account', '2024-11-04 12:11:23', NULL, 24250010, NULL),
(1226, 'You logged in to your account', '2024-11-04 12:12:03', 24251014, NULL, NULL),
(1227, 'You logged in to your account', '2024-11-04 12:37:06', NULL, 22230001, NULL),
(1228, 'You updated a question (ID: 1)', '2024-11-04 05:55:13', NULL, 22230001, NULL),
(1229, 'You logged in to your account', '2024-11-04 12:55:35', NULL, NULL, 20191115),
(1230, 'You logged out of your account', '2024-11-04 12:56:00', NULL, NULL, 20191115),
(1231, 'You logged in to your account', '2024-11-04 12:56:04', NULL, NULL, 20201179),
(1232, 'You logged out of your account', '2024-11-04 12:56:21', NULL, NULL, 20201179),
(1233, 'You logged in to your account', '2024-11-04 12:56:24', NULL, NULL, 20201270),
(1234, 'You logged in to your account', '2024-11-04 12:57:21', NULL, 22230001, NULL),
(1373, 'You deleted a question in NBSC Quick Response Team\'s application form.', '2024-11-04 07:29:09', NULL, 22230001, NULL),
(1374, 'You deleted a question in Mountaineering Society\'s application form.', '2024-11-04 07:39:15', NULL, 22230001, NULL),
(1375, 'You deleted a question in Mountaineering Society\'s application form.', '2024-11-04 07:39:55', NULL, 22230001, NULL),
(1376, 'You deleted a question in Mountaineering Society\'s application form.', '2024-11-04 07:40:45', NULL, 22230001, NULL),
(1377, 'You deleted a question in NBSC Quick Response Team\'s application form.', '2024-11-04 07:41:07', NULL, 22230001, NULL),
(1378, 'You deleted a question in Mountaineering Society\'s application form.', '2024-11-04 07:44:20', NULL, 22230001, NULL),
(1379, 'You updated a question with ID 1', '2024-11-04 07:44:35', NULL, 22230001, NULL),
(1380, 'You updated a question with ID 2', '2024-11-04 07:44:35', NULL, 22230001, NULL),
(1381, 'You updated a question with ID 3', '2024-11-04 07:44:35', NULL, 22230001, NULL),
(1382, 'You updated a question with ID 4', '2024-11-04 07:44:35', NULL, 22230001, NULL),
(1383, 'You updated a question with ID 5', '2024-11-04 07:44:35', NULL, 22230001, NULL),
(1384, 'You updated a question with ID 142', '2024-11-04 07:44:35', NULL, 22230001, NULL),
(1385, 'You updated a question with ID 1', '2024-11-04 07:44:37', NULL, 22230001, NULL),
(1386, 'You updated a question with ID 2', '2024-11-04 07:44:37', NULL, 22230001, NULL),
(1387, 'You updated a question with ID 3', '2024-11-04 07:44:37', NULL, 22230001, NULL),
(1388, 'You updated a question with ID 4', '2024-11-04 07:44:37', NULL, 22230001, NULL),
(1389, 'You updated a question with ID 5', '2024-11-04 07:44:37', NULL, 22230001, NULL),
(1390, 'You updated a question with ID 142', '2024-11-04 07:44:37', NULL, 22230001, NULL),
(1391, 'You deleted a question in NBSC Quick Response Team\'s application form.', '2024-11-04 07:44:43', NULL, 22230001, NULL),
(1392, 'You logged out of your account', '2024-11-04 14:50:05', NULL, NULL, 20211521),
(1393, 'You logged in to your account', '2024-11-04 14:50:08', NULL, NULL, 20191124),
(1394, 'You logged in to your account', '2024-11-04 14:50:30', NULL, 22230001, NULL),
(1395, 'You deleted a question in NBSC Quick Response Team\'s application form.', '2024-11-04 07:51:00', NULL, 22230001, NULL),
(1396, 'You added a new question to \'s application form', '2024-11-04 08:11:43', NULL, 22230001, NULL),
(1397, 'You added a new question to \'s application form', '2024-11-04 08:12:06', NULL, 22230001, NULL),
(1398, 'You added a new question to \'s application form', '2024-11-04 08:12:49', NULL, 22230001, NULL),
(1399, 'You deleted a question in Mountaineering Society\'s application form.', '2024-11-04 08:16:21', NULL, 22230001, NULL),
(1400, 'You deleted a question in Mountaineering Society\'s application form.', '2024-11-04 08:16:24', NULL, 22230001, NULL),
(1401, 'You added a new question to \'s application form', '2024-11-04 08:16:31', NULL, 22230001, NULL),
(1402, 'You added a new question to \'s application form', '2024-11-04 08:16:45', NULL, 22230001, NULL),
(1403, 'You added a new question to \'s application form', '2024-11-04 08:17:51', NULL, 22230001, NULL),
(1404, 'You added a new question to Unknown Club\'s application form', '2024-11-04 08:19:09', NULL, 22230001, NULL),
(1405, 'You added a new question to Unknown Club\'s application form', '2024-11-04 08:22:31', NULL, 22230001, NULL),
(1406, 'You added a new question to NBSC Quick Response Team\'s application form', '2024-11-04 08:23:01', NULL, 22230001, NULL),
(1407, 'You deleted a question in NBSC Quick Response Team\'s application form.', '2024-11-04 08:23:37', NULL, 22230001, NULL),
(1408, 'You logged out of your account', '2024-11-04 15:38:27', NULL, NULL, 20191124),
(1409, 'You logged in to your account', '2024-11-04 15:38:34', NULL, NULL, 20211521),
(1410, 'You submitted an application to join club with ID 1', '2024-11-04 15:39:03', NULL, NULL, 20211521),
(1411, 'You logged in to your account', '2024-11-04 15:40:42', NULL, 22230001, NULL),
(1412, 'You deleted a question in NBSC Quick Response Team\'s application form.', '2024-11-04 08:40:50', NULL, 22230001, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(20) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profilePic` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `email`, `password`, `dateAdded`, `dateModified`, `profilePic`) VALUES
(24251014, 'admin@gmail.com', '1', '2024-10-14 05:34:18', '2024-10-14 05:34:18', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_application`
--

CREATE TABLE `tbl_application` (
  `application_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `question1` text NOT NULL,
  `question2` text NOT NULL,
  `question3` text NOT NULL,
  `status` varchar(200) NOT NULL,
  `remark` text NOT NULL,
  `dateApplied` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateDecided` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_application`
--

INSERT INTO `tbl_application` (`application_id`, `student_id`, `question1`, `question2`, `question3`, `status`, `remark`, `dateApplied`, `dateDecided`, `dateModified`, `club_id`) VALUES
(1, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', 'Nice !', '2022-08-19 19:19:13', '2022-09-04 11:06:15', '2022-09-04 11:06:15', 1),
(2, 20201179, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2023-04-19 07:23:09', '2023-04-23 07:23:09', '2024-10-07 20:39:46', 2),
(3, 20211521, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', 'Nice! Godbless!', '2024-08-19 17:53:24', '2024-11-04 06:48:24', '2024-11-04 06:48:24', 14),
(4, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-08-16 17:53:24', '2024-08-20 17:53:24', '2024-10-07 20:39:51', 16),
(5, 20191115, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '', '2024-09-15 01:20:15', '2024-09-15 01:20:15', '2024-09-26 01:53:24', 21),
(6, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '', '2022-08-19 19:19:13', '2022-08-19 19:19:13', '2024-09-26 01:53:24', 1),
(7, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '', '2024-09-24 17:37:15', '2024-09-26 03:37:15', '2024-10-26 10:35:23', 4),
(8, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'maxed', '', '2024-05-14 18:05:15', '2024-09-26 05:27:05', '2024-10-24 19:14:12', 10),
(9, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-09-15 01:20:15', '2024-10-25 06:59:36', '2024-10-25 07:15:14', 22),
(10, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '', '2024-09-28 03:37:15', '2024-10-01 03:37:15', '2024-10-26 10:34:13', 4),
(11, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '', '2024-10-02 03:37:15', '2024-10-05 04:37:15', '2024-10-26 10:35:04', 4),
(12, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'maxed', '', '2024-09-25 05:40:18', '2024-09-26 03:37:15', '2024-10-24 19:14:12', 25),
(13, 20201179, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-09-15 01:20:15', '2024-10-25 13:41:10', '2024-10-25 13:41:10', 22),
(14, 20191115, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2022-08-19 19:19:13', '2022-08-21 19:19:13', '2024-09-26 01:53:24', 1),
(16, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '', '2024-09-15 01:20:15', '2024-09-18 01:20:15', '2024-09-26 01:53:24', 22),
(17, 20190000, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-03-14 22:26:41', '2024-10-26 12:38:02', '2024-10-26 12:38:25', 1),
(18, 20211524, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-09-15 20:23:09', '2024-09-15 20:23:09', '2024-09-26 01:53:24', 1),
(19, 20211525, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-09-15 20:25:46', '2024-09-15 20:26:20', '2024-09-26 01:53:24', 1),
(20, 20211526, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-09-15 20:28:19', '2024-09-15 20:28:19', '2024-10-26 14:06:11', 1),
(21, 20211527, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '', '2024-09-15 20:29:59', '2024-09-15 20:29:59', '2024-10-26 15:37:54', 1),
(22, 20211521, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '', '2024-05-14 18:05:15', '2024-09-26 06:05:45', '2024-09-26 06:05:58', 10),
(23, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '', '2024-05-14 18:05:15', '2024-09-26 03:33:52', '2024-09-26 03:33:52', 10),
(24, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', 'Sample remark', '2024-09-17 23:49:09', '2024-11-02 03:19:11', '2024-11-02 03:19:11', 1),
(25, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '', '2024-09-26 04:14:43', '2024-09-30 08:31:18', '2024-09-30 08:31:49', 4),
(26, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '', '2024-09-26 04:16:26', '2024-09-30 08:31:18', '2024-09-30 08:31:55', 4),
(40, 20191115, '', '', '', 'pending', '', '2024-11-04 08:39:38', '2024-11-04 08:39:38', '2024-11-04 08:39:38', 5),
(41, 20211521, '', '', '', 'pending', '', '2024-11-04 15:39:03', '2024-11-04 15:39:03', '2024-11-04 15:39:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_application_answers`
--

CREATE TABLE `tbl_application_answers` (
  `answer_id` int(20) NOT NULL,
  `answer` text NOT NULL,
  `question_id` int(20) NOT NULL,
  `question` text NOT NULL,
  `application_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_application_answers`
--

INSERT INTO `tbl_application_answers` (`answer_id`, `answer`, `question_id`, `question`, `application_id`, `student_id`, `club_id`, `dateAdded`, `dateModified`) VALUES
(1, 'I want to join NBSC Quick because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 1, 20191124, 1, '2022-08-19 19:19:13', '2024-11-04 11:57:00'),
(2, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 1, 20191124, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(3, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 1, 20191124, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(4, '#4 answer', 4, '', 1, 20191124, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(5, '#5 answer', 5, '', 1, 20191124, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(6, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 6, '', 2, 20201179, 2, '2023-04-19 07:23:09', '2023-04-19 07:23:09'),
(7, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 7, '', 2, 20201179, 2, '2023-04-19 07:23:09', '2023-04-19 07:23:09'),
(8, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 8, '', 2, 20201179, 2, '2023-04-19 07:23:09', '2023-04-19 07:23:09'),
(9, '#4 answer', 9, '', 2, 20201179, 2, '2023-04-19 07:23:09', '2023-04-19 07:23:09'),
(10, '#5 answer', 10, '', 2, 20201179, 2, '2023-04-19 07:23:09', '2023-04-19 07:23:09'),
(11, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 66, '', 3, 20211521, 14, '2024-08-19 17:53:24', '2024-08-19 17:53:24'),
(12, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 67, '', 3, 20211521, 14, '2024-08-19 17:53:24', '2024-08-19 17:53:24'),
(13, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 68, '', 3, 20211521, 14, '2024-08-19 17:53:24', '2024-08-19 17:53:24'),
(14, '#4 answer', 69, '', 3, 20211521, 14, '2024-08-19 17:53:24', '2024-08-19 17:53:24'),
(15, '#5 answer', 70, '', 3, 20211521, 14, '2024-08-19 17:53:24', '2024-08-19 17:53:24'),
(16, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 76, '', 4, 20201270, 16, '2024-08-16 17:53:24', '2024-08-16 17:53:24'),
(17, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 77, '', 4, 20201270, 16, '2024-08-16 17:53:24', '2024-08-16 17:53:24'),
(18, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 78, '', 4, 20201270, 16, '2024-08-16 17:53:24', '2024-08-16 17:53:24'),
(19, '#4 answer', 79, '', 4, 20201270, 16, '2024-08-16 17:53:24', '2024-08-16 17:53:24'),
(20, '#5 answer', 80, '', 4, 20201270, 16, '2024-08-16 17:53:24', '2024-08-16 17:53:24'),
(21, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 96, '', 5, 20191115, 21, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(22, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 97, '', 5, 20191115, 21, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(23, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 98, '', 5, 20191115, 21, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(24, '#4 answer', 99, '', 5, 20191115, 21, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(25, '#5 answer', 100, '', 5, 20191115, 21, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(26, 'I want to join NBSC Quick Response Team because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 6, 20201270, 1, '2022-08-19 19:19:13', '2024-11-04 10:35:45'),
(27, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 6, 20201270, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(28, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 6, 20201270, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(29, 'Self-discipline and professionalism', 4, '', 6, 20201270, 1, '2022-08-19 19:19:13', '2024-11-04 10:36:27'),
(30, 'My passion to offer any services to one another', 5, '', 6, 20201270, 1, '2022-08-19 19:19:13', '2024-11-04 10:37:06'),
(31, 'I want to join Muslim Student\'s Society because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 16, '', 7, 20191124, 4, '2024-09-24 17:37:15', '2024-11-04 12:23:15'),
(32, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 17, '', 7, 20191124, 4, '2024-09-24 17:37:15', '2024-09-24 17:37:15'),
(33, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 18, '', 7, 20191124, 4, '2024-09-24 17:37:15', '2024-09-24 17:37:15'),
(34, '#4 answer', 19, '', 7, 20191124, 4, '2024-09-24 17:37:15', '2024-09-24 17:37:15'),
(35, '#5 answer', 20, '', 7, 20191124, 4, '2024-09-24 17:37:15', '2024-09-24 17:37:15'),
(36, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 46, '', 8, 20191124, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(37, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 47, '', 8, 20191124, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(38, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 48, '', 8, 20191124, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(39, '#4 answer', 49, '', 8, 20191124, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(40, '#5 answer', 50, '', 8, 20191124, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(41, 'I want to join Infotech Club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 101, '', 9, 20191124, 22, '2024-09-15 01:20:15', '2024-11-04 09:52:00'),
(42, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 102, '', 9, 20191124, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(43, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 103, '', 9, 20191124, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(44, '#4 Infotech Club answer', 104, '', 9, 20191124, 22, '2024-09-15 01:20:15', '2024-11-04 09:52:18'),
(45, '#5 Infotech Club answer', 105, '', 9, 20191124, 22, '2024-09-15 01:20:15', '2024-11-04 09:52:28'),
(46, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 16, '', 10, 20191124, 4, '2024-09-28 03:37:15', '2024-09-28 03:37:15'),
(47, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 17, '', 10, 20191124, 4, '2024-09-28 03:37:15', '2024-09-28 03:37:15'),
(48, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 18, '', 10, 20191124, 4, '2024-09-28 03:37:15', '2024-09-28 03:37:15'),
(49, '#4 answer', 19, '', 10, 20191124, 4, '2024-09-28 03:37:15', '2024-09-28 03:37:15'),
(50, '#5 answer', 20, '', 10, 20191124, 4, '2024-09-28 03:37:15', '2024-09-28 03:37:15'),
(51, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 16, '', 11, 20191124, 4, '2024-10-02 03:37:15', '2024-10-02 03:37:15'),
(52, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 17, '', 11, 20191124, 4, '2024-10-02 03:37:15', '2024-10-02 03:37:15'),
(53, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 18, '', 11, 20191124, 4, '2024-10-02 03:37:15', '2024-10-02 03:37:15'),
(54, '#4 answer', 19, '', 11, 20191124, 4, '2024-10-02 03:37:15', '2024-10-02 03:37:15'),
(55, '#5 answer', 20, '', 11, 20191124, 4, '2024-10-02 03:37:15', '2024-10-02 03:37:15'),
(56, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 116, '', 12, 20191124, 25, '2024-09-25 05:40:18', '2024-09-25 05:40:18'),
(57, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 117, '', 12, 20191124, 25, '2024-09-25 05:40:18', '2024-09-25 05:40:18'),
(58, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 118, '', 12, 20191124, 25, '2024-09-25 05:40:18', '2024-09-25 05:40:18'),
(59, '#4 answer', 119, '', 12, 20191124, 25, '2024-09-25 05:40:18', '2024-09-25 05:40:18'),
(60, '#5 answer', 120, '', 12, 20191124, 25, '2024-09-25 05:40:18', '2024-09-25 05:40:18'),
(61, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 101, '', 13, 20201179, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(62, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 102, '', 13, 20201179, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(63, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 103, '', 13, 20201179, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(64, '#4 answer', 104, '', 13, 20201179, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(65, '#5 answer', 105, '', 13, 20201179, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(66, 'I want to join NBSC Quick Resonse Team because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 14, 20191115, 1, '2022-08-19 19:19:13', '2024-11-04 10:03:16'),
(67, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 14, 20191115, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(68, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 14, 20191115, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(69, '#4 answer', 4, '', 14, 20191115, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(70, '#5 answer', 5, '', 14, 20191115, 1, '2022-08-19 19:19:13', '2022-08-19 19:19:13'),
(71, 'I want to join Infotech Club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 101, '', 16, 20201270, 22, '2024-09-15 01:20:15', '2024-11-04 11:00:14'),
(72, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 102, '', 16, 20201270, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(73, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 103, '', 16, 20201270, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(74, '#4 answer', 104, '', 16, 20201270, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(75, '#5 answer', 105, '', 16, 20201270, 22, '2024-09-15 01:20:15', '2024-09-15 01:20:15'),
(76, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 17, 20190000, 1, '2024-11-04 07:05:59', '2024-11-04 07:05:59'),
(77, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 17, 20190000, 1, '2024-11-04 07:05:59', '2024-11-04 07:05:59'),
(78, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 17, 20190000, 1, '2024-11-04 07:05:59', '2024-11-04 07:05:59'),
(79, '#4 answer', 4, '', 17, 20190000, 1, '2024-11-04 07:05:59', '2024-11-04 07:05:59'),
(80, '#5 answer', 5, '', 17, 20190000, 1, '2024-11-04 07:05:59', '2024-11-04 07:05:59'),
(81, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 18, 20211524, 1, '2024-09-15 20:23:09', '2024-09-15 20:23:09'),
(82, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 18, 20211524, 1, '2024-09-15 20:23:09', '2024-09-15 20:23:09'),
(83, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 18, 20211524, 1, '2024-09-15 20:23:09', '2024-09-15 20:23:09'),
(84, '#4 answer', 4, '', 18, 20211524, 1, '2024-09-15 20:23:09', '2024-09-15 20:23:09'),
(85, '#5 answer', 5, '', 18, 20211524, 1, '2024-09-15 20:23:09', '2024-09-15 20:23:09'),
(86, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 19, 20211525, 1, '2024-09-15 20:25:46', '2024-09-15 20:25:46'),
(87, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 19, 20211525, 1, '2024-09-15 20:25:46', '2024-09-15 20:25:46'),
(88, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 19, 20211525, 1, '2024-09-15 20:25:46', '2024-09-15 20:25:46'),
(89, '#4 answer', 4, '', 19, 20211525, 1, '2024-09-15 20:25:46', '2024-09-15 20:25:46'),
(90, '#5 answer', 5, '', 19, 20211525, 1, '2024-09-15 20:25:46', '2024-09-15 20:25:46'),
(91, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 20, 20211526, 1, '2024-09-15 20:28:19', '2024-09-15 20:28:19'),
(92, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 20, 20211526, 1, '2024-09-15 20:28:19', '2024-09-15 20:28:19'),
(93, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 20, 20211526, 1, '2024-09-15 20:28:19', '2024-09-15 20:28:19'),
(94, '#4 answer', 4, '', 20, 20211526, 1, '2024-09-15 20:28:19', '2024-09-15 20:28:19'),
(95, '#5 answer', 5, '', 20, 20211526, 1, '2024-09-15 20:28:19', '2024-09-15 20:28:19'),
(96, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 21, 20211527, 1, '2024-09-15 20:29:59', '2024-09-15 20:29:59'),
(97, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 21, 20211527, 1, '2024-09-15 20:29:59', '2024-09-15 20:29:59'),
(98, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 21, 20211527, 1, '2024-09-15 20:29:59', '2024-09-15 20:29:59'),
(99, '#4 answer', 4, '', 21, 20211527, 1, '2024-09-15 20:29:59', '2024-09-15 20:29:59'),
(100, '#5 answer', 5, '', 21, 20211527, 1, '2024-09-15 20:29:59', '2024-09-15 20:29:59'),
(101, 'I want to join Mountaineering Society because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 46, '', 22, 20211521, 10, '2024-05-14 18:05:15', '2024-11-04 11:01:52'),
(102, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 47, '', 22, 20211521, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(103, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 48, '', 22, 20211521, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(104, '#4 answer', 49, '', 22, 20211521, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(105, '#5 answer', 50, '', 22, 20211521, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(106, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 46, '', 23, 111, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(107, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 47, '', 23, 111, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(108, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 48, '', 23, 111, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(109, '#4 answer', 49, '', 23, 111, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(110, '#5 answer', 50, '', 23, 111, 10, '2024-05-14 18:05:15', '2024-05-14 18:05:15'),
(111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 1, '', 24, 111, 1, '2024-09-17 23:49:09', '2024-09-17 23:49:09'),
(112, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 2, '', 24, 111, 1, '2024-09-17 23:49:09', '2024-09-17 23:49:09'),
(113, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 3, '', 24, 111, 1, '2024-09-17 23:49:09', '2024-09-17 23:49:09'),
(114, '#4 answer', 4, '', 24, 111, 1, '2024-09-17 23:49:09', '2024-09-17 23:49:09'),
(115, '#5 answer', 5, '', 24, 111, 1, '2024-09-17 23:49:09', '2024-09-17 23:49:09'),
(116, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 16, '', 25, 111, 4, '2024-09-26 04:14:43', '2024-09-26 04:14:43'),
(117, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 17, '', 25, 111, 4, '2024-09-26 04:14:43', '2024-09-26 04:14:43'),
(118, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 18, '', 25, 111, 4, '2024-09-26 04:14:43', '2024-09-26 04:14:43'),
(119, '#4 answer', 19, '', 25, 111, 4, '2024-09-26 04:14:43', '2024-09-26 04:14:43'),
(120, '#5 answer', 20, '', 25, 111, 4, '2024-09-26 04:14:43', '2024-09-26 04:14:43'),
(121, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 16, '', 26, 111, 4, '2024-09-26 04:16:26', '2024-09-26 04:16:26'),
(122, 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 17, '', 26, 111, 4, '2024-09-26 04:16:26', '2024-09-26 04:16:26'),
(123, 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 18, '', 26, 111, 4, '2024-09-26 04:16:26', '2024-09-26 04:16:26'),
(124, '#4 answer', 19, '', 26, 111, 4, '2024-09-26 04:16:26', '2024-09-26 04:16:26'),
(125, '#5 answer', 20, '', 26, 111, 4, '2024-09-26 04:16:26', '2024-09-26 04:16:26'),
(129, 'Sample 1', 1, '', 40, 20191115, 5, '2024-11-04 08:39:38', '2024-11-04 10:56:26'),
(130, 'Sample 2', 2, '', 40, 20191115, 5, '2024-11-04 08:39:38', '2024-11-04 08:39:38'),
(131, 'Sample 3', 3, '', 40, 20191115, 5, '2024-11-04 08:39:38', '2024-11-04 08:39:38'),
(132, 'Sample 4', 4, '', 40, 20191115, 5, '2024-11-04 08:39:38', '2024-11-04 08:39:38'),
(133, 'Sample 5', 5, '', 40, 20191115, 5, '2024-11-04 08:39:38', '2024-11-04 08:39:38'),
(134, 'aaa', 141, '', 1, 20191124, 1, '2024-11-04 14:49:57', '2024-11-04 14:49:57'),
(135, 'bbb', 142, '', 1, 20191124, 1, '2024-11-04 14:49:57', '2024-11-04 14:49:57'),
(136, 'a', 1, 'Why do you want to join NBSC Quick Response Team?', 41, 20211521, 1, '2024-11-04 15:39:03', '2024-11-04 15:39:03'),
(137, 'b', 2, 'What skills or experiences do you have that will contribute to the club\'s activities?', 41, 20211521, 1, '2024-11-04 15:39:03', '2024-11-04 15:39:03'),
(138, 'c', 3, 'How do you plan to balance your time between club activities and your academic responsibilities?', 41, 20211521, 1, '2024-11-04 15:39:03', '2024-11-04 15:39:03'),
(139, 'd', 4, 'How would you handle high-pressure situations where immediate action is required?', 41, 20211521, 1, '2024-11-04 15:39:03', '2024-11-04 15:39:03'),
(140, 'e', 5, 'What motivates you to serve and support others, especially in emergency scenarios?', 41, 20211521, 1, '2024-11-04 15:39:03', '2024-11-04 15:39:03'),
(141, 'aaa', 141, 'a', 41, 20211521, 1, '2024-11-04 15:39:03', '2024-11-04 15:39:03');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_application_questions`
--

CREATE TABLE `tbl_application_questions` (
  `question_id` int(20) NOT NULL,
  `question` text NOT NULL,
  `club_id` int(20) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_application_questions`
--

INSERT INTO `tbl_application_questions` (`question_id`, `question`, `club_id`, `dateAdded`, `dateModified`) VALUES
(1, 'Why do you want to join NBSC Quick Response Team?', 1, '2024-11-04 02:44:20', '2024-11-04 14:44:37'),
(2, 'What skills or experiences do you have that will contribute to the club\'s activities?', 1, '2024-11-04 02:44:35', '2024-11-04 14:44:37'),
(3, 'How do you plan to balance your time between club activities and your academic responsibilities?', 1, '2024-11-04 02:44:49', '2024-11-04 14:44:37'),
(4, 'How would you handle high-pressure situations where immediate action is required?', 1, '2024-11-04 04:39:44', '2024-11-04 14:44:37'),
(5, 'What motivates you to serve and support others, especially in emergency scenarios?', 1, '2024-11-04 04:40:11', '2024-11-04 14:44:37'),
(6, 'Why do you want to join this club?', 2, '2024-11-04 04:40:32', '2024-11-04 04:50:47'),
(7, 'What skills or experiences do you have that will contribute to the club\'s activities?', 2, '2024-11-04 04:51:11', '2024-11-04 04:54:13'),
(8, 'How do you plan to balance your time between club activities and your academic responsibilities?', 2, '2024-11-04 04:52:46', '2024-11-04 04:54:17'),
(9, 'Describe your experience with any musical instruments or vocal performance. How do you see yourself contributing to the band\'s sound and performances?', 2, '2024-11-04 04:53:42', '2024-11-04 04:54:21'),
(10, 'Band practice and performances often require dedication and teamwork. How do you plan to contribute to the club’s collaborative environment and support other members in achieving our collective goals?', 2, '2024-11-04 04:53:42', '2024-11-04 04:54:24'),
(11, 'Why do you want to join this club?', 3, '2024-11-04 04:54:41', '2024-11-04 04:54:41'),
(12, 'What skills or experiences do you have that will contribute to the club\'s activities?', 3, '2024-11-04 04:54:58', '2024-11-04 04:54:58'),
(13, 'How do you plan to balance your time between club activities and your academic responsibilities?', 3, '2024-11-04 04:55:18', '2024-11-04 04:55:18'),
(14, 'How do you see yourself growing through your involvement with MAS-AMICUS, both personally and professionally?', 3, '2024-11-04 04:56:37', '2024-11-04 04:56:48'),
(15, 'What unique perspective or ideas do you hope to bring to the MAS-AMICUS club\'s activities and projects?', 3, '2024-11-04 04:56:37', '2024-11-04 04:56:37'),
(16, 'Why do you want to join Muslim Student\'s Society?', 4, '2024-11-04 04:58:06', '2024-11-04 12:20:47'),
(17, 'What skills or experiences do you have that will contribute to the club\'s activities?', 4, '2024-11-04 04:58:06', '2024-11-04 04:58:06'),
(18, 'How do you plan to balance your time between club activities and your academic responsibilities?', 4, '2024-11-04 04:58:54', '2024-11-04 04:58:54'),
(19, 'How do you see this club supporting your personal and spiritual growth as a member of the Muslim community?', 4, '2024-11-04 04:58:54', '2024-11-04 04:58:54'),
(20, 'In what ways would you like to contribute to promoting cultural understanding and respect through our club’s activities?', 4, '2024-11-04 04:59:09', '2024-11-04 04:59:09'),
(21, 'Why do you want to join this club?', 5, '2024-11-04 04:59:45', '2024-11-04 08:52:35'),
(22, 'What skills or experiences do you have that will contribute to the club\'s activities?', 5, '2024-11-04 04:59:45', '2024-11-04 04:59:45'),
(23, 'How do you plan to balance your time between club activities and your academic responsibilities?', 5, '2024-11-04 05:00:28', '2024-11-04 05:00:28'),
(24, 'Describe a memorable performance you’ve seen or participated in. What impact did it have on you?', 5, '2024-11-04 05:00:28', '2024-11-04 05:00:28'),
(25, 'How do you handle constructive criticism and feedback on your performances?', 5, '2024-11-04 05:00:42', '2024-11-04 05:00:42'),
(26, 'Why do you want to join this club?', 6, '2024-11-04 05:01:17', '2024-11-04 05:01:17'),
(27, 'What skills or experiences do you have that will contribute to the club\'s activities?', 6, '2024-11-04 05:01:17', '2024-11-04 05:01:17'),
(28, 'How do you plan to balance your time between club activities and your academic responsibilities?', 6, '2024-11-04 05:01:55', '2024-11-04 05:01:55'),
(29, 'What historical events or figures inspire you the most, and why?', 6, '2024-11-04 05:01:55', '2024-11-04 05:01:55'),
(30, 'How do you think joining the Young Historians Club will enhance your understanding of history?', 6, '2024-11-04 05:02:08', '2024-11-04 05:02:08'),
(31, 'Why do you want to join this club?', 7, '2024-11-04 05:02:48', '2024-11-04 05:02:48'),
(32, 'What skills or experiences do you have that will contribute to the club\'s activities?', 7, '2024-11-04 05:02:48', '2024-11-04 05:02:48'),
(33, 'How do you plan to balance your time between club activities and your academic responsibilities?', 7, '2024-11-04 05:03:25', '2024-11-04 05:03:25'),
(34, 'What is your favorite book or author, and how has it influenced your understanding of English literature?', 7, '2024-11-04 05:03:25', '2024-11-04 05:03:25'),
(35, 'How do you envision contributing to club activities, such as discussions, workshops, or events related to English language and literature?', 7, '2024-11-04 05:03:35', '2024-11-04 05:03:35'),
(36, 'Why do you want to join this club?', 8, '2024-11-04 05:04:10', '2024-11-04 05:04:10'),
(37, 'What skills or experiences do you have that will contribute to the club\'s activities?', 8, '2024-11-04 05:04:10', '2024-11-04 05:04:10'),
(38, 'How do you plan to balance your time between club activities and your academic responsibilities?', 8, '2024-11-04 05:04:45', '2024-11-04 05:04:45'),
(39, 'What specific areas of math or science are you most passionate about, and why?', 8, '2024-11-04 05:04:45', '2024-11-04 05:04:45'),
(40, 'How do you envision contributing to group projects or activities within the club?', 8, '2024-11-04 05:04:58', '2024-11-04 05:04:58'),
(41, 'Why do you want to join this club?', 9, '2024-11-04 05:05:22', '2024-11-04 05:05:22'),
(42, 'What skills or experiences do you have that will contribute to the club\'s activities?', 9, '2024-11-04 05:05:22', '2024-11-04 05:05:22'),
(43, 'How do you plan to balance your time between club activities and your academic responsibilities?', 9, '2024-11-04 05:06:00', '2024-11-04 05:06:00'),
(44, 'What unique perspectives or ideas do you bring that could enhance the activities of the KAMFIL Club?', 9, '2024-11-04 05:06:00', '2024-11-04 05:06:00'),
(45, 'How do you envision your role in the KAMFIL Club, and what specific contributions do you hope to make to its success?', 9, '2024-11-04 05:06:16', '2024-11-04 05:06:16'),
(46, 'Why do you want to join Mountaineering Society?', 10, '2024-11-04 05:07:14', '2024-11-04 14:02:22'),
(47, 'What skills or experiences do you have that will contribute to the club\'s activities?', 10, '2024-11-04 05:07:14', '2024-11-04 14:02:22'),
(48, 'How do you plan to balance your time between club activities and your academic responsibilities?', 10, '2024-11-04 05:07:38', '2024-11-04 14:02:22'),
(49, 'What motivates you to participate in mountaineering and outdoor activities?', 10, '2024-11-04 05:07:38', '2024-11-04 14:02:22'),
(50, 'How do you ensure safety and preparedness while participating in hiking or climbing activities?', 10, '2024-11-04 05:07:59', '2024-11-04 14:02:22'),
(51, 'Why do you want to join this club?', 11, '2024-11-04 05:09:22', '2024-11-04 05:09:22'),
(52, 'What skills or experiences do you have that will contribute to the club\'s activities?', 11, '2024-11-04 05:09:22', '2024-11-04 05:09:22'),
(53, 'How do you plan to balance your time between club activities and your academic responsibilities?', 11, '2024-11-04 05:10:01', '2024-11-04 05:10:01'),
(54, 'What topics are you most passionate about debating, and why?', 11, '2024-11-04 05:10:01', '2024-11-04 05:10:01'),
(55, 'Describe a situation where you had to defend your viewpoint against opposing opinions. How did you handle it?', 11, '2024-11-04 05:10:13', '2024-11-04 05:10:13'),
(56, 'Why do you want to join this club?', 12, '2024-11-04 05:10:47', '2024-11-04 05:10:47'),
(57, 'What skills or experiences do you have that will contribute to the club\'s activities?', 12, '2024-11-04 05:10:47', '2024-11-04 05:10:47'),
(58, 'How do you plan to balance your time between club activities and your academic responsibilities?', 12, '2024-11-04 05:11:23', '2024-11-04 05:11:23'),
(59, 'What artistic mediums or techniques are you most passionate about, and how do you plan to incorporate them into the club\'s activities?', 12, '2024-11-04 05:11:23', '2024-11-04 05:11:23'),
(60, 'Can you describe a previous project or artwork that you created? What did you learn from that experience, and how do you think it can contribute to the Arts Society?', 12, '2024-11-04 05:11:50', '2024-11-04 05:11:50'),
(61, 'Why do you want to join this club?', 13, '2024-11-04 05:14:12', '2024-11-04 05:14:12'),
(62, 'What skills or experiences do you have that will contribute to the club\'s activities?', 13, '2024-11-04 05:14:12', '2024-11-04 05:14:12'),
(63, 'How do you plan to balance your time between club activities and your academic responsibilities?', 13, '2024-11-04 05:14:40', '2024-11-04 05:14:40'),
(64, 'What is your understanding of indigenous cultures and their significance in our society today?', 13, '2024-11-04 05:14:40', '2024-11-04 05:14:40'),
(65, 'How do you plan to promote awareness and understanding of indigenous issues within the campus community?', 13, '2024-11-04 05:14:50', '2024-11-04 05:14:50'),
(66, 'Why do you want to join this club?', 14, '2024-11-04 05:16:01', '2024-11-04 05:16:01'),
(67, 'What skills or experiences do you have that will contribute to the club\'s activities?', 14, '2024-11-04 05:16:01', '2024-11-04 05:16:01'),
(68, 'How do you plan to balance your time between club activities and your academic responsibilities?', 14, '2024-11-04 05:16:42', '2024-11-04 05:16:42'),
(69, 'How do you see your faith influencing your involvement in the club\'s activities and service projects?', 14, '2024-11-04 05:16:42', '2024-11-04 05:16:42'),
(70, 'What ideas do you have for community service projects that the club could undertake to make a positive impact?', 14, '2024-11-04 05:16:52', '2024-11-04 05:16:52'),
(71, 'Why do you want to join this club?', 15, '2024-11-04 05:17:18', '2024-11-04 05:17:18'),
(72, 'What skills or experiences do you have that will contribute to the club\'s activities?', 15, '2024-11-04 05:17:18', '2024-11-04 05:17:18'),
(73, 'How do you plan to balance your time between club activities and your academic responsibilities?', 15, '2024-11-04 05:17:55', '2024-11-04 05:17:55'),
(74, 'What qualities do you believe are important for a peer counselor, and how do you embody these qualities?', 15, '2024-11-04 05:17:55', '2024-11-04 05:17:55'),
(75, 'Describe a time when you helped someone through a difficult situation. What did you learn from that experience?', 15, '2024-11-04 05:18:08', '2024-11-04 05:18:08'),
(76, 'Why do you want to join this club?', 16, '2024-11-04 05:18:37', '2024-11-04 05:18:37'),
(77, 'What skills or experiences do you have that will contribute to the club\'s activities?', 16, '2024-11-04 05:18:37', '2024-11-04 05:18:37'),
(78, 'How do you plan to balance your time between club activities and your academic responsibilities?', 16, '2024-11-04 05:19:19', '2024-11-04 05:19:19'),
(79, 'What sports do you currently participate in or have experience with? Please describe your level of involvement.', 16, '2024-11-04 05:19:19', '2024-11-04 05:19:19'),
(80, 'How do you plan to contribute to the team\'s success and promote sportsmanship within the club?', 16, '2024-11-04 05:19:28', '2024-11-04 05:19:28'),
(81, 'Why do you want to join this club?', 17, '2024-11-04 05:20:07', '2024-11-04 05:20:07'),
(82, 'What skills or experiences do you have that will contribute to the club\'s activities?', 17, '2024-11-04 05:20:07', '2024-11-04 05:20:07'),
(83, 'How do you plan to balance your time between club activities and your academic responsibilities?', 17, '2024-11-04 05:20:39', '2024-11-04 05:20:39'),
(84, 'What specific environmental issues are you passionate about, and how do you plan to address them as a member of the club?', 17, '2024-11-04 05:20:39', '2024-11-04 05:20:39'),
(85, 'Describe any previous volunteer work or projects you have participated in related to environmental conservation or sustainability. What did you learn from those experiences?', 17, '2024-11-04 05:20:51', '2024-11-04 05:20:51'),
(86, 'Why do you want to join this club?', 18, '2024-11-04 05:21:21', '2024-11-04 05:21:21'),
(87, 'What skills or experiences do you have that will contribute to the club\'s activities?', 18, '2024-11-04 05:21:21', '2024-11-04 05:21:21'),
(88, 'How do you plan to balance your time between club activities and your academic responsibilities?', 18, '2024-11-04 05:21:57', '2024-11-04 05:21:57'),
(89, 'What styles of dance are you familiar with, and how do you plan to incorporate them into the troupe\'s performances?', 18, '2024-11-04 05:21:57', '2024-11-04 05:21:57'),
(90, 'Can you describe a time when you worked as part of a team in a performance or practice setting? What role did you play, and what did you learn from the experience?', 18, '2024-11-04 05:22:10', '2024-11-04 05:22:10'),
(91, 'Why do you want to join this club?', 19, '2024-11-04 05:22:39', '2024-11-04 05:22:39'),
(92, 'What skills or experiences do you have that will contribute to the club\'s activities?', 19, '2024-11-04 05:22:39', '2024-11-04 05:22:39'),
(93, 'How do you plan to balance your time between club activities and your academic responsibilities?', 19, '2024-11-04 05:23:17', '2024-11-04 05:23:17'),
(94, 'What type of music do you enjoy singing, and how do you believe it aligns with the repertoire of the NBSC Chorale?', 19, '2024-11-04 05:23:17', '2024-11-04 05:23:17'),
(95, 'Have you had any previous experience in choral singing or musical performances? If so, please describe your experiences and what you learned from them.', 19, '2024-11-04 05:23:29', '2024-11-04 05:23:29'),
(96, 'Why do you want to join this club?', 21, '2024-11-04 05:24:06', '2024-11-04 05:24:06'),
(97, 'What skills or experiences do you have that will contribute to the club\'s activities?', 21, '2024-11-04 05:24:06', '2024-11-04 05:24:06'),
(98, 'How do you plan to balance your time between club activities and your academic responsibilities?', 21, '2024-11-04 05:24:36', '2024-11-04 05:24:36'),
(99, 'What teaching methodologies or approaches do you believe are most effective, and how would you incorporate them into club activities?', 21, '2024-11-04 05:24:36', '2024-11-04 05:24:36'),
(100, 'How do you envision contributing to the club\'s goals and initiatives, and what specific ideas do you have for projects or events?', 21, '2024-11-04 05:24:46', '2024-11-04 05:24:46'),
(101, 'Why do you want to join Infotech Club?', 22, '2024-11-04 05:25:13', '2024-11-04 11:58:07'),
(102, 'What skills or experiences do you have that will contribute to the club\'s activities?', 22, '2024-11-04 05:25:13', '2024-11-04 05:25:13'),
(103, 'How do you plan to balance your time between club activities and your academic responsibilities?', 22, '2024-11-04 05:25:45', '2024-11-04 05:25:45'),
(104, 'What specific technologies or programming languages are you familiar with, and how do you plan to use them within the club?', 22, '2024-11-04 05:25:45', '2024-11-04 05:25:45'),
(105, 'Describe a project or experience where you utilized your technical skills. What challenges did you face, and how did you overcome them?', 22, '2024-11-04 05:25:54', '2024-11-04 05:25:54'),
(106, 'Why do you want to join this club?', 23, '2024-11-04 05:26:28', '2024-11-04 05:26:28'),
(107, 'What skills or experiences do you have that will contribute to the club\'s activities?', 23, '2024-11-04 05:26:28', '2024-11-04 05:26:28'),
(108, 'How do you plan to balance your time between club activities and your academic responsibilities?', 23, '2024-11-04 05:27:03', '2024-11-04 05:27:03'),
(109, 'Describe a situation where you helped someone in need. What did you learn from that experience?', 23, '2024-11-04 05:27:03', '2024-11-04 05:27:03'),
(110, 'How do you envision contributing to community service initiatives through the Red-Cross Youth club?', 23, '2024-11-04 05:27:15', '2024-11-04 05:27:15'),
(111, 'Why do you want to join this club?', 24, '2024-11-04 05:28:00', '2024-11-04 05:28:00'),
(112, 'What skills or experiences do you have that will contribute to the club\'s activities?', 24, '2024-11-04 05:28:00', '2024-11-04 05:28:00'),
(113, 'How do you plan to balance your time between club activities and your academic responsibilities?', 24, '2024-11-04 05:28:30', '2024-11-04 05:28:30'),
(114, 'What academic achievements or extracurricular activities demonstrate your commitment to scholarship and leadership?', 24, '2024-11-04 05:28:30', '2024-11-04 05:28:30'),
(115, 'How do you envision contributing to the community within the Scholar\'s Society, and what initiatives would you like to propose or support?', 24, '2024-11-04 05:28:40', '2024-11-04 05:28:40'),
(116, 'Why do you want to join this club?', 25, '2024-11-04 05:29:25', '2024-11-04 05:29:25'),
(117, 'What skills or experiences do you have that will contribute to the club\'s activities?', 25, '2024-11-04 05:29:25', '2024-11-04 05:29:25'),
(118, 'How do you plan to balance your time between club activities and your academic responsibilities?', 25, '2024-11-04 05:31:06', '2024-11-04 05:31:06'),
(119, 'How do you define your faith, and how has it influenced your life choices?', 25, '2024-11-04 05:31:06', '2024-11-04 05:31:06'),
(120, 'What role do you see yourself playing in the Campus Seekers of Christ community, and how do you hope to contribute to our activities and outreach efforts?', 25, '2024-11-04 05:31:16', '2024-11-04 05:31:16'),
(121, 'Why do you want to join this club?', 42, '2024-11-04 05:33:08', '2024-11-04 05:33:08'),
(122, 'What skills or experiences do you have that will contribute to the club\'s activities?', 42, '2024-11-04 05:33:08', '2024-11-04 05:33:08'),
(123, 'How do you plan to balance your time between club activities and your academic responsibilities?', 42, '2024-11-04 05:33:55', '2024-11-04 05:33:55'),
(124, 'What is your experience with string instruments or related musical activities, and how do you think it will enhance your contribution to the club?', 42, '2024-11-04 05:33:55', '2024-11-04 05:33:55'),
(125, 'In your opinion, how can music and symbolism be integrated to create impactful artistic expressions, and how would you like to explore this in our club?', 42, '2024-11-04 05:34:09', '2024-11-04 05:34:09'),
(126, 'Why do you want to join this club?', 43, '2024-11-04 05:34:37', '2024-11-04 05:34:37'),
(127, 'What skills or experiences do you have that will contribute to the club\'s activities?', 43, '2024-11-04 05:34:37', '2024-11-04 05:34:37'),
(128, 'How do you plan to balance your time between club activities and your academic responsibilities?', 43, '2024-11-04 05:35:15', '2024-11-04 05:35:15'),
(129, 'What ideas or initiatives do you have that could enhance the activities or outreach of YASM?', 43, '2024-11-04 05:35:15', '2024-11-04 05:35:15'),
(130, 'How do you envision your involvement in YASM impacting your personal and professional growth?', 43, '2024-11-04 05:35:27', '2024-11-04 05:35:27'),
(131, 'Why do you want to join this club?', 44, '2024-11-04 05:36:03', '2024-11-04 05:36:03'),
(132, 'What skills or experiences do you have that will contribute to the club\'s activities?', 44, '2024-11-04 05:36:03', '2024-11-04 05:36:03'),
(133, 'How do you plan to balance your time between club activities and your academic responsibilities?', 44, '2024-11-04 05:36:33', '2024-11-04 05:36:33'),
(134, 'What ideas or topics would you like to explore in our publications, and how do you envision contributing to the creative process?', 44, '2024-11-04 05:36:33', '2024-11-04 05:36:33'),
(135, 'How do you handle constructive criticism, and can you provide an example of a time you used feedback to improve your work?', 44, '2024-11-04 05:36:46', '2024-11-04 05:36:46'),
(136, 'Why do you want to join this club?', 45, '2024-11-04 05:37:29', '2024-11-04 05:37:29'),
(137, 'What skills or experiences do you have that will contribute to the club\'s activities?', 45, '2024-11-04 05:37:29', '2024-11-04 05:37:29'),
(138, 'How do you plan to balance your time between club activities and your academic responsibilities?', 45, '2024-11-04 05:38:11', '2024-11-04 05:38:11'),
(139, 'How do you envision your faith influencing your participation in club activities?', 45, '2024-11-04 05:38:11', '2024-11-04 05:38:11'),
(140, 'What specific contributions do you hope to make to the Campus Bible Fellowship community?', 45, '2024-11-04 05:38:23', '2024-11-04 05:38:23'),
(143, 'b', 10, '2024-11-04 15:11:43', '2024-11-04 15:11:43');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_chats`
--

CREATE TABLE `tbl_chats` (
  `chat_id` int(20) NOT NULL,
  `message` text NOT NULL,
  `replied_id` text DEFAULT NULL,
  `sender_id` int(20) NOT NULL,
  `recipient_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_chats`
--

INSERT INTO `tbl_chats` (`chat_id`, `message`, `replied_id`, `sender_id`, `recipient_id`, `club_id`, `dateAdded`, `dateModified`) VALUES
(69, 'Hi Sir Idol', NULL, 20191124, 22230001, 1, '2024-10-13 11:54:21', '2024-10-13 11:54:21'),
(70, 'Hello. How are you?', NULL, 22230001, 20191124, 1, '2024-10-13 11:54:40', '2024-10-13 11:54:40'),
(78, 'hello', NULL, 20191124, 22230001, 1, '2024-10-15 01:46:18', '2024-10-15 01:46:18'),
(79, 'I\'m fine, Sir!', NULL, 20191124, 22230001, 1, '2024-10-20 12:36:23', '2024-10-20 12:36:23');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clubs`
--

CREATE TABLE `tbl_clubs` (
  `club_id` int(20) NOT NULL,
  `clubName` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `mission` text NOT NULL,
  `vision` text NOT NULL,
  `history` text NOT NULL,
  `keywords` text NOT NULL,
  `coverPhoto` varchar(200) NOT NULL,
  `slots` int(20) DEFAULT NULL,
  `founder_id` int(20) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_clubs`
--

INSERT INTO `tbl_clubs` (`club_id`, `clubName`, `description`, `mission`, `vision`, `history`, `keywords`, `coverPhoto`, `slots`, `founder_id`, `dateAdded`, `dateModified`) VALUES
(1, 'NBSC Quick Response Team', 'The NBSC Quick Response Team (QRT) is a dedicated student organization at NBSC College, focused on providing rapid assistance and support in emergency situations within the campus. Comprised of well-trained and committed students, the QRT specializes in first aid, emergency response, and disaster preparedness. The club regularly conducts training sessions and workshops in collaboration with local emergency services to ensure its members are equipped with the latest knowledge and skills. This proactive approach not only enhances the safety and well-being of the NBSC community but also fosters a culture of readiness and resilience among students.\r\nIn addition to emergency response, the NBSC Quick Response Team plays a significant role in promoting health and safety awareness across the campus. Through various outreach programs, the club educates students and staff on best practices for personal safety, emergency preparedness, and effective response strategies. The QRT also actively participates in campus-wide drills and simulations, ensuring that the entire college community is prepared to handle potential crises. By serving as a vital resource and advocate for safety, the NBSC Quick Response Team contributes to creating a secure and supportive environment at NBSC College.', 'The NBSC Quick Response Team is dedicated to promoting safety and preparedness across the Northern Bukidnon State College campus and community. Our mission is to empower students and faculty by providing them with the knowledge and tools to respond effectively during emergencies, ensuring a safe and secure environment for everyone. We aim to develop a well-trained team ready to act swiftly in the event of natural disasters, medical emergencies, and other critical situations.', 'We envision a campus where every individual is equipped with the skills and confidence to respond to emergencies. The NBSC Quick Response Team strives to be recognized as a leading force in campus safety, fostering a culture of readiness, resilience, and proactive response that extends beyond the campus and into the surrounding community.', 'The NBSC Quick Response Team was established in 2022 in response to growing concerns about emergency preparedness on campus. Initially formed by a small group of students passionate about disaster response and public safety, the team quickly grew with the support of the administration and local emergency agencies. Over the years, the club has conducted numerous training sessions, safety drills, and community outreach programs. Today, it stands as a vital part of campus life, ensuring that both students and faculty are prepared for emergencies, while also contributing to the overall safety and well-being of Northern Bukidnon State College.', '', 'COVERPHOTO_QUICKRESPONSETEAM.png', 250, 24251014, '2022-08-18 03:19:13', '2024-10-25 10:29:00'),
(2, 'NBSC Band Sound Space', 'The NBSC Band Sound Space is a dynamic and vibrant student organization at NBSC College, dedicated to cultivating musical talent and fostering a sense of community among its members. Established in 2010, the band has grown to become a staple of college events, performing at ceremonies, sports games, and various campus activities. The group welcomes students from all departments and years, encouraging collaboration and skill development across different musical genres, including classical, jazz, pop, and rock. The band practices regularly in the school\'s music hall, ensuring that members have ample opportunity to hone their craft and prepare for performances.\n\nIn addition to providing a platform for musical expression, the NBSC Band also emphasizes leadership and teamwork. Members have the opportunity to take on roles such as section leaders, event coordinators, and public relations officers, gaining valuable experience in organization and management. The band also participates in regional and national competitions, often earning accolades for their performances. Beyond the music, the NBSC Band fosters a supportive and inclusive environment, where students can build lasting friendships and develop a deep appreciation for the arts.', 'The NBSC Band Sound Space aims to inspire, nurture, and unite students through music, providing a creative space where passion, talent, and collaboration can thrive. We are dedicated to fostering artistic growth, building community, and showcasing diverse musical styles, while encouraging students to develop their skills and express themselves through performance and teamwork.', 'To become a leading hub of musical excellence at Northern Bukidnon State College, where students from all backgrounds can hone their talents, explore new genres, and contribute to a vibrant, inclusive music culture that resonates beyond the campus.', 'Founded in 2023, the NBSC Band Sound Space was created by a group of music-loving students who sought a dedicated platform to explore their musical passions. What began as informal jam sessions evolved into an official club that now hosts workshops, performances, and collaborations with other student organizations. Over the years, the club has grown in both membership and influence, becoming a staple of campus life with annual concerts, inter-school competitions, and a supportive community for aspiring musicians.', '', 'COVERPHOTO_NBSCBAND.png', 150, 24251014, '2023-03-19 15:23:09', '2024-10-23 15:06:48'),
(3, 'MAS-AMICUS', 'MAS-AMICUS, short for \"Mutual Aid System for Affiliates of the Medical Informatics Community in the United States,\" is a collaborative initiative aimed at fostering solidarity and support among medical informatics professionals across the country. Founded with the vision of enhancing professional development and knowledge sharing within the field of medical informatics, MAS-AMICUS provides a structured platform for members to engage in peer-to-peer learning, mentorship, and networking opportunities. Through its various programs and events, MAS-AMICUS strives to cultivate a community where members can exchange insights, discuss emerging trends, and address challenges in healthcare informatics.\r\n\r\nCentral to MAS-AMICUS\'s mission is the promotion of innovation and best practices in medical informatics. By facilitating dialogue and collaboration among its affiliates, MAS-AMICUS aims to drive advancements in healthcare technology and data management practices. Members benefit from access to resources such as workshops, webinars, and research forums that enable them to stay current with industry developments and contribute to the evolution of healthcare informatics standards. As a supportive network, MAS-AMICUS plays a pivotal role in empowering its members to navigate complexities within the healthcare landscape, ultimately enhancing patient care outcomes through the effective use of informatics solutions.', 'The MAS-AMICUS club strives to foster an inclusive, supportive, and diverse community where members can build lasting friendships, engage in meaningful dialogues, and contribute positively to both the campus and society. We aim to create a welcoming environment where every individual feels valued and encouraged to participate in activities that promote camaraderie, cultural exchange, and personal growth.', 'To be a leading student organization that cultivates a spirit of friendship, empathy, and inclusivity. MAS-AMICUS envisions a campus where individuals from all walks of life connect, support one another, and contribute to a unified, harmonious, and compassionate community.', 'Founded in 2015, MAS-AMICUS started as a small gathering of students who sought to create a space where everyone, regardless of background or beliefs, could feel welcome and appreciated. Over the years, it has grown into one of the most active and diverse clubs on campus, hosting a wide range of events, from cultural celebrations to community service projects. MAS-AMICUS has become a home for students seeking friendship, personal development, and a sense of belonging in the campus community. Its founding principles of respect, understanding, and unity continue to guide the club’s activities and mission today.', '', 'COVERPHOTO_MASAMICUS.png', NULL, 24251014, '2023-09-18 12:51:22', '2024-10-23 02:22:19'),
(4, 'Muslim Student\'s Society', 'The Muslim Student\'s Society (MSS) at NBSC is a vibrant and inclusive community dedicated to fostering spiritual growth, cultural understanding, and social responsibility. We organize a range of activities, including thought-provoking discussions, cultural festivals, and interfaith dialogues, aimed at deepening knowledge and appreciation of Islamic values and traditions. Our events provide a platform for students to connect, share experiences, and engage in meaningful conversations, creating a supportive environment for personal and spiritual development. Beyond our internal activities, MSS is committed to giving back to the community through various outreach programs and charity initiatives. By participating in community service projects and fundraising events, members contribute to positive social change while building a strong sense of camaraderie. Joining MSS not only offers a chance to strengthen one\'s faith and cultural identity but also to make a tangible impact on the lives of others, enhancing both personal growth and community well-being.', 'The Muslim Student\'s Society aims to provide a supportive and inclusive community for Muslim students, fostering spiritual growth, academic success, and cultural understanding. We strive to uphold Islamic values through service, education, and outreach while promoting unity among the Muslim Ummah and contributing positively to the broader campus environment.', 'Our vision is to become a leading student organization that inspires and empowers Muslim students to be active, engaged members of their faith and community. Through collaboration, education, and service, we envision a society where the values of Islam promote peace, understanding, and compassion within the university and beyond.', 'The Muslim Student\'s Society was established in 2023 by a group of passionate and dedicated students seeking a platform to connect with fellow Muslim students and share the teachings of Islam with the wider campus community. Since its inception, the society has organized numerous educational seminars, community service projects, and interfaith dialogues, steadily growing in membership and influence. Today, the society continues to uphold its founding principles of unity, knowledge, and service, while adapting to the changing needs of the student body.', '', 'COVERPHOTO_MUSLIMSTUDENT\'SSOCIETY.png', NULL, 24251014, '2023-11-18 12:51:22', '2024-10-23 02:25:54'),
(5, 'Dramatic Society', 'Join the Dramatic Society and immerse yourself in the vibrant world of theater and performance! Whether you\'re an aspiring actor, a backstage wizard, or just passionate about the arts, our club offers a creative outlet to explore and express your talents. With regular workshops, rehearsals, and performances, you\'ll have countless opportunities to hone your craft and showcase your skills. Our experienced mentors and supportive community are here to guide you every step of the way. Be part of a dynamic group where your passion for drama can flourish and make lasting friendships along the way.  As a member of the Dramatic Society, you\'ll gain hands-on experience in all aspects of theater production, from acting and directing to set design and stage management. We welcome students of all skill levels, providing a nurturing environment where you can develop your abilities and grow as a performer. Our club not only enhances your creativity but also builds confidence and teamwork skills that are valuable beyond the stage. Join us to participate in exciting projects, collaborate with like-minded individuals, and make a meaningful impact through the power of storytelling. Discover the thrill of live performance and become a key player in our artistic community.', 'Our mission is to foster a vibrant and inclusive environment where individuals can express themselves creatively through the performing arts. We aim to nurture talent, build confidence, and promote a deep appreciation for theatre and drama within our community by providing opportunities for training, performance, and collaboration.', 'To become a leading platform for artistic expression in the performing arts, where members develop lifelong skills in acting, directing, and production. We envision a society that cultivates both passion and professionalism, inspiring our community to embrace creativity, storytelling, and culture through dramatic performances.', 'Founded in 2023, the Dramatic Society was established by a group of passionate students who sought to create a space for theatre lovers to explore their creative talents. Starting with small performances in classrooms, the society quickly gained popularity and expanded its activities to include larger productions, workshops, and inter-collegiate drama competitions. Over the years, we have grown into a thriving community, known for our high-quality performances and dedication to the arts. Today, the Dramatic Society continues to uphold its tradition of excellence while embracing new forms of drama and performance art.', '', 'COVERPHOTO_DRAMATICSOCIETY.png', 150, 24251014, '2023-12-18 12:51:22', '2024-10-23 02:24:31'),
(6, 'Young Historians Club', 'Discover the Young Historians Club, where history comes alive and every member\'s voice matters. We delve into fascinating historical events, from ancient civilizations to modern times, exploring their impact on today\'s world. With engaging discussions, interactive projects, and historical reenactments, we offer a dynamic way to learn and connect with fellow history enthusiasts. Our club also provides opportunities to participate in history-themed competitions and attend exclusive events with guest speakers. Join us to deepen your understanding of the past while making lasting friendships.  Being part of the Young Historians Club means being at the forefront of historical exploration and analysis. We foster a supportive environment where members can share their perspectives, conduct research, and contribute to meaningful projects. Whether you have a passion for history or are looking to explore new interests, our club offers a welcoming space to expand your knowledge and skills. We believe that history is not just about the past but also about shaping the future through understanding. Come be a part of our journey and help us uncover the stories that define our world.', 'The Young Historians Club seeks to ignite a passion for history among students by fostering a deep understanding of the past, cultivating critical thinking, and encouraging research. Through dynamic discussions, educational trips, and interactive activities, the club aims to bridge the gap between historical events and their relevance to today’s world, empowering members to appreciate and preserve our cultural heritage.', 'We envision a community of young, curious minds united by a shared passion for history, equipped with the knowledge and analytical skills to become future leaders and advocates for the preservation of historical truths. Our goal is to inspire lifelong learning and to instill a commitment to uncovering and sharing diverse narratives that shape our collective future.', 'The Young Historians Club was founded in 2024 at Northern Bukidnon State College with the goal of providing students with a platform to explore and engage with history beyond the classroom. What started as a small group of like-minded students has since grown into one of the most active and respected clubs on campus. Over the years, the club has organized a variety of activities, including historical debates, museum visits, and heritage conservation projects, all aimed at promoting historical literacy and appreciation among the youth.', '', 'COVERPHOTO_YOUNGHISTORIANSCLUB.png', NULL, 24251014, '2024-01-18 12:51:22', '2024-10-23 02:27:38'),
(7, 'English Club', 'Join the English Club and immerse yourself in a vibrant community dedicated to the love of language and literature. Whether you\'re passionate about classic novels, contemporary poetry, or creative writing, our club offers a range of activities designed to spark your literary enthusiasm. Participate in engaging discussions, creative workshops, and exciting competitions that help you hone your writing and communication skills. Our members also enjoy exclusive access to author talks, book fairs, and literary events that enrich their understanding of the English language. Embrace the opportunity to connect with fellow students who share your interests and explore the endless possibilities that come with mastering English.  The English Club is not just about reading and writing; it\'s about building friendships and creating lasting memories. Join us for fun social events, including themed parties, movie nights, and group outings, all while improving your language skills. We offer mentorship and support for academic and personal growth, ensuring that every member feels valued and inspired. Take part in community service projects that use language to make a positive impact, and develop skills that will benefit you both academically and professionally. Become a part of a club where your passion for English can truly flourish and where your voice will be heard.', 'The mission of the English Club is to foster a love for the English language and literature among students, providing a welcoming environment for members to improve their language skills, engage in meaningful discussions, and explore diverse cultures through reading, writing, and creative expression. We aim to enhance members’ communication abilities and confidence in using English in both academic and social contexts.', 'The English Club envisions a vibrant community of learners who appreciate the richness of the English language and its literature. We aspire to be a platform that empowers students to articulate their thoughts, share their creativity, and develop critical thinking skills, ultimately preparing them for success in their academic pursuits and future careers. Our goal is to cultivate lifelong learners who are enthusiastic about language and its power to connect people across cultures.', 'The English Club was established in 2024 at Northern Bukidnon State College to address the growing interest in English language learning and literature among students. Since its inception, the club has organized various activities, including book readings, writing workshops, and cultural exchange events. Over the years, the English Club has grown from a small group of enthusiastic members to a thriving community, attracting students from diverse backgrounds. We have collaborated with local authors, hosted guest speakers, and participated in national writing competitions, continuously striving to enrich the experience of our members and promote a deeper understanding of the English language and its literary heritage.', '', 'COVERPHOTO_ENGLSIHCLUB.png', 150, 24251014, '2024-02-14 14:55:34', '2024-10-23 02:29:36'),
(8, 'Math-Sci Club', 'The Math-Sci Club offers an exciting opportunity for students who are passionate about mathematics and science to dive deeper into these fascinating fields. Members can participate in engaging activities such as solving complex problems, conducting experiments, and exploring cutting-edge technologies. The club regularly hosts workshops, guest lectures, and competitions that provide hands-on experience and enhance problem-solving skills. By joining, students gain access to a community of like-minded peers and mentors who are dedicated to fostering a love for STEM. This is not just a club; it\'s a gateway to academic and career growth in the fields of math and science.  Being a part of the Math-Sci Club means you\'ll be involved in innovative projects and collaborative research that push the boundaries of traditional learning. Our members enjoy exclusive access to various resources, including specialized software and research opportunities. We also organize field trips to science museums, laboratories, and tech companies, offering real-world insights into the industries they aspire to join. Whether you\'re aiming for a career in engineering, research, or education, the Math-Sci Club provides a supportive environment to develop your skills and achieve your goals. Join us and turn your curiosity into expertise while making lifelong connections in the world of math and science.', 'The Math-Sci Club aims to foster a love for mathematics and science among students by providing a collaborative environment that encourages exploration, creativity, and critical thinking. We strive to enhance academic performance, promote scientific inquiry, and develop problem-solving skills through engaging activities, workshops, and competitions.', 'We envision a vibrant community of learners who embrace the challenges of mathematics and science, inspiring one another to achieve excellence and innovation. The Math-Sci Club seeks to empower students to become confident, knowledgeable, and resourceful individuals who can contribute to the advancement of society through their understanding and application of mathematical and scientific principles.', 'Founded in 2024, the Math-Sci Club originated from a group of passionate students who recognized the need for a dedicated space to explore their interests in mathematics and science beyond the classroom. Since its inception, the club has organized various activities, including math competitions, science fairs, guest lectures from professionals in the field, and hands-on experiments. With a growing membership of enthusiastic students, the Math-Sci Club has become a cornerstone of the academic community, fostering collaboration and friendship among students while promoting a deeper understanding of the world through math and science.', '', 'COVERPHOTO_MATH-SCICLUB.png', 100, 24251014, '2024-03-15 02:05:15', '2024-10-23 02:30:29'),
(9, 'KAMFIL Club', 'The KAMFIL Club, which stands for \"Kabalikat ng Masisipag na Filipino\" or \"Companion of Diligent Filipinos,\" offers a vibrant and dynamic environment where students can engage in a range of exciting activities and make lasting friendships. As a member, you\'ll have the opportunity to participate in various workshops, seminars, and community service projects aimed at personal and professional development. Our club prides itself on fostering a collaborative atmosphere where your ideas are valued and you can take on leadership roles. Join us to enhance your skills, gain valuable experience, and be part of a supportive network of peers. Whether you\'re interested in developing new skills or contributing to meaningful causes, KAMFIL Club is the perfect place to start.  Being part of KAMFIL Club means you\'ll be involved in projects that make a real impact within our community and beyond. We provide numerous opportunities for networking with professionals, engaging in hands-on experiences, and working on projects that align with your interests. Our members enjoy exclusive access to events and resources designed to help you succeed both academically and personally. With a focus on growth and collaboration, KAMFIL Club is dedicated to helping you achieve your goals and make the most of your college experience. Discover the benefits of joining a club that values innovation, teamwork, and community.', 'The KAMFIL Club is dedicated to fostering a vibrant community of students who share a passion for knowledge, creativity, and collaboration. Our mission is to provide a supportive environment where members can explore their interests, develop their skills, and contribute positively to the campus and local community through various activities, workshops, and outreach programs.', 'Our vision is to become a leading student organization recognized for inspiring personal growth, academic excellence, and social responsibility. We aim to cultivate leaders who are equipped to make a difference in their communities and the world, embracing diversity and promoting inclusivity within our club and beyond.', 'Founded in 2024, the KAMFIL Club emerged from a collective desire among students to create a space for sharing ideas and fostering a sense of belonging. Initially started as a small group of friends, the club quickly gained traction and attracted members from various disciplines. Over the years, KAMFIL has organized numerous events, including guest lectures, community service projects, and cultural exchanges, establishing itself as a cornerstone of student life. With a commitment to continuous improvement and innovation, the KAMFIL Club continues to evolve, adapting to the needs and interests of its members while maintaining its core values of collaboration and community engagement.', '', 'COVERPHOTO_KAMFILCLUB.png', 150, 24251014, '2024-03-15 02:05:15', '2024-10-23 02:31:27'),
(10, 'Mountaineering Society', 'The Mountaineering Society is the perfect club for students seeking adventure and personal growth. Join us to explore breathtaking mountain trails, tackle thrilling climbs, and develop essential outdoor skills in a supportive community. ...Our activities cater to all skill levels, from beginners to seasoned hikers, ensuring everyone can enjoy the thrill of mountaineering safely. By becoming a member, you’ll not only challenge yourself but also make lasting friendships with fellow enthusiasts who share your passion for the great outdoors. Don’t miss out on this opportunity to push your limits and experience the world from a new perspective.  In addition to our regular hikes and climbs, the Mountaineering Society offers workshops on navigation, survival techniques, and environmental stewardship. Our experienced guides and instructors are dedicated to providing a comprehensive learning experience while ensuring your safety and enjoyment. We also host social events and team-building activities, creating a vibrant and inclusive environment. Whether you\\\'re looking to conquer new heights or simply connect with nature, the Mountaineering Society is your gateway to an exhilarating and rewarding experience. Join us and embark on your next adventure with a community that truly understands the spirit of mountaineering.', 'The Mountaineering Society aims to inspire and unite outdoor enthusiasts through the pursuit of mountaineering and adventure. We are dedicated to promoting safe and responsible climbing practices, fostering environmental stewardship, and encouraging personal growth and teamwork among our members. Our mission is to create a supportive community that empowers individuals to explore the great outdoors while cultivating a passion for mountaineering.', 'Our vision is to be a leading mountaineering club that promotes adventure, resilience, and environmental awareness. We strive to connect people from diverse backgrounds through shared experiences in nature, fostering a sense of belonging and adventure. By cultivating a culture of safety, education, and respect for the mountains, we envision a future where everyone can experience the transformative power of the outdoors.', 'Founded in 2024, the Mountaineering Society began as a small group of passionate climbers who sought to share their love for the mountains and outdoor adventure. Over the years, the club has grown significantly, attracting members from all walks of life, from novice climbers to seasoned mountaineers.\r\n\r\nThe club has organized numerous expeditions, workshops, and events, focusing on skill development, environmental conservation, and community engagement. In the same year, we launched our flagship program, the “Climb for Conservation,” which combines our love for mountaineering with efforts to protect and restore the natural environments we cherish.\r\n\r\nToday, the Mountaineering Society is a vibrant community that continues to inspire adventure and promote safe climbing practices, ensuring that the spirit of mountaineering thrives for generations to come.', '', 'COVERPHOTO_MOUNTAINEERINGSOCIETY.png', 150, 24251014, '2024-05-16 02:05:15', '2024-05-16 02:05:15'),
(11, 'Debate Club', 'Join the Debate Club and sharpen your critical thinking skills while engaging in stimulating discussions on a wide range of topics. This club provides a dynamic platform for students to articulate their opinions, build strong arguments, and enhance public speaking abilities. Whether you\'re passionate about current events, politics, or social issues, the Debate Club offers an opportunity to explore and debate these subjects with peers. You\'ll gain valuable experience in research, teamwork, and persuasive communication that can benefit you in both academic and professional settings. By joining, you become part of a community that values intellectual growth and the exchange of diverse perspectives.  In addition to weekly debates and meetings, the Debate Club participates in local and national competitions, allowing members to showcase their skills on a larger stage. The club\'s supportive environment encourages members to practice and perfect their debating techniques, receive constructive feedback, and celebrate each other\'s successes. Joining the Debate Club means becoming a part of a network of like-minded individuals who are committed to learning and personal development. Embrace the challenge, develop lifelong skills, and make lasting friendships by joining the Debate Club today.', 'The mission of the Debate Club is to foster critical thinking, effective communication, and respectful discourse among students. We aim to create a supportive environment where members can develop their debating skills, engage with diverse perspectives, and cultivate confidence in public speaking. Through regular debates, workshops, and competitions, we strive to empower members to articulate their ideas clearly and persuasively.', 'The Debate Club envisions a community of articulate and informed individuals who are equipped to engage in thoughtful discussions on pressing social, political, and ethical issues. We aspire to be a leading platform for students to enhance their analytical abilities and public speaking skills, inspiring a new generation of leaders who value reasoned argumentation and collaboration.', 'Founded in 2024, the Debate Club began as a small gathering of students passionate about discussing current events and social issues. Over the years, it has grown into a vibrant organization with a diverse membership, hosting weekly debates and participating in regional and national competitions. The club has achieved numerous accolades, reflecting the dedication and talent of its members. Our history is marked by a commitment to inclusivity, innovation in debate formats, and a focus on community engagement, with initiatives that encourage public dialogue and promote civic awareness. As we look to the future, the Debate Club remains dedicated to empowering students to become articulate advocates for change in their communities.', '', 'COVERPHOTO_DEBATECLUB.png', 150, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:35:26'),
(12, 'Arts Society', 'The Arts Society is a vibrant and inclusive community dedicated to fostering creativity and artistic expression. Our club provides a dynamic platform for students to explore various art forms, from painting and sculpture to digital design and performance arts. By joining, you\'ll gain access to exclusive workshops, exhibitions, and collaborative projects that will help you refine your skills and build a strong portfolio. Whether you’re a seasoned artist or just starting out, our supportive environment encourages growth and self-expression. We believe in the power of art to inspire, connect, and transform lives, and we invite you to be a part of this exciting journey.  As a member of the Arts Society, you\'ll have the opportunity to work alongside passionate peers and experienced mentors who share your enthusiasm for the arts. Our club regularly hosts events such as art shows, open mic nights, and community outreach programs that not only showcase your talents but also engage with the broader community. Networking with fellow artists and participating in collaborative projects will expand your creative horizons and provide valuable experience. Join us to be part of a creative family that celebrates diversity, innovation, and artistic excellence. Your unique perspective and creativity will contribute to our vibrant community, making a lasting impact on both your personal development and the art world.', 'The mission of the Arts Society is to foster a vibrant community of artists and art enthusiasts dedicated to exploring, creating, and celebrating various forms of art. We aim to provide a supportive environment where members can collaborate, share knowledge, and showcase their talents while promoting appreciation for the arts within our community.', 'Our vision is to be a leading platform for artistic expression and cultural engagement, where individuals from diverse backgrounds come together to inspire creativity and innovation. We aspire to cultivate a love for the arts that transcends boundaries, encouraging lifelong learning and enriching the lives of our members and the wider community.', 'Founded in 2024, the Arts Society began as a small group of passionate artists seeking a space to connect and share their work. Over the years, it has grown into a thriving community organization. The club has hosted numerous exhibitions, workshops, and events, collaborating with local schools and organizations to promote art education and appreciation. Through our initiatives, we have created opportunities for emerging artists to gain exposure, while also celebrating the rich history and diversity of artistic practices. As we move forward, we remain committed to our founding principles of inclusivity, creativity, and community engagement.', '', 'COVERPHOTO_ARTSSOCIETY.png', 150, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:37:52'),
(13, 'Indigenous People Society', 'The Indigenous People Society offers a unique opportunity for students to engage with and support indigenous communities and cultures. Through various activities and initiatives, members learn about traditional practices, languages, and the rich heritage of indigenous peoples. The club organizes workshops, cultural events, and community outreach programs that foster a deeper understanding and appreciation of these vital cultures. Joining this society allows you to contribute to preserving and promoting indigenous traditions while gaining valuable insights and experiences. It’s an ideal way to make a meaningful impact and broaden your cultural horizons.  Being part of the Indigenous People Society connects you with a diverse group of passionate individuals who share an interest in social justice and cultural preservation. You will have the chance to collaborate on projects that address current issues facing indigenous communities, from education to environmental sustainability. The club also provides a platform for you to develop leadership and organizational skills through hands-on involvement in planning and executing events. By participating, you become an advocate for important causes and help drive positive change in the community. Join us to be part of a movement that celebrates and respects the rich tapestry of indigenous cultures.', 'The Indigenous People Society aims to celebrate, preserve, and promote the rich cultural heritage of Indigenous communities. We strive to create a supportive environment for education, advocacy, and empowerment, fostering understanding and respect for Indigenous traditions, rights, and contributions to society.', 'We envision a world where Indigenous cultures are honored and respected, and Indigenous voices are heard and valued. Through our initiatives, we aspire to build a bridge between Indigenous and non-Indigenous communities, fostering collaboration, respect, and appreciation for the diverse perspectives that shape our society.', 'Founded in 2024, the Indigenous People Society emerged from a collective desire to raise awareness about Indigenous issues and promote cultural preservation among younger generations. Recognizing the challenges faced by Indigenous communities, including cultural erosion and social inequality, our founders sought to create a platform for dialogue, education, and activism. Since our inception, we have organized cultural events, workshops, and community outreach programs aimed at educating the public about Indigenous histories, languages, and traditions. As we grow, we remain committed to fostering partnerships with Indigenous leaders and organizations to amplify our efforts and ensure that Indigenous voices lead our initiatives.', '', 'COVERPHOTO_INDIGENOUSPEOPLESOCIETY.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:39:05'),
(14, 'Young Catholic Servants of Christ', 'The Young Catholic Servants of Christ is a vibrant and welcoming community dedicated to fostering spiritual growth and active service among students. By joining, you’ll engage in meaningful activities that promote personal development, leadership, and a deeper connection with your faith. Our club offers regular retreats, prayer meetings, and community outreach programs that not only enrich your spiritual life but also contribute positively to the surrounding community. We believe in creating a supportive environment where every member can grow and thrive. Whether you’re looking to deepen your faith or make a difference, YCSC provides opportunities for both personal and communal fulfillment.  As a member of YCSC, you will be part of a dynamic group of like-minded peers who are passionate about making a difference. We organize a range of events and projects that allow you to apply your skills and interests in a way that benefits others. Additionally, the club provides a platform for building lasting friendships and developing skills that will serve you well in all areas of life. Joining YCSC means becoming part of a legacy of service and faith, with numerous opportunities for growth and impact. Embrace the chance to be a part of something greater and make a positive change in the world around you.', 'The Young Catholic Servants of Christ club is dedicated to fostering spiritual growth, community service, and leadership among young Catholics. We aim to inspire and empower our members to live their faith actively through service, fellowship, and devotion to Christ. By engaging in charitable activities and forming meaningful relationships, we strive to become beacons of hope and love in our communities.', 'Our vision is to cultivate a generation of young leaders grounded in Catholic values who actively contribute to the well-being of their communities and the Church. We aspire to create an inclusive environment where young people can explore their faith, develop their spiritual gifts, and serve others with joy and compassion. Together, we envision a future where young Catholics are united in purpose, driven by love, and committed to making a positive impact in the world.', 'Founded in 2024, the Young Catholic Servants of Christ club emerged from a desire among young people to deepen their faith and engage in meaningful service. Recognizing the need for a supportive community that encourages spiritual growth and active participation in the Church, a group of dedicated youth came together to establish the club.\r\n\r\nSince its inception, the club has organized numerous activities, including community service projects, faith-sharing sessions, and retreats, fostering a vibrant and welcoming atmosphere. With a focus on developing leadership skills and encouraging a spirit of service, the Young Catholic Servants of Christ club continues to grow, welcoming new members who are eager to make a difference in their lives and the lives of others.', '', 'COVERPHOTO_YOUNGCATHOLICSERVANTSOFCHRIST.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:40:14'),
(15, 'Peer Counselor\'s Club', 'Join the Peer Counselor\'s Club and become a vital part of a supportive community dedicated to helping fellow students thrive. Our club offers a unique opportunity to develop essential skills in counseling, communication, and leadership while making a meaningful impact on campus. As a member, you\'ll gain hands-on experience in providing peer support, organizing workshops, and participating in various community outreach programs. Engage in regular training sessions and collaborate with like-minded individuals who are passionate about making a difference in others\' lives.  In addition to personal growth and skill development, the Peer Counselor\'s Club provides a platform for building lasting friendships and networking with professionals in the field of mental health and counseling. By joining, you\'ll be part of a dynamic team committed to fostering a positive and inclusive campus environment. Take advantage of this chance to enhance your resume, gain valuable life experience, and contribute to the well-being of your peers. We welcome all students who are eager to make a difference and grow both personally and professionally.', 'The Peer Counselor\'s Club is dedicated to fostering a supportive environment where students can connect, share experiences, and provide guidance to one another. Our mission is to promote mental wellness, build resilience, and empower students to navigate challenges through peer support, active listening, and community engagement.', 'Our vision is to create a campus culture where every student feels valued, heard, and supported. We aspire to be a leading resource for mental health awareness and peer support, fostering a community of compassionate individuals committed to helping each other thrive.', 'Founded in 2024, the Peer Counselor\'s Club emerged from the need for accessible mental health resources and peer support among students. Recognizing the importance of mental well-being in academic success and personal development, a group of passionate students came together to establish a safe space for open dialogue and peer counseling. Since its inception, the club has organized workshops, training sessions, and outreach programs, connecting students with vital resources and fostering a sense of community. The Peer Counselor\'s Club continues to evolve, striving to meet the needs of the student body and to advocate for mental health awareness on campus.', '', 'COVERPHOTO_PEERCOUNSELOR\'SCLUB.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:41:55'),
(16, 'Sports', 'Join the Sports Club and immerse yourself in a world of excitement and teamwork! Whether you’re passionate about competitive sports or just looking to stay active, our club offers a variety of activities that cater to all skill levels. From intense games to casual matches, you\'ll find opportunities to challenge yourself and improve your skills. Our experienced coaches and friendly members will support you every step of the way. Plus, you\'ll have access to exclusive events, tournaments, and workshops to elevate your game.  Being part of the Sports Club means more than just playing sports—it’s about building lifelong friendships and fostering a sense of community. We regularly organize social events and team-building activities that help strengthen bonds and create unforgettable memories. By joining, you\'ll also gain valuable leadership and teamwork experience that will benefit you beyond the playing field. Don’t miss out on the chance to be part of a vibrant and active community that values both fun and personal growth. Sign up today and start your journey with us!', 'The mission of the Sports Club is to promote a culture of active living and healthy competition among our members through a diverse range of sports and recreational activities. We strive to provide a supportive and inclusive environment that fosters teamwork, sportsmanship, and personal growth, encouraging individuals to achieve their highest potential both on and off the field.', 'Our vision is to be a leading sports club recognized for developing athletic talent and fostering a lifelong love for sports within the community. We aim to create a vibrant and engaging atmosphere where members of all ages can participate, compete, and connect, contributing to a healthier and more active society.', 'Founded in 2024, the Sports Club began as a grassroots initiative among a group of passionate athletes and fitness enthusiasts who recognized the need for a dedicated space to cultivate sports in the community. With the support of local organizations and a growing membership base, the club quickly expanded its offerings to include various sports, such as soccer, basketball, volleyball, and athletics.\r\n\r\nIn its inaugural year, the club organized its first inter-club tournament, which drew participation from several schools and community teams, fostering camaraderie and competition. As the club continues to grow, it remains committed to its core values of inclusion, teamwork, and excellence, paving the way for future generations of athletes.', '', 'COVERPHOTO_SPORTS.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:42:44'),
(17, 'Environmental Club', 'Join the Environmental Club and become a champion for sustainability! Our club is dedicated to making a positive impact on the environment through various initiatives, including campus clean-ups, tree planting, and educational workshops. By participating, you\'ll gain hands-on experience in environmental conservation and connect with like-minded individuals who are passionate about protecting our planet. You\'ll also have the opportunity to collaborate on exciting projects and advocate for eco-friendly practices within our community. Together, we can create a greener future and make a real difference.  In the Environmental Club, you\'ll not only contribute to meaningful environmental change but also develop valuable skills and leadership qualities. We host regular events and campaigns to raise awareness about pressing environmental issues and promote sustainable living. Membership provides a platform for you to voice your ideas, engage in innovative solutions, and participate in fun, impactful activities. Join us to enhance your resume, build a network of environmentally-conscious peers, and be part of a movement that truly matters. Your involvement can lead to lasting positive effects on our surroundings and inspire others to take action.', 'The Environmental Club is dedicated to promoting sustainability and environmental awareness on campus and in our community. Our mission is to educate and empower individuals to take action against climate change, reduce waste, and protect natural resources. We strive to create a vibrant community of environmentally conscious individuals who work together to make a positive impact on our planet.', 'We envision a future where every individual is actively engaged in protecting the environment, fostering a culture of sustainability that transcends generations. The Environmental Club aims to be a leading force in environmental advocacy, inspiring innovative solutions to ecological challenges and cultivating a deep appreciation for the natural world.', 'Founded in 2024, the Environmental Club emerged from a growing recognition of the urgent need to address environmental issues at the local, national, and global levels. A group of passionate students, concerned about climate change and ecological degradation, came together to create a platform for advocacy and action. Since its inception, the club has organized various initiatives, including tree planting drives, waste reduction campaigns, educational workshops, and community clean-up events. With a commitment to fostering a sustainable future, the Environmental Club continues to grow and inspire positive change in the community.', '', 'COVERPHOTO_ENVIRONMENTALCLUB.png', 150, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:44:52');
INSERT INTO `tbl_clubs` (`club_id`, `clubName`, `description`, `mission`, `vision`, `history`, `keywords`, `coverPhoto`, `slots`, `founder_id`, `dateAdded`, `dateModified`) VALUES
(18, 'NBSC Dance Troup', 'Join the NBSC Dance Troupe and become part of a vibrant and dynamic community where your passion for dance can truly flourish. Our club offers a diverse range of dance styles, from contemporary and hip-hop to traditional and ballroom, ensuring that there’s something for everyone. You\'ll have the opportunity to work with talented choreographers and experienced dancers, who will help you refine your skills and boost your confidence. Regular performances and showcases allow you to demonstrate your talent and creativity, gaining valuable stage experience. Being a member also means forging lasting friendships and connections within the college, making your time here memorable and enjoyable.  In addition to honing your dance abilities, the NBSC Dance Troupe fosters a supportive and encouraging environment where personal growth and teamwork are emphasized. We believe in the power of dance to inspire, energize, and unite, creating a space where all members can thrive. The club hosts workshops, social events, and collaborations with other student organizations, ensuring a well-rounded experience. By joining, you\'ll be part of a tradition of excellence and creativity that has left a lasting impact on the college community. Embrace the rhythm and join us in making every step count!', 'The mission of the NBSC Dance Troupe is to foster a vibrant community of dancers who express their creativity, enhance their skills, and promote cultural appreciation through the art of dance. We aim to provide a supportive environment for students of all levels to learn, collaborate, and perform, encouraging personal growth and teamwork.', 'The vision of the NBSC Dance Troupe is to become a leading dance community within Northern Bukidnon State College, known for its artistic excellence, diverse performances, and commitment to inclusivity. We aspire to inspire students and audiences alike, cultivating a love for dance that transcends boundaries and fosters connections.', 'Founded in 2024, the NBSC Dance Troupe emerged from a shared passion for dance among students at Northern Bukidnon State College. Recognizing the need for a dedicated space to explore various dance styles and promote cultural exchange, a group of enthusiastic dancers came together to establish the troupe. Since its inception, the NBSC Dance Troupe has organized numerous workshops, performances, and community outreach events, aiming to engage students and showcase the talent within the college. The troupe continues to grow, embracing new members and styles while upholding its commitment to creativity and collaboration.', '', 'COVERPHOTO_NBSCDANCETROUP.png', 200, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:46:54'),
(19, 'NBSC Chorale', 'The NBSC Chorale is a vibrant and inclusive club that brings together students with a passion for music and performance. As part of our choir, you\'ll have the opportunity to explore a diverse repertoire, from classical pieces to contemporary hits, all while enhancing your vocal skills. Our rehearsals and performances offer a supportive environment where members can grow as artists and develop lifelong friendships. Joining the NBSC Chorale means becoming part of a community that values creativity, teamwork, and dedication. Whether you\'re an experienced singer or a beginner, you\'ll find a welcoming space to share your love for music.  Being a part of the NBSC Chorale not only allows you to showcase your talents but also provides numerous benefits, including opportunities to perform at various campus and community events. Our club fosters a strong sense of camaraderie and offers a chance to represent NBSC with pride. With regular practice sessions and exciting performance schedules, you\'ll gain confidence and improve your musical abilities. Join us and be a part of something special—an enriching experience that blends musical excellence with personal growth. Let your voice be heard and make lasting memories with the NBSC Chorale.', 'The NBSC Chorale aims to foster a passion for music and vocal performance among students by providing a supportive environment for artistic expression and collaboration. Through rehearsals, performances, and community outreach, we strive to enhance the musical skills of our members, promote cultural appreciation, and strengthen the bonds of friendship within the Northern Bukidnon State College community.', 'We envision the NBSC Chorale as a vibrant and inclusive musical ensemble recognized for its excellence in choral performance. We aspire to be a source of inspiration and cultural enrichment on campus and in the wider community, showcasing the talent and diversity of our members while promoting the transformative power of music.', 'Founded in 2024, the NBSC Chorale emerged from a shared love of music among a group of passionate students at Northern Bukidnon State College. The club was established to provide a platform for students to explore their musical talents and engage with the community through performances and events. Since its inception, the chorale has grown to include members from various disciplines, fostering an inclusive environment where students can learn from one another and share their love of singing. The NBSC Chorale has participated in various campus events and competitions, consistently striving for excellence in every performance.', '', 'COVERPHOTO_NBSCCHORALE.png', 100, 24251014, '2024-08-17 01:53:24', '2024-10-23 02:48:03'),
(21, 'Educator\'s Club', 'The Educator\'s Club is a vibrant community dedicated to fostering a passion for teaching and learning among students. As a member, you\'ll have the opportunity to engage in a variety of educational activities, from organizing workshops and tutoring sessions to participating in mentorship programs. Our club values collaboration and creativity, providing a platform where you can develop essential skills such as public speaking, leadership, and critical thinking. We regularly host guest speakers and educational events to broaden your perspective and inspire you to make a positive impact in the educational field. Joining the Educator\'s Club means becoming part of a supportive network committed to academic excellence and personal growth.  By participating in the Educator\'s Club, you will gain valuable experience that can enhance your resume and prepare you for future careers in education, counseling, and beyond. Whether you have a deep-seated interest in teaching or simply want to contribute to your community, our club offers a welcoming environment for all who are eager to learn and grow. We encourage you to take advantage of our diverse range of activities and connect with like-minded peers who share your enthusiasm for education. Our inclusive and dynamic environment ensures that every member can find their niche and make a meaningful contribution. Join us to explore new opportunities, develop your skills, and become an integral part of a community dedicated to educational advancement.', 'The Educator\'s Club is dedicated to fostering a community of passionate educators who inspire and support one another in their professional growth. We aim to provide a collaborative platform for sharing innovative teaching strategies, resources, and experiences, enhancing the educational landscape for both educators and students alike.', 'Our vision is to create an inclusive and dynamic network of educators committed to lifelong learning and excellence in teaching. We aspire to empower educators to lead transformative practices that promote equity, creativity, and engagement in classrooms, ultimately shaping the future of education.', 'Founded in 2024, the Educator\'s Club emerged from a shared desire among educators to connect and collaborate in an increasingly complex educational environment. Recognizing the need for a supportive community, a group of dedicated teachers came together to establish the club, aiming to provide a space where educators could share their experiences, resources, and best practices. Since its inception, the club has hosted numerous workshops, seminars, and networking events, contributing to the professional development of its members and fostering a culture of innovation and support within the educational community.', '', 'COVERPHOTO_EDUCATOR\'SCLUB.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-23 02:48:48'),
(22, 'Infotech Club', 'The Infotech Club offers an exciting and dynamic environment for students passionate about technology and innovation. Our club provides hands-on experience with the latest tech trends, from coding and app development to cybersecurity and artificial intelligence. Members have the opportunity to participate in workshops, hackathons, and tech talks led by industry experts, enhancing both their skills and their resumes. We also foster a collaborative community where students can work on projects, share ideas, and build lasting connections with like-minded peers. Joining the Infotech Club means stepping into a world of endless possibilities and career growth in the ever-evolving field of information technology.  In addition to technical skills, the Infotech Club emphasizes personal and professional development. Our members benefit from networking opportunities with local tech companies and alumni who offer valuable insights and mentorship. We organize regular events, including coding competitions and tech expos, to keep members engaged and motivated. By joining, you’ll be part of a supportive and innovative team dedicated to pushing the boundaries of technology. Don’t miss the chance to be at the forefront of tech advancements and make a meaningful impact in the field of information technology.', 'The Infotech Club aims to foster a community of tech enthusiasts dedicated to exploring and advancing information technology. We strive to provide a collaborative environment where students can enhance their technical skills, engage in innovative projects, and stay updated with the latest industry trends. Through workshops, hackathons, and networking opportunities, we empower our members to become leaders in the field of technology.', 'Our vision is to be a premier student organization that inspires and cultivates a passion for technology among students. We envision a future where our members contribute to impactful technological advancements and develop solutions that address real-world challenges. By promoting collaboration and creativity, we aim to create a thriving ecosystem of innovation within our academic community.', 'Founded in 2024, the Infotech Club emerged from a shared passion for technology and a desire to create a platform for students to connect and learn. The founding members recognized the growing importance of technology in today’s world and sought to bridge the gap between academic knowledge and practical application. Since its inception, the club has organized various events, including coding competitions, guest lectures from industry professionals, and hands-on workshops. With a focus on inclusivity and collaboration, the Infotech Club has rapidly grown, becoming a vibrant community that nurtures the next generation of tech leaders.', '', 'COVERPHOTO_INFOTECHCLUB.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-23 02:49:33'),
(23, 'Red-Cross Youth', 'The Red-Cross Youth Club is dedicated to making a positive impact both locally and globally through humanitarian efforts. As a member, you\'ll engage in exciting and meaningful activities like disaster response training, community service projects, and health awareness campaigns. You’ll gain valuable skills in leadership, teamwork, and emergency preparedness, all while contributing to the well-being of others. Our club also provides opportunities to connect with peers who share your passion for helping those in need and to build lasting friendships. Join us to be part of a supportive community committed to making a difference and enhancing your personal growth.  Being part of the Red-Cross Youth Club means being at the forefront of community and global initiatives. You\'ll have access to specialized workshops and seminars led by experienced professionals in emergency management and first aid. Additionally, the club offers numerous volunteering opportunities that not only benefit the community but also enrich your resume and academic profile. Our activities are designed to be both impactful and engaging, ensuring that you have a rewarding experience. Embrace the chance to contribute to meaningful causes and develop skills that will benefit you throughout your life.', 'The mission of the Red-Cross Youth Club is to empower young individuals to become active contributors to their communities through humanitarian service, education, and advocacy. We aim to instill values of compassion, leadership, and teamwork while promoting awareness about health, disaster preparedness, and community resilience.', 'Our vision is to create a generation of socially responsible youth who are committed to making a positive impact in their communities and beyond. We aspire to foster a culture of service, empathy, and inclusivity, ultimately working towards a world where everyone is equipped to respond to challenges and support one another in times of need.', 'Founded in 2024, the Red-Cross Youth Club emerged from a growing need for youth engagement in humanitarian efforts and community service. Recognizing the pivotal role young people can play in fostering resilience and preparedness, a group of passionate students came together to establish the club. Since its inception, the club has engaged in various initiatives, including first aid training, community health campaigns, and disaster response drills, all while promoting the core principles of the Red Cross. With each project, the club aims to inspire youth to take an active role in their communities, fostering a legacy of service and compassion.', '', 'COVERPHOTO_REDCROSSYOUTH.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-23 02:50:15'),
(24, 'NBSC Scholar\'s Society', 'The NBSC Scholar\'s Society is a vibrant community dedicated to fostering academic excellence and personal growth among students. As a member, you\'ll gain access to a network of motivated peers and experienced mentors who are passionate about helping you succeed. Our club offers a range of engaging activities, including study sessions, workshops, and guest lectures from industry professionals. Joining the Society provides you with valuable opportunities to enhance your skills, build your resume, and connect with like-minded individuals. Whether you\'re aiming for academic success or looking to develop leadership abilities, the Scholar\'s Society is the perfect platform for your growth.  Being part of the NBSC Scholar\'s Society also means participating in exciting social events and collaborative projects that make learning fun and impactful. We are committed to creating a supportive environment where each member can thrive and reach their full potential. Our club regularly organizes networking events and community service projects, giving you a chance to make a difference both academically and socially. By joining, you\'ll be part of a close-knit community that celebrates achievements and supports each other through challenges. Embrace this opportunity to join a club that values academic excellence and personal development while having fun along the way.', 'To cultivate a community of passionate, high-achieving students at Northern Bukidnon State College (NBSC) through academic excellence, leadership development, and service-oriented initiatives, empowering scholars to reach their full potential and make meaningful contributions to society.', 'To become a leading organization within NBSC that fosters intellectual growth, personal development, and social responsibility, producing well-rounded scholars who will excel in their fields and create positive change in their communities.', 'Founded in 2024, the NBSC Scholar’s Society was established to unite the brightest minds at Northern Bukidnon State College. The society was born from the desire to create a support system for students receiving academic scholarships, recognizing their potential and dedication. The founding members envisioned a space where scholars could collaborate, engage in meaningful dialogue, and give back to the community through service projects and outreach. Since its inception, the NBSC Scholar’s Society has grown to be a hub for academic support, leadership opportunities, and peer mentoring, serving as a cornerstone of excellence within the NBSC community.', '', 'COVERPHOTO_NBSCSCHOLAR\'SSOCIETY.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-23 02:51:23'),
(25, 'Campus Seekers of Christ', 'Campus Seekers of Christ is a vibrant and welcoming community dedicated to spiritual growth and fellowship. Our club provides a supportive environment where students can explore their faith, engage in meaningful discussions, and build lifelong friendships. We organize regular events such as Bible studies, prayer meetings, and social gatherings that cater to diverse interests and schedules. Joining us means becoming part of a nurturing group that values personal development and collective well-being. With a focus on inclusivity and mutual support, we invite you to experience the joy and purpose that comes from being part of our community.  By participating in Campus Seekers of Christ, you will have the opportunity to deepen your understanding of your faith while contributing to various outreach initiatives. Our members actively engage in service projects and community events, making a positive impact both on and off-campus. Whether you are seeking spiritual enrichment or looking to connect with others who share your values, our club offers a platform for growth and meaningful connections. We encourage students from all backgrounds and beliefs to join us in our journey of faith and service. Discover how being part of our club can enrich your college experience and provide a sense of belonging.', 'The Campus Seekers of Christ exists to inspire, empower, and unite students in their journey of faith. We aim to foster a Christ-centered community that encourages spiritual growth, discipleship, and service, equipping members to live out their faith both on and off campus.', 'Our vision is to create a vibrant community where students encounter the love of Christ, deepen their personal relationship with God, and become transformative leaders in their spheres of influence. We strive to be a beacon of light, reflecting Christ’s compassion, humility, and grace within our campus and beyond.', 'The Campus Seekers of Christ was founded in 2024 by a group of passionate students who sought to create a space for spiritual fellowship and encouragement on campus. Recognizing the challenges students face in navigating faith during their academic journey, they envisioned a club where students could grow in their understanding of Christ while building meaningful relationships with one another. Since its inception, the club has grown steadily, hosting weekly Bible studies, prayer meetings, and outreach events, all centered on nurturing a strong and inclusive faith community.', '', 'COVERPHOTO_CAMPUSSEEKERSOFCHRIST.png', NULL, 24251014, '2024-09-21 13:40:18', '2024-10-23 02:52:13'),
(42, 'Strings and Symbols', 'The Strings and Symbols Club at NBSC College is a vibrant student organization dedicated to exploring the art and science of music and mathematics. With a unique blend of creative expression and analytical thinking, the club serves as a space for students passionate about music, instruments, composition, and mathematical concepts. Members of the Strings and Symbols Club engage in collaborative music sessions, performances, and workshops that highlight the connection between patterns in music and mathematical theories.  In addition to fostering musical talent, the club provides a platform for academic discussions on the intersection of math and music. The club regularly organizes events such as performances, math-themed music challenges, and guest lectures from experts in both fields. By combining creativity with critical thinking, the Strings and Symbols Club aims to nurture a well-rounded skill set among its members, encouraging them to appreciate the harmony between numbers and notes. Through its activities, the club cultivates an inclusive environment where students can freely express their musical and mathematical interests while enhancing their academic and artistic skills.', 'The Strings and Symbols Club aims to foster a community of students passionate about mathematics, programming, and music. By encouraging creativity and collaboration, the club seeks to provide a platform for students to explore the connections between logic, patterns, and artistic expression through coding and musical instruments.', 'To be a leading student organization that inspires innovative thinking, cultivates interdisciplinary skills, and nurtures a love for both the arts and sciences. We envision a future where students use their knowledge of mathematics, music, and programming to create unique projects that bridge the gap between creativity and technology.', 'Founded in 2024, the Strings and Symbols Club was established by a group of students who shared a love for both mathematical logic and musical expression. They recognized that the intricate patterns in coding and music share similarities in structure and creativity. The club started with a few passionate members who held coding workshops, music jam sessions, and interdisciplinary seminars that demonstrated the beauty of blending these fields. Since its inception, the club has grown, becoming a space where students can collaborate on innovative projects, engage in discussions, and develop new skills that transcend traditional boundaries.', '', 'COVERPHOTO_STRINGSANDSYMBOLS.png', 150, 24251014, '2024-09-21 13:40:18', '2024-10-23 02:53:24'),
(43, 'YASM', 'The Youth Advocates for Sustainable Movements (YASM) Club at NBSC College is a student-driven organization dedicated to promoting environmental sustainability and social responsibility. Its members are passionate about addressing pressing global issues such as climate change, waste management, and sustainable development. The club organizes various activities, including environmental clean-up drives, tree-planting events, and sustainability workshops, to raise awareness and engage the NBSC community in meaningful action.  YASM also collaborates with local organizations and environmental groups to implement long-term initiatives aimed at reducing the campus\' ecological footprint. By encouraging students to adopt eco-friendly habits and participate in sustainable practices, the YASM Club fosters a culture of environmental stewardship. Through its efforts, the club aims to inspire the next generation of leaders to champion sustainability both on campus and in the wider community.', 'The Youth for Arts, Science, and Music (YASM) Club is committed to fostering creativity, curiosity, and collaboration among students through the exploration and integration of arts, science, and music. We aim to create a vibrant community where members can discover their passions, develop their talents, and contribute meaningfully to society through interdisciplinary projects and initiatives.', 'YASM envisions a future where young individuals are empowered to bridge the gap between arts, science, and music, creating innovative solutions and inspiring change in both local and global communities. We strive to be a leading club that nurtures holistic development, promotes creative thinking, and drives social impact through artistic expression and scientific inquiry.', 'The Youth for Arts, Science, and Music (YASM) Club was founded in 2024 with the goal of bringing together students with diverse interests in creative and intellectual pursuits. Recognizing the power of interdisciplinary collaboration, a group of students from different fields came together to form YASM, aiming to provide a platform for those passionate about arts, science, and music. In its founding year, YASM organized several successful events, including art exhibitions, music performances, and science fairs, becoming a central hub for creativity and knowledge-sharing at Northern Bukidnon State College.', '', 'COVERPHOTO_DEFAULT.png', NULL, 24251014, '2024-09-21 13:40:18', '2024-10-23 02:54:59'),
(44, 'Ballpoint Publication', 'The Ballpoint Publication Club at NBSC College is a creative platform for students passionate about writing, journalism, and media. This student-led organization offers members the opportunity to engage in various forms of literary expression, from news reporting and feature writing to poetry and creative nonfiction. The club regularly publishes a student newsletter and manages digital content that highlights campus events, student achievements, and pressing issues within the college community. Through collaborative efforts, members of the Ballpoint Publication Club develop their skills in writing, editing, and multimedia production.  In addition to producing high-quality publications, the Ballpoint Publication Club plays an important role in fostering a culture of communication and critical thinking across campus. The club organizes writing workshops, seminars, and discussions on journalism ethics and media literacy, helping students improve their craft while staying informed about the responsibilities of media in society. By providing a space for student voices to be heard and stories to be told, the Ballpoint Publication Club enriches the intellectual and creative life of NBSC College.', 'The Ballpoint Publication Club is dedicated to providing a platform for students to explore their journalistic and creative talents, promote diverse perspectives, and develop essential skills in writing, editing, and media production. We aim to engage the academic community through insightful, responsible, and inspiring content while nurturing the next generation of communicators and storytellers.', 'To be the leading voice for student expression, creativity, and journalism in the academic community, fostering a culture of innovation, inclusivity, and critical thinking through high-quality publications.', 'Founded in 2024, the Ballpoint Publication Club was established by a group of passionate students with a shared vision of creating a dedicated space for student-led journalism and creative writing. With a commitment to excellence in content creation and a desire to reflect the diverse voices of the student body, the club quickly became a cornerstone of campus life. Through a mix of print and digital media, Ballpoint Publication has steadily grown, producing engaging newsletters, magazines, blogs, and more.', '', 'COVERPHOTO_BALLPOINTPUBLICATION.png', 100, 24251014, '2024-09-22 03:54:57', '2024-10-23 02:55:55'),
(45, 'Campus Bible Fellowship', 'The Campus Bible Fellowship (CBF) at NBSC College is a student-led organization dedicated to fostering spiritual growth, fellowship, and biblical understanding among students. The CBF provides a welcoming environment where students can come together for Bible study, prayer, and meaningful discussions on faith-related topics. Regular meetings offer opportunities for reflection, learning, and the strengthening of personal relationships with God.   In addition to spiritual nourishment, the CBF organizes community outreach programs, aiming to make a positive impact both on campus and beyond. These initiatives include service projects, charity drives, and collaboration with other Christian groups. The club also holds special events such as retreats and fellowship gatherings, providing members with deeper connections to their faith and each other. The Campus Bible Fellowship serves as a supportive space for students seeking to explore and grow in their faith journey while building lasting friendships with like-minded peers.', 'Our mission is to provide a welcoming community where students can explore, understand, and grow in their relationship with God through the study of the Bible. We aim to foster spiritual growth, encourage discipleship, and promote service to others, empowering students to live out their faith both on and off campus.', 'We envision a campus where students from all walks of life are transformed by the Word of God, growing in their faith, and impacting their communities with love, compassion, and service. Through fellowship, worship, and outreach, we seek to build a generation of leaders who live with purpose and reflect the teachings of Christ in all they do.', 'Campus Bible Fellowship was founded in 2024 by a group of dedicated students who desired a space to explore their faith and build a supportive Christian community on campus. With a passion for the Bible and a heart for serving others, the club began meeting weekly for Bible study, prayer, and fellowship. Since its founding, the club has grown into a vibrant part of campus life, offering spiritual guidance, hosting outreach events, and providing opportunities for students to grow in their faith. Campus Bible Fellowship continues to be a beacon of hope and faith on campus, committed to sharing the love of Christ with all.', '', 'COVERPHOTO_BIBLICALCAMPUSMINISTRY.png', NULL, 24251014, '2024-09-23 03:55:18', '2024-10-23 02:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clubs_and_moderators`
--

CREATE TABLE `tbl_clubs_and_moderators` (
  `clubmod_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_clubs_and_moderators`
--

INSERT INTO `tbl_clubs_and_moderators` (`clubmod_id`, `club_id`, `moderator_id`, `dateAdded`, `dateModified`) VALUES
(1, 1, 22230001, '2022-08-18 03:19:13', '2024-10-02 12:24:27'),
(2, 1, 22230002, '2022-08-18 03:19:13', '2024-10-02 12:24:34'),
(3, 2, 22230003, '2022-05-18 11:05:05', '2024-10-02 12:30:25'),
(4, 3, 23240001, '2023-09-18 12:51:22', '2024-10-02 12:39:22'),
(5, 4, 23240002, '2023-11-18 12:51:22', '2024-10-02 12:40:32'),
(6, 5, 23240003, '2023-12-18 12:51:22', '2024-10-02 12:41:09'),
(7, 6, 23240004, '2024-01-18 12:51:22', '2024-10-02 12:42:09'),
(8, 7, 23240005, '2024-02-14 14:55:34', '2024-10-02 12:42:37'),
(9, 8, 23240006, '2024-03-15 02:05:15', '2024-10-02 12:43:12'),
(10, 9, 23240007, '2024-03-15 02:05:15', '2024-10-02 12:43:50'),
(11, 10, 22230001, '2024-05-16 02:05:15', '2024-10-25 04:19:51'),
(12, 11, 24250001, '2024-08-17 01:53:24', '2024-10-02 12:45:33'),
(13, 12, 24250002, '2024-08-17 01:53:24', '2024-10-02 12:45:57'),
(14, 13, 24250003, '2024-08-17 01:53:24', '2024-10-02 12:47:05'),
(15, 14, 24250004, '2024-08-17 01:53:24', '2024-10-02 12:49:03'),
(16, 15, 24250005, '2024-08-17 01:53:24', '2024-10-02 12:49:24'),
(17, 16, 24250006, '2024-08-17 01:53:24', '2024-10-02 12:49:43'),
(18, 17, 24250007, '2024-08-17 01:53:24', '2024-10-02 12:50:05'),
(19, 18, 24250008, '2024-08-17 01:53:24', '2024-10-02 12:50:22'),
(20, 19, 24250009, '2024-08-17 01:53:24', '2024-10-02 12:50:45'),
(22, 21, 23240005, '2024-09-15 09:20:15', '2024-10-02 12:42:44'),
(23, 22, 24250010, '2024-09-15 09:20:15', '2024-10-02 12:52:14'),
(24, 23, 24250011, '2024-09-15 09:20:15', '2024-10-02 12:54:05'),
(25, 24, 24250012, '2024-09-15 09:20:15', '2024-10-02 12:54:34'),
(26, 25, 24250013, '2024-09-21 13:40:18', '2024-10-02 12:54:46'),
(27, 42, 24250009, '2024-09-21 13:40:18', '2024-10-02 12:51:34'),
(28, 43, 24250018, '2024-09-21 13:40:18', '2024-10-02 12:56:37'),
(29, 44, 24250019, '2024-09-22 03:54:57', '2024-10-02 12:56:50'),
(30, 45, 24250020, '2024-09-23 03:55:18', '2024-10-02 12:57:05'),
(31, 5, 24250014, '2024-09-25 01:25:04', '2024-10-02 12:55:07'),
(32, 10, 24250015, '2024-09-25 01:25:04', '2024-10-02 12:55:46'),
(33, 13, 24250016, '2024-09-25 01:25:04', '2024-10-02 12:56:01'),
(34, 17, 24250017, '2024-09-25 01:25:04', '2024-10-02 12:56:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_club_officers`
--

CREATE TABLE `tbl_club_officers` (
  `officer_id` int(20) NOT NULL,
  `president` int(20) DEFAULT NULL,
  `vicePresident` int(20) DEFAULT NULL,
  `secretary` int(20) DEFAULT NULL,
  `treasurer` int(20) DEFAULT NULL,
  `pio` int(20) DEFAULT NULL,
  `srgtAtArms` int(20) DEFAULT NULL,
  `club_id` int(20) DEFAULT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_club_officers`
--

INSERT INTO `tbl_club_officers` (`officer_id`, `president`, `vicePresident`, `secretary`, `treasurer`, `pio`, `srgtAtArms`, `club_id`, `dateAdded`, `dateModified`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, NULL, 22, '2024-10-18 15:43:13', '2024-10-19 03:31:53'),
(2, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2024-10-19 02:00:59', '2024-10-25 10:39:29'),
(3, NULL, NULL, NULL, NULL, NULL, NULL, 10, '2024-10-19 02:04:49', '2024-10-19 16:33:51');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_club_recommendations`
--

CREATE TABLE `tbl_club_recommendations` (
  `recommendation_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `department` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_club_recommendations`
--

INSERT INTO `tbl_club_recommendations` (`recommendation_id`, `club_id`, `department`, `dateAdded`, `dateModified`) VALUES
(21, 21, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(31, 22, 'CCS', '2024-09-23 16:10:50', '2024-09-23 16:14:25'),
(47, 19, 'BSBA', '2024-09-23 16:16:54', '2024-09-23 16:16:54'),
(49, 42, 'BSBA', '2024-09-23 16:17:22', '2024-09-23 16:17:22'),
(66, 19, 'TEP', '2024-10-07 04:46:00', '2024-10-07 04:46:00'),
(67, 19, 'BSBA', '2024-10-07 04:46:12', '2024-10-07 04:46:12'),
(79, 42, 'TEP', '2024-10-07 04:47:38', '2024-10-07 04:47:38'),
(84, 44, 'CCS', '2024-10-07 04:48:04', '2024-10-07 04:48:04'),
(85, 44, 'TEP', '2024-10-07 04:48:17', '2024-10-07 04:48:17'),
(86, 44, 'BSBA', '2024-10-07 04:48:17', '2024-10-07 04:48:17'),
(90, 24250019, 'CCS', '2024-10-07 04:59:59', '2024-10-07 04:59:59'),
(100, 24250020, 'CCS', '2024-10-07 05:03:19', '2024-10-07 05:03:19'),
(101, 24250020, 'TEP', '2024-10-07 05:03:19', '2024-10-07 05:03:19'),
(102, 24250020, 'BSBA', '2024-10-07 05:03:19', '2024-10-07 05:03:19'),
(103, 24250021, 'CCS', '2024-10-08 12:07:54', '2024-10-08 12:07:54'),
(104, 24250022, 'TEP', '2024-10-08 13:27:37', '2024-10-08 13:27:37'),
(105, 24250024, 'CCS', '2024-10-08 13:50:25', '2024-10-08 13:50:25'),
(106, 24250025, 'CCS', '2024-10-08 13:53:24', '2024-10-08 13:53:24'),
(107, 24250026, 'CCS', '2024-10-08 13:55:47', '2024-10-08 13:55:47'),
(108, 24250027, 'CCS', '2024-10-08 13:57:48', '2024-10-08 13:57:48'),
(109, 24250028, 'CCS', '2024-10-08 14:00:26', '2024-10-08 14:00:26'),
(110, 24250029, 'CCS', '2024-10-08 14:09:32', '2024-10-08 14:09:32'),
(111, 24250030, 'CCS', '2024-10-08 14:22:22', '2024-10-08 14:22:22'),
(112, 24250031, 'CCS', '2024-10-08 14:23:19', '2024-10-08 14:23:19'),
(113, 24250032, 'CCS', '2024-10-08 14:28:30', '2024-10-08 14:28:30'),
(115, 46, 'CCS', '2024-10-08 14:34:15', '2024-10-08 14:34:15'),
(116, 46, 'TEP', '2024-10-08 14:34:15', '2024-10-08 14:34:15'),
(117, 47, 'CCS', '2024-10-08 14:51:23', '2024-10-08 14:51:23'),
(119, 48, 'CCS', '2024-10-09 01:28:52', '2024-10-09 01:28:52'),
(120, 48, 'TEP', '2024-10-09 01:28:52', '2024-10-09 01:28:52'),
(121, 49, 'TEP', '2024-10-09 01:56:40', '2024-10-09 01:56:40'),
(122, 49, 'BSBA', '2024-10-09 01:56:40', '2024-10-09 01:56:40'),
(123, 49, 'CCS', '2024-10-09 01:56:40', '2024-10-09 01:56:40'),
(124, 50, 'CCS', '2024-10-09 02:00:26', '2024-10-09 02:00:26'),
(125, 51, 'CCS', '2024-10-09 13:20:21', '2024-10-09 13:20:21'),
(129, 52, 'CCS', '2024-10-09 13:24:30', '2024-10-09 13:24:30'),
(138, 53, 'CCS', '2024-10-14 03:19:15', '2024-10-14 03:19:15'),
(164, 54, 'BSBA', '2024-10-14 05:54:21', '2024-10-14 05:54:21'),
(165, 55, 'TEP', '2024-10-14 08:30:06', '2024-10-14 08:30:06'),
(166, 55, 'BSBA', '2024-10-14 08:30:06', '2024-10-14 08:30:06'),
(167, 55, 'CCS', '2024-10-14 08:30:06', '2024-10-14 08:30:06'),
(171, 57, 'BSBA', '2024-10-14 10:12:34', '2024-10-14 10:12:34'),
(172, 58, 'BSBA', '2024-10-14 10:13:46', '2024-10-14 10:13:46'),
(173, 59, 'BSBA', '2024-10-14 10:16:32', '2024-10-14 10:16:32'),
(174, 60, 'BSBA', '2024-10-14 10:20:48', '2024-10-14 10:20:48'),
(175, 61, 'BSBA', '2024-10-14 10:22:54', '2024-10-14 10:22:54'),
(176, 62, 'BSBA', '2024-10-14 10:23:16', '2024-10-14 10:23:16'),
(177, 63, 'BSBA', '2024-10-14 10:23:27', '2024-10-14 10:23:27'),
(178, 64, 'BSBA', '2024-10-14 10:25:36', '2024-10-14 10:25:36'),
(179, 65, 'TEP', '2024-10-14 10:26:32', '2024-10-14 10:26:32'),
(180, 66, 'TEP', '2024-10-14 11:55:39', '2024-10-14 11:55:39'),
(181, 67, 'CCS', '2024-10-15 01:51:19', '2024-10-15 01:51:19'),
(182, 68, 'CCS', '2024-10-15 01:58:21', '2024-10-15 01:58:21'),
(185, 69, 'BSBA', '2024-10-19 03:46:04', '2024-10-19 03:46:04'),
(187, 70, 'BSBA', '2024-10-19 09:48:39', '2024-10-19 09:48:39'),
(188, 70, 'CCS', '2024-10-19 09:48:39', '2024-10-19 09:48:39'),
(199, 72, 'TEP', '2024-10-23 01:18:49', '2024-10-23 01:18:49'),
(200, 72, 'CCS', '2024-10-23 01:18:49', '2024-10-23 01:18:49'),
(201, 5, 'TEP', '2024-10-23 02:24:31', '2024-10-23 02:24:31'),
(202, 6, 'TEP', '2024-10-23 02:27:38', '2024-10-23 02:27:38'),
(203, 7, 'TEP', '2024-10-23 02:29:36', '2024-10-23 02:29:36'),
(204, 8, 'TEP', '2024-10-23 02:30:29', '2024-10-23 02:30:29'),
(205, 8, 'BSBA', '2024-10-23 02:30:29', '2024-10-23 02:30:29'),
(206, 9, 'TEP', '2024-10-23 02:31:27', '2024-10-23 02:31:27'),
(207, 11, 'TEP', '2024-10-23 02:35:26', '2024-10-23 02:35:26'),
(211, 12, 'TEP', '2024-10-23 02:37:52', '2024-10-23 02:37:52'),
(212, 12, 'CCS', '2024-10-23 02:37:52', '2024-10-23 02:37:52'),
(213, 12, 'BSBA', '2024-10-23 02:37:52', '2024-10-23 02:37:52'),
(214, 16, 'TEP', '2024-10-23 02:42:44', '2024-10-23 02:42:44'),
(215, 16, 'CCS', '2024-10-23 02:42:44', '2024-10-23 02:42:44'),
(216, 16, 'BSBA', '2024-10-23 02:42:44', '2024-10-23 02:42:44'),
(217, 18, 'TEP', '2024-10-23 02:46:54', '2024-10-23 02:46:54'),
(218, 18, 'CCS', '2024-10-23 02:46:54', '2024-10-23 02:46:54'),
(219, 18, 'BSBA', '2024-10-23 02:46:54', '2024-10-23 02:46:54'),
(226, 73, 'TEP', '2024-10-23 13:24:04', '2024-10-23 13:24:04'),
(227, 73, 'BSBA', '2024-10-23 13:24:04', '2024-10-23 13:24:04'),
(228, 75, 'CCS', '2024-11-01 04:43:26', '2024-11-01 04:43:26'),
(229, 76, 'CCS', '2024-11-01 04:49:38', '2024-11-01 04:49:38'),
(230, 77, 'CCS', '2024-11-01 04:51:50', '2024-11-01 04:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_club_requests`
--

CREATE TABLE `tbl_club_requests` (
  `request_id` int(20) NOT NULL,
  `clubName` varchar(200) NOT NULL,
  `goal` text NOT NULL,
  `mission` text NOT NULL,
  `vision` text NOT NULL,
  `activities` text NOT NULL,
  `status` varchar(200) DEFAULT 'pending',
  `coverPhoto` varchar(200) NOT NULL,
  `requestLetter` varchar(255) DEFAULT NULL,
  `dateDecided` timestamp NULL DEFAULT NULL,
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dateRequested` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_club_requests`
--

INSERT INTO `tbl_club_requests` (`request_id`, `clubName`, `goal`, `mission`, `vision`, `activities`, `status`, `coverPhoto`, `requestLetter`, `dateDecided`, `dateModified`, `dateRequested`, `student_id`) VALUES
(1, 'Bikers Club', 'To promote safe and responsible biking among members.', 'To create a supportive community for biking enthusiasts, encouraging exploration and camaraderie.', 'A vibrant community where biking fosters friendship and adventure.', 'Group rides, safety workshops, charity bike events, and maintenance clinics.', 'disapproved', 'COVERPHOTO_BIKERS.png', 'request_letter_66f6e1ff2f929.pdf', '2024-09-27 05:07:27', '2024-10-06 05:54:16', '2024-10-06 05:54:16', 20191124),
(2, 'Pet Lovers Club', 'To advocate for animal welfare and responsible pet ownership.', 'To unite pet lovers and promote education about pet care and adoption.', 'A community where every pet is loved and cared for.', 'Pet adoption drives, educational seminars, volunteer opportunities at shelters, and pet care workshops.', 'pending', 'COVERPHOTO_PETLOVERS.png', 'request_letter_66f6d0b9df37c.pdf', '2024-10-28 14:31:21', '2024-10-28 14:31:39', '2024-10-09 02:00:13', 20191124),
(3, 'Multimedia Productions', 'To enhance members\' skills in multimedia creation and production.', 'To provide a platform for creativity and collaboration in multimedia projects.', 'A hub for innovative storytelling through various media.', 'Film screenings, workshops on video editing, collaborative projects, and guest speaker events.', 'approved', 'COVERPHOTO_MULTIMEDIA.png', 'request_letter_66f6e1ff2f929.pdf', '2024-09-27 05:07:27', '2024-10-06 05:54:19', '2024-10-06 05:54:19', 20191124),
(4, 'Cooking Club', 'The Cooking Club aims to unite culinary enthusiasts at our institution, offering a collaborative environment to explore diverse cuisines and refine cooking skills. Members will engage in hands-on cooking experiences, share recipes, and celebrate their love for food in a supportive community setting.', 'To foster a love for cooking and sharing meals within the community.', 'A culinary community that celebrates creativity and diversity in cooking.', 'The club will feature regular cooking workshops to teach various techniques and cuisines, recipe exchanges for sharing and discovering new dishes, and friendly cooking competitions to showcase members\' talents. Additional activities include hosting guest speakers from the culinary world and organizing community events to prepare and serve meals for local charities.', 'pending', 'COVERPHOTO_COOKING.png', 'request_letter_66f6e1ff2f929.pdf', '2024-11-01 04:28:25', '2024-11-01 04:44:55', '2024-10-09 12:08:40', 20191124),
(14, 'Agriculture Club', 'To empower students with practical knowledge and skills in sustainable agriculture, fostering innovation and leadership in farming practices while promoting environmental stewardship and food security within the community.', 'To educate members about agriculture, gardening, and environmental stewardship.', 'A community that values and practices sustainable farming for a healthier planet.', 'Workshops on gardening, farm visits, community service in local gardens, and seminars on sustainability practices.', 'approved', 'COVERPHOTO_AGRICULTURE.png', 'request_letter_66f6e1ff2f929.pdf', '2024-10-10 08:37:59', '2024-10-11 03:10:43', '2024-10-09 12:26:38', 20191115),
(32, 'Singles Club', 'To make every single double', 'Unite all singles and pair them as they want', 'Every single can find their true love', 'Dating activites', 'disapproved', 'COVERPHOTO_SINGLESCLUB.jpg', 'request_letter_66f6e1ff2f929.pdf', '2024-09-27 15:40:14', '2024-10-11 03:13:13', '2024-10-06 05:53:50', 20191124),
(37, 'Song Writers Clubs', 'To foster creativity and collaboration among aspiring songwriters, helping them develop their skills and share their original music.', 'The mission of the Song Writers Club is to create a supportive community where members can learn from one another, receive constructive feedback, and showcase their work through workshops, performances, and networking opportunities.', 'To be a vibrant hub for songwriters that inspires creativity, nurtures talent, and promotes the appreciation of original music within the community.', 'Weekly Writing Workshops: Members gather to write songs collaboratively and individually, share ideas, and provide feedback.', 'pending', 'request_66f6e1ff2f4e3.png', 'request_letter_6724b147b65eb.pdf', '2024-11-04 08:12:25', '2024-11-04 08:12:55', '2024-10-02 16:56:53', 20191124),
(49, 'for handsome', 'hi', 'y', 'y', 'h6y', 'pending', '670cb43b9123d.jpeg', '670cb43b914d6.docx', NULL, '2024-10-14 06:03:39', '2024-10-14 06:03:39', 20211150),
(50, 'cgh', 'dh', 'dj', 'yh', 'uru', 'pending', '670cb7a483a0a.jpeg', '670cb7a484110.docx', NULL, '2024-10-14 06:18:12', '2024-10-14 06:18:12', 20211514),
(51, 'Arts and culture\'s', 'To promote our natural culture', 's', 'sss', 'arts and sign', 'disapproved', '670cbc65436f3.png', '670cbc6543bee.docx', NULL, '2024-10-14 10:39:31', '2024-10-14 06:38:29', 20211860),
(52, 'Sample ', 'Sample', 'Sample ', 'Sample ', 'Sample', 'pending', '670cc287ded51.png', '670cc287df278.docx', NULL, '2024-10-14 07:04:39', '2024-10-14 07:04:39', 20211245),
(70, 'Fur Club', 'Sample Goal', 'Sample Mission', 'Sample Vision', 'Sample Activity', 'pending', 'request_672588cd905ae.png', '6725875c04e1b.pdf', NULL, '2024-11-02 02:05:01', '2024-11-02 01:58:52', 20191124),
(71, 'Motobikes Club', 'Sample Goal', 'Sample Mission', 'Sample Vision', 'Sample Activity', 'pending', 'request_672588c09d165.png', '6725886b940ff.pdf', NULL, '2024-11-02 02:04:48', '2024-11-02 02:03:23', 20191124),
(72, 'Teacher\'s Club', 'Sample Goal', 'Sample Mission', 'Sample Vision', 'Sample Activity', 'pending', '67258924295b6.png', '6725892429da9.pdf', NULL, '2024-11-02 02:06:28', '2024-11-02 02:06:28', 20191115),
(73, 'Culinary Club', 'Sample Goal', 'Sample Mission', 'Sample Vision', 'Sample Activity', 'pending', 'request_672589b0dd17c.png', '672589a0c98ba.pdf', NULL, '2024-11-02 02:08:48', '2024-11-02 02:08:32', 20191115),
(74, 'Chefs Club', 'Sample Goal', 'Sample Mission', 'Sample Vision', 'Sample Activity', 'pending', '672589f1ec330.png', '672589f1ecd2b.pdf', NULL, '2024-11-02 02:09:53', '2024-11-02 02:09:53', 20191115);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_comments`
--

CREATE TABLE `tbl_comments` (
  `comment_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `dateAdded` datetime DEFAULT current_timestamp(),
  `dateModified` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `post_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_comments`
--

INSERT INTO `tbl_comments` (`comment_id`, `comment`, `dateAdded`, `dateModified`, `post_id`, `club_id`, `student_id`) VALUES
(1, 'Thank you!🎶', '2024-07-26 19:15:28', '2024-09-01 19:58:40', 3, 2, 20201179),
(2, 'Okay, Maam. Copy and noted.', '2024-07-26 19:31:10', '2024-09-01 19:58:46', 26, 12, 20191124),
(3, 'Thank you, Maam!', '2024-07-26 19:32:39', '2024-09-01 19:59:09', 24, 12, 20191124),
(4, 'Thank you for the warm welcome, Maam!', '2024-07-26 19:41:35', '2024-09-01 19:59:12', 22, 12, 20191124),
(5, 'Copy!', '2024-07-26 21:27:33', '2024-09-01 19:59:14', 26, 12, 20191124),
(6, 'Copy, Maam!', '2024-09-01 21:27:26', '2024-09-01 21:27:26', 26, 12, 20191124),
(24, 'Pardon, Sir. Not Maam.', '2024-09-01 22:29:28', '2024-09-01 22:29:28', 26, 12, 20191124),
(25, 'Thank you, Sir!', '2024-09-01 22:32:24', '2024-09-02 20:04:23', 24, 12, 20191124),
(26, 'Thank you.', '2024-09-06 15:14:09', '2024-10-09 09:27:07', 25, 16, 20201270),
(27, 'samples', '2024-09-10 10:48:32', '2024-09-14 20:43:41', 26, 12, 20191124),
(28, 'Pardon, Sir. Not Maam.', '2024-09-12 10:16:24', '2024-09-12 10:16:24', 22, 12, 20191124),
(33, 'Hello Maam.', '2024-09-12 21:01:44', '2024-09-15 07:55:28', 29, 22, 20201179),
(36, 'Thank you!', '2024-09-12 21:24:58', '2024-09-12 21:24:58', 20, 2, 20201179),
(39, 'Thank you, Maam.', '2024-09-14 14:30:51', '2024-09-15 07:55:35', 29, 22, 20191124),
(45, 'e', '2024-09-14 19:13:15', '2024-09-15 07:51:25', 29, 22, 20201179),
(79, 'Hello, Maam!', '2024-10-04 22:10:04', '2024-10-08 21:14:24', 75, 22, 20191124),
(87, 'bdhgfh', '2024-10-14 14:02:10', '2024-10-14 14:02:10', 3, 2, 20211150),
(88, 'vjg', '2024-10-14 14:17:26', '2024-10-14 14:17:26', 25, 16, 20211514),
(89, 'hgd', '2024-10-14 14:25:08', '2024-10-14 14:25:08', 26, 12, 20211341),
(90, 'hello', '2024-10-14 14:34:09', '2024-10-14 14:34:09', 26, 12, 20211860),
(91, 'hi', '2024-10-14 14:34:33', '2024-10-14 14:34:33', 24, 12, 20211860),
(92, 'hi', '2024-10-14 14:34:41', '2024-10-14 14:35:14', 22, 12, 20211860),
(93, 'Hi', '2024-10-14 15:02:56', '2024-10-14 15:02:56', 137, 18, 20211245),
(95, 'hi', '2024-10-14 16:37:40', '2024-10-14 16:37:40', 21, 10, 111),
(99, 'Good evening, Maam!', '2024-10-19 20:48:21', '2024-10-19 20:48:21', 153, 22, 20191124),
(102, 'Thank you, Maam!', '2024-10-20 20:57:31', '2024-10-20 20:58:11', 4, 2, 20201179);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_departure_requests`
--

CREATE TABLE `tbl_departure_requests` (
  `departure_id` int(20) NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'pending',
  `dateRequested` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateDecided` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_departure_requests`
--

INSERT INTO `tbl_departure_requests` (`departure_id`, `reason`, `status`, `dateRequested`, `dateDecided`, `student_id`, `club_id`) VALUES
(42, 'Drop', 'pending', '2024-10-19 13:18:48', '2024-10-19 13:19:13', 111, 4),
(52, 'Drop', 'pending', '2024-11-02 03:20:03', '2024-11-02 03:20:03', 111, 1),
(54, 'Graduate', 'pending', '2024-11-02 03:22:08', '2024-11-02 03:22:08', 20191115, 1),
(55, 'Drop', 'pending', '2024-11-02 03:33:39', '2024-11-02 03:33:39', 20201270, 16);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `event_id` int(20) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `timeStarts` time NOT NULL,
  `timeEnds` time NOT NULL,
  `location` text NOT NULL,
  `applicationLink` text NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_events`
--

INSERT INTO `tbl_events` (`event_id`, `title`, `description`, `date`, `timeStarts`, `timeEnds`, `location`, `applicationLink`, `dateAdded`, `dateModified`, `club_id`, `moderator_id`) VALUES
(1, 'Club Orientation', 'Join us for the Club Orientation, where you\'ll discover the vibrant world of student organizations at Northern Bukidnon State College! This event is designed for new students and returning members to learn about the various clubs available on campus, meet club leaders, and find out how you can get involved.', '2024-10-22', '08:00:00', '12:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-03 12:27:20', '2024-10-10 07:09:46', 22, 24250010),
(2, 'IT Days', 'Archers!? Pew! Pew! Pew!', '2024-10-30', '07:30:00', '17:00:00', 'NBSC', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-03 13:22:10', '2024-10-10 07:10:17', 22, 24250010),
(3, 'Earthquake Drill', 'Safety training during earthquake attacks', '2024-10-28', '09:00:00', '12:00:00', 'NBSC Ground', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-03 14:24:38', '2024-10-19 11:07:20', 1, 22230001),
(93, 'Meeting', 'Election for Muslim Officers', '2024-10-16', '08:01:00', '09:01:00', 'IT Laboratory 1', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-14 09:02:25', '2024-10-14 09:02:25', 4, 23240002),
(105, 'Sample Event', 'abcd', '2024-10-31', '09:30:00', '10:30:00', 'NBSC Ground', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-28 13:30:26', '2024-10-28 13:30:26', 1, 22230001),
(107, 'Sample Infotech Event', 'Sample description', '2024-11-30', '19:45:41', '22:45:41', 'NBSC Ground', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-02 02:46:55', '2024-11-02 03:01:13', 22, 24250010),
(108, 'Sample NBSC Quick Response Team Event ', 'Sample description', '2024-11-12', '13:00:00', '17:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-02 03:10:51', '2024-11-02 03:11:11', 1, 22230001),
(109, 'Sample Quick Response Team Event ', 'Sample description', '2024-11-22', '09:00:00', '12:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-02 03:11:42', '2024-11-02 03:15:15', 1, 22230001),
(110, 'Sample Mountaineering Society Event ', 'Sample description', '2024-11-09', '13:00:00', '16:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-02 03:13:24', '2024-11-02 03:13:24', 10, 22230001),
(111, ' Sample Mountaineering Society Event', 'Sample description', '2024-11-10', '13:00:00', '15:30:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-02 03:14:16', '2024-11-02 03:14:16', 10, 22230001),
(112, ' Sample Mountaineering Society Event', 'Sample description', '2024-11-24', '07:30:00', '10:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-02 03:15:58', '2024-11-02 03:15:58', 10, 22230001),
(113, ' Sample Mountaineering Society Event', 'Sample description', '2024-11-25', '13:00:00', '15:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-02 03:17:27', '2024-11-02 03:17:27', 10, 22230001),
(120, 'Sample Quick Response Team Event', 'Sample description', '2024-11-20', '10:30:00', '12:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-03 12:19:41', '2024-11-03 12:19:41', 1, 22230001),
(121, 'Sample Quick Response Team Event', 'Sample description', '2024-11-27', '13:30:00', '16:30:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-03 12:25:20', '2024-11-03 12:25:20', 1, 22230001),
(122, 'Sample Quick Response Team Event', 'Sample description', '2024-11-15', '10:30:00', '12:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-03 14:34:42', '2024-11-03 14:34:42', 1, 22230001),
(123, 'Sample Quick Response Team Event', 'Sample description', '2024-11-17', '10:30:00', '12:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-03 14:37:54', '2024-11-03 14:37:54', 1, 22230001),
(124, 'Sample Quick Response Team Event', 'Sample description', '2024-11-05', '07:30:00', '09:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-11-04 08:18:25', '2024-11-04 08:18:25', 1, 22230001);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_moderators`
--

CREATE TABLE `tbl_moderators` (
  `moderator_id` int(20) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `middleName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `age` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phoneNumber` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `profession` varchar(200) NOT NULL,
  `profilePic` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_moderators`
--

INSERT INTO `tbl_moderators` (`moderator_id`, `firstName`, `middleName`, `lastName`, `age`, `birthday`, `gender`, `email`, `password`, `phoneNumber`, `department`, `profession`, `profilePic`, `dateAdded`, `dateModified`) VALUES
(22230001, 'Cliff', 'Amadeus', 'Evangelio', '18', '2024-09-20', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'CCS', 'Instructor', '670dcd3f402c7-PROFPIC_SIR_CLIFF.png', '2022-08-18 03:19:13', '2024-11-01 01:46:26'),
(22230002, 'John Mark', '', 'Liwat', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2022-08-18 03:19:13', '2024-11-01 06:00:07'),
(22230003, 'Blessel', '', 'Quino', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2023-03-19 15:23:09', '2024-11-04 12:37:21'),
(23240001, 'Teofany', '', 'Siton', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2023-09-18 12:51:22', '2024-11-04 12:37:24'),
(23240002, 'Faisah', '', 'Bacarat', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'CCS', 'Instructor', 'PROF_PIC.png', '2023-11-18 12:51:22', '2024-11-01 06:36:41'),
(23240003, 'Nekka', 'A.', 'Mondaga', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2023-12-18 12:51:22', '2024-11-04 10:39:36'),
(23240004, 'Jee Ann', '', 'Guibone', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-01-18 12:51:22', '2024-11-04 12:37:27'),
(23240005, 'Charmaine', '', 'Tapulayan', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-02-14 14:55:34', '2024-11-04 10:39:05'),
(23240006, 'Helen', '', 'Ajon', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-03-15 02:05:15', '2024-11-04 12:37:29'),
(23240007, 'Marites', '', 'Salce', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-03-15 02:05:15', '2024-11-04 12:37:32'),
(24250001, 'John Kevin', '', 'Artuz', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:34'),
(24250002, 'John', '', 'Soriano', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:36'),
(24250003, 'Cherry Mar', '', 'Tutica', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:38'),
(24250004, 'Adonis', '', 'Onahon', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 06:47:19'),
(24250005, 'Jo Agustine', '', 'Corpuz', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:40'),
(24250006, 'Grace', '', 'Quiblat', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:43'),
(24250007, 'Michaela', '', 'Jamora', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:45'),
(24250008, 'Edilyn', '', 'Culajara', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:47'),
(24250009, 'Anthony', '', 'Sanchez', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-11-04 12:37:49'),
(24250010, 'Marchilyn', 'A.', 'Abunda', '25', '1111-01-01', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'CCS', 'Instructor', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-11-01 06:00:52'),
(24250011, 'Karl', '', 'Acosta', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'Health Clinic', 'Nurse', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-11-04 12:37:52'),
(24250012, 'Rahbie', 'N.', 'Adaptar', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'ASO', 'Head', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-11-04 12:37:55'),
(24250013, 'Roseanne', 'B.', 'Lontian', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-21 13:40:18', '2024-11-04 12:37:57'),
(24250014, 'John Michael', '', 'Ganzan', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:09:28', '2024-11-04 12:37:59'),
(24250015, 'Kim-Lee', 'B.', 'Domingo', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:09:28', '2024-11-04 12:38:01'),
(24250016, 'Milleanne Kaye', '', 'Remotigue', '', '0000-00-00', 'Female', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:11:06', '2024-11-04 12:38:12'),
(24250017, 'Melvin', '', 'Valmoria', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:11:06', '2024-11-04 12:38:14'),
(24250018, 'John Mark', '', 'Boyonas', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-21 13:40:18', '2024-11-04 12:38:16'),
(24250019, 'Cero', '', '', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-22 03:54:57', '2024-11-04 12:38:18'),
(24250020, 'Ramer', 'N.', 'Verdejo', '', '0000-00-00', 'Male', '20191124@nbsc.edu.ph', '$2y$10$yd5t4LzPF5.b8WpNVU08YeM.tJJDaoi.fusJUMd3lOyvBNyKdWfxy', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-09-23 03:55:18', '2024-11-04 12:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(20) NOT NULL,
  `notification` varchar(200) NOT NULL,
  `student_id` int(20) DEFAULT NULL,
  `club_id` int(20) DEFAULT NULL,
  `post_id` int(20) DEFAULT NULL,
  `event_id` int(20) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `notification`, `student_id`, `club_id`, `post_id`, `event_id`, `is_read`, `dateAdded`) VALUES
(113, 'Posted an announcement', 20211245, 18, 137, 0, 1, '2024-10-14 07:02:41'),
(192, 'Posted an announcement', 20191124, 22, 153, 0, 1, '2024-10-17 11:26:54'),
(193, 'Posted an announcement', 20201179, 22, 153, 0, 1, '2024-10-17 11:26:54'),
(248, 'Posted an announcement', 20191124, 1, 161, 0, 1, '2024-10-19 18:30:01'),
(249, 'Posted an announcement', 20191115, 1, 161, 0, 1, '2024-10-19 18:30:01'),
(250, 'Posted an announcement', 20190000, 1, 161, 0, 1, '2024-10-19 18:30:01'),
(251, 'Posted an announcement', 20211524, 1, 161, 0, 0, '2024-10-19 18:30:01'),
(252, 'Posted an announcement', 20211525, 1, 161, 0, 0, '2024-10-19 18:30:01'),
(253, 'Posted an announcement', 20211526, 1, 161, 0, 0, '2024-10-19 18:30:01'),
(254, 'Posted an announcement', 20211527, 1, 161, 0, 0, '2024-10-19 18:30:01'),
(255, 'Posted an announcement', 20211081, 1, 161, 0, 0, '2024-10-19 18:30:01'),
(377, 'Posted an event', 20191124, 1, NULL, 1056, 1, '2024-11-03 10:07:45'),
(378, 'Posted an event', 20191115, 1, NULL, 377, 0, '2024-11-03 10:07:49'),
(379, 'Posted an event', 20190000, 1, NULL, 378, 0, '2024-11-03 10:07:53'),
(380, 'Posted an event', 20211524, 1, NULL, 379, 0, '2024-11-03 10:07:56'),
(381, 'Posted an event', 20211525, 1, NULL, 380, 0, '2024-11-03 10:08:00'),
(382, 'Posted an event', 20211526, 1, NULL, 381, 0, '2024-11-03 10:08:05'),
(383, 'Posted an event', 20211527, 1, NULL, 382, 0, '2024-11-03 10:08:08'),
(384, 'Posted an event', 111, 1, NULL, 383, 0, '2024-11-03 10:08:12'),
(385, 'Posted an event', 20191124, 1, NULL, 1059, 1, '2024-11-03 10:18:11'),
(386, 'Posted an event', 20191115, 1, NULL, 385, 0, '2024-11-03 10:18:15'),
(387, 'Posted an event', 20190000, 1, NULL, 386, 0, '2024-11-03 10:18:19'),
(388, 'Posted an event', 20211524, 1, NULL, 387, 0, '2024-11-03 10:18:24'),
(389, 'Posted an event', 20211525, 1, NULL, 388, 0, '2024-11-03 10:18:28'),
(390, 'Posted an event', 20211526, 1, NULL, 389, 0, '2024-11-03 10:18:32'),
(391, 'Posted an event', 20211527, 1, NULL, 390, 0, '2024-11-03 10:18:36'),
(392, 'Posted an event', 111, 1, NULL, 391, 0, '2024-11-03 10:18:41'),
(529, 'Posted an event', 20191124, 1, NULL, 1105, 1, '2024-11-03 12:19:45'),
(530, 'Posted an event', 20191115, 1, NULL, 529, 0, '2024-11-03 12:19:49'),
(531, 'Posted an event', 20190000, 1, NULL, 530, 0, '2024-11-03 12:19:52'),
(532, 'Posted an event', 20211524, 1, NULL, 531, 0, '2024-11-03 12:19:56'),
(533, 'Posted an event', 20211525, 1, NULL, 532, 0, '2024-11-03 12:19:59'),
(534, 'Posted an event', 20211526, 1, NULL, 533, 0, '2024-11-03 12:20:03'),
(535, 'Posted an event', 20211527, 1, NULL, 534, 0, '2024-11-03 12:20:06'),
(536, 'Posted an event', 111, 1, NULL, 535, 0, '2024-11-03 12:20:10'),
(537, 'Posted an announcement', 20191124, 1, 221, 0, 1, '2024-11-03 12:24:29'),
(538, 'Posted an announcement', 20191115, 1, 221, 0, 0, '2024-11-03 12:24:29'),
(539, 'Posted an announcement', 20190000, 1, 221, 0, 0, '2024-11-03 12:24:29'),
(540, 'Posted an announcement', 20211524, 1, 221, 0, 0, '2024-11-03 12:24:29'),
(541, 'Posted an announcement', 20211525, 1, 221, 0, 0, '2024-11-03 12:24:29'),
(542, 'Posted an announcement', 20211526, 1, 221, 0, 0, '2024-11-03 12:24:29'),
(543, 'Posted an announcement', 20211527, 1, 221, 0, 0, '2024-11-03 12:24:29'),
(544, 'Posted an announcement', 111, 1, 221, 0, 0, '2024-11-03 12:24:29'),
(545, 'Posted an event', 20191124, 1, NULL, 1110, 1, '2024-11-03 12:25:25'),
(546, 'Posted an event', 20191115, 1, NULL, 545, 0, '2024-11-03 12:25:30'),
(547, 'Posted an event', 20190000, 1, NULL, 546, 0, '2024-11-03 12:25:34'),
(548, 'Posted an event', 20211524, 1, NULL, 547, 0, '2024-11-03 12:25:38'),
(549, 'Posted an event', 20211525, 1, NULL, 548, 0, '2024-11-03 12:25:42'),
(550, 'Posted an event', 20211526, 1, NULL, 549, 0, '2024-11-03 12:25:47'),
(551, 'Posted an event', 20211527, 1, NULL, 550, 0, '2024-11-03 12:25:51'),
(552, 'Posted an event', 111, 1, NULL, 551, 0, '2024-11-03 12:25:56'),
(714, 'Posted an event', 20191124, 1, NULL, 1137, 1, '2024-11-03 14:34:46'),
(715, 'Posted an event', 20191115, 1, NULL, 714, 0, '2024-11-03 14:34:51'),
(716, 'Posted an event', 20190000, 1, NULL, 715, 0, '2024-11-03 14:34:54'),
(717, 'Posted an event', 20211524, 1, NULL, 716, 0, '2024-11-03 14:34:58'),
(718, 'Posted an event', 20211525, 1, NULL, 717, 0, '2024-11-03 14:35:02'),
(719, 'Posted an event', 20211526, 1, NULL, 718, 0, '2024-11-03 14:35:06'),
(720, 'Posted an event', 20211527, 1, NULL, 719, 0, '2024-11-03 14:35:10'),
(721, 'Posted an event', 111, 1, NULL, 720, 0, '2024-11-03 14:35:14'),
(722, 'Posted an event', 20191124, 1, NULL, 1138, 1, '2024-11-03 14:37:58'),
(723, 'Posted an event', 20191115, 1, NULL, 722, 0, '2024-11-03 14:38:02'),
(724, 'Posted an event', 20190000, 1, NULL, 723, 0, '2024-11-03 14:38:08'),
(725, 'Posted an event', 20211524, 1, NULL, 724, 0, '2024-11-03 14:38:12'),
(726, 'Posted an event', 20211525, 1, NULL, 725, 0, '2024-11-03 14:38:17'),
(727, 'Posted an event', 20211526, 1, NULL, 726, 0, '2024-11-03 14:38:21'),
(728, 'Posted an event', 20211527, 1, NULL, 727, 0, '2024-11-03 14:38:25'),
(729, 'Posted an event', 111, 1, NULL, 728, 0, '2024-11-03 14:38:29'),
(762, 'Posted an event', 20191124, 1, NULL, 1184, 1, '2024-11-04 08:18:29'),
(763, 'Posted an event', 20191115, 1, NULL, 762, 0, '2024-11-04 08:18:34'),
(764, 'Posted an event', 20190000, 1, NULL, 763, 0, '2024-11-04 08:18:38'),
(765, 'Posted an event', 20211524, 1, NULL, 764, 0, '2024-11-04 08:18:43'),
(766, 'Posted an event', 20211525, 1, NULL, 765, 0, '2024-11-04 08:18:47'),
(767, 'Posted an event', 20211526, 1, NULL, 766, 0, '2024-11-04 08:18:51'),
(768, 'Posted an event', 20211527, 1, NULL, 767, 0, '2024-11-04 08:18:55'),
(769, 'Posted an event', 111, 1, NULL, 768, 0, '2024-11-04 08:19:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_officers_charts`
--

CREATE TABLE `tbl_officers_charts` (
  `chart_id` int(20) NOT NULL,
  `chart` varchar(200) NOT NULL,
  `organizationType` varchar(200) NOT NULL,
  `department` varchar(200) DEFAULT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_officers_charts`
--

INSERT INTO `tbl_officers_charts` (`chart_id`, `chart`, `organizationType`, `department`, `dateAdded`, `dateModified`) VALUES
(1, 'chart_67132f12ae4f5.jpeg', 'CSG', '', '2024-10-10 21:01:27', '2024-10-19 04:01:22'),
(2, 'OFFICERS_SBO_TEP.png', 'SBO', 'TEP', '2024-10-10 21:03:43', '2024-10-10 23:44:50'),
(3, 'OFFICERS_SBO_BSBA.png', 'SBO', 'BSBA', '2024-10-10 21:04:15', '2024-10-10 21:04:15'),
(4, 'chart_670cfeb85ea69.png', 'SBO', 'CCS', '2024-10-10 21:05:03', '2024-10-14 11:21:28');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts`
--

CREATE TABLE `tbl_posts` (
  `post_id` int(20) NOT NULL,
  `post` text NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_posts`
--

INSERT INTO `tbl_posts` (`post_id`, `post`, `dateAdded`, `dateModified`, `club_id`, `moderator_id`) VALUES
(1, 'Welcome to the NBSC Quick Response Team! 🚑\r\n\r\nHello, NBSC Community! We’re excited to have you here as we work together to ensure safety and support on campus. Whether you’re looking to help in emergencies or learn more about crisis management, our team is ready to guide you. Stay tuned for training sessions, safety tips, and updates on how we can assist one another. Let’s be prepared and make our campus a safer place together!\r\n\r\nStay safe!\r\nYour Quick Response Team 🌟', '2024-07-18 03:26:19', '2024-10-19 18:28:11', 1, 22230001),
(3, '🎵 Welcome to the NBSC Band! 🎵\r\n\r\nHello NBSC Band members and music enthusiasts,\r\n\r\nWelcome to our official NBSC Band club page! We are thrilled to have you join our musical family. Whether you play an instrument, sing, or simply love music, there\'s a place for you here.\r\n\r\nAs the newest members of our band, you are now part of a vibrant community that values creativity, collaboration, and the joy of making music together. Our club is not just about playing instruments; it\'s about creating unforgettable experiences, forming lifelong friendships, and expressing ourselves through the universal language of music.\r\n\r\nHere’s what you can look forward to:\r\n\r\n🎶 Weekly Jam Sessions: Every [insert day], join us for a fun and relaxed jam session where we can play our favorite tunes, learn new ones, and just enjoy making music together.\r\n\r\n🎤 Workshops and Masterclasses: Improve your skills with workshops led by talented musicians and guest artists. From technique to performance tips, there’s always something new to learn.\r\n\r\n🎸 Performances and Gigs: Showcase your talent at various events throughout the year. Whether it’s a school concert, community event, or a spontaneous performance, there are plenty of opportunities to shine.\r\n\r\n👥 Community and Friendship: Meet fellow music lovers, make new friends, and be part of a supportive community that celebrates each other’s growth and achievements.\r\n\r\n📅 Exciting Events: Stay tuned for our upcoming events, including music competitions, collaborative projects with other clubs, and much more!\r\n\r\nYour First Steps:\r\n\r\nIntroduce Yourself: Comment below with your name, the instrument you play or your favorite genre of music, and what you’re looking forward to the most in our band.\r\n\r\nGet Involved: Check out our calendar for upcoming events and practice sessions. We encourage you to participate as much as you can.\r\n\r\nStay Connected: Follow our social media pages [insert links] to stay updated on the latest news and events.\r\n\r\nRemember, whether you’re a seasoned musician or just starting out, everyone is welcome in the NBSC Band. Let’s make beautiful music and unforgettable memories together!\r\n\r\nIf you have any questions or need assistance, feel free to reach out to [Contact Person] or any of our club officers.\r\n\r\nWelcome aboard, and let’s rock the NBSC Band!\r\n\r\n#NBSCBand #MusicLovers #JoinTheBand #Welcome', '2024-07-18 03:26:19', '2024-10-02 12:36:41', 2, 22230003),
(4, '**🎶 Hello NBSC Band! 🎶**\r\n\r\nWe’re thrilled to have you join our musical journey! Get ready for amazing practices, fun performances, and lots of opportunities to showcase your talents. \r\n\r\nLet’s make some unforgettable music together! 🎵\r\n\r\nStay tuned for upcoming events and updates!\r\n\r\n---', '2024-07-18 03:30:19', '2024-10-02 12:36:45', 2, 22230003),
(5, 'mas-amicus post 1', '2024-07-18 03:26:19', '2024-10-02 12:39:35', 3, 23240001),
(6, 'mas-amicus post 2', '2024-07-26 03:30:19', '2024-10-02 12:39:38', 3, 23240001),
(12, 'dramatic post 1', '2024-07-18 03:46:19', '2024-10-02 12:41:38', 5, 23240003),
(13, 'muslim post 1', '2024-07-18 03:27:19', '2024-10-02 12:40:47', 4, 23240002),
(16, 'Hi', '2024-07-18 04:26:19', '2024-10-14 09:00:30', 4, 23240002),
(20, '🌟 NBSC Band Update 🌟 A huge shoutout to everyone for the fantastic turnout at our first rehearsal! Your energy and enthusiasm were amazing. Next practice is on August 1, 2024 at 9:00am—don’t miss it! Keep your instruments ready and your spirits high! 🎺🥁🎻', '2024-07-26 03:26:19', '2024-10-02 12:36:52', 2, 22230003),
(21, 'Welcome to the NBSC Mountaineering Society! 🏞️ Hello Adventurers! We\'re thrilled to have you join us on this exhilarating journey of exploration and adventure. Whether you\'re a seasoned mountaineer or new to the thrill of climbing, our club is here to support and inspire you. Get ready for exciting hikes, breathtaking views, and unforgettable experiences. Stay tuned for upcoming events, meetups, and tips to help you reach new heights. Let\'s conquer mountains together and make incredible memories! Happy climbing! Your Mountaineering Club Team 🌄', '2024-07-18 03:16:19', '2024-10-19 11:12:43', 10, 22230001),
(22, 'Welcome to the NBSC ARTS Society! 🎨\r\n\r\nHello Creatives!\r\n\r\nWe’re excited to welcome you to the vibrant world of the ARTS Society! Whether you’re a painter, sculptor, writer, or just someone with a passion for the arts, you’ve come to the right place.\r\n\r\nPrepare to dive into a whirlwind of creativity, inspiration, and collaboration. We have a fantastic lineup of workshops, exhibitions, and creative sessions coming your way. Let\'s explore new artistic horizons together, share our talents, and celebrate the beauty of art in all its forms.\r\n\r\nStay tuned for updates and events—your next masterpiece might just be around the corner!\r\n\r\nLet’s create something amazing together!\r\n\r\nThe ARTS Society Team 🎭', '2024-07-18 03:26:19', '2024-10-02 12:46:23', 12, 24250002),
(24, 'Hello ARTS Society Members!\r\n\r\nWe are thrilled to announce our upcoming art exhibition, \"Colors of Imagination,\" which will showcase the diverse and vibrant works of our talented artists.\r\n\r\nEvent Details:\r\n\r\nDate: August 15, 2024\r\nTime: 2:00 PM - 6:00 PM\r\nVenue: NBSC Art Gallery, Main Campus\r\nTheme: Abstract Art and Modern Impressions\r\nHighlights:\r\n\r\nGuest Speaker: Renowned artist and alumnus, John Doe, will be sharing his journey and insights into the world of abstract art.\r\nLive Art Demonstration: Watch live as artists create their masterpieces right before your eyes.\r\nInteractive Sessions: Participate in Q&A sessions with the artists and learn more about their techniques and inspirations.\r\nArt Sale: Get a chance to purchase unique artworks and support our local artists.\r\nContact Information:\r\n\r\nEmail: artssociety@nbsc.edu\r\nPhone: (123) 456-7890\r\nDon\'t miss this opportunity to immerse yourself in the world of abstract art and connect with fellow art enthusiasts. We look forward to seeing you there!\r\n\r\nBest regards,\r\n\r\nArts Moderator\r\nModerator, ARTS Society\r\nNBSC College', '2024-07-18 03:30:19', '2024-10-02 12:46:25', 12, 24250002),
(25, 'Hello Everyone!\r\n\r\nWelcome to the Sports Society at NBSC! We’re excited to have you join us for an action-packed year of sports, fitness, and fun. Whether you\'re a seasoned athlete or just looking to get active, there\'s something for everyone.\r\n\r\nStay tuned for upcoming events, activities, and opportunities to get involved. Let’s make this year a winning one!\r\n\r\nBest Regards,\r\nSports Moderator\r\nModerator, Sports Society\r\nNBSC College', '2024-07-18 02:06:28', '2024-10-02 12:49:50', 16, 24250006),
(26, 'Paging: Ryan P. Cepada to report to the office right now. Thank you!', '2024-07-26 03:26:19', '2024-10-02 12:46:29', 12, 24250002),
(29, 'Welcome to the NBSC Infotech Club! 💻\r\n\r\nHello Tech Enthusiasts! We\'re thrilled to welcome you to the dynamic world of the Infotech Club! Whether you\'re passionate about coding, cybersecurity, AI, or just tech in general, you\'ve landed in the right place. Get ready for a series of exciting workshops, hackathons, and tech talks designed to fuel your curiosity and expand your skills. Together, we\'ll dive into the latest technologies, tackle real-world challenges, and collaborate on innovative projects. Stay tuned for updates and events—your next tech breakthrough could be just around the corner! Let’s code, create, and revolutionize the tech world together!\r\n\r\nThe Infotech Club Team 🚀', '2024-09-12 11:58:17', '2024-10-02 12:52:26', 22, 24250010),
(75, 'Hi everyone!', '2024-10-04 14:09:25', '2024-10-04 14:09:25', 22, 24250010),
(137, 'Good afternoon!', '2024-10-14 07:02:41', '2024-10-14 07:02:41', 18, 24250008),
(153, 'good eve', '2024-10-17 11:26:54', '2024-10-17 11:26:54', 22, 24250010),
(161, 'Attention NBSC Community! 🌟\r\n\r\nWe\'re excited to announce that the NBSC Quick Response Team is gearing up for some thrilling events! 🚨 From training workshops to community outreach programs, we have a lineup of activities designed to enhance your skills and foster teamwork. Stay tuned for updates on our upcoming events—your participation is key to making a difference!\r\n\r\nTogether, we can create a safer environment for everyone. Keep an eye on our announcements, and let’s make an impact!\r\n\r\nYour Quick Response Team 💪', '2024-10-19 18:30:01', '2024-10-19 18:30:01', 1, 22230002),
(178, 'Hello everyone!', '2024-11-02 03:12:11', '2024-11-02 03:12:11', 10, 22230001),
(179, 'Hi everyone!', '2024-11-02 03:12:21', '2024-11-02 03:12:21', 10, 22230001),
(180, 'Good day everyone!', '2024-11-02 03:12:33', '2024-11-02 03:12:33', 10, 22230001),
(221, 'Good evening, rescuers!', '2024-11-03 12:24:29', '2024-11-03 12:24:29', 1, 22230001);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_posts_attachments`
--

CREATE TABLE `tbl_posts_attachments` (
  `attachment_id` int(20) NOT NULL,
  `attachmentType` varchar(200) NOT NULL,
  `attachmentFileName` varchar(200) NOT NULL,
  `dateUploaded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `post_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `student_id` int(20) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `middleName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `age` varchar(200) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(200) NOT NULL,
  `instiEmail` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `phoneNumber` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `course` varchar(200) NOT NULL,
  `year` varchar(200) NOT NULL,
  `street` varchar(200) NOT NULL,
  `barangay` varchar(200) NOT NULL,
  `municipality` varchar(200) NOT NULL,
  `province` varchar(200) NOT NULL,
  `zipcode` varchar(200) NOT NULL,
  `profilePic` varchar(200) NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`student_id`, `firstName`, `middleName`, `lastName`, `age`, `birthday`, `gender`, `instiEmail`, `password`, `phoneNumber`, `department`, `course`, `year`, `street`, `barangay`, `municipality`, `province`, `zipcode`, `profilePic`, `dateAdded`, `dateModified`) VALUES
(1, 'sample', 'sample', 'sample', '', '0000-00-00', '', '', '1', '', 'CCS', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-10 16:15:44', '2024-10-10 16:15:44'),
(111, 'hey', '', '', '', '2000-11-11', 'Male', 'hey@gmail.com', '1', '', 'CCS', '', '1st Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 02:17:26', '2024-10-09 04:40:54'),
(20190000, 'Sample', 'Sample', 'Sample', '32', '1992-09-09', 'Male', '20190000@nbsc.edu.ph', '1', '09614588546', 'BSBA', 'BSIT', '3rd Year', 'Zone 2', 'Diclum', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-19 09:14:25', '2024-09-14 22:25:42'),
(20191111, 'Sample', 'Sample', 'Sample', 'Sample', '1111-11-11', 'Female', '20191111@nbsc.edu.ph', '1', '09111111111', 'TEP', 'BSED', '1st Year', 'Zone 3', 'Agusan Canyon', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-15 09:42:37', '2024-08-22 23:48:39'),
(20191115, 'Lovely Nicole', 'Sapong', 'Figueroa', '23', '2001-03-26', 'Female', '20191115@nbsc.edu.ph', '1', '09097989765', 'TEP', 'BSECE', '4th Year', 'Zone 9', 'Lingion', 'Manolo Fortich', 'Province 1', '8703', 'LOVELYNICOLE.png', '2024-07-15 09:41:35', '2024-11-03 13:46:51'),
(20191124, 'Ryan', 'Palmares', 'Cepada', '32', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '1', '09614588546', 'CCS', 'BSIT', '4th Year', 'Zone 2', 'Diclum', 'Manolo Fortich', 'Province 1', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', '2024-07-19 18:42:04', '2024-11-03 13:46:46'),
(20200000, 'Jomar', 'Jenisan', 'Yeri', '25', '1111-11-11', 'Male', '20211111@nbsc.edu.ph', '', '09876543210', 'CCS', 'BSIT', '4th Year', 'Zone 5', 'Agusan Canyon', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-15 09:42:37', '2024-08-22 23:49:34'),
(20201179, 'Angela', 'Naive', 'Libay', '23', '1111-11-11', 'Female', '20201179@nbsc.edu.ph', '1', '09876543210', 'CCS', 'BSIT', '4th Year', 'Zone 6', 'Damilag', 'Manolo Fortich', 'Province 1', '8703', 'ANGELA.png', '2024-07-15 09:40:23', '2024-09-15 08:21:17'),
(20201270, 'Andrie Jose', 'Ipulan', 'Macas', '22', '1111-11-11', 'Male', '20201270@nbsc.edu.ph', '1', '1', 'CCS', 'BSIT', '4th Year', 'Zone 0', 'Lunocan', 'Manolo Fortich', 'Bukidnon', '8703', 'ANDRIE.png', '2024-07-19 21:09:48', '2024-09-15 08:21:40'),
(20211035, 'Kristine Ligaya', '', 'Bagongon', '', '0000-00-00', 'Female', '20211035@nbsc.edu.ph\n', '1', '', 'CCS', 'BSIT', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-13 21:57:54', '2024-10-13 22:42:18'),
(20211081, 'Aya', '', 'Alim', '', '0000-00-00', 'Male', '', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-13 23:08:32', '2024-10-13 23:08:32'),
(20211150, 'Kurt Anthony', '', 'Sitoy', '', '0000-00-00', '', '20211150@nbsc.edu.ph', '1', '', 'CCS', 'BSIT', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-13 21:57:54', '2024-10-13 22:42:35'),
(20211245, 'Rhea Mae', '', 'Viola', '', '0000-00-00', 'Female', '', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-13 22:58:42', '2024-10-13 22:58:42'),
(20211341, 'Jonathan', '', 'Lumanoy', '', '0000-00-00', '', '20211341@nbsc.edu.ph', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-13 22:20:57', '2024-10-13 22:42:45'),
(20211514, 'Elluid', '', 'Tinga', '', '0000-00-00', 'Male', '20211514@nbsc.edu.ph', '1', '', 'BSBA', '', '', '', '', '', '', '', 'PROF_PIC.png', '2024-10-13 22:12:43', '2024-10-13 22:42:48'),
(20211521, 'Merlinda', 'Yepes', 'Magno', '22', '0000-00-00', 'Female', '20211521@nbsc.edu.ph', '1', '', 'CCS', 'BSIT', '4th Year', '', '', '', '', '', 'MERLINDA.png', '2024-07-19 21:10:05', '2024-09-15 08:21:52'),
(20211524, 'John', 'Dummy', 'Account', '', '0000-00-00', 'Male', '20211524@nbsc.edu.ph', '1', '', 'BSBA', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-15 20:21:49', '2024-10-13 22:41:03'),
(20211525, 'Jane', 'Dummy', 'Account', '', '0000-00-00', 'Female', '20211525@nbsc.edu.ph', '1', '', 'BSBA', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-15 20:24:56', '2024-10-13 22:42:59'),
(20211526, 'Carl', 'Dummy', 'Account', '', '0000-00-00', 'Male', '20211526@nbsc.edu.ph', '1', '', 'TEP', '', '1st Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-15 20:27:54', '2024-10-13 22:43:09'),
(20211527, 'Joe', 'Dummy', 'Account', '', '0000-00-00', 'Male', '20211527@nbsc.edu.ph', '1', '', 'CCS', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-15 20:29:35', '2024-10-13 22:43:12'),
(20211860, 'Allan', '', 'Cenia', '', '0000-00-00', 'Male', '20211860@nbsc.edu.ph', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-13 22:30:59', '2024-10-13 22:43:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_activity_logs`
--
ALTER TABLE `tbl_activity_logs`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `tbl_application`
--
ALTER TABLE `tbl_application`
  ADD PRIMARY KEY (`application_id`);

--
-- Indexes for table `tbl_application_answers`
--
ALTER TABLE `tbl_application_answers`
  ADD PRIMARY KEY (`answer_id`);

--
-- Indexes for table `tbl_application_questions`
--
ALTER TABLE `tbl_application_questions`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  ADD PRIMARY KEY (`club_id`);

--
-- Indexes for table `tbl_clubs_and_moderators`
--
ALTER TABLE `tbl_clubs_and_moderators`
  ADD PRIMARY KEY (`clubmod_id`);

--
-- Indexes for table `tbl_club_officers`
--
ALTER TABLE `tbl_club_officers`
  ADD PRIMARY KEY (`officer_id`);

--
-- Indexes for table `tbl_club_recommendations`
--
ALTER TABLE `tbl_club_recommendations`
  ADD PRIMARY KEY (`recommendation_id`);

--
-- Indexes for table `tbl_club_requests`
--
ALTER TABLE `tbl_club_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `tbl_departure_requests`
--
ALTER TABLE `tbl_departure_requests`
  ADD PRIMARY KEY (`departure_id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tbl_moderators`
--
ALTER TABLE `tbl_moderators`
  ADD PRIMARY KEY (`moderator_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `tbl_officers_charts`
--
ALTER TABLE `tbl_officers_charts`
  ADD PRIMARY KEY (`chart_id`);

--
-- Indexes for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `tbl_posts_attachments`
--
ALTER TABLE `tbl_posts_attachments`
  ADD PRIMARY KEY (`attachment_id`);

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_activity_logs`
--
ALTER TABLE `tbl_activity_logs`
  MODIFY `activity_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1413;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24251016;

--
-- AUTO_INCREMENT for table `tbl_application`
--
ALTER TABLE `tbl_application`
  MODIFY `application_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `tbl_application_answers`
--
ALTER TABLE `tbl_application_answers`
  MODIFY `answer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `tbl_application_questions`
--
ALTER TABLE `tbl_application_questions`
  MODIFY `question_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;

--
-- AUTO_INCREMENT for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  MODIFY `chat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  MODIFY `club_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `tbl_clubs_and_moderators`
--
ALTER TABLE `tbl_clubs_and_moderators`
  MODIFY `clubmod_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=323;

--
-- AUTO_INCREMENT for table `tbl_club_officers`
--
ALTER TABLE `tbl_club_officers`
  MODIFY `officer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_club_recommendations`
--
ALTER TABLE `tbl_club_recommendations`
  MODIFY `recommendation_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231;

--
-- AUTO_INCREMENT for table `tbl_club_requests`
--
ALTER TABLE `tbl_club_requests`
  MODIFY `request_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT for table `tbl_departure_requests`
--
ALTER TABLE `tbl_departure_requests`
  MODIFY `departure_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `event_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=770;

--
-- AUTO_INCREMENT for table `tbl_officers_charts`
--
ALTER TABLE `tbl_officers_charts`
  MODIFY `chart_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  MODIFY `post_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;

--
-- AUTO_INCREMENT for table `tbl_posts_attachments`
--
ALTER TABLE `tbl_posts_attachments`
  MODIFY `attachment_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `student_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201911245;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
