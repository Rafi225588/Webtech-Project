-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 26, 2025 at 05:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `LAW_PROJECT`
--

-- --------------------------------------------------------

--
-- Table structure for table `COMMENTS`
--

CREATE TABLE `COMMENTS` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `COMMENTS`
--

INSERT INTO `COMMENTS` (`comment_id`, `post_id`, `user_id`, `comment_content`, `created_at`) VALUES
(12, 18, 63, 'What the hell you want- lawyer Mim', '2025-01-24 09:56:32'),
(13, 18, 63, 'Hello Everyone', '2025-01-26 04:35:27'),
(14, 18, 63, 'another Comment', '2025-01-26 04:35:48'),
(15, 19, 63, 'Comment 1', '2025-01-26 04:36:06'),
(16, 19, 63, 'Comment 2', '2025-01-26 04:36:11'),
(17, 20, 63, 'comment', '2025-01-26 04:43:36'),
(18, 20, 63, 'another comment', '2025-01-26 04:43:42'),
(19, 20, 63, 'more comment', '2025-01-26 04:43:46'),
(20, 20, 63, 'working comment', '2025-01-26 04:43:51'),
(21, 20, 63, 'another working', '2025-01-26 04:43:55'),
(22, 20, 63, 'ghood', '2025-01-26 04:43:58'),
(23, 20, 63, 'after see more\r\n', '2025-01-26 04:44:07'),
(24, 20, 63, 'after see moreeeeee', '2025-01-26 04:44:21'),
(25, 19, 63, 'another Comment', '2025-01-26 05:13:09'),
(26, 21, 63, 'adsjhfbhjaw', '2025-01-26 06:15:10'),
(27, 21, 63, 'adsn fnasd', '2025-01-26 06:15:13'),
(28, 21, 63, 'adsjfbnjasdbnc', '2025-01-26 06:15:15'),
(29, 21, 63, 'dajhsbfhjdsa', '2025-01-26 06:15:18'),
(30, 22, 67, 'what you need putul', '2025-01-26 17:32:43'),
(31, 22, 67, 'hi ?\r\n', '2025-01-26 17:32:48'),
(32, 22, 67, '??', '2025-01-26 17:32:51'),
(33, 22, 67, 'any update?', '2025-01-26 17:32:57'),
(34, 22, 67, 'say please', '2025-01-26 17:33:04'),
(35, 22, 67, 'where are you?', '2025-01-26 17:33:12'),
(36, 23, 69, 'i am near to that location ok', '2025-01-27 05:21:13'),
(37, 24, 58, 'hello sir,  need to know some info', '2025-02-01 10:00:34'),
(38, 25, 46, 'where are you , wxactly ?', '2025-02-15 13:41:47'),
(39, 25, 46, 'h\r\n', '2025-02-15 13:46:38'),
(40, 25, 46, 'adf', '2025-02-15 13:46:40'),
(41, 25, 46, 'adf', '2025-02-15 13:46:42'),
(42, 25, 46, 'fadf', '2025-02-15 13:46:45'),
(43, 25, 46, 'gsdgf', '2025-02-15 13:46:47'),
(44, 25, 46, 'dafdsf', '2025-02-15 13:46:50'),
(45, 26, 74, 'hello i am raisa .', '2025-03-03 15:14:52');

-- --------------------------------------------------------

--
-- Table structure for table `Hire`
--

