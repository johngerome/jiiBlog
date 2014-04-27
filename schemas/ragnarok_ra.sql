-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2013 at 08:02 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ragnarok_ra`
--

-- --------------------------------------------------------

--
-- Table structure for table `jii_blog_categories`
--

CREATE TABLE IF NOT EXISTS `jii_blog_categories` (
  `category_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `description` text,
  `parent_id` bigint(20) NOT NULL,
  `slug` text NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data for table `jii_blog_categories`
--

INSERT INTO `jii_blog_categories` (`category_id`, `name`, `description`, `parent_id`, `slug`, `created_by`, `created_at`) VALUES
(1, 'Uncategorized', '', 0, 'uncategorized', 2000000, '2013-10-26 14:03:00'),
(15, 'ok', 'asdasd', 0, 'asdasd', 2000000, '2013-11-01 22:32:35');

-- --------------------------------------------------------

--
-- Table structure for table `jii_blog_comments`
--

CREATE TABLE IF NOT EXISTS `jii_blog_comments` (
  `comment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `comment` longtext NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `commented_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jii_blog_posts`
--

CREATE TABLE IF NOT EXISTS `jii_blog_posts` (
  `post_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` longtext NOT NULL,
  `category_id` int(11) NOT NULL,
  `author_alias` varchar(45) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `slug` text NOT NULL,
  `hits` bigint(20) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `updated_by` int(11) NOT NULL,
  `allow_comment` tinyint(4) NOT NULL,
  `allow_author_alias` tinyint(4) NOT NULL,
  `show_hits` tinyint(4) NOT NULL,
  `show_category` tinyint(4) NOT NULL,
  `show_author` tinyint(4) NOT NULL,
  `show_created_date` tinyint(4) NOT NULL,
  `show_modified_date` tinyint(4) NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

--
-- Dumping data for table `jii_blog_posts`
--

INSERT INTO `jii_blog_posts` (`post_id`, `title`, `content`, `category_id`, `author_alias`, `author_id`, `slug`, `hits`, `published`, `created_at`, `updated_at`, `updated_by`, `allow_comment`, `allow_author_alias`, `show_hits`, `show_category`, `show_author`, `show_created_date`, `show_modified_date`) VALUES
(84, 'ball tags', '', 1, 'admin', 2000000, 'ball-tags', 0, 1, '2013-11-01 20:52:23', '2013-11-01 20:52:48', 2000000, 1, 0, 1, 1, 1, 1, 1),
(85, 'lol', '', 1, 'admin', 2000000, 'lol', 0, 1, '2013-11-01 21:56:29', '0000-00-00 00:00:00', 0, 1, 0, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `jii_blog_post_tags`
--

CREATE TABLE IF NOT EXISTS `jii_blog_post_tags` (
  `blog_tags_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL,
  `post_id` bigint(20) NOT NULL,
  PRIMARY KEY (`blog_tags_id`),
  KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `jii_blog_post_tags`
--

INSERT INTO `jii_blog_post_tags` (`blog_tags_id`, `tag_id`, `post_id`) VALUES
(4, 24, 84),
(5, 28, 85);

-- --------------------------------------------------------

--
-- Table structure for table `jii_blog_tags`
--

CREATE TABLE IF NOT EXISTS `jii_blog_tags` (
  `tag_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `slug` text NOT NULL,
  `description` text,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  UNIQUE KEY `tag_id` (`tag_id`),
  UNIQUE KEY `tag_id_2` (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `jii_blog_tags`
--

INSERT INTO `jii_blog_tags` (`tag_id`, `name`, `slug`, `description`, `created_by`, `created_at`) VALUES
(24, 'test', '', 'asd', 0, '0000-00-00 00:00:00'),
(28, 'lol2', 'lol', 'ok', 2000000, '2013-11-01 21:56:29');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `jii_blog_post_tags`
--
ALTER TABLE `jii_blog_post_tags`
  ADD CONSTRAINT `jii_blog_post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `jii_blog_posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
