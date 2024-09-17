-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2024 at 08:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php-assignment`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `email`, `phone`, `created_at`) VALUES
(10001, 'admin', '1234', 'admin01@gmail.com', '012-123-1234', '2024-09-07 05:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `bookinginformation`
--

CREATE TABLE `bookinginformation` (
  `booking_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `paymentAmount` float NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookinginformation`
--

INSERT INTO `bookinginformation` (`booking_id`, `event_id`, `member_id`, `paymentAmount`, `booking_date`) VALUES
(51, 35, 31, 36.36, '2024-09-07 00:07:48'),
(53, 35, 31, 48.48, '2024-09-07 00:14:17'),
(54, 35, 31, 48.48, '2024-09-07 00:14:22'),
(57, 35, 31, 12.12, '2024-09-07 00:40:16'),
(58, 35, 31, 36.36, '2024-09-07 05:32:38'),
(59, 32, 31, 36.36, '2024-09-08 04:01:55'),
(60, 32, 31, 24.24, '2024-09-09 05:01:31'),
(61, 31, 31, 369, '2024-09-10 03:11:44'),
(62, 31, 31, 0, '2024-09-12 12:23:01'),
(63, 31, 31, 0, '2024-09-12 12:23:24'),
(64, 31, 31, 0, '2024-09-12 12:25:29'),
(65, 31, 31, 0, '2024-09-12 12:31:30'),
(66, 31, 31, 0, '2024-09-12 12:32:10'),
(67, 31, 31, 0, '2024-09-12 12:34:59'),
(68, 31, 31, 0, '2024-09-12 12:37:29'),
(69, 31, 31, 0, '2024-09-12 12:37:35'),
(70, 31, 31, 0, '2024-09-12 12:38:01'),
(71, 31, 31, 369, '2024-09-12 12:40:48'),
(72, 31, 31, 369, '2024-09-12 12:42:07'),
(73, 31, 31, 369, '2024-09-12 12:44:08'),
(74, 31, 31, 369, '2024-09-12 12:45:28'),
(75, 31, 31, 369, '2024-09-12 12:46:37'),
(76, 31, 31, 369, '2024-09-12 12:47:42'),
(77, 31, 31, 369, '2024-09-12 12:49:46'),
(78, 31, 31, 369, '2024-09-12 12:50:23'),
(79, 31, 31, 369, '2024-09-12 12:51:05'),
(80, 31, 31, 369, '2024-09-12 12:51:17'),
(81, 31, 31, 369, '2024-09-12 12:52:48'),
(82, 31, 31, 369, '2024-09-12 12:53:01'),
(83, 31, 31, 369, '2024-09-12 12:53:22'),
(84, 31, 31, 369, '2024-09-12 13:00:07'),
(85, 31, 31, 369, '2024-09-12 13:01:49'),
(86, 32, 31, 36.36, '2024-09-12 13:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `banner_image` blob DEFAULT NULL,
  `note` varchar(200) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `seat` int(4) NOT NULL,
  `event_host` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`event_id`, `event_name`, `event_date`, `start_time`, `end_time`, `location`, `description`, `banner_image`, `note`, `fee`, `seat`, `event_host`, `phone`, `created_at`) VALUES
(31, 'Sauna and Yoga Workshop', '2024-07-20', '03:24:00', '04:25:00', '456 Jalan XYZ, Kuala Lumpur, 50300 Kuala Lumpur, Malaysia', 'A relaxing space designed to promote wellness and relieve stress through heat therapy. Enjoy the soothing warmth and health benefits of a traditional sauna session', 0x75706c6f6164732f3562633634663661643533313861303034623837333932645f776869746e65792d7065616b2d686f74656c2d7361756e612d737465616d2d726f6f6d2d616d656e69746965732d6261736563616d702d636c696d62696e672d67796d2d312e6a7067, 'Wear appropriate workout attire and supportive footwear.', 123.00, 52, 'matthew', '2312312', '2024-08-28 17:24:01'),
(32, 'Push Up Competition', '2024-08-30', '10:52:00', '12:52:00', 'Fitness Arena, 789 Jalan Fitness, Kuala Lumpur, 50400 Kuala Lumpur, Malaysia', 'Test Your Strength and Endurance!', 0x75706c6f6164732f315f357079726474496c595670335a7677523843315972772e6a7067, 'Please arrive at least 15 minutes before your scheduled time to check in and warm up.\r\n', 12.12, 92, 'ethan', '012-1212-1122', '2024-08-29 01:53:14'),
(34, 'Personal Training Demos', '2024-08-31', '05:05:00', '06:03:00', '123 Jalan ABC, Kuala Lumpur, 50400 Kuala Lumpur, Malaysia', 'Free sessions to introduce personal training.\r\n', 0x75706c6f6164732f3139313030332d6d616c65706572736f6e616c747261696e65722d73746f636b2e6a7067, 'Remember to bring your towel', 121.11, 10, 'matthew', '012-1212-1122', '2024-08-29 17:02:05'),
(35, 'Fitness Challange Event', '2024-09-14', '02:48:00', '03:49:00', 'Fitness Center, 12 Jalan Strength, Kuala Lumpur, 50100 Kuala Lumpur, Malaysia', 'Test your limits in our Fitness Challenge! Compete in diverse exercises and push your fitness to the next level. Join the fun, earn rewards, and connect with fellow enthusiasts!', 0x75706c6f6164732f31393233383139365f3331323430393736393238313035395f33353033313032363434383637323235365f6f2e77696474682d313932302e6a7067, 'Wear appropriate workout attire and bring a water bottle.', 12.12, 114, 'ethan', '012-1212-1122', '2024-09-05 16:47:05'),
(36, 'Swimming Event', '2024-09-21', '02:00:00', '04:00:00', 'Aquatic Center, 34 Jalan Poolside, Kuala Lumpur, 50200 Kuala Lumpur, Malaysia', 'Dive into Fun and Fitness!', 0x75706c6f6164732f4261636b79617264706f6f6c2e6a7067, 'Arrive at least 15 minutes early for check-in and warm-up.', 12.00, 122, 'matthew', '123 123 1234', '2024-09-08 16:25:53');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `item_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `logo` blob DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`item_id`, `event_id`, `logo`, `description`) VALUES
(47, 32, 0x75706c6f6164732f363665393237356530633134345f30353531363030305f5f31352e6a7067, 'Workout Pants'),
(48, 32, 0x75706c6f6164732f363665393237356530653830335fe5b18fe5b995e688aae59bbe20323032342d30392d3136203233313733302e706e67, 'Workout Clothes'),
(49, 32, 0x75706c6f6164732f363665393237356530663839375f636c65616e2d746f77656c2d69636f6e2d636172746f6f6e2d74657874696c652d68616e642d766563746f722e6a7067, 'Towels'),
(55, 34, 0x75706c6f6164732f363665393237376239666133375f6669746e6573732d77617465722d626f74746c652d69636f6e2d6f75746c696e652d7374796c652d766563746f722e6a7067, 'water bottle'),
(56, 34, 0x75706c6f6164732f363665393237376261306132645f766172696f75732d74797065732d6f662d636c6f636b732d746f2d68656c702d68756d616e2d616374697669746965732d766563746f722e6a7067, 'Time watch'),
(58, 35, 0x75706c6f6164732f363665393236346164623862645f6669746e6573732d77617465722d626f74746c652d69636f6e2d6f75746c696e652d7374796c652d766563746f722e6a7067, 'bottle'),
(59, 35, 0x75706c6f6164732f363665393236346164633536625f636c65616e2d746f77656c2d69636f6e2d636172746f6f6e2d74657874696c652d68616e642d766563746f722e6a7067, 'towels'),
(60, 35, 0x75706c6f6164732f363665393236346164643831315fe5b18fe5b995e688aae59bbe20323032342d30392d3136203233313733302e706e67, 'Workout Clothes'),
(97, 31, 0x75706c6f6164732f363665393235386237636531375fe5b18fe5b995e688aae59bbe20323032342d30392d3136203233313733302e706e67, 'Workout clothes'),
(98, 31, 0x75706c6f6164732f363665393235386237663036615f68616e642d647261776e2d72756e6e696e672d73686f65732d636172746f6f6e2d696c6c757374726174696f6e5f32332d323135303936313835302e6a7067, 'Supportive footwear'),
(99, 31, 0x75706c6f6164732f363665393235386238326332375f6669746e6573732d77617465722d626f74746c652d69636f6e2d6f75746c696e652d7374796c652d766563746f722e6a7067, 'Water bottle'),
(100, 36, 0x75706c6f6164732f363665393236613138633631655f7377696d2d706f6f6c2d676f67676c65732d636172746f6f6e2d696c6c757374726174696f6e2d766563746f722e6a7067, 'Toggles'),
(101, 36, 0x75706c6f6164732f363665393236613138646230635f65786572636973652d7377696d2d6361702d69636f6e2d636172746f6f6e2d7377696d6d65722d65717569706d656e742d766563746f722e6a7067, 'swimming caps'),
(102, 31, 0x75706c6f6164732f363665393235386238353763665f636c65616e2d746f77656c2d69636f6e2d636172746f6f6e2d74657874696c652d68616e642d766563746f722e6a7067, 'Towel');

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `avatar` blob DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL,
  `gender` char(7) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(20) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `socialMedia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `avatar`, `name`, `username`, `gender`, `email`, `password`, `phone`, `address`, `bio`, `experience`, `socialMedia`) VALUES
(31, 0x2e2f75706c6f6164732f363665393237636565393539302e6a7067, 'Ethan Khoo Tsui Ern', 'ethan', 'Male', 'ethan05@gmail.com', '1234', '012-123-1234', '25, Jalan Sultan Ismail, Bukit Bintang, 54021 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur, Malaysia', 'Ethan is a passionate fitness enthusiast who joined first fitness three years ago. He is dedicated to maintaining a healthy lifestyle and enjoys a mix of cardio, weightlifting, and HIIT workouts. John is a regular participant in our group fitness classes, especially spin and boot camp sessions. Outside of the gym, he loves hiking, cooking nutritious meals, and reading about the latest trends in fitness and wellness.', 'As a student juggling classes and assignments, going to the gym helps me stay fit and relax. I use the gym’s equipment and join classes to get a good workout. It’s a great way to unwind and keep my energy up, which makes it easier to handle my studies.', 'Follow with me in Instragram @ethannnn07 and my Facebook Name is Ethan Khoo');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `ticket_id` int(10) NOT NULL,
  `booking_id` int(10) NOT NULL,
  `event_id` int(11) NOT NULL,
  `issue_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `member_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`ticket_id`, `booking_id`, `event_id`, `issue_date`, `member_id`) VALUES
(43, 51, 35, '2024-09-07 00:07:48', 31),
(44, 51, 35, '2024-09-07 00:07:48', 31),
(45, 51, 35, '2024-09-07 00:07:48', 31),
(46, 53, 35, '2024-09-07 00:14:17', 31),
(47, 53, 35, '2024-09-07 00:14:17', 31),
(48, 53, 35, '2024-09-07 00:14:17', 31),
(49, 53, 35, '2024-09-07 00:14:17', 31),
(50, 54, 35, '2024-09-07 00:14:22', 31),
(51, 54, 35, '2024-09-07 00:14:22', 31),
(52, 54, 35, '2024-09-07 00:14:22', 31),
(53, 54, 35, '2024-09-07 00:14:22', 31),
(54, 57, 35, '2024-09-07 00:40:16', 31),
(55, 58, 35, '2024-09-07 05:32:38', 31),
(56, 58, 35, '2024-09-07 05:32:38', 31),
(58, 59, 32, '2024-09-08 04:01:55', 31),
(59, 59, 32, '2024-09-08 04:01:55', 31),
(61, 60, 32, '2024-09-09 05:01:31', 31),
(62, 60, 32, '2024-09-09 05:01:31', 31),
(63, 61, 31, '2024-09-10 03:11:44', 31),
(64, 61, 31, '2024-09-10 03:11:44', 31),
(65, 61, 31, '2024-09-10 03:11:44', 31),
(66, 71, 31, '2024-09-12 12:40:48', 31),
(67, 71, 31, '2024-09-12 12:40:48', 31),
(68, 71, 31, '2024-09-12 12:40:48', 31),
(69, 72, 31, '2024-09-12 12:42:07', 31),
(70, 72, 31, '2024-09-12 12:42:07', 31),
(71, 72, 31, '2024-09-12 12:42:07', 31),
(72, 73, 31, '2024-09-12 12:44:08', 31),
(73, 73, 31, '2024-09-12 12:44:08', 31),
(74, 73, 31, '2024-09-12 12:44:08', 31),
(75, 74, 31, '2024-09-12 12:45:28', 31),
(76, 74, 31, '2024-09-12 12:45:28', 31),
(77, 74, 31, '2024-09-12 12:45:28', 31),
(78, 75, 31, '2024-09-12 12:46:37', 31),
(79, 75, 31, '2024-09-12 12:46:37', 31),
(80, 75, 31, '2024-09-12 12:46:37', 31),
(81, 76, 31, '2024-09-12 12:47:42', 31),
(82, 76, 31, '2024-09-12 12:47:42', 31),
(83, 76, 31, '2024-09-12 12:47:42', 31),
(84, 77, 31, '2024-09-12 12:49:46', 31),
(85, 77, 31, '2024-09-12 12:49:46', 31),
(86, 77, 31, '2024-09-12 12:49:46', 31),
(87, 78, 31, '2024-09-12 12:50:23', 31),
(88, 78, 31, '2024-09-12 12:50:23', 31),
(89, 78, 31, '2024-09-12 12:50:23', 31),
(90, 79, 31, '2024-09-12 12:51:05', 31),
(91, 79, 31, '2024-09-12 12:51:05', 31),
(92, 79, 31, '2024-09-12 12:51:05', 31),
(93, 80, 31, '2024-09-12 12:51:17', 31),
(94, 80, 31, '2024-09-12 12:51:17', 31),
(95, 80, 31, '2024-09-12 12:51:17', 31),
(96, 81, 31, '2024-09-12 12:52:48', 31),
(97, 81, 31, '2024-09-12 12:52:48', 31),
(98, 81, 31, '2024-09-12 12:52:48', 31),
(99, 82, 31, '2024-09-12 12:53:01', 31),
(100, 82, 31, '2024-09-12 12:53:01', 31),
(101, 82, 31, '2024-09-12 12:53:01', 31),
(102, 83, 31, '2024-09-12 12:53:22', 31),
(103, 83, 31, '2024-09-12 12:53:22', 31),
(104, 83, 31, '2024-09-12 12:53:22', 31),
(105, 84, 31, '2024-09-12 13:00:07', 31),
(106, 84, 31, '2024-09-12 13:00:07', 31),
(107, 84, 31, '2024-09-12 13:00:07', 31),
(108, 85, 31, '2024-09-12 13:01:49', 31),
(109, 85, 31, '2024-09-12 13:01:49', 31),
(110, 85, 31, '2024-09-12 13:01:49', 31),
(111, 86, 32, '2024-09-12 13:34:07', 31),
(112, 86, 32, '2024-09-12 13:34:07', 31),
(113, 86, 32, '2024-09-12 13:34:07', 31);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `bookinginformation`
--
ALTER TABLE `bookinginformation`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
  ADD PRIMARY KEY (`ticket_id`) USING BTREE,
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `member_id` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10002;

--
-- AUTO_INCREMENT for table `bookinginformation`
--
ALTER TABLE `bookinginformation`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `ticket_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookinginformation`
--
ALTER TABLE `bookinginformation`
  ADD CONSTRAINT `bookinginformation_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookinginformation_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`) ON DELETE CASCADE;

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `event` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
