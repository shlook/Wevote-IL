-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2011 at 05:29 PM
-- Server version: 5.1.52
-- PHP Version: 5.2.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `koladmin_voteprod`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(111) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(111) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `is_movil` tinyint(4) DEFAULT '0',
  `name` varchar(20) DEFAULT NULL,
  `fb_uid` bigint(80) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `about_me` text NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `movil_muval`
--

CREATE TABLE IF NOT EXISTS `movil_muval` (
  `member_id_movil` int(11) DEFAULT NULL,
  `member_id_muval` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
  `vote_id` int(11) NOT NULL AUTO_INCREMENT,
  `vote_subject` varchar(100) DEFAULT NULL,
  `vote_body` text,
  `vote_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vote_finish` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`vote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `vote_ballot`
--

CREATE TABLE IF NOT EXISTS `vote_ballot` (
  `vote_id` int(11) NOT NULL,
  `vote_option_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `movil_id` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vote_id`,`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vote_option`
--

CREATE TABLE IF NOT EXISTS `vote_option` (
  `vote_option_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `body` text CHARACTER SET latin1,
  PRIMARY KEY (`vote_option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `vote_vote_option`
--

CREATE TABLE IF NOT EXISTS `vote_vote_option` (
  `vote_id` int(11) DEFAULT NULL,
  `vote_option_id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