CREATE TABLE `Hire` (
  `hire_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lawyer_id` int(11) NOT NULL,
  `hire_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Accepted','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Hire`
--

INSERT INTO `Hire` (`hire_id`, `user_id`, `lawyer_id`, `hire_date`, `status`) VALUES
(45, 58, 62, '2025-01-24 13:19:45', 'Accepted'),
(47, 58, 46, '2025-01-24 16:40:28', 'Accepted'),
(48, 58, 47, '2025-01-24 16:48:41', 'Accepted'),
(49, 58, 50, '2025-01-24 16:48:43', 'Accepted'),
(50, 58, 54, '2025-01-24 16:48:47', 'Pending'),
(51, 63, 46, '2025-01-25 13:54:39', 'Accepted'),
(52, 63, 50, '2025-01-26 02:20:13', 'Accepted'),
(53, 63, 47, '2025-01-26 04:31:28', 'Pending'),
(54, 66, 67, '2025-01-26 17:35:46', 'Accepted'),
(55, 66, 46, '2025-01-26 17:38:33', 'Accepted'),
(56, 63, 46, '2025-01-27 01:49:04', 'Accepted'),
(58, 63, 47, '2025-01-27 03:25:38', 'Accepted'),
(59, 63, 62, '2025-01-27 03:46:07', 'Pending'),
(60, 68, 46, '2025-01-27 04:14:08', 'Accepted'),
(61, 69, 70, '2025-01-27 05:19:25', 'Accepted'),
(62, 63, 65, '2025-01-27 07:44:37', 'Pending'),
(63, 58, 50, '2025-01-28 03:21:48', 'Accepted'),
(64, 58, 71, '2025-02-01 09:59:58', 'Accepted'),
(65, 72, 46, '2025-02-15 13:40:29', 'Accepted'),
(66, 58, 73, '2025-02-21 08:14:38', 'Accepted'),
(67, 74, 65, '2025-03-03 15:14:30', 'Accepted');

-- --------------------------------------------------------

--
-- Table structure for table `laws`
--

CREATE TABLE `laws` (
  `law_id` int(11) NOT NULL,
  `law_index` varchar(50) NOT NULL,
  `short_description` varchar(255) NOT NULL,
  `full_description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `lawyer_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laws`
--

INSERT INTO `laws` (`law_id`, `law_index`, `short_description`, `full_description`, `created_at`, `lawyer_id`, `status`) VALUES
(1, 'ধারা ৩৭৫', 'ধর্ষণ', 'যদি কোনো ব্যক্তি কোনও নারীর ইচ্ছার বিরুদ্ধে, তার সম্মতি ছাড়াই বা ভুল সম্মতি দিয়ে সহবাস করে, তবে তাকে ধর্ষণ বলে। \r\nভুক্তভোগীর করণীয়: \r\n১. তাৎক্ষণিকভাবে নিরাপদ স্থানে চলে যান।\r\n২. নিকটস্থ থানায় দ্রুত অভিযোগ দায়ের করুন। \r\n৩. চিকিৎসার জন্য নিকটস্থ হাসপাতালে যান এবং সমস্ত মেডিকেল রিপোর্ট সংরক্ষণ করুন।\r\n৪. একটি ফ্যামিলি সদস্য বা বন্ধু এবং একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\nযেখান থেকে সাহায্য নিতে পারেন:\r\n- আইন ও সালিশ কেন্দ্র\r\n- বাংলাদেশ মহিলা আইনজীবী সমিতি\r\n- সরকারি হেল্পলাইন: ৯৯৯ \r\nপরবর্তী পদক্ষেপ:\r\n১. থানায় অভিযোগ দায়ের করার পরে প্রমাণ সংগ্রহে পুলিশকে সহায়তা করুন। \r\n২. মানসিক সহায়তার জন্য পেশাদার পরামর্শদাতার সঙ্গে যোগাযোগ করুন।\r\n৩. মামলার কার্যক্রম সম্পর্কে অবগত থাকুন এবং প্রয়োজনীয় কাগজপত্র সংগৃহীত ও সংরক্ষণ করুন।', '2025-01-11 11:13:06', NULL, 1),
(2, 'ধারা ৩০২', 'খুন', 'যদি কেউ ইচ্ছাকৃতভাবে কোনও ব্যক্তিকে হত্যা করে, তবে এটি খুন হিসেবে গণ্য হবে। \r\nভুক্তভোগীর পরিবারের করণীয়: \r\n১. দ্রুত নিকটস্থ থানায় গিয়ে মামলা দায়ের করুন। \r\n২. ঘটনাস্থলের প্রমাণ সংরক্ষণ করুন এবং পুলিশকে তথ্য দিন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\nযেখান থেকে সাহায্য নিতে পারেন:\r\n- মানবাধিকার সংস্থা\r\n- আইন ও সালিশ কেন্দ্র\r\n- সরকারি হেল্পলাইন: ৯৯৯\r\nপরবর্তী পদক্ষেপ: \r\n১. মামলার সকল তথ্য এবং সাক্ষী সংগ্রহে মনোযোগ দিন। \r\n২. আদালতের কার্যক্রম সম্পর্কে নিজেকে অবগত রাখুন।\r\n৩. মানসিক সহায়তার জন্য পেশাদার পরামর্শদাতা বা সাপোর্ট গ্রুপের সাহায্য নিন।', '2025-01-11 11:13:06', NULL, 1),
(3, 'ধারা ৩৭৮', 'চুরি', 'যদি কোনও ব্যক্তি অন্যের অনুমতি ছাড়াই কোনও সম্পত্তি অপহরণ করে, তবে এটি চুরি হিসেবে গণ্য হবে। \r\nভুক্তভোগীর করণীয়: \r\n১. নিকটস্থ থানায় অভিযোগ জানান। \r\n২. চুরি হওয়া সম্পত্তির তথ্য সংরক্ষণ করুন এবং থানায় প্রদান করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\nযেখান থেকে সাহায্য নিতে পারেন: \r\n- স্থানীয় আইন সহায়তা কেন্দ্র\r\n- আইন ও সালিশ কেন্দ্র\r\nপরবর্তী পদক্ষেপ: \r\n১. সম্পত্তি পুনরুদ্ধারের জন্য পুলিশ তদন্তে সহায়তা করুন। \r\n২. আদালতে মামলার অগ্রগতি সম্পর্কে নিজেকে অবগত রাখুন।', '2025-01-11 11:13:06', NULL, 1),
(4, 'ধারা ৪২০', 'প্রতারণা', 'যদি কোনো ব্যক্তি প্রতারণা করার উদ্দেশ্যে কোনও জিনিস বা মূল্যবান নিরাপত্তা দস্তাবেজ প্রদান করে, তবে এটি প্রতারণা হিসেবে গণ্য হবে। \r\nপ্রতারিত ব্যক্তির করণীয়: \r\n১. নিকটস্থ থানায় প্রতারণার সমস্ত তথ্যসহ অভিযোগ দায়ের করুন।\r\n২. প্রয়োজনীয় প্রমাণ সংগ্রহ করুন এবং থানায় জমা দিন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\nযেখান থেকে সাহায্য নিতে পারেন: \r\n- ন্যাশনাল কনজিউমার রাইটস প্রটেকশন \r\n- আইন ও সালিশ কেন্দ্র\r\nপরবর্তী পদক্ষেপ: \r\n১. মামলার সমস্ত প্রক্রিয়া সম্পর্কে অবগত থাকুন। \r\n২. প্রতারিত সম্পত্তি পুনরুদ্ধারের জন্য আইনি প্রক্রিয়ায় সম্পৃক্ত থাকুন। \r\n৩. প্রতারণার শিকার হয়ে পরবর্তী প্রতারণা থেকে নিজেকে রক্ষার জন্য সচেতনতা বৃদ্ধি করুন।', '2025-01-11 11:13:06', NULL, 1),
(5, 'ধারা ৪০৬', 'বিশ্বাসভঙ্গ', 'যদি কোনো ব্যক্তি অন্যের সম্পত্তি গ্রহণ করে এবং সেই সম্পত্তি ফেরত না দেয়, তবে এটি বিশ্বাসভঙ্গ হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. নিকটস্থ থানায় অভিযোগ জানান। \r\n২. সম্পত্তি সম্পর্কিত সমস্ত তথ্য প্রদান করুন এবং প্রমাণ সংরক্ষণ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- আইন ও সালিশ কেন্দ্র\r\n- স্থানীয় আইন সহায়তা কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশের তদন্তে সহায়তা করুন এবং প্রয়োজনীয় প্রমাণ প্রদান করুন। \r\n২. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন।', '2025-01-11 11:16:26', 50, 1),
(6, 'ধারা ৩৭৪', 'অপহরণ', 'যদি কোনো ব্যক্তি অন্য একজনকে জোরপূর্বক বা প্রতারণা করে অপহরণ করে, তবে এটি অপহরণ হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর পরিবারের করণীয়:** \r\n১. দ্রুত নিকটস্থ থানায় অপহরণের অভিযোগ জানান। \r\n২. অপহৃত ব্যক্তির সম্পর্কে সকল তথ্য সংগ্রহ করুন এবং পুলিশকে প্রদান করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় আইন সহায়তা কেন্দ্র\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. অপহৃত ব্যক্তির মুক্তির জন্য পুলিশকে সহযোগিতা করুন। \r\n২. মামলার পরিস্থিতি সম্পর্কে অবগত থাকুন এবং প্রয়োজনীয় প্রমাণ সংগ্রহ করুন।', '2025-01-11 11:16:26', 50, 1),
(7, 'ধারা ৫০৬', 'আত্মহত্যার প্ররোচনা', 'যদি কোনো ব্যক্তি অন্যকে আত্মহত্যা করতে প্ররোচিত করে, তবে এটি আত্মহত্যার প্ররোচনা হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. প্ররোচনাকারীর বিরুদ্ধে অভিযোগ জানান। \r\n২. নিরাপত্তার জন্য সাহায্য চাইতে সঠিক কর্তৃপক্ষের সঙ্গে যোগাযোগ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- মানসিক স্বাস্থ্য সহায়তা\r\n- আইন ও সালিশ কেন্দ্র\r\n- সরকারি হেল্পলাইন: ৯৯৯\r\n**পরবর্তী পদক্ষেপ:** \r\n১. প্ররোচনাকারীর বিরুদ্ধে তদন্তে সহায়তা করুন। \r\n২. মানসিক সহায়তার জন্য পেশাদারদের সাথে যোগাযোগ করুন।', '2025-01-11 11:16:26', 50, 1),
(8, 'ধারা ৩৫৫', 'অপমান', 'যদি কোনো ব্যক্তি অন্যের মর্যাদা ক্ষুণ্ন করে এবং তাকে অপমানিত করে, তবে এটি অপমান হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. থানায় অভিযোগ দায়ের করুন। \r\n২. সমস্ত প্রমাণ এবং ঘটনার বিস্তারিত তথ্য থানায় প্রদান করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- মানবাধিকার সংস্থা\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন। \r\n২. প্রমাণ সংগ্রহ করে তদন্তে সহায়তা করুন।', '2025-01-11 11:16:26', NULL, 1),
(9, 'ধারা ৩৭৪', 'দুর্নীতি', 'যদি কোনো ব্যক্তি সরকারি বা ব্যক্তিগত সম্পত্তি আত্মসাৎ বা দুর্নীতি করে, তবে এটি দুর্নীতি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. দুর্নীতির ঘটনার তথ্য ও প্রমাণ সহ থানায় অভিযোগ করুন। \r\n২. সংশ্লিষ্ট প্রতিষ্ঠানের দুর্নীতি বিভাগে অভিযোগ দায়ের করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- দুর্নীতি দমন কমিশন\r\n- আইন ও সালিশ কেন্দ্র\r\n- সরকারি হেল্পলাইন: ৯৯৯\r\n**পরবর্তী পদক্ষেপ:** \r\n১. তদন্তে সহায়তা করুন এবং প্রমাণ সংগ্রহ করুন। \r\n২. মামলার প্রক্রিয়া সম্পর্কে অবগত থাকুন এবং প্রয়োজনীয় কাগজপত্র সংরক্ষণ করুন।', '2025-01-11 11:16:41', NULL, 1),
(10, 'ধারা ৪০৭', 'ঘটনাস্থল থেকে পালানো', 'যদি কোনো ব্যক্তি ঘটনার পর পালিয়ে যায় এবং পুলিশকে সহায়তা না করে, তবে এটি ঘটনার স্থান থেকে পালানো হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. থানায় দ্রুত পালানোর তথ্য প্রদান করুন। \r\n২. ঘটনার প্রমাণ এবং ঘটনার স্থান সম্পর্কিত তথ্য সংরক্ষণ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- আইন ও সালিশ কেন্দ্র\r\n- স্থানীয় পুলিশ স্টেশন\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশ তদন্তে সহায়তা করুন। \r\n২. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন এবং প্রমাণ সহ সহযোগিতা করুন।', '2025-01-11 11:16:41', NULL, 1),
(11, 'ধারা ৩২৮', 'আঘাত', 'যদি কোনো ব্যক্তি ইচ্ছাকৃতভাবে অন্য ব্যক্তির দেহে আঘাত করে, তবে এটি আঘাত হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. দ্রুত থানায় অভিযোগ জানান। \r\n২. আঘাতের স্থান এবং প্রমাণ সংরক্ষণ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশকে সহায়তা করুন এবং প্রয়োজনীয় প্রমাণ প্রদান করুন। \r\n২. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন এবং আইনি প্রক্রিয়ায় অংশগ্রহণ করুন।', '2025-01-11 11:16:41', NULL, 1),
(12, 'ধারা ৩৪০', 'অবৈধ কারাবাস', 'যদি কোনো ব্যক্তি অন্যকে অবৈধভাবে আটক করে এবং তাকে কারাবন্দি করে, তবে এটি অবৈধ কারাবাস হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. নিকটস্থ থানায় অভিযোগ জানিয়ে ঘটনার বিস্তারিত তথ্য দিন। \r\n২. আটক হওয়া ব্যক্তির অবস্থান সম্পর্কিত প্রমাণ সংগ্রহ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- মানবাধিকার সংস্থা\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশকে তদন্তে সহায়তা করুন এবং প্রয়োজনীয় প্রমাণ প্রদান করুন। \r\n২. মামলার প্রক্রিয়া সম্পর্কে অবগত থাকুন এবং প্রয়োজনীয় কাগজপত্র সংরক্ষণ করুন।', '2025-01-11 11:16:41', NULL, 1),
(13, 'ধারা ৩২৩', 'অসদাচরণ', 'যদি কোনো ব্যক্তি অন্যকে অনৈতিক বা অসম্মানজনকভাবে আচরণ করে, তবে এটি অসদাচরণ হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. ঘটনার সম্পর্কিত তথ্য থানায় দায়ের করুন। \r\n২. ঘটনার সাক্ষী এবং প্রমাণ সংগ্রহ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন। \r\n২. প্রমাণ সংগ্রহ করুন এবং তদন্তে সহায়তা করুন।', '2025-01-11 11:16:41', NULL, 1),
(14, 'ধারা ৩৭৯', 'চুরির চেষ্টা', 'যদি কোনো ব্যক্তি অন্যের সম্পত্তি চুরির উদ্দেশ্যে চেষ্টা করে, তবে এটি চুরির চেষ্টা হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. অবিলম্বে নিকটস্থ থানায় অভিযোগ জানান। \r\n২. প্রমাণ ও ঘটনাস্থলের তথ্য সংগ্রহ করুন এবং পুলিশকে সরবরাহ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশের তদন্তে সহায়তা করুন এবং প্রমাণ জমা দিন। \r\n২. মামলার প্রক্রিয়া সম্পর্কে অবগত থাকুন।', '2025-01-11 11:18:09', NULL, 1),
(15, 'ধারা ৩৮০', 'গৃহচুরি', 'যদি কোনো ব্যক্তি অন্যের বাড়ি বা গৃহে চুরি করে, তবে এটি গৃহচুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. গৃহচুরির অভিযোগ থানায় জানান। \r\n২. চুরি হওয়া সম্পত্তির তালিকা তৈরি করুন এবং পুলিশের কাছে জমা দিন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশ তদন্তে সহায়তা করুন এবং চুরি হওয়া সামগ্রীর তথ্য দিন। \r\n২. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন এবং প্রয়োজনীয় প্রমাণ সংগ্রহ করুন।', '2025-01-11 11:18:09', NULL, 1),
(16, 'ধারা ৩৮২', 'দোকান চুরি', 'যদি কোনো ব্যক্তি অন্যের দোকান থেকে মালামাল চুরি করে, তবে এটি দোকান চুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. দোকান চুরির অভিযোগ থানায় জানান। \r\n২. চুরি হওয়া মালামালের তালিকা এবং প্রমাণ সংগ্রহ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশ তদন্তে সহায়তা করুন এবং প্রমাণ জমা দিন। \r\n২. মামলা চলাকালে আদালতে প্রমাণ প্রদানে সহায়তা করুন।', '2025-01-11 11:18:09', NULL, 1),
(17, 'ধারা ৩৮১', 'কর্মচারী দ্বারা চুরি', 'যদি কোনো ব্যক্তি তার কর্মস্থলে কর্তব্যরত অবস্থায় অন্যের মাল চুরি করে, তবে এটি কর্মচারী দ্বারা চুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. কর্মচারীর বিরুদ্ধে থানায় অভিযোগ জানান। \r\n২. চুরি হওয়া সম্পত্তি ও তথ্য সংরক্ষণ করুন এবং পুলিশকে সরবরাহ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশ তদন্তে সহায়তা করুন এবং সমস্ত প্রমাণ জমা দিন। \r\n২. আদালতে মামলার কার্যক্রম সম্পর্কে অবগত থাকুন।', '2025-01-11 11:18:09', NULL, 1),
(18, 'ধারা ৩৮৩', 'চুরি হওয়া মালামাল বিক্রি', 'যদি কোনো ব্যক্তি চুরি করা মালামাল বিক্রি করে, তবে এটি চুরি হওয়া মালামাল বিক্রি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. মালামাল বিক্রির অভিযোগ থানায় জানান। \r\n২. চুরি হওয়া মালামালের বিক্রির তথ্য সংগ্রহ করুন এবং পুলিশকে জানান।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশকে তদন্তে সহায়তা করুন এবং প্রমাণ জমা দিন। \r\n২. চুরি হওয়া মালামালের ফিরিয়ে আনার জন্য আইনি পদক্ষেপ নিন।', '2025-01-11 11:18:09', NULL, 1),
(19, 'ধারা ৩৮৪', 'ব্যাংক চুরি', 'যদি কোনো ব্যক্তি ব্যাংক থেকে টাকা চুরি করে, তবে এটি ব্যাংক চুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. ব্যাংকের নিরাপত্তা ব্যবস্থা পরীক্ষা করে থানায় অভিযোগ জানান। \r\n২. চুরি হওয়া টাকা বা মালামালের তথ্য সংগ্রহ করুন এবং পুলিশের কাছে জমা দিন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশ তদন্তে সহায়তা করুন এবং সমস্ত প্রমাণ জমা দিন। \r\n২. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন।', '2025-01-11 11:18:54', NULL, 1),
(20, 'ধারা ৩৮৫', 'গাড়ি চুরি', 'যদি কোনো ব্যক্তি অন্যের গাড়ি চুরি করে, তবে এটি গাড়ি চুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. চুরি হওয়া গাড়ির তথ্য দ্রুত থানায় প্রদান করুন। \r\n২. গাড়ির নিবন্ধন নম্বর, চেসিস নম্বর এবং অন্যান্য প্রমাণ সংগ্রহ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. গাড়ি উদ্ধার করতে পুলিশের সঙ্গে কাজ করুন। \r\n২. আদালতে মামলা চলাকালে প্রমাণ প্রদান করুন।', '2025-01-11 11:18:54', NULL, 1),
(21, 'ধারা ৩৮৬', 'মোবাইল ফোন চুরি', 'যদি কোনো ব্যক্তি অন্যের মোবাইল ফোন চুরি করে, তবে এটি মোবাইল ফোন চুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. চুরি হওয়া মোবাইল ফোনের তথ্য থানায় জানান। \r\n২. মোবাইল ফোনের IMEI নম্বর এবং অন্যান্য প্রমাণ সংগ্রহ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. মোবাইল ফোন পুনরুদ্ধারের জন্য পুলিশকে সহায়তা করুন। \r\n২. মামলা চলাকালীন আদালতে প্রমাণ প্রদান করুন।', '2025-01-11 11:18:54', NULL, 1),
(22, 'ধারা ৩৮৭', 'জমি চুরি', 'যদি কোনো ব্যক্তি অন্যের জমি অবৈধভাবে দখল করে বা চুরি করে, তবে এটি জমি চুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. থানায় জমি চুরির অভিযোগ জানান। \r\n২. জমির দলিলপত্র ও অন্যান্য প্রমাণ সংরক্ষণ করুন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. জমি উদ্ধার করতে পুলিশের তদন্তে সহায়তা করুন। \r\n২. আদালতের কার্যক্রম সম্পর্কে অবগত থাকুন এবং প্রমাণ জমা দিন।', '2025-01-11 11:18:54', NULL, 1),
(23, 'ধারা ৩৮৮', 'দোকান বা ব্যবসায়ী চুরি', 'যদি কোনো ব্যক্তি অন্যের দোকান বা ব্যবসায় থেকে পণ্য বা মালামাল চুরি করে, তবে এটি দোকান বা ব্যবসায়ী চুরি হিসেবে গণ্য হবে। \r\n**ভুক্তভোগীর করণীয়:** \r\n১. চুরির অভিযোগ থানায় জানান। \r\n২. চুরি হওয়া মালামালের তালিকা তৈরি করুন এবং পুলিশের কাছে জমা দিন।\r\n৩. একজন দক্ষ আইনজীবীর সঙ্গে পরামর্শ করুন।\r\n**যেখান থেকে সাহায্য নিতে পারেন:** \r\n- স্থানীয় পুলিশ স্টেশন\r\n- আইন ও সালিশ কেন্দ্র\r\n**পরবর্তী পদক্ষেপ:** \r\n১. পুলিশ তদন্তে সহায়তা করুন এবং সমস্ত প্রমাণ জমা দিন। \r\n২. মামলার প্রক্রিয়া সম্পর্কে অবগত থাকুন এবং আদালতের কার্যক্রম অনুসরণ করুন।', '2025-01-11 11:18:54', NULL, 1),
(53, '1234', 'car', 'fasdiuhfqdk', '2025-01-27 07:42:55', 46, 1),
(54, '698245', 'cable', 'fafhaodfjoad', '2025-02-01 09:57:50', 71, 1),
(55, '3434', 'mobile', 'foajgpajpfgkpfga', '2025-02-15 13:43:20', 46, 1),
(56, '৩৭৮', 'দণ্ডবিধি ', 'random info is added here.', '2025-02-21 08:12:26', 73, 1),
(57, '6543', 'hazel', 'falfalsnclawc', '2025-03-03 15:10:32', 46, 1);

-- --------------------------------------------------------

--
-- Table structure for table `LAWYER`
--

CREATE TABLE `LAWYER` (
  `lawyer_id` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `nid` varchar(100) NOT NULL,
  `license` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `charge` varchar(55) DEFAULT NULL,
  `experience` varchar(55) DEFAULT NULL,
  `rating` float DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `LAWYER`
--

INSERT INTO `LAWYER` (`lawyer_id`, `phone`, `nid`, `license`, `category`, `district`, `address`, `charge`, `experience`, `rating`) VALUES
(46, '01919932481', '24096824', '57394724', 'criminal', 'dhaka', 'Azimpur Govt Officiers Quatar, Building-4, Flat no: 9-C', '18000', '30 year', 3.8),
(47, '01912333481', '34352452345', '23452435', 'criminal', 'dhaka', 'Canada, bogura,canada', '14000', '3 year', NULL),
(50, '01234567891', '57248527485', '5274578245', 'criminal', 'dhaka', NULL, NULL, NULL, 4.75),
(54, '01912333464', '352354', '45325234', 'criminal', 'dhaka', 'savar', '70000', '10 year', 0),
(62, '01234567891', '45274352392', '3411324', 'family', 'dhaka', 'Notun Bazar, Dhaka, sayednagar, B block, House-12', '80000', '5 year', 0),
(65, '01633576991', '1212', '1212', 'family', 'dhaka', NULL, NULL, NULL, 0),
(67, '01919933481', '658339', '2358458', 'criminal', 'dhaka', 'Dhaka, new polton', '15000', '4 year', 3.66667),
(70, '01919933481', '49258', '92847528', 'family', 'dhaka', 'savar, DHAKA', '15000', '5 year', 4),
(71, '01919933481', '5843285', '452485', 'property', 'dhaka', 'Dhaka, new polton', '60000', '5 year', 4),
(73, '01723500783', '69348533', '26244', 'criminal', 'khulna', 'khulna , 45/1 Bakshipara lane ', '20000', '5 year', 4);

-- --------------------------------------------------------

--
-- Table structure for table `LAWYER_RATINGS`
--

CREATE TABLE `LAWYER_RATINGS` (
  `rating_id` int(11) NOT NULL,
  `lawyer_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `LAWYER_RATINGS`
--

INSERT INTO `LAWYER_RATINGS` (`rating_id`, `lawyer_id`, `user_id`, `rating`, `created_at`) VALUES
(7, 46, 46, 5, '2025-01-26 15:51:18'),
(8, 46, 46, 5, '2025-01-26 15:51:21'),
(9, 46, 46, 5, '2025-01-26 15:51:23'),
(10, 65, 65, 1, '2025-01-26 17:02:57'),
(11, 65, 65, 1, '2025-01-26 17:07:06'),
(12, 67, 67, 1, '2025-01-26 17:34:54'),
(13, 67, 66, 5, '2025-01-26 17:37:33'),
(14, 67, 66, 5, '2025-01-26 17:38:47'),
(15, 46, 63, 1, '2025-01-27 01:40:44'),
(16, 54, 54, 5, '2025-01-27 02:40:52'),
(17, 46, 46, 1, '2025-01-27 02:50:02'),
(18, 54, 54, 1, '2025-01-27 02:50:04'),
(19, 46, 46, 5, '2025-01-27 02:54:59'),
(20, 67, 67, 5, '2025-01-27 03:02:49'),
(21, 50, 50, 5, '2025-01-27 03:05:59'),
(22, 50, 50, 5, '2025-01-27 03:19:02'),
(23, 50, 50, 5, '2025-01-27 03:21:02'),
(24, 50, 50, 4, '2025-01-27 03:23:06'),
(25, 46, 46, 4, '2025-01-27 04:15:12'),
(26, 70, 70, 4, '2025-01-27 05:16:05'),
(27, 46, 46, 4, '2025-01-27 07:43:16'),
(28, 71, 71, 4, '2025-02-01 09:58:16'),
(29, 46, 46, 4, '2025-02-15 13:44:15'),
(30, 73, 73, 4, '2025-02-21 08:13:08'),
(31, 46, 46, 4, '2025-03-03 15:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `Message`
--

CREATE TABLE `Message` (
  `message_id` int(11) NOT NULL,
  `hire_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message_text` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Message`
--

INSERT INTO `Message` (`message_id`, `hire_id`, `sender_id`, `message_text`, `sent_at`) VALUES
(7, 52, 50, 'hello abir', '2025-01-26 02:21:14'),
(8, 52, 63, 'heelo sir', '2025-01-26 02:25:11'),
(9, 51, 46, 'abc', '2025-01-26 14:51:12'),
(10, 51, 46, 'xyz', '2025-01-26 14:51:29'),
(11, 54, 67, 'hi putul', '2025-01-26 17:36:18'),
(12, 54, 66, 'hello', '2025-01-26 17:38:06'),
(13, 60, 46, 'Hello kayes , what you need?', '2025-01-27 04:14:46'),
(14, 61, 70, 'Hello biporna.\r\nWhat you need say .', '2025-01-27 05:22:11'),
(15, 61, 69, 'when you will free, \r\ncan i call you ?', '2025-01-27 05:23:42'),
(16, 51, 63, 'sir i need your help', '2025-01-27 07:40:35'),
(17, 60, 46, 'hello kayed', '2025-01-27 07:42:08'),
(18, 60, 46, 'hello kayes ', '2025-01-28 03:21:16'),
(19, 63, 50, 'hello', '2025-01-28 03:22:30'),
(20, 63, 58, 'hi', '2025-01-28 03:22:56'),
(21, 64, 71, 'Hello Foisal , what you need ?', '2025-02-01 10:01:19'),
(22, 64, 58, 'can i call you sir , when you are free?', '2025-02-01 10:02:11'),
(23, 65, 46, 'hello faisal what you need ?', '2025-02-15 13:42:57'),
(24, 65, 72, 'i need your assistance', '2025-02-15 13:44:44'),
(25, 66, 73, 'Hello Foisal what you want ?', '2025-02-21 08:16:05'),
(26, 67, 65, 'hello raisa, what you need , <3', '2025-03-03 15:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `POST`
--

CREATE TABLE `POST` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `POST`
--

INSERT INTO `POST` (`post_id`, `user_id`, `content`, `created_at`) VALUES
(18, 63, 'HII -> i am User ABIR ', '2025-01-24 09:56:12'),
(19, 63, 'This is new post', '2025-01-26 04:36:00'),
(20, 63, 'Another Post here', '2025-01-26 04:40:58'),
(21, 63, 'New POST!!!', '2025-01-26 06:15:07'),
(22, 66, 'hi i am putul.\r\ni need a help here near to the sayed nagar.\r\n\r\nanyone here for help', '2025-01-26 17:28:04'),
(23, 70, 'Theres a accident occur near to the SAVAR.\r\n\r\nNeed a car right now.', '2025-01-27 05:17:30'),
(24, 71, 'hello everyone , if anybody need land related help ,\r\nknock me please.', '2025-02-01 09:59:04'),
(25, 72, 'i need help , near to azimpur', '2025-02-15 13:40:43'),
(26, 46, 'Hello , hre something occure near to azimpur, anyone here for help . \r\n\r\nroad - 16, near boro mosjid', '2025-03-03 15:13:13');

-- --------------------------------------------------------

--
-- Table structure for table `Review`
--

CREATE TABLE `Review` (
  `review_id` int(11) NOT NULL,
  `hire_id` int(11) NOT NULL,
  `review_text` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Review`
--

INSERT INTO `Review` (`review_id`, `hire_id`, `review_text`, `review_date`) VALUES
(9, 45, 'foisal------> biporna', '2025-01-24 13:20:33'),
(10, 47, 'its working properly ->foisal', '2025-01-24 16:47:45'),
(11, 49, 'hi nur --> foisal', '2025-01-24 16:52:04'),
(12, 48, 'Hi binty--> foisal', '2025-01-24 16:58:39'),
(13, 51, 'hi ---> abir', '2025-01-25 13:56:32'),
(14, 56, 'review test manual-> abir ', '2025-01-27 01:49:51'),
(15, 52, 'hello-> abit', '2025-01-27 01:59:32'),
(16, 52, 'ok', '2025-01-27 01:59:56'),
(17, 56, 'comented by abir-> abir', '2025-01-27 02:15:46'),
(18, 52, 'bad -> abir', '2025-01-27 03:24:14'),
(19, 52, 'no', '2025-01-27 03:25:11'),
(20, 52, 'aa', '2025-01-27 03:25:22'),
(21, 58, 'abir<---good', '2025-01-27 03:26:33'),
(22, 58, 'good', '2025-01-27 03:35:26'),
(23, 58, 'ok', '2025-01-27 03:36:24'),
(24, 58, 'ok', '2025-01-27 03:36:37'),
(25, 58, '3', '2025-01-27 03:41:26'),
(26, 58, 'go', '2025-01-27 03:45:51'),
(27, 58, 'asfa', '2025-01-27 03:52:03'),
(28, 58, 'yess', '2025-01-27 03:52:13'),
(29, 58, 'fahad', '2025-01-27 03:52:25'),
(30, 58, '??', '2025-01-27 03:58:37'),
(31, 60, 'good helper', '2025-01-27 04:16:00'),
(32, 64, 'very supportive .', '2025-02-01 10:03:31'),
(33, 66, 'she is very supportive and also experienced', '2025-02-21 08:17:13');

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_type` enum('User','Lawyer') NOT NULL DEFAULT 'User',
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(15) DEFAULT NULL,
  `profile_picture_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `USER`
--

INSERT INTO `USER` (`user_id`, `first_name`, `last_name`, `email`, `user_type`, `password`, `created_at`, `phone`, `profile_picture_url`) VALUES
(46, 'Akbor', 'hossain', 'akbor@gmail.com', 'Lawyer', '$2y$10$Yz1JYnF86x2edt/KZsT.NeVWBlRZ544Ssb8wiyYfB024jq3yzVAHC', '2025-01-13 12:34:17', '01919932481', 'thisone.jpg'),
(47, 'binty', 'borsha', 'binty@gmail.com', 'Lawyer', '$2y$10$PG9buT6XNYrT0vghKVbn/OJCjaHYxr08WAe.55cE.UEzVGO.FauZi', '2025-01-13 12:35:04', '01912333481', NULL),
(50, 'nur', 'akter', 'nur@gmail.com', 'Lawyer', '$2y$10$2S6qBCsXsJxeG00746AGC.jSM2Uv1OaUiqkXqW4j3RNVYYPHqZcje', '2025-01-13 16:39:03', '', 'lawyer.jpeg'),
(54, 'Jui Xaman', 'Zaman', 'jui@gmail.com', 'Lawyer', '$2y$10$RyTjHJIqNCIc6gZEp8C1uewTmh4IH2kgIYKRw.F4Fbs9XNfN1RP12', '2025-01-17 14:06:29', '01912333464', 'signup-background.jpg'),
(58, 'Foisal', 'Arefin', 'foisal@gmail.com', 'User', '$2y$10$E/W2ByiQGhYY3xIKlHI2Pu2YgQ5NRDoINQZOCxa.XhTpgDydRj8VK', '2025-01-20 18:07:39', '01919933481', 'logo.png'),
(62, 'Biporna', 'mubashira', 'mim@gmail.com', 'Lawyer', '$2y$10$oyTHXlStvUo1oKVF7c5Kru467mCZ3DzrqeywZXfXQn45qeDA4WhKq', '2025-01-24 09:36:54', '01234567891', NULL),
(63, 'abir', 'ahmed', 'abir@gmail.com', 'User', '$2y$10$PvTkc/CuJeNx/mDbIdyw2.pPeXRmvlvgB8ljwmtClC16gZmRjJjzS', '2025-01-24 09:45:05', '01919933481', 'thisone.jpg'),
(65, 'ab', 'ab', 'ab@gmail.com', 'Lawyer', '$2y$10$1wu/yyIJfaPftJLh1PraQuCJWcl5IXiw2/4vV39xDPCNzojY3fOZS', '2025-01-26 16:58:51', NULL, '01.jpg'),
(66, 'putul', 'putul', 'putul@gmail.com', 'User', '$2y$10$n/Bn5ROA5g07yBotuHECpOFqI0OnU5xojhK.sSk1rq47idKAH8Tae', '2025-01-26 17:25:13', '01919933482', 'putul.jpg'),
(67, 'adeeb', 'adeeb', 'adeeb@gmail.com', 'Lawyer', '$2y$10$RGYCAuDMCe43v.CfycIc2eieJNS3Kcks40oCHCjPXWHopP6oRyExy', '2025-01-26 17:30:01', '01919933481', 'bread.jpg'),
(68, 'Imrul', 'Kayes', 'imrul@gmail.com', 'User', '$2y$10$tpmku0YNUxX3uvdKjLCv0erZR5xN8bVxBqmUodopdMJXqEMSiCDBe', '2025-01-27 04:13:38', NULL, 'logo.png'),
(69, 'biporna', 'biporna', 'biporna@gmail.com', 'User', '$2y$10$6N8fSBhNtXM1S9MAVpnKbeO/Y2GN1HPuk20jjQllBTPV4dVf2rtUi', '2025-01-27 05:09:20', '', 'First Design.png'),
(70, 'riya', 'riya', 'riya@gmail.com', 'Lawyer', '$2y$10$GYU9VF4rBcYTaaaReqRfk.d//YgGGkbbcyw3PFzD8Mbc1eivH3IXa', '2025-01-27 05:09:58', '01919933481', '2.jpeg'),
(71, 'Enamul', 'Hauque', 'enamul@gmail.com', 'Lawyer', '$2y$10$wJwyr0YnYgXD804Uebfkwu9xzrU77pM83.48tF2pwh5xir3HG4VOy', '2025-02-01 09:55:36', '01919933481', 'lawyer.jpeg'),
(72, 'amir', 'faisal', 'amir@gmail.com', 'User', '$2y$10$W3ed1FtuIpRztBIkd28QcuKwQ7EZ9xrZThtH2bKz6.yZqappip5Bi', '2025-02-15 13:39:34', '01919933481', 'First Design.png'),
(73, 'Advocate Tahrima', 'Parvin', 'tahrimaamin@gmail.com', 'Lawyer', '$2y$10$J0CqUloLZtlDBmR4/mheVe2KXfPKi5GAShG1UsSgfq8qbCjsWIi5m', '2025-02-21 08:06:08', '01723500783', 'Screenshot 2025-02-21 at 2.08.13 PM.png'),
(74, 'raisa', 'raisa', 'raisa@gmail.com', 'User', '$2y$10$kXFTX9rpo48EFRnx7EnU5e.xBc/fygmYHRu0b94C5ZNI3OQi9Mgxa', '2025-03-03 15:14:11', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Hire`
--
ALTER TABLE `Hire`
  ADD PRIMARY KEY (`hire_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `lawyer_id` (`lawyer_id`);

--
-- Indexes for table `laws`
--
ALTER TABLE `laws`
  ADD PRIMARY KEY (`law_id`),
  ADD KEY `fk_lawyer_id` (`lawyer_id`);

--
-- Indexes for table `LAWYER`
--
ALTER TABLE `LAWYER`
  ADD PRIMARY KEY (`lawyer_id`),
  ADD UNIQUE KEY `nid` (`nid`),
  ADD UNIQUE KEY `license` (`license`);

--
-- Indexes for table `LAWYER_RATINGS`
--
ALTER TABLE `LAWYER_RATINGS`
  ADD PRIMARY KEY (`rating_id`),
  ADD KEY `lawyer_id` (`lawyer_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Message`
--
ALTER TABLE `Message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `hire_id` (`hire_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `POST`
--
ALTER TABLE `POST`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Review`
--
ALTER TABLE `Review`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `hire_id` (`hire_id`);

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `Hire`
--
ALTER TABLE `Hire`
  MODIFY `hire_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `laws`
--
ALTER TABLE `laws`
  MODIFY `law_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `LAWYER_RATINGS`
--
ALTER TABLE `LAWYER_RATINGS`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `Message`
--
ALTER TABLE `Message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `POST`
--
ALTER TABLE `POST`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `Review`
--
ALTER TABLE `Review`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `POST` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`);

--
-- Constraints for table `Hire`
--
ALTER TABLE `Hire`
  ADD CONSTRAINT `hire_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`),
  ADD CONSTRAINT `hire_ibfk_2` FOREIGN KEY (`lawyer_id`) REFERENCES `LAWYER` (`lawyer_id`);

--
-- Constraints for table `laws`
--
ALTER TABLE `laws`
  ADD CONSTRAINT `fk_lawyer_id` FOREIGN KEY (`lawyer_id`) REFERENCES `LAWYER` (`lawyer_id`);

--
-- Constraints for table `LAWYER`
--
ALTER TABLE `LAWYER`
  ADD CONSTRAINT `lawyer_ibfk_1` FOREIGN KEY (`lawyer_id`) REFERENCES `USER` (`user_id`);

--
-- Constraints for table `LAWYER_RATINGS`
--
ALTER TABLE `LAWYER_RATINGS`
  ADD CONSTRAINT `lawyer_ratings_ibfk_1` FOREIGN KEY (`lawyer_id`) REFERENCES `LAWYER` (`lawyer_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lawyer_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Message`
--
ALTER TABLE `Message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`hire_id`) REFERENCES `Hire` (`hire_id`),
  ADD CONSTRAINT `message_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `USER` (`user_id`);

--
-- Constraints for table `POST`
--
ALTER TABLE `POST`
  ADD CONSTRAINT `post_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`);

--
-- Constraints for table `Review`
--
ALTER TABLE `Review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`hire_id`) REFERENCES `Hire` (`hire_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
