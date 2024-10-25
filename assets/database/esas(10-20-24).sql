-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2024 at 02:59 PM
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
(568, 'You logged out of your account', '2024-10-20 12:56:20', NULL, NULL, 20191115);

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
  `information` text NOT NULL,
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

INSERT INTO `tbl_clubs` (`club_id`, `clubName`, `information`, `keywords`, `coverPhoto`, `slots`, `founder_id`, `dateAdded`, `dateModified`) VALUES
(1, 'NBSC Quick Response Team', 'The NBSC Quick Response Team (QRT) is a dedicated student organization at NBSC College, focused on providing rapid assistance and support in emergency situations within the campus. Comprised of well-trained and committed students, the QRT specializes in first aid, emergency response, and disaster preparedness. The club regularly conducts training sessions and workshops in collaboration with local emergency services to ensure its members are equipped with the latest knowledge and skills. This proactive approach not only enhances the safety and well-being of the NBSC community but also fosters a culture of readiness and resilience among students.\\r\\nIn addition to emergency response, the NBSC Quick Response Team plays a significant role in promoting health and safety awareness across the campus. Through various outreach programs, the club educates students and staff on best practices for personal safety, emergency preparedness, and effective response strategies. The QRT also actively participates in campus-wide drills and simulations, ensuring that the entire college community is prepared to handle potential crises. By serving as a vital resource and advocate for safety, the NBSC Quick Response Team contributes to creating a secure and supportive environment at NBSC College.', '', 'COVERPHOTO_QUICKRESPONSETEAM.png', 250, 24251014, '2022-08-18 03:19:13', '2024-10-20 12:22:13'),
(2, 'NBSC Band Sound Space', 'The NBSC Band is a dynamic and vibrant student organization at NBSC College, dedicated to cultivating musical talent and fostering a sense of community among its members. Established in 2010, the band has grown to become a staple of college events, performing at ceremonies, sports games, and various campus activities. The group welcomes students from all departments and years, encouraging collaboration and skill development across different musical genres, including classical, jazz, pop, and rock. The band practices regularly in the school\'s music hall, ensuring that members have ample opportunity to hone their craft and prepare for performances.\n\nIn addition to providing a platform for musical expression, the NBSC Band also emphasizes leadership and teamwork. Members have the opportunity to take on roles such as section leaders, event coordinators, and public relations officers, gaining valuable experience in organization and management. The band also participates in regional and national competitions, often earning accolades for their performances. Beyond the music, the NBSC Band fosters a supportive and inclusive environment, where students can build lasting friendships and develop a deep appreciation for the arts.', '', 'COVERPHOTO_NBSCBAND.png', 150, 24251014, '2023-03-19 15:23:09', '2024-10-19 16:26:37'),
(3, 'MAS-AMICUS', 'MAS-AMICUS, short for \"Mutual Aid System for Affiliates of the Medical Informatics Community in the United States,\" is a collaborative initiative aimed at fostering solidarity and support among medical informatics professionals across the country. Founded with the vision of enhancing professional development and knowledge sharing within the field of medical informatics, MAS-AMICUS provides a structured platform for members to engage in peer-to-peer learning, mentorship, and networking opportunities. Through its various programs and events, MAS-AMICUS strives to cultivate a community where members can exchange insights, discuss emerging trends, and address challenges in healthcare informatics.\n\nCentral to MAS-AMICUS\'s mission is the promotion of innovation and best practices in medical informatics. By facilitating dialogue and collaboration among its affiliates, MAS-AMICUS aims to drive advancements in healthcare technology and data management practices. Members benefit from access to resources such as workshops, webinars, and research forums that enable them to stay current with industry developments and contribute to the evolution of healthcare informatics standards. As a supportive network, MAS-AMICUS plays a pivotal role in empowering its members to navigate complexities within the healthcare landscape, ultimately enhancing patient care outcomes through the effective use of informatics solutions.', '', 'COVERPHOTO_MASAMICUS.png', NULL, 24251014, '2023-09-18 12:51:22', '2024-10-19 15:00:53'),
(4, 'Muslim Student\'s Society', 'The Muslim Student\'s Society (MSS) at NBSC is a vibrant and inclusive community dedicated to fostering spiritual growth, cultural understanding, and social responsibility. We organize a range of activities, including thought-provoking discussions, cultural festivals, and interfaith dialogues, aimed at deepening knowledge and appreciation of Islamic values and traditions. Our events provide a platform for students to connect, share experiences, and engage in meaningful conversations, creating a supportive environment for personal and spiritual development. Beyond our internal activities, MSS is committed to giving back to the community through various outreach programs and charity initiatives. By participating in community service projects and fundraising events, members contribute to positive social change while building a strong sense of camaraderie. Joining MSS not only offers a chance to strengthen one\'s faith and cultural identity but also to make a tangible impact on the lives of others, enhancing both personal growth and community well-being.', '', 'COVERPHOTO_MUSLIMSTUDENT\'SSOCIETY.png', NULL, 24251014, '2023-11-18 12:51:22', '2024-10-19 15:00:53'),
(5, 'Dramatic Society', 'Join the Dramatic Society and immerse yourself in the vibrant world of theater and performance! Whether you\'re an aspiring actor, a backstage wizard, or just passionate about the arts, our club offers a creative outlet to explore and express your talents. With regular workshops, rehearsals, and performances, you\'ll have countless opportunities to hone your craft and showcase your skills. Our experienced mentors and supportive community are here to guide you every step of the way. Be part of a dynamic group where your passion for drama can flourish and make lasting friendships along the way.  As a member of the Dramatic Society, you\'ll gain hands-on experience in all aspects of theater production, from acting and directing to set design and stage management. We welcome students of all skill levels, providing a nurturing environment where you can develop your abilities and grow as a performer. Our club not only enhances your creativity but also builds confidence and teamwork skills that are valuable beyond the stage. Join us to participate in exciting projects, collaborate with like-minded individuals, and make a meaningful impact through the power of storytelling. Discover the thrill of live performance and become a key player in our artistic community.', '', 'COVERPHOTO_DRAMATICSOCIETY.png', 150, 24251014, '2023-12-18 12:51:22', '2024-10-19 16:26:52'),
(6, 'Young Historians Club', 'Discover the Young Historians Club, where history comes alive and every member\'s voice matters. We delve into fascinating historical events, from ancient civilizations to modern times, exploring their impact on today\'s world. With engaging discussions, interactive projects, and historical reenactments, we offer a dynamic way to learn and connect with fellow history enthusiasts. Our club also provides opportunities to participate in history-themed competitions and attend exclusive events with guest speakers. Join us to deepen your understanding of the past while making lasting friendships.  Being part of the Young Historians Club means being at the forefront of historical exploration and analysis. We foster a supportive environment where members can share their perspectives, conduct research, and contribute to meaningful projects. Whether you have a passion for history or are looking to explore new interests, our club offers a welcoming space to expand your knowledge and skills. We believe that history is not just about the past but also about shaping the future through understanding. Come be a part of our journey and help us uncover the stories that define our world.', '', 'COVERPHOTO_YOUNGHISTORIANSCLUB.png', NULL, 24251014, '2024-01-18 12:51:22', '2024-10-19 15:00:53'),
(7, 'English Club', 'Join the English Club and immerse yourself in a vibrant community dedicated to the love of language and literature. Whether you\'re passionate about classic novels, contemporary poetry, or creative writing, our club offers a range of activities designed to spark your literary enthusiasm. Participate in engaging discussions, creative workshops, and exciting competitions that help you hone your writing and communication skills. Our members also enjoy exclusive access to author talks, book fairs, and literary events that enrich their understanding of the English language. Embrace the opportunity to connect with fellow students who share your interests and explore the endless possibilities that come with mastering English.  The English Club is not just about reading and writing; it\'s about building friendships and creating lasting memories. Join us for fun social events, including themed parties, movie nights, and group outings, all while improving your language skills. We offer mentorship and support for academic and personal growth, ensuring that every member feels valued and inspired. Take part in community service projects that use language to make a positive impact, and develop skills that will benefit you both academically and professionally. Become a part of a club where your passion for English can truly flourish and where your voice will be heard.', '', 'COVERPHOTO_ENGLSIHCLUB.png', 150, 24251014, '2024-02-14 14:55:34', '2024-10-19 16:27:07'),
(8, 'Math-Sci Club', 'The Math-Sci Club offers an exciting opportunity for students who are passionate about mathematics and science to dive deeper into these fascinating fields. Members can participate in engaging activities such as solving complex problems, conducting experiments, and exploring cutting-edge technologies. The club regularly hosts workshops, guest lectures, and competitions that provide hands-on experience and enhance problem-solving skills. By joining, students gain access to a community of like-minded peers and mentors who are dedicated to fostering a love for STEM. This is not just a club; it\'s a gateway to academic and career growth in the fields of math and science.  Being a part of the Math-Sci Club means you\'ll be involved in innovative projects and collaborative research that push the boundaries of traditional learning. Our members enjoy exclusive access to various resources, including specialized software and research opportunities. We also organize field trips to science museums, laboratories, and tech companies, offering real-world insights into the industries they aspire to join. Whether you\'re aiming for a career in engineering, research, or education, the Math-Sci Club provides a supportive environment to develop your skills and achieve your goals. Join us and turn your curiosity into expertise while making lifelong connections in the world of math and science.', '', 'COVERPHOTO_MATH-SCICLUB.png', 100, 24251014, '2024-03-15 02:05:15', '2024-10-19 16:27:14'),
(9, 'KAMFIL Club', 'The KAMFIL Club, which stands for \"Kabalikat ng Masisipag na Filipino\" or \"Companion of Diligent Filipinos,\" offers a vibrant and dynamic environment where students can engage in a range of exciting activities and make lasting friendships. As a member, you\'ll have the opportunity to participate in various workshops, seminars, and community service projects aimed at personal and professional development. Our club prides itself on fostering a collaborative atmosphere where your ideas are valued and you can take on leadership roles. Join us to enhance your skills, gain valuable experience, and be part of a supportive network of peers. Whether you\'re interested in developing new skills or contributing to meaningful causes, KAMFIL Club is the perfect place to start.  Being part of KAMFIL Club means you\'ll be involved in projects that make a real impact within our community and beyond. We provide numerous opportunities for networking with professionals, engaging in hands-on experiences, and working on projects that align with your interests. Our members enjoy exclusive access to events and resources designed to help you succeed both academically and personally. With a focus on growth and collaboration, KAMFIL Club is dedicated to helping you achieve your goals and make the most of your college experience. Discover the benefits of joining a club that values innovation, teamwork, and community.', '', 'COVERPHOTO_KAMFILCLUB.png', 150, 24251014, '2024-03-15 02:05:15', '2024-10-19 16:27:31'),
(10, 'Mountaineering Society', 'The Mountaineering Society is the perfect club for students seeking adventure and personal growth. Join us to explore breathtaking mountain trails, tackle thrilling climbs, and develop essential outdoor skills in a supportive community. Our activities cater to all skill levels, from beginners to seasoned hikers, ensuring everyone can enjoy the thrill of mountaineering safely. By becoming a member, you’ll not only challenge yourself but also make lasting friendships with fellow enthusiasts who share your passion for the great outdoors. Don’t miss out on this opportunity to push your limits and experience the world from a new perspective.  In addition to our regular hikes and climbs, the Mountaineering Society offers workshops on navigation, survival techniques, and environmental stewardship. Our experienced guides and instructors are dedicated to providing a comprehensive learning experience while ensuring your safety and enjoyment. We also host social events and team-building activities, creating a vibrant and inclusive environment. Whether you\\\'re looking to conquer new heights or simply connect with nature, the Mountaineering Society is your gateway to an exhilarating and rewarding experience. Join us and embark on your next adventure with a community that truly understands the spirit of mountaineering.', '', 'COVERPHOTO_MOUNTAINEERINGSOCIETY.png', 150, 24251014, '2024-05-15 02:05:15', '2024-10-19 18:00:41'),
(11, 'Debate Club', 'Join the Debate Club and sharpen your critical thinking skills while engaging in stimulating discussions on a wide range of topics. This club provides a dynamic platform for students to articulate their opinions, build strong arguments, and enhance public speaking abilities. Whether you\'re passionate about current events, politics, or social issues, the Debate Club offers an opportunity to explore and debate these subjects with peers. You\'ll gain valuable experience in research, teamwork, and persuasive communication that can benefit you in both academic and professional settings. By joining, you become part of a community that values intellectual growth and the exchange of diverse perspectives.  In addition to weekly debates and meetings, the Debate Club participates in local and national competitions, allowing members to showcase their skills on a larger stage. The club\'s supportive environment encourages members to practice and perfect their debating techniques, receive constructive feedback, and celebrate each other\'s successes. Joining the Debate Club means becoming a part of a network of like-minded individuals who are committed to learning and personal development. Embrace the challenge, develop lifelong skills, and make lasting friendships by joining the Debate Club today.', '', 'COVERPHOTO_DEBATECLUB.png', 150, 24251014, '2024-08-17 01:53:24', '2024-10-19 16:27:39'),
(12, 'Arts Society', 'The Arts Society is a vibrant and inclusive community dedicated to fostering creativity and artistic expression. Our club provides a dynamic platform for students to explore various art forms, from painting and sculpture to digital design and performance arts. By joining, you\'ll gain access to exclusive workshops, exhibitions, and collaborative projects that will help you refine your skills and build a strong portfolio. Whether you’re a seasoned artist or just starting out, our supportive environment encourages growth and self-expression. We believe in the power of art to inspire, connect, and transform lives, and we invite you to be a part of this exciting journey.  As a member of the Arts Society, you\'ll have the opportunity to work alongside passionate peers and experienced mentors who share your enthusiasm for the arts. Our club regularly hosts events such as art shows, open mic nights, and community outreach programs that not only showcase your talents but also engage with the broader community. Networking with fellow artists and participating in collaborative projects will expand your creative horizons and provide valuable experience. Join us to be part of a creative family that celebrates diversity, innovation, and artistic excellence. Your unique perspective and creativity will contribute to our vibrant community, making a lasting impact on both your personal development and the art world.', '', 'COVERPHOTO_ARTSSOCIETY.png', 150, 24251014, '2024-08-17 01:53:24', '2024-10-19 16:27:44'),
(13, 'Indigenous People Society', 'The Indigenous People Society offers a unique opportunity for students to engage with and support indigenous communities and cultures. Through various activities and initiatives, members learn about traditional practices, languages, and the rich heritage of indigenous peoples. The club organizes workshops, cultural events, and community outreach programs that foster a deeper understanding and appreciation of these vital cultures. Joining this society allows you to contribute to preserving and promoting indigenous traditions while gaining valuable insights and experiences. It’s an ideal way to make a meaningful impact and broaden your cultural horizons.  Being part of the Indigenous People Society connects you with a diverse group of passionate individuals who share an interest in social justice and cultural preservation. You will have the chance to collaborate on projects that address current issues facing indigenous communities, from education to environmental sustainability. The club also provides a platform for you to develop leadership and organizational skills through hands-on involvement in planning and executing events. By participating, you become an advocate for important causes and help drive positive change in the community. Join us to be part of a movement that celebrates and respects the rich tapestry of indigenous cultures.', '', 'COVERPHOTO_INDIGENOUSPEOPLESOCIETY.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-19 15:00:53'),
(14, 'Young Catholic Servants of Christ', 'The Young Catholic Servants of Christ is a vibrant and welcoming community dedicated to fostering spiritual growth and active service among students. By joining, you’ll engage in meaningful activities that promote personal development, leadership, and a deeper connection with your faith. Our club offers regular retreats, prayer meetings, and community outreach programs that not only enrich your spiritual life but also contribute positively to the surrounding community. We believe in creating a supportive environment where every member can grow and thrive. Whether you’re looking to deepen your faith or make a difference, YCSC provides opportunities for both personal and communal fulfillment.  As a member of YCSC, you will be part of a dynamic group of like-minded peers who are passionate about making a difference. We organize a range of events and projects that allow you to apply your skills and interests in a way that benefits others. Additionally, the club provides a platform for building lasting friendships and developing skills that will serve you well in all areas of life. Joining YCSC means becoming part of a legacy of service and faith, with numerous opportunities for growth and impact. Embrace the chance to be a part of something greater and make a positive change in the world around you.', '', 'COVERPHOTO_YOUNGCATHOLICSERVANTSOFCHRIST.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-19 15:00:53'),
(15, 'Peer Counselor\'s Club', 'Join the Peer Counselor\'s Club and become a vital part of a supportive community dedicated to helping fellow students thrive. Our club offers a unique opportunity to develop essential skills in counseling, communication, and leadership while making a meaningful impact on campus. As a member, you\'ll gain hands-on experience in providing peer support, organizing workshops, and participating in various community outreach programs. Engage in regular training sessions and collaborate with like-minded individuals who are passionate about making a difference in others\' lives.  In addition to personal growth and skill development, the Peer Counselor\'s Club provides a platform for building lasting friendships and networking with professionals in the field of mental health and counseling. By joining, you\'ll be part of a dynamic team committed to fostering a positive and inclusive campus environment. Take advantage of this chance to enhance your resume, gain valuable life experience, and contribute to the well-being of your peers. We welcome all students who are eager to make a difference and grow both personally and professionally.', '', 'COVERPHOTO_PEERCOUNSELOR\'SCLUB.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-19 15:00:53'),
(16, 'Sports', 'Join the Sports Club and immerse yourself in a world of excitement and teamwork! Whether you’re passionate about competitive sports or just looking to stay active, our club offers a variety of activities that cater to all skill levels. From intense games to casual matches, you\'ll find opportunities to challenge yourself and improve your skills. Our experienced coaches and friendly members will support you every step of the way. Plus, you\'ll have access to exclusive events, tournaments, and workshops to elevate your game.  Being part of the Sports Club means more than just playing sports—it’s about building lifelong friendships and fostering a sense of community. We regularly organize social events and team-building activities that help strengthen bonds and create unforgettable memories. By joining, you\'ll also gain valuable leadership and teamwork experience that will benefit you beyond the playing field. Don’t miss out on the chance to be part of a vibrant and active community that values both fun and personal growth. Sign up today and start your journey with us!', '', 'COVERPHOTO_SPORTS.png', NULL, 24251014, '2024-08-17 01:53:24', '2024-10-19 15:00:53'),
(17, 'Environmental Club', 'Join the Environmental Club and become a champion for sustainability! Our club is dedicated to making a positive impact on the environment through various initiatives, including campus clean-ups, tree planting, and educational workshops. By participating, you\'ll gain hands-on experience in environmental conservation and connect with like-minded individuals who are passionate about protecting our planet. You\'ll also have the opportunity to collaborate on exciting projects and advocate for eco-friendly practices within our community. Together, we can create a greener future and make a real difference.  In the Environmental Club, you\'ll not only contribute to meaningful environmental change but also develop valuable skills and leadership qualities. We host regular events and campaigns to raise awareness about pressing environmental issues and promote sustainable living. Membership provides a platform for you to voice your ideas, engage in innovative solutions, and participate in fun, impactful activities. Join us to enhance your resume, build a network of environmentally-conscious peers, and be part of a movement that truly matters. Your involvement can lead to lasting positive effects on our surroundings and inspire others to take action.', '', 'COVERPHOTO_ENVIRONMENTALCLUB.png', 150, 24251014, '2024-08-17 01:53:24', '2024-10-19 16:28:08'),
(18, 'NBSC Dance Troup', 'Join the NBSC Dance Troupe and become part of a vibrant and dynamic community where your passion for dance can truly flourish. Our club offers a diverse range of dance styles, from contemporary and hip-hop to traditional and ballroom, ensuring that there’s something for everyone. You\'ll have the opportunity to work with talented choreographers and experienced dancers, who will help you refine your skills and boost your confidence. Regular performances and showcases allow you to demonstrate your talent and creativity, gaining valuable stage experience. Being a member also means forging lasting friendships and connections within the college, making your time here memorable and enjoyable.  In addition to honing your dance abilities, the NBSC Dance Troupe fosters a supportive and encouraging environment where personal growth and teamwork are emphasized. We believe in the power of dance to inspire, energize, and unite, creating a space where all members can thrive. The club hosts workshops, social events, and collaborations with other student organizations, ensuring a well-rounded experience. By joining, you\'ll be part of a tradition of excellence and creativity that has left a lasting impact on the college community. Embrace the rhythm and join us in making every step count!', '', 'COVERPHOTO_NBSCDANCETROUP.png', 200, 24251014, '2024-08-17 01:53:24', '2024-10-19 16:28:26'),
(19, 'NBSC Chorale', 'The NBSC Chorale is a vibrant and inclusive club that brings together students with a passion for music and performance. As part of our choir, you\'ll have the opportunity to explore a diverse repertoire, from classical pieces to contemporary hits, all while enhancing your vocal skills. Our rehearsals and performances offer a supportive environment where members can grow as artists and develop lifelong friendships. Joining the NBSC Chorale means becoming part of a community that values creativity, teamwork, and dedication. Whether you\'re an experienced singer or a beginner, you\'ll find a welcoming space to share your love for music.  Being a part of the NBSC Chorale not only allows you to showcase your talents but also provides numerous benefits, including opportunities to perform at various campus and community events. Our club fosters a strong sense of camaraderie and offers a chance to represent NBSC with pride. With regular practice sessions and exciting performance schedules, you\'ll gain confidence and improve your musical abilities. Join us and be a part of something special—an enriching experience that blends musical excellence with personal growth. Let your voice be heard and make lasting memories with the NBSC Chorale.', '', 'COVERPHOTO_NBSCCHORALE.png', 100, 24251014, '2024-08-17 01:53:24', '2024-10-19 16:28:33'),
(21, 'Educator\'s Club', 'The Educator\'s Club is a vibrant community dedicated to fostering a passion for teaching and learning among students. As a member, you\'ll have the opportunity to engage in a variety of educational activities, from organizing workshops and tutoring sessions to participating in mentorship programs. Our club values collaboration and creativity, providing a platform where you can develop essential skills such as public speaking, leadership, and critical thinking. We regularly host guest speakers and educational events to broaden your perspective and inspire you to make a positive impact in the educational field. Joining the Educator\'s Club means becoming part of a supportive network committed to academic excellence and personal growth.  By participating in the Educator\'s Club, you will gain valuable experience that can enhance your resume and prepare you for future careers in education, counseling, and beyond. Whether you have a deep-seated interest in teaching or simply want to contribute to your community, our club offers a welcoming environment for all who are eager to learn and grow. We encourage you to take advantage of our diverse range of activities and connect with like-minded peers who share your enthusiasm for education. Our inclusive and dynamic environment ensures that every member can find their niche and make a meaningful contribution. Join us to explore new opportunities, develop your skills, and become an integral part of a community dedicated to educational advancement.', '', 'COVERPHOTO_EDUCATOR\'SCLUB.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-19 15:00:53'),
(22, 'Infotech Club', 'The Infotech Club offers an exciting and dynamic environment for students passionate about technology and innovation. Our club provides hands-on experience with the latest tech trends, from coding and app development to cybersecurity and artificial intelligence. Members have the opportunity to participate in workshops, hackathons, and tech talks led by industry experts, enhancing both their skills and their resumes. We also foster a collaborative community where students can work on projects, share ideas, and build lasting connections with like-minded peers. Joining the Infotech Club means stepping into a world of endless possibilities and career growth in the ever-evolving field of information technology.  In addition to technical skills, the Infotech Club emphasizes personal and professional development. Our members benefit from networking opportunities with local tech companies and alumni who offer valuable insights and mentorship. We organize regular events, including coding competitions and tech expos, to keep members engaged and motivated. By joining, you’ll be part of a supportive and innovative team dedicated to pushing the boundaries of technology. Don’t miss the chance to be at the forefront of tech advancements and make a meaningful impact in the field of information technology.', '', 'COVERPHOTO_INFOTECHCLUB.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-19 15:00:53'),
(23, 'Red-Cross Youth', 'The Red-Cross Youth Club is dedicated to making a positive impact both locally and globally through humanitarian efforts. As a member, you\'ll engage in exciting and meaningful activities like disaster response training, community service projects, and health awareness campaigns. You’ll gain valuable skills in leadership, teamwork, and emergency preparedness, all while contributing to the well-being of others. Our club also provides opportunities to connect with peers who share your passion for helping those in need and to build lasting friendships. Join us to be part of a supportive community committed to making a difference and enhancing your personal growth.  Being part of the Red-Cross Youth Club means being at the forefront of community and global initiatives. You\'ll have access to specialized workshops and seminars led by experienced professionals in emergency management and first aid. Additionally, the club offers numerous volunteering opportunities that not only benefit the community but also enrich your resume and academic profile. Our activities are designed to be both impactful and engaging, ensuring that you have a rewarding experience. Embrace the chance to contribute to meaningful causes and develop skills that will benefit you throughout your life.', '', 'COVERPHOTO_REDCROSSYOUTH.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-19 16:29:37'),
(24, 'NBSC Scholar\'s Society', 'The NBSC Scholar\'s Society is a vibrant community dedicated to fostering academic excellence and personal growth among students. As a member, you\'ll gain access to a network of motivated peers and experienced mentors who are passionate about helping you succeed. Our club offers a range of engaging activities, including study sessions, workshops, and guest lectures from industry professionals. Joining the Society provides you with valuable opportunities to enhance your skills, build your resume, and connect with like-minded individuals. Whether you\'re aiming for academic success or looking to develop leadership abilities, the Scholar\'s Society is the perfect platform for your growth.  Being part of the NBSC Scholar\'s Society also means participating in exciting social events and collaborative projects that make learning fun and impactful. We are committed to creating a supportive environment where each member can thrive and reach their full potential. Our club regularly organizes networking events and community service projects, giving you a chance to make a difference both academically and socially. By joining, you\'ll be part of a close-knit community that celebrates achievements and supports each other through challenges. Embrace this opportunity to join a club that values academic excellence and personal development while having fun along the way.', '', 'COVERPHOTO_NBSCSCHOLAR\'SSOCIETY.png', NULL, 24251014, '2024-09-15 09:20:15', '2024-10-19 15:00:53'),
(25, 'Campus Seekers of Christ', 'Campus Seekers of Christ is a vibrant and welcoming community dedicated to spiritual growth and fellowship. Our club provides a supportive environment where students can explore their faith, engage in meaningful discussions, and build lifelong friendships. We organize regular events such as Bible studies, prayer meetings, and social gatherings that cater to diverse interests and schedules. Joining us means becoming part of a nurturing group that values personal development and collective well-being. With a focus on inclusivity and mutual support, we invite you to experience the joy and purpose that comes from being part of our community.  By participating in Campus Seekers of Christ, you will have the opportunity to deepen your understanding of your faith while contributing to various outreach initiatives. Our members actively engage in service projects and community events, making a positive impact both on and off-campus. Whether you are seeking spiritual enrichment or looking to connect with others who share your values, our club offers a platform for growth and meaningful connections. We encourage students from all backgrounds and beliefs to join us in our journey of faith and service. Discover how being part of our club can enrich your college experience and provide a sense of belonging.', '', 'COVERPHOTO_CAMPUSSEEKERSOFCHRIST.png', NULL, 24251014, '2024-09-21 13:40:18', '2024-10-19 15:00:53'),
(42, 'Strings and Symbols', 'The Strings and Symbols Club at NBSC College is a vibrant student organization dedicated to exploring the art and science of music and mathematics. With a unique blend of creative expression and analytical thinking, the club serves as a space for students passionate about music, instruments, composition, and mathematical concepts. Members of the Strings and Symbols Club engage in collaborative music sessions, performances, and workshops that highlight the connection between patterns in music and mathematical theories.  In addition to fostering musical talent, the club provides a platform for academic discussions on the intersection of math and music. The club regularly organizes events such as performances, math-themed music challenges, and guest lectures from experts in both fields. By combining creativity with critical thinking, the Strings and Symbols Club aims to nurture a well-rounded skill set among its members, encouraging them to appreciate the harmony between numbers and notes. Through its activities, the club cultivates an inclusive environment where students can freely express their musical and mathematical interests while enhancing their academic and artistic skills.', '', 'COVERPHOTO_STRINGSANDSYMBOLS.png', 150, 24251014, '2024-09-21 13:40:18', '2024-10-19 16:30:01'),
(43, 'YASM', 'The Youth Advocates for Sustainable Movements (YASM) Club at NBSC College is a student-driven organization dedicated to promoting environmental sustainability and social responsibility. Its members are passionate about addressing pressing global issues such as climate change, waste management, and sustainable development. The club organizes various activities, including environmental clean-up drives, tree-planting events, and sustainability workshops, to raise awareness and engage the NBSC community in meaningful action.  YASM also collaborates with local organizations and environmental groups to implement long-term initiatives aimed at reducing the campus\' ecological footprint. By encouraging students to adopt eco-friendly habits and participate in sustainable practices, the YASM Club fosters a culture of environmental stewardship. Through its efforts, the club aims to inspire the next generation of leaders to champion sustainability both on campus and in the wider community.', '', 'COVERPHOTO_DEFAULT.png', NULL, 24251014, '2024-09-21 13:40:18', '2024-10-19 15:00:53'),
(44, 'Ballpoint Publication', 'The Ballpoint Publication Club at NBSC College is a creative platform for students passionate about writing, journalism, and media. This student-led organization offers members the opportunity to engage in various forms of literary expression, from news reporting and feature writing to poetry and creative nonfiction. The club regularly publishes a student newsletter and manages digital content that highlights campus events, student achievements, and pressing issues within the college community. Through collaborative efforts, members of the Ballpoint Publication Club develop their skills in writing, editing, and multimedia production.  In addition to producing high-quality publications, the Ballpoint Publication Club plays an important role in fostering a culture of communication and critical thinking across campus. The club organizes writing workshops, seminars, and discussions on journalism ethics and media literacy, helping students improve their craft while staying informed about the responsibilities of media in society. By providing a space for student voices to be heard and stories to be told, the Ballpoint Publication Club enriches the intellectual and creative life of NBSC College.', '', 'COVERPHOTO_BALLPOINTPUBLICATION.png', 100, 24251014, '2024-09-22 03:54:57', '2024-10-19 16:30:18'),
(45, 'Campus Bible Fellowship', 'The Campus Bible Fellowship (CBF) at NBSC College is a student-led organization dedicated to fostering spiritual growth, fellowship, and biblical understanding among students. The CBF provides a welcoming environment where students can come together for Bible study, prayer, and meaningful discussions on faith-related topics. Regular meetings offer opportunities for reflection, learning, and the strengthening of personal relationships with God.   In addition to spiritual nourishment, the CBF organizes community outreach programs, aiming to make a positive impact both on campus and beyond. These initiatives include service projects, charity drives, and collaboration with other Christian groups. The club also holds special events such as retreats and fellowship gatherings, providing members with deeper connections to their faith and each other. The Campus Bible Fellowship serves as a supportive space for students seeking to explore and grow in their faith journey while building lasting friendships with like-minded peers.', '', 'COVERPHOTO_BIBLICALCAMPUSMINISTRY.png', NULL, 24251014, '2024-09-23 03:55:18', '2024-10-19 15:00:53'),
(71, 'ArsyArts', 'a', '', 'club_6713db52bb21c.jpg', NULL, 24251014, '2024-10-19 16:16:18', '2024-10-19 16:16:18');

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
(11, 10, 22230001, '2024-05-15 02:05:15', '2024-10-02 12:31:00'),
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
(34, 17, 24250017, '2024-09-25 01:25:04', '2024-10-02 12:56:20'),
(259, 16, 0, '2024-10-08 12:19:24', '2024-10-08 12:19:24'),
(260, 17, 0, '2024-10-08 12:20:02', '2024-10-08 12:20:02'),
(263, 0, 0, '2024-10-08 12:32:56', '2024-10-08 12:32:56');

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
(2, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2024-10-19 02:00:59', '2024-10-19 18:15:28'),
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
(2, 2, 'TEP', '2024-09-21 10:14:57', '2024-10-07 04:39:55'),
(5, 5, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(6, 6, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(7, 7, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(8, 8, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(9, 8, 'BSBA', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(10, 9, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(12, 11, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(13, 12, 'TEP', '2024-09-21 10:14:57', '2024-10-07 04:42:44'),
(14, 12, 'BSBA', '2024-09-21 10:14:57', '2024-10-07 04:43:02'),
(15, 12, 'CCS', '2024-09-21 10:14:57', '2024-10-07 04:43:17'),
(21, 21, 'TEP', '2024-09-21 10:14:57', '2024-09-21 10:14:57'),
(22, 18, 'CCS', '2024-09-21 10:14:57', '2024-09-23 16:12:47'),
(25, 16, 'TEP', '2024-09-21 10:14:57', '2024-10-07 04:44:18'),
(26, 16, 'BSBA', '2024-09-21 10:14:57', '2024-10-07 04:44:27'),
(27, 16, 'CCS', '2024-09-21 10:14:57', '2024-10-07 04:44:34'),
(31, 22, 'CCS', '2024-09-23 16:10:50', '2024-09-23 16:14:25'),
(35, 12, 'CCS', '2024-09-23 16:12:31', '2024-09-23 16:12:31'),
(36, 16, 'CCS', '2024-09-23 16:12:31', '2024-09-23 16:12:31'),
(37, 2, 'CCS', '2024-09-23 16:13:46', '2024-09-23 16:14:28'),
(39, 2, 'BSBA', '2024-09-23 16:15:27', '2024-09-23 16:15:27'),
(43, 12, 'BSBA', '2024-09-23 16:16:16', '2024-09-23 16:16:16'),
(44, 16, 'BSBA', '2024-09-23 16:16:34', '2024-09-23 16:16:34'),
(46, 18, 'BSBA', '2024-09-23 16:16:54', '2024-09-23 16:16:54'),
(47, 19, 'BSBA', '2024-09-23 16:16:54', '2024-09-23 16:16:54'),
(49, 42, 'BSBA', '2024-09-23 16:17:22', '2024-09-23 16:17:22'),
(52, 2, 'CCS', '2024-10-07 04:39:00', '2024-10-07 04:39:42'),
(55, 2, 'BSBA', '2024-10-07 04:40:09', '2024-10-07 04:40:09'),
(63, 18, 'TEP', '2024-10-07 04:45:46', '2024-10-07 04:45:46'),
(64, 18, 'BSBA', '2024-10-07 04:45:46', '2024-10-07 04:45:46'),
(65, 18, 'CCS', '2024-10-07 04:46:00', '2024-10-07 04:46:00'),
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
(188, 70, 'CCS', '2024-10-19 09:48:39', '2024-10-19 09:48:39');

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
(2, 'Pet Lovers Club', 'To advocate for animal welfare and responsible pet ownership.', 'To unite pet lovers and promote education about pet care and adoption.', 'A community where every pet is loved and cared for.', 'Pet adoption drives, educational seminars, volunteer opportunities at shelters, and pet care workshops.', 'pending', 'COVERPHOTO_PETLOVERS.png', 'request_letter_66f6d0b9df37c.pdf', '2024-10-09 02:00:13', '2024-10-13 09:43:51', '2024-10-09 02:00:13', 20191124),
(3, 'Multimedia Productions', 'To enhance members\' skills in multimedia creation and production.', 'To provide a platform for creativity and collaboration in multimedia projects.', 'A hub for innovative storytelling through various media.', 'Film screenings, workshops on video editing, collaborative projects, and guest speaker events.', 'approved', 'COVERPHOTO_MULTIMEDIA.png', 'request_letter_66f6e1ff2f929.pdf', '2024-09-27 05:07:27', '2024-10-06 05:54:19', '2024-10-06 05:54:19', 20191124),
(4, 'Cooking Club', 'The Cooking Club aims to unite culinary enthusiasts at our institution, offering a collaborative environment to explore diverse cuisines and refine cooking skills. Members will engage in hands-on cooking experiences, share recipes, and celebrate their love for food in a supportive community setting.', 'To foster a love for cooking and sharing meals within the community.', 'A culinary community that celebrates creativity and diversity in cooking.', 'The club will feature regular cooking workshops to teach various techniques and cuisines, recipe exchanges for sharing and discovering new dishes, and friendly cooking competitions to showcase members\' talents. Additional activities include hosting guest speakers from the culinary world and organizing community events to prepare and serve meals for local charities.', 'pending', 'COVERPHOTO_COOKING.png', 'request_letter_66f6e1ff2f929.pdf', '0000-00-00 00:00:00', '2024-10-09 12:08:40', '2024-10-09 12:08:40', 20191124),
(14, 'Agriculture Club', 'To empower students with practical knowledge and skills in sustainable agriculture, fostering innovation and leadership in farming practices while promoting environmental stewardship and food security within the community.', 'To educate members about agriculture, gardening, and environmental stewardship.', 'A community that values and practices sustainable farming for a healthier planet.', 'Workshops on gardening, farm visits, community service in local gardens, and seminars on sustainability practices.', 'approved', 'COVERPHOTO_AGRICULTURE.png', 'request_letter_66f6e1ff2f929.pdf', '2024-10-10 08:37:59', '2024-10-11 03:10:43', '2024-10-09 12:26:38', 20191115),
(32, 'Singles Club', 'To make every single double', 'Unite all singles and pair them as they want', 'Every single can find their true love', 'Dating activites', 'disapproved', 'COVERPHOTO_SINGLESCLUB.jpg', 'request_letter_66f6e1ff2f929.pdf', '2024-09-27 15:40:14', '2024-10-11 03:13:13', '2024-10-06 05:53:50', 20191124),
(37, 'Song Writers Clubs', 'To foster creativity and collaboration among aspiring songwriters, helping them develop their skills and share their original music.', 'The mission of the Song Writers Club is to create a supportive community where members can learn from one another, receive constructive feedback, and showcase their work through workshops, performances, and networking opportunities.', 'To be a vibrant hub for songwriters that inspires creativity, nurtures talent, and promotes the appreciation of original music within the community.', 'Weekly Writing Workshops: Members gather to write songs collaboratively and individually, share ideas, and provide feedback.', 'approved', 'request_66f6e1ff2f4e3.png', 'request_letter_66f6e1ff2f929.pdf', '2024-10-02 16:56:53', '2024-10-02 16:56:53', '2024-10-02 16:56:53', 20191124),
(49, 'for handsome', 'hi', 'y', 'y', 'h6y', 'pending', '670cb43b9123d.jpeg', '670cb43b914d6.docx', NULL, '2024-10-14 06:03:39', '2024-10-14 06:03:39', 20211150),
(50, 'cgh', 'dh', 'dj', 'yh', 'uru', 'pending', '670cb7a483a0a.jpeg', '670cb7a484110.docx', NULL, '2024-10-14 06:18:12', '2024-10-14 06:18:12', 20211514),
(51, 'Arts and culture\'s', 'To promote our natural culture', 's', 'sss', 'arts and sign', 'disapproved', '670cbc65436f3.png', '670cbc6543bee.docx', NULL, '2024-10-14 10:39:31', '2024-10-14 06:38:29', 20211860),
(52, 'Sample ', 'Sample', 'Sample ', 'Sample ', 'Sample', 'pending', '670cc287ded51.png', '670cc287df278.docx', NULL, '2024-10-14 07:04:39', '2024-10-14 07:04:39', 20211245),
(56, 'ArsyArts', 'sample1', 'sample2', 'sample3', 'sample4', 'approved', '670dc584eacc9.jpg', '670dc584eb507.docx', '2024-10-15 01:56:51', '2024-10-15 01:56:51', '2024-10-15 01:29:40', 20211521);

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
  `dateApproved` timestamp NOT NULL DEFAULT current_timestamp(),
  `student_id` int(20) NOT NULL,
  `club_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_departure_requests`
--

INSERT INTO `tbl_departure_requests` (`departure_id`, `reason`, `status`, `dateRequested`, `dateApproved`, `student_id`, `club_id`) VALUES
(12, 'Confidential.', 'pending', '2024-10-08 05:25:59', '2024-10-14 02:47:46', 20201179, 22),
(42, 'Drop', 'approved', '2024-10-19 13:18:48', '2024-10-19 13:19:13', 111, 4);

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
  `registrationLink` text NOT NULL,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL,
  `moderator_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_events`
--

INSERT INTO `tbl_events` (`event_id`, `title`, `description`, `date`, `timeStarts`, `timeEnds`, `location`, `registrationLink`, `dateAdded`, `dateModified`, `club_id`, `moderator_id`) VALUES
(1, 'Club Orientation', 'Join us for the Club Orientation, where you\'ll discover the vibrant world of student organizations at Northern Bukidnon State College! This event is designed for new students and returning members to learn about the various clubs available on campus, meet club leaders, and find out how you can get involved.', '2024-10-22', '08:00:00', '12:00:00', 'NBSC Covered Court', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-03 12:27:20', '2024-10-10 07:09:46', 22, 24250010),
(2, 'IT Days', 'Archers!? Pew! Pew! Pew!', '2024-10-30', '07:30:00', '17:00:00', 'NBSC', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-03 13:22:10', '2024-10-10 07:10:17', 22, 24250010),
(3, 'Earthquake Drill', 'Safety training during earthquake attacks', '2024-10-28', '09:00:00', '12:00:00', 'NBSC Ground', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-03 14:24:38', '2024-10-19 11:07:20', 1, 22230001),
(93, 'Meeting', 'Election for Muslim Officers', '2024-10-16', '08:01:00', '09:01:00', 'IT Laboratory 1', 'https://www.facebook.com/nbscstudentaffairsandservices', '2024-10-14 09:02:25', '2024-10-14 09:02:25', 4, 23240002);

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
(22230001, 'Cliff', 'Amadeus', 'Evangelio', '18', '2024-09-20', 'Male', 'cliff@gmail.com', '1', '09876543210', 'CCS', 'Instructor', '670dcd3f402c7-PROFPIC_SIR_CLIFF.png', '2022-08-18 03:19:13', '2024-10-18 11:56:17'),
(22230002, 'John Mark', '', 'Liwat', '', '0000-00-00', 'Male', 'johnmark@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2022-08-18 03:19:13', '2024-10-18 11:58:40'),
(22230003, 'Blessel', '', 'Quino', '', '0000-00-00', 'Female', 'blessel@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2023-03-19 15:23:09', '2024-10-02 12:30:14'),
(23240001, 'Teofany', '', 'Siton', '', '0000-00-00', 'Female', 'teofany@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2023-09-18 12:51:22', '2024-10-02 12:39:16'),
(23240002, 'Faisah', '', 'Bacarat', '', '0000-00-00', 'Female', 'faisah@gmail.com', '1', '09876543210', 'CCS', 'Instructor', 'PROF_PIC.png', '2023-11-18 12:51:22', '2024-10-02 12:40:25'),
(23240003, 'Nekka', 'A.', 'Mondaga', '', '0000-00-00', 'Female', 'nekka@gmail.com', '1', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2023-12-18 12:51:22', '2024-10-02 12:41:00'),
(23240004, 'Jee Ann', '', 'Guibone', '', '0000-00-00', 'Female', 'jeeanngmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-01-18 12:51:22', '2024-10-02 12:42:02'),
(23240005, 'Charmaine', '', 'Tapulayan', '', '0000-00-00', 'Female', 'charmaine@gmail.com', '1', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-02-14 14:55:34', '2024-10-02 12:42:28'),
(23240006, 'Helen', '', 'Ajon', '', '0000-00-00', 'Female', 'helen@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-03-15 02:05:15', '2024-10-02 12:43:01'),
(23240007, 'Marites', '', 'Salce', '', '0000-00-00', 'Female', 'marites@gmail.com', '1', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-03-15 02:05:15', '2024-10-02 12:43:42'),
(24250001, 'John Kevin', '', 'Artuz', '', '0000-00-00', 'Male', 'johnkevin@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:44:50'),
(24250002, 'John', '', 'Soriano', '', '0000-00-00', 'Male', 'john@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:45:46'),
(24250003, 'Cherry Mar', '', 'Tutica', '', '0000-00-00', 'Female', 'cherrymar@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:46:38'),
(24250004, 'Adonis', '', 'Onahon', '', '0000-00-00', 'Male', 'adonis@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:48:58'),
(24250005, 'Jo Agustine', '', 'Corpuz', '', '0000-00-00', 'Male', 'joaugustine@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:49:17'),
(24250006, 'Grace', '', 'Quiblat', '', '0000-00-00', 'Female', 'grace@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:49:35'),
(24250007, 'Michaela', '', 'Jamora', '', '0000-00-00', 'Female', 'michaela@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:49:57'),
(24250008, 'Edilyn', '', 'Culajara', '', '0000-00-00', 'Female', 'edilyn@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:50:16'),
(24250009, 'Anthony', '', 'Sanchez', '', '0000-00-00', 'Male', 'anthony@gmail.com', '1', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2024-08-17 01:53:24', '2024-10-02 12:50:41'),
(24250010, 'Marchilyn', 'A.', 'Abunda', '25', '1111-01-01', 'Female', 'marchilyn@gmail.com', '1', '09876543210', 'CCS', 'Instructor', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-10-18 14:14:05'),
(24250011, 'Karl', '', 'Acosta', '', '0000-00-00', 'Male', 'karl@gmail.com', '1', '09876543210', 'Health Clinic', 'Nurse', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-10-02 12:53:58'),
(24250012, 'Rahbie', 'N.', 'Adaptar', '', '0000-00-00', 'Female', 'rahbie@gmail.com', '1', '09876543210', 'ASO', 'Head', 'PROF_PIC.png', '2024-09-15 09:20:15', '2024-10-02 12:54:29'),
(24250013, 'Roseanne', 'B.', 'Lontian', '', '0000-00-00', 'Female', 'roseanne@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-21 13:40:18', '2024-10-02 12:54:41'),
(24250014, 'John Michael', '', 'Ganzan', '', '0000-00-00', 'Male', 'johnmichael@gmail.com', '1', '09876543210', 'GEC', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:09:28', '2024-10-02 12:54:59'),
(24250015, 'Kim-Lee', 'B.', 'Domingo', '', '0000-00-00', 'Male', 'kimlee@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:09:28', '2024-10-02 12:55:38'),
(24250016, 'Milleanne Kaye', '', 'Remotigue', '', '0000-00-00', 'Female', 'milleannekaye@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:11:06', '2024-10-02 12:55:53'),
(24250017, 'Melvin', '', 'Valmoria', '', '0000-00-00', 'Male', 'melvin@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-18 04:11:06', '2024-10-02 12:56:14'),
(24250018, 'John Mark', '', 'Boyonas', '', '0000-00-00', 'Male', 'johnmark@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-21 13:40:18', '2024-10-02 12:56:25'),
(24250019, 'Cero', '', '', '', '0000-00-00', 'Male', 'cero@gmail.com', '1', '09876543210', '', 'Instructor', 'PROF_PIC.png', '2024-09-22 03:54:57', '2024-10-02 12:56:45'),
(24250020, 'Ramer', 'N.', 'Verdejo', '', '0000-00-00', 'Male', 'ramer@gmail.com', '1', '09876543210', 'TEP', 'Instructor', 'PROF_PIC.png', '2024-09-23 03:55:18', '2024-10-02 12:56:59');

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
  `is_read` tinyint(1) DEFAULT 0,
  `dateAdded` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `notification`, `student_id`, `club_id`, `post_id`, `is_read`, `dateAdded`) VALUES
(113, 'Posted an announcement', 20211245, 18, 137, 1, '2024-10-14 07:02:41'),
(192, 'Posted an announcement', 20191124, 22, 153, 1, '2024-10-17 11:26:54'),
(193, 'Posted an announcement', 20201179, 22, 153, 0, '2024-10-17 11:26:54'),
(212, 'Posted an announcement', 111, 10, 156, 0, '2024-10-18 12:50:58'),
(248, 'Posted an announcement', 20191124, 1, 161, 1, '2024-10-19 18:30:01'),
(249, 'Posted an announcement', 20191115, 1, 161, 0, '2024-10-19 18:30:01'),
(250, 'Posted an announcement', 20190000, 1, 161, 0, '2024-10-19 18:30:01'),
(251, 'Posted an announcement', 20211524, 1, 161, 0, '2024-10-19 18:30:01'),
(252, 'Posted an announcement', 20211525, 1, 161, 0, '2024-10-19 18:30:01'),
(253, 'Posted an announcement', 20211526, 1, 161, 0, '2024-10-19 18:30:01'),
(254, 'Posted an announcement', 20211527, 1, 161, 0, '2024-10-19 18:30:01'),
(255, 'Posted an announcement', 20211081, 1, 161, 0, '2024-10-19 18:30:01'),
(288, 'Posted an announcement', 20191124, 1, 166, 1, '2024-10-20 07:03:46'),
(289, 'Posted an announcement', 20191115, 1, 166, 0, '2024-10-20 07:03:46'),
(290, 'Posted an announcement', 20190000, 1, 166, 0, '2024-10-20 07:03:46'),
(291, 'Posted an announcement', 20211524, 1, 166, 0, '2024-10-20 07:03:46'),
(292, 'Posted an announcement', 20211525, 1, 166, 0, '2024-10-20 07:03:46'),
(293, 'Posted an announcement', 20211526, 1, 166, 0, '2024-10-20 07:03:46'),
(294, 'Posted an announcement', 20211527, 1, 166, 0, '2024-10-20 07:03:46'),
(295, 'Posted an announcement', 20211081, 1, 166, 0, '2024-10-20 07:03:46');

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
(156, 'Hello', '2024-10-18 12:50:58', '2024-10-18 12:50:58', 10, 22230001),
(161, 'Attention NBSC Community! 🌟\r\n\r\nWe\'re excited to announce that the NBSC Quick Response Team is gearing up for some thrilling events! 🚨 From training workshops to community outreach programs, we have a lineup of activities designed to enhance your skills and foster teamwork. Stay tuned for updates on our upcoming events—your participation is key to making a difference!\r\n\r\nTogether, we can create a safer environment for everyone. Keep an eye on our announcements, and let’s make an impact!\r\n\r\nYour Quick Response Team 💪', '2024-10-19 18:30:01', '2024-10-19 18:30:01', 1, 22230002),
(166, 'hello', '2024-10-20 07:03:45', '2024-10-20 07:03:45', 1, 22230001);

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
-- Table structure for table `tbl_registration`
--

CREATE TABLE `tbl_registration` (
  `registration_id` int(20) NOT NULL,
  `student_id` int(20) NOT NULL,
  `question1` text NOT NULL,
  `question2` text NOT NULL,
  `question3` text NOT NULL,
  `status` varchar(200) NOT NULL,
  `dateApplied` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateApproved` timestamp NOT NULL DEFAULT current_timestamp(),
  `dateModified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `club_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_registration`
--

INSERT INTO `tbl_registration` (`registration_id`, `student_id`, `question1`, `question2`, `question3`, `status`, `dateApplied`, `dateApproved`, `dateModified`, `club_id`) VALUES
(1, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2022-08-20 03:19:13', '2024-10-19 11:22:56', '2024-10-19 11:38:15', 1),
(2, 20201179, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2023-04-19 15:23:09', '2023-04-23 15:23:09', '2024-10-08 04:39:46', 2),
(3, 20211521, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-08-20 01:53:24', '2024-08-22 01:53:24', '2024-10-07 18:49:29', 14),
(4, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-08-17 01:53:24', '2024-08-21 01:53:24', '2024-10-08 04:39:51', 16),
(5, 20191115, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2024-09-15 09:20:15', '2024-09-15 09:20:15', '2024-09-26 09:53:24', 21),
(6, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'pending', '2022-08-20 03:19:13', '2024-10-19 11:30:22', '2024-10-19 11:34:23', 1),
(7, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'maxed', '2022-08-20 03:19:13', '2024-09-26 11:37:15', '2024-10-14 02:53:04', 4),
(8, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'maxed', '2024-05-15 02:05:15', '2024-09-26 13:27:05', '2024-10-14 02:53:04', 10),
(9, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-15 09:20:15', '2024-09-26 11:37:15', '2024-10-13 06:11:48', 22),
(10, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2022-08-20 03:19:13', '2024-09-26 11:37:15', '2024-10-14 10:02:18', 4),
(11, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2022-08-20 03:19:13', '2024-09-26 11:37:15', '2024-09-30 16:04:39', 4),
(12, 20191124, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'maxed', '2024-09-25 13:40:18', '2024-09-26 11:37:15', '2024-10-14 02:53:04', 25),
(13, 20201179, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-15 09:20:15', '2024-10-14 02:47:46', '2024-10-14 02:48:15', 22),
(14, 20191115, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2022-08-20 03:19:13', '2022-08-22 03:19:13', '2024-09-26 09:53:24', 1),
(16, 20201270, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'maxed', '2024-09-15 09:20:15', '2024-09-18 09:20:15', '2024-10-14 02:12:28', 22),
(17, 20190000, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-03-15 06:26:41', '2024-03-15 06:27:46', '2024-09-26 09:53:24', 1),
(18, 20211524, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:23:09', '2024-09-16 04:23:09', '2024-09-26 09:53:24', 1),
(19, 20211525, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:25:46', '2024-09-16 04:26:20', '2024-09-26 09:53:24', 1),
(20, 20211526, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:28:19', '2024-09-16 04:28:19', '2024-09-26 09:53:24', 1),
(21, 20211527, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-09-16 04:29:59', '2024-09-16 04:29:59', '2024-09-26 09:53:24', 1),
(22, 20211521, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2024-05-15 02:05:15', '2024-10-15 01:23:50', '2024-10-15 01:23:50', 10),
(23, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'active', '2024-05-15 02:05:15', '2024-09-26 11:33:52', '2024-10-13 07:10:34', 10),
(24, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'inactive', '2024-09-18 07:49:09', '2024-10-09 13:19:23', '2024-10-14 02:09:21', 1),
(25, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2024-09-26 12:14:43', '2024-09-30 16:31:18', '2024-09-30 16:31:49', 4),
(26, 111, 'I want to join this club because it aligns with my interests and passions, and I believe it offers a great opportunity to connect with like-minded individuals. I am eager to contribute to meaningful projects and participate in activities that can make a positive impact within our community.', 'I have strong organizational and teamwork skills gained from previous group projects and volunteer experiences. Additionally, my background in [specific skill or subject relevant to the club, e.g., event planning, graphic design, coding] will allow me to contribute effectively to the club’s initiatives and activities.', 'I plan to prioritize my academic responsibilities by creating a schedule that allocates specific time blocks for studying and club activities. I will also communicate openly with club members about my availability and ensure that I remain committed to both my studies and the club’s events.', 'disapproved', '2024-09-26 12:16:26', '2024-09-30 16:31:18', '2024-09-30 16:31:55', 4),
(28, 111, 'a', 'a', 'a', 'departed', '2024-09-30 16:23:44', '2024-10-19 13:19:13', '2024-10-19 13:19:13', 4),
(29, 111, 'sample', 'sample', 'sample', 'disapproved', '2024-10-06 10:13:09', '2024-10-06 10:21:19', '2024-10-06 10:22:16', 22),
(42, 1, 'sample', 'sample', 'sample', 'departed', '2024-10-11 00:16:17', '2024-10-11 00:32:33', '2024-10-11 04:21:47', 1),
(43, 1, 'sample', 'sample', 'sample', 'inactive', '2024-10-11 00:16:25', '2024-10-11 01:18:37', '2024-10-14 02:50:11', 22),
(44, 1, 'sample', 'sample', 'sample', 'maxed', '2024-10-11 00:17:00', '2024-10-11 01:17:33', '2024-10-11 01:18:37', 10),
(45, 1, 'sample', 'sample', 'sample', 'disapproved', '2024-10-11 00:17:00', '2024-10-11 00:23:14', '2024-10-11 01:18:29', 2),
(46, 20211150, 'ccxb', 'v', 'fg', 'active', '2024-10-14 05:59:27', '2024-10-14 06:00:34', '2024-10-14 06:00:34', 2),
(47, 20211035, 'gg', 'dgdg', 'dsgdsg', 'pending', '2024-10-14 06:08:25', '2024-10-14 06:08:25', '2024-10-14 06:08:25', 18),
(48, 20211514, 'cgh', 'gr', 'ey', 'active', '2024-10-14 06:14:57', '2024-10-14 06:16:56', '2024-10-14 06:16:56', 16),
(49, 20211341, 'wddf', 'gfghj', 'tyre', 'active', '2024-10-14 06:23:22', '2024-10-14 06:24:23', '2024-10-14 06:24:23', 12),
(50, 20211860, 'sssswsw', 'dd', 'd', 'active', '2024-10-14 06:33:09', '2024-10-14 06:33:37', '2024-10-14 06:33:37', 12),
(51, 20211245, 'Yes', 'Yes', 'Yes', 'active', '2024-10-14 07:00:23', '2024-10-14 07:01:38', '2024-10-14 07:01:38', 18),
(52, 20211081, 'Because i just want to', 'confidential', 'secret', 'active', '2024-10-14 07:10:17', '2024-10-14 07:10:17', '2024-10-14 07:10:52', 1),
(53, 20211521, 'sample', 'sample', 'sample', 'pending', '2024-10-15 01:19:38', '2024-10-15 01:19:38', '2024-10-15 01:19:38', 45),
(54, 20211521, 'submit1', 'submit2', 'submit3', 'pending', '2024-10-15 01:20:09', '2024-10-15 01:20:09', '2024-10-15 01:20:09', 5),
(55, 111, 'sample', 'sample', 'sample', 'pending', '2024-10-19 13:20:07', '2024-10-19 13:20:07', '2024-10-19 13:20:07', 1);

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
(1, 'sample', 'sample', 'sample', '', '0000-00-00', '', '', '1', '', 'CCS', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-11 00:15:44', '2024-10-11 00:15:44'),
(111, 'hey', '', '', '', '2000-11-11', 'Male', 'hey@gmail.com', '1', '', 'CCS', '', '1st Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 10:17:26', '2024-10-09 12:40:54'),
(20190000, 'Sample', 'Sample', 'Sample', '32', '1992-09-09', 'Male', '20190000@nbsc.edu.ph', '1', '09614588546', 'BSBA', 'BSIT', '3rd Year', 'Zone 2', 'Diclum', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-19 17:14:25', '2024-09-15 06:25:42'),
(20191111, 'Sample', 'Sample', 'Sample', 'Sample', '1111-11-11', 'Female', '20191111@nbsc.edu.ph', '1', '09111111111', 'TEP', 'BSED', '1st Year', 'Zone 3', 'Agusan Canyon', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-15 17:42:37', '2024-08-23 07:48:39'),
(20191115, 'Lovely Nicole', 'Sapong', 'Figueroa', '23', '2001-03-26', 'Female', '20191115@nbsc.edu.ph', '1', '09097989765', 'TEP', 'BSECE', '4th Year', 'Zone 9', 'Lingion', 'Manolo Fortich', 'Province 1', '8703', 'LOVELYNICOLE.png', '2024-07-15 17:41:35', '2024-10-12 16:42:23'),
(20191124, 'Ryan', 'Palmares', 'Cepada', '32', '1992-09-09', 'Male', '20191124@nbsc.edu.ph', '1', '09614588546', 'CCS', 'BSIT', '4th Year', 'Zone 2', 'Diclum', 'Manolo Fortich', 'Province 1', '8703', 'ARSY_CIRCLE_GREEN_PNG.png', '2024-07-20 02:42:04', '2024-10-18 11:57:07'),
(20200000, 'Jomar', 'Jenisan', 'Yeri', '25', '1111-11-11', 'Male', '20211111@nbsc.edu.ph', '', '09876543210', 'CCS', 'BSIT', '4th Year', 'Zone 5', 'Agusan Canyon', 'Manolo Fortich', 'Province 1', '8703', 'PROF_PIC.png', '2024-07-15 17:42:37', '2024-08-23 07:49:34'),
(20201179, 'Angela', 'Naive', 'Libay', '23', '1111-11-11', 'Female', '20201179@nbsc.edu.ph', '1', '09876543210', 'CCS', 'BSIT', '4th Year', 'Zone 6', 'Damilag', 'Manolo Fortich', 'Province 1', '8703', 'ANGELA.png', '2024-07-15 17:40:23', '2024-09-15 16:21:17'),
(20201270, 'Andrie Jose', 'Ipulan', 'Macas', '22', '1111-11-11', 'Male', '20201270@nbsc.edu.ph', '1', '1', 'CCS', 'BSIT', '4th Year', 'Zone 0', 'Lunocan', 'Manolo Fortich', 'Bukidnon', '8703', 'ANDRIE.png', '2024-07-20 05:09:48', '2024-09-15 16:21:40'),
(20211035, 'Kristine Ligaya', '', 'Bagongon', '', '0000-00-00', 'Female', '20211035@nbsc.edu.ph\n', '1', '', 'CCS', 'BSIT', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-14 05:57:54', '2024-10-14 06:42:18'),
(20211081, 'Aya', '', 'Alim', '', '0000-00-00', 'Male', '', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-14 07:08:32', '2024-10-14 07:08:32'),
(20211150, 'Kurt Anthony', '', 'Sitoy', '', '0000-00-00', '', '20211150@nbsc.edu.ph', '1', '', 'CCS', 'BSIT', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-14 05:57:54', '2024-10-14 06:42:35'),
(20211245, 'Rhea Mae', '', 'Viola', '', '0000-00-00', 'Female', '', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-14 06:58:42', '2024-10-14 06:58:42'),
(20211341, 'Jonathan', '', 'Lumanoy', '', '0000-00-00', '', '20211341@nbsc.edu.ph', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-14 06:20:57', '2024-10-14 06:42:45'),
(20211514, 'Elluid', '', 'Tinga', '', '0000-00-00', 'Male', '20211514@nbsc.edu.ph', '1', '', 'BSBA', '', '', '', '', '', '', '', 'PROF_PIC.png', '2024-10-14 06:12:43', '2024-10-14 06:42:48'),
(20211521, 'Merlinda', 'Yepes', 'Magno', '22', '0000-00-00', 'Female', '20211521@nbsc.edu.ph', '1', '', 'CCS', 'BSIT', '4th Year', '', '', '', '', '', 'MERLINDA.png', '2024-07-20 05:10:05', '2024-09-15 16:21:52'),
(20211524, 'John', 'Dummy', 'Account', '', '0000-00-00', 'Male', '20211524@nbsc.edu.ph', '1', '', 'BSBA', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:21:49', '2024-10-14 06:41:03'),
(20211525, 'Jane', 'Dummy', 'Account', '', '0000-00-00', 'Female', '20211525@nbsc.edu.ph', '1', '', 'BSBA', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:24:56', '2024-10-14 06:42:59'),
(20211526, 'Carl', 'Dummy', 'Account', '', '0000-00-00', 'Male', '20211526@nbsc.edu.ph', '1', '', 'TEP', '', '1st Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:27:54', '2024-10-14 06:43:09'),
(20211527, 'Joe', 'Dummy', 'Account', '', '0000-00-00', 'Male', '20211527@nbsc.edu.ph', '1', '', 'CCS', '', '2nd Year', '', '', '', '', '', 'PROF_PIC.png', '2024-09-16 04:29:35', '2024-10-14 06:43:12'),
(20211860, 'Allan', '', 'Cenia', '', '0000-00-00', 'Male', '20211860@nbsc.edu.ph', '1', '', 'BSBA', '', '4th Year', '', '', '', '', '', 'PROF_PIC.png', '2024-10-14 06:30:59', '2024-10-14 06:43:17');

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
-- Indexes for table `tbl_registration`
--
ALTER TABLE `tbl_registration`
  ADD PRIMARY KEY (`registration_id`);

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
  MODIFY `activity_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=572;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24251016;

--
-- AUTO_INCREMENT for table `tbl_chats`
--
ALTER TABLE `tbl_chats`
  MODIFY `chat_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `tbl_clubs`
--
ALTER TABLE `tbl_clubs`
  MODIFY `club_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `tbl_clubs_and_moderators`
--
ALTER TABLE `tbl_clubs_and_moderators`
  MODIFY `clubmod_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=321;

--
-- AUTO_INCREMENT for table `tbl_club_officers`
--
ALTER TABLE `tbl_club_officers`
  MODIFY `officer_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_club_recommendations`
--
ALTER TABLE `tbl_club_recommendations`
  MODIFY `recommendation_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `tbl_club_requests`
--
ALTER TABLE `tbl_club_requests`
  MODIFY `request_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `tbl_comments`
--
ALTER TABLE `tbl_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `tbl_departure_requests`
--
ALTER TABLE `tbl_departure_requests`
  MODIFY `departure_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `event_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `tbl_officers_charts`
--
ALTER TABLE `tbl_officers_charts`
  MODIFY `chart_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_posts`
--
ALTER TABLE `tbl_posts`
  MODIFY `post_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT for table `tbl_posts_attachments`
--
ALTER TABLE `tbl_posts_attachments`
  MODIFY `attachment_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_registration`
--
ALTER TABLE `tbl_registration`
  MODIFY `registration_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `tbl_students`
--
ALTER TABLE `tbl_students`
  MODIFY `student_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20211861;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
