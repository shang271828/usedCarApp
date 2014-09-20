-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-08-31 14:12:14
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `car_market`
--

-- --------------------------------------------------------

--
-- 表的结构 `prefix_comment`
--

CREATE TABLE IF NOT EXISTS `prefix_comment` (
  `cid` int(30) NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `content` text,
  `img_list` varchar(512) DEFAULT NULL,
  `parent_type` varchar(512) DEFAULT NULL,
  `pid` int(30) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `prefix_message`
--

CREATE TABLE IF NOT EXISTS `prefix_message` (
  `mid` int(30) NOT NULL AUTO_INCREMENT,
  `content` text,
  `img_list` varchar(512) DEFAULT NULL,
  `destination_list` varchar(512) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `is_fetched` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `prefix_notice`
--

CREATE TABLE IF NOT EXISTS `prefix_notice` (
  `nid` int(30) NOT NULL AUTO_INCREMENT,
  `title` tinytext,
  `content` text,
  `img_list` varchar(512) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `coordinate` varchar(64) DEFAULT NULL,
  `counter_view` int(11) DEFAULT NULL,
  `counter_follow` int(11) DEFAULT NULL,
  `counter_praise` int(11) DEFAULT NULL,
  PRIMARY KEY (`nid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- 表的结构 `prefix_image`
--

CREATE TABLE IF NOT EXISTS `prefix_image` (
  `pid` int(30) NOT NULL AUTO_INCREMENT,
  `image_name` varchar(32) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `image_url` varchar(256) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `image_url_origin` varchar(256) DEFAULT NULL, 
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `prefix_relation`
--

CREATE TABLE IF NOT EXISTS `prefix_relation` (
  `uid` int(30) NOT NULL,
  `friend_list_initial` varchar(512) DEFAULT NULL,
  `friend_list_secondary` varchar(512) DEFAULT NULL,
  `notice_list_following` varchar(512) DEFAULT NULL,
  `user_list_following` varchar(512) DEFAULT NULL,
  `summoning_list` varchar(512) DEFAULT NULL,
  `disgust_list` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `prefix_user`
--

CREATE TABLE IF NOT EXISTS `prefix_user` (
  `uid` int(30) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `register_time` datetime DEFAULT NULL,
  `register_email` varchar(128) DEFAULT NULL,
  `login_time` datetime DEFAULT NULL,
  `login_coordinate` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- 表的结构 `prefix_user_state`
--

CREATE TABLE IF NOT EXISTS `prefix_user_state` (
  `uid` int(30) NOT NULL,
  `login_state` varchar(16) DEFAULT NULL,
  `show_state` varchar(16) DEFAULT NULL,
  `latest_coordinate` varchar(64) DEFAULT NULL,
  `login_record` text,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
