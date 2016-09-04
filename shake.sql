-- phpMyAdmin SQL Dump
-- version phpStudy 2014
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 年 04 月 14 日 11:56
-- 服务器版本: 5.5.40
-- PHP 版本: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `shake`
--

-- --------------------------------------------------------

--
-- 表的结构 `tp_customer`
--

CREATE TABLE IF NOT EXISTS `tp_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(32) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `updatetime` varchar(32) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tp_customer_preorder`
--

CREATE TABLE IF NOT EXISTS `tp_customer_preorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lottery_id` varchar(32) DEFAULT NULL,
  `hb_type` varchar(32) DEFAULT NULL,
  `jetype` varchar(32) DEFAULT NULL,
  `signle_total` varchar(32) DEFAULT NULL,
  `min` varchar(32) DEFAULT NULL,
  `max` varchar(32) DEFAULT NULL,
  `total_num` varchar(32) DEFAULT NULL,
  `wishing` varchar(32) DEFAULT NULL,
  `send_name` varchar(32) DEFAULT NULL,
  `act_name` varchar(32) DEFAULT NULL,
  `remark` varchar(32) DEFAULT NULL,
  `updatetime` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tp_customer_preorder`
--

INSERT INTO `tp_customer_preorder` (`id`, `lottery_id`, `hb_type`, `jetype`, `signle_total`, `min`, `max`, `total_num`, `wishing`, `send_name`, `act_name`, `remark`, `updatetime`) VALUES
(1, '8_cfEHEDoDjdZaS_aorB4A', 'NORMAL', 'gd', '100', '', '', '1', 'test', 'test', 'test', 'test', '1459828604');

-- --------------------------------------------------------

--
-- 表的结构 `tp_pages`
--

CREATE TABLE IF NOT EXISTS `tp_pages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `description` varchar(30) NOT NULL,
  `comment` varchar(30) NOT NULL,
  `icon_url` text NOT NULL,
  `page_id` text NOT NULL,
  `page_url` text NOT NULL,
  `device_num` varchar(10) NOT NULL,
  `createtime` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `tp_pages`
--

INSERT INTO `tp_pages` (`id`, `title`, `description`, `comment`, `icon_url`, `page_id`, `page_url`, `device_num`, `createtime`) VALUES
(4, 'test', 'test', 'test', 'http://shp.qpic.cn/wx_shake_bus/0/1460430872e9dd2797018cad79186e03e8c5aec8dc/120', '2595193', 'http://www.baidu.com', '0', '1460430872'),
(3, '测试', '测试', '测试', 'http://shp.qpic.cn/wx_shake_bus/0/1460430841e9dd2797018cad79186e03e8c5aec8dc/120', '2595149', 'http://www.baidu.com', '0', '1460430842');

-- --------------------------------------------------------

--
-- 表的结构 `tp_preorder`
--

CREATE TABLE IF NOT EXISTS `tp_preorder` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `hb_type` varchar(30) DEFAULT NULL,
  `jetype` varchar(30) DEFAULT NULL,
  `signle_total` varchar(30) DEFAULT NULL,
  `min` varchar(10) DEFAULT NULL,
  `max` varchar(10) DEFAULT NULL,
  `total_num` varchar(10) NOT NULL,
  `wishing` varchar(30) NOT NULL,
  `send_name` varchar(30) NOT NULL,
  `act_name` varchar(30) NOT NULL,
  `remark` varchar(30) NOT NULL,
  `lottery_id` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tp_preorder`
--

INSERT INTO `tp_preorder` (`id`, `hb_type`, `jetype`, `signle_total`, `min`, `max`, `total_num`, `wishing`, `send_name`, `act_name`, `remark`, `lottery_id`) VALUES
(1, 'GROUP', 'gd', '200', '', '', '10', 'test', 'test', 'test', 'test', 's3rfQDmEJhL3fb_sriNiqQ');

-- --------------------------------------------------------

--
-- 表的结构 `tp_qr_card`
--

CREATE TABLE IF NOT EXISTS `tp_qr_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(32) DEFAULT NULL,
  `title` varchar(32) DEFAULT NULL,
  `card_type` varchar(32) DEFAULT NULL,
  `card_id` varchar(32) DEFAULT NULL,
  `nums` varchar(10) DEFAULT NULL,
  `expire_seconds` varchar(20) DEFAULT NULL,
  `is_unique_code` varchar(10) DEFAULT NULL,
  `ticket` text,
  `url` text,
  `show_qrcode_url` text,
  `timestamp` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `tp_qr_card`
--

INSERT INTO `tp_qr_card` (`id`, `brand_name`, `title`, `card_type`, `card_id`, `nums`, `expire_seconds`, `is_unique_code`, `ticket`, `url`, `show_qrcode_url`, `timestamp`) VALUES
(1, '青岛微商宝网络科技', '10元代金券', 'CASH', 'pf29euL0PGdDqoZO9av2UwYFj6j4', '5', '7776000', '0', 'gQFV8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xLy0wUFdWVGZsU2Rrd3d1SlBrbVM3AAIEC6sNVwMEAKd2AA==', 'http://weixin.qq.com/q/-0PWVTflSdkwwuJPkmS7', 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQFV8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xLy0wUFdWVGZsU2Rrd3d1SlBrbVM3AAIEC6sNVwMEAKd2AA%3D%3D', '1460513548'),
(2, '青岛微商宝网络科技', '1元代金券', 'CASH', 'pf29euFDM9j14ZB1TChhnbCg8eOY', '2', '7776000', '1', 'gQGS8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL25FTy1kQjdsSzlsU3FZRmxfMlM3AAIEfasNVwMEAKd2AA==', 'http://weixin.qq.com/q/nEO-dB7lK9lSqYFl_2S7', 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGS8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL25FTy1kQjdsSzlsU3FZRmxfMlM3AAIEfasNVwMEAKd2AA%3D%3D', '1460513661');

-- --------------------------------------------------------

--
-- 表的结构 `tp_shakeactivity`
--

CREATE TABLE IF NOT EXISTS `tp_shakeactivity` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `lottery_id` varchar(40) NOT NULL,
  `title` varchar(20) NOT NULL,
  `desc` varchar(30) NOT NULL,
  `begin_time` varchar(40) NOT NULL,
  `expire_time` varchar(40) NOT NULL,
  `sponsor_appid` varchar(40) NOT NULL,
  `jump_url` text NOT NULL,
  `total` varchar(20) NOT NULL,
  `creattime` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `tp_shakeactivity`
--

INSERT INTO `tp_shakeactivity` (`id`, `lottery_id`, `title`, `desc`, `begin_time`, `expire_time`, `sponsor_appid`, `jump_url`, `total`, `creattime`) VALUES
(1, 's3rfQDmEJhL3fb_sriNiqQ', '测试', '测试', '2016-04-01 00:00:00', '2016-05-31 00:00:00', 'wxc452fa15657e54bf', '', '10', '1460106385');

-- --------------------------------------------------------

--
-- 表的结构 `tp_shakeuser`
--

CREATE TABLE IF NOT EXISTS `tp_shakeuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` varchar(40) CHARACTER SET gbk DEFAULT NULL,
  `nickname` varchar(32) DEFAULT NULL,
  `days` varchar(32) CHARACTER SET gbk DEFAULT NULL,
  `updatetime` varchar(40) CHARACTER SET gbk DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tp_sign`
--

CREATE TABLE IF NOT EXISTS `tp_sign` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `days` varchar(32) DEFAULT NULL,
  `total_amount` varchar(32) DEFAULT NULL,
  `lottery_id` varchar(40) DEFAULT NULL,
  `timestamp` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=gbk AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
