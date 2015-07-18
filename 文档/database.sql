-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2014-10-23 05:37:54
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jnsurvey`
--

-- --------------------------------------------------------

--
-- 表的结构 `wj_admin`
--

CREATE TABLE IF NOT EXISTS `wj_admin` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL COMMENT '用户名',
  `password` varchar(64) NOT NULL COMMENT '密码',
  `fullname` varchar(50) NOT NULL COMMENT '全名',
  `email` varchar(254) NOT NULL COMMENT '邮箱',
  `qq` tinyint(15) NOT NULL,
  `phone` tinyint(15) NOT NULL COMMENT '电话号码',
  `ctime` datetime NOT NULL COMMENT '注册时间',
  `isdel` int(11) NOT NULL COMMENT '是否有效',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `wj_answer`
--

CREATE TABLE IF NOT EXISTS `wj_answer` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '用户uid',
  `qid` int(11) NOT NULL COMMENT '问题id',
  `oid` int(11) NOT NULL COMMENT '选项id',
  `score` int(11) NOT NULL COMMENT '分数',
  `content` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '内容',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=108 ;

--
-- 转存表中的数据 `wj_answer`
--

INSERT INTO `wj_answer` (`aid`, `uid`, `qid`, `oid`, `score`, `content`, `ctime`) VALUES
(2, '0', 12, 39, 0, '0', 1413005647),
(3, '0', 12, 39, 0, '0', 1413005653),
(4, '0', 13, 41, 0, '0', 1413005653),
(5, '0', 13, 43, 0, '0', 1413005653),
(6, '0', 14, 0, 0, '0', 1413005653),
(7, '0', 12, 39, 0, '0', 1413005709),
(8, '0', 13, 41, 0, '0', 1413005709),
(9, '0', 13, 43, 0, '0', 1413005709),
(10, '0', 14, 0, 0, '0', 1413005709),
(11, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '0', 1413005729),
(12, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '0', 1413005729),
(13, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 43, 0, '0', 1413005729),
(14, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '0', 1413005729),
(15, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '', 1413005774),
(16, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413005774),
(17, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 43, 0, '', 1413005774),
(18, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '反馈不够好', 1413005774),
(19, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '', 1413005928),
(20, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413005928),
(21, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 43, 0, '', 1413005928),
(22, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '反馈不够好', 1413005928),
(23, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 38, 0, '', 1413005939),
(24, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413005939),
(25, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 43, 0, '', 1413005939),
(26, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '我想看到我的反馈', 1413005939),
(27, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 38, 0, '', 1413006399),
(28, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413006399),
(29, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 43, 0, '', 1413006399),
(30, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '我想看到我的反馈', 1413006399),
(31, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 38, 0, '', 1413006416),
(32, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413006416),
(33, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 43, 0, '', 1413006416),
(34, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '我想看到我的反馈', 1413006416),
(35, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 38, 0, '', 1413006548),
(36, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413006548),
(37, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 43, 0, '', 1413006548),
(38, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '我想看到我的反馈', 1413006548),
(39, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '', 1413008399),
(40, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '', 1413008399),
(41, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '', 1413008412),
(42, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '', 1413008412),
(43, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '', 1413008608),
(44, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413008608),
(45, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '', 1413008608),
(46, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '', 1413008682),
(47, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '', 1413008682),
(48, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413008695),
(49, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '', 1413008695),
(50, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 41, 0, '', 1413008941),
(51, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '', 1413008941),
(52, 'l2d9lis0k3b0lmnnpjuc9689t4', 12, 39, 0, '', 1413009072),
(53, 'l2d9lis0k3b0lmnnpjuc9689t4', 13, 42, 0, '', 1413009072),
(54, 'l2d9lis0k3b0lmnnpjuc9689t4', 14, 0, 0, '', 1413009072),
(55, 'l2d9lis0k3b0lmnnpjuc9689t4', 15, 45, 0, '', 1413010351),
(56, 'l2d9lis0k3b0lmnnpjuc9689t4', 16, 47, 0, '', 1413010351),
(57, 'l2d9lis0k3b0lmnnpjuc9689t4', 16, 48, 0, '', 1413010351),
(58, 'l2d9lis0k3b0lmnnpjuc9689t4', 17, 50, 0, '超级多', 1413031985),
(59, 'l2d9lis0k3b0lmnnpjuc9689t4', 17, 52, 0, '是啊', 1413031985),
(60, 'l2d9lis0k3b0lmnnpjuc9689t4', 18, 55, 0, '关你屁事', 1413031985),
(61, 'l2d9lis0k3b0lmnnpjuc9689t4', 19, 0, 0, '好看的', 1413033241),
(62, 'l2d9lis0k3b0lmnnpjuc9689t4', 18, 55, 0, '', 1413035504),
(63, 'l2d9lis0k3b0lmnnpjuc9689t4', 19, 0, 0, '好看的', 1413035504),
(64, 'l2d9lis0k3b0lmnnpjuc9689t4', 20, 56, 0, '', 1413036390),
(65, 'l2d9lis0k3b0lmnnpjuc9689t4', 20, 58, 0, '', 1413036390),
(66, 'l2d9lis0k3b0lmnnpjuc9689t4', 21, 60, 0, '2.5', 1413036390),
(67, 'l2d9lis0k3b0lmnnpjuc9689t4', 22, 0, 0, '好看哦', 1413036390),
(68, 'l2d9lis0k3b0lmnnpjuc9689t4', 20, 57, 0, '', 1413037552),
(69, 'l2d9lis0k3b0lmnnpjuc9689t4', 20, 58, 0, '', 1413037552),
(70, 'l2d9lis0k3b0lmnnpjuc9689t4', 21, 61, 0, '7.2', 1413037552),
(71, 'l2d9lis0k3b0lmnnpjuc9689t4', 22, 0, 0, '多一点空格', 1413037552),
(72, '9orrt5m7js8m7hirauph8lcc43', 20, 56, 0, '', 1413042651),
(73, '9orrt5m7js8m7hirauph8lcc43', 20, 58, 0, '', 1413042651),
(74, '9orrt5m7js8m7hirauph8lcc43', 21, 59, 0, '', 1413042651),
(75, '9orrt5m7js8m7hirauph8lcc43', 22, 0, 0, '', 1413042651),
(76, 'r2p8fmgbec3lt18jr6ba25qv63', 20, 56, 0, '', 1413043050),
(77, 'r2p8fmgbec3lt18jr6ba25qv63', 20, 58, 0, '', 1413043050),
(78, 'r2p8fmgbec3lt18jr6ba25qv63', 21, 60, 0, '', 1413043050),
(79, 'r2p8fmgbec3lt18jr6ba25qv63', 22, 0, 0, '不知毫无', 1413043050),
(80, 'kh77b58j5cg232on00rbgolap0', 20, 56, 0, '很多', 1413381436),
(81, 'kh77b58j5cg232on00rbgolap0', 21, 60, 0, '', 1413381436),
(82, 'kh77b58j5cg232on00rbgolap0', 22, 0, 0, '', 1413381436),
(83, 'kh77b58j5cg232on00rbgolap0', 23, 63, 0, '', 1413381436),
(84, 'kh77b58j5cg232on00rbgolap0', 24, 65, 0, '', 1413381436),
(85, 'd4s3qsbcgp8788p30g5rjfcbl4', 20, 57, 0, '好的', 1413381559),
(86, 'd4s3qsbcgp8788p30g5rjfcbl4', 20, 58, 0, '', 1413381559),
(87, 'd4s3qsbcgp8788p30g5rjfcbl4', 21, 59, 0, '', 1413381559),
(88, 'd4s3qsbcgp8788p30g5rjfcbl4', 22, 0, 0, '', 1413381559),
(89, 'd4s3qsbcgp8788p30g5rjfcbl4', 23, 63, 0, '', 1413381559),
(90, 'd4s3qsbcgp8788p30g5rjfcbl4', 24, 65, 0, '', 1413381559),
(91, 'kh77b58j5cg232on00rbgolap0', 20, 57, 0, '', 1413440782),
(92, 'kh77b58j5cg232on00rbgolap0', 21, 59, 0, '', 1413440782),
(93, 'kh77b58j5cg232on00rbgolap0', 22, 0, 0, '', 1413440782),
(94, '8b7aifhntrbut9rjsndkch32o7', 20, 56, 0, '', 1413450771),
(95, '8b7aifhntrbut9rjsndkch32o7', 20, 58, 0, '', 1413450771),
(96, '8b7aifhntrbut9rjsndkch32o7', 21, 60, 0, '', 1413450771),
(97, '8b7aifhntrbut9rjsndkch32o7', 22, 0, 0, 'YYYY', 1413450771),
(98, '8b7aifhntrbut9rjsndkch32o7', 24, 64, 0, '', 1413450771),
(99, 'cj0hti2olkvla8e8urjq9a6tt2', 20, 56, 0, '好啊', 1413452641),
(100, 'cj0hti2olkvla8e8urjq9a6tt2', 20, 57, 0, '', 1413452641),
(101, 'cj0hti2olkvla8e8urjq9a6tt2', 21, 59, 0, '嗯呢', 1413452641),
(102, 'cj0hti2olkvla8e8urjq9a6tt2', 22, 0, 0, '', 1413452641),
(103, 'cj0hti2olkvla8e8urjq9a6tt2', 24, 64, 0, '', 1413452641),
(104, 'cj0hti2olkvla8e8urjq9a6tt2', 24, 65, 0, '', 1413452641),
(105, 'cj0hti2olkvla8e8urjq9a6tt2', 26, 71, 0, '', 1413452890),
(106, 'kh77b58j5cg232on00rbgolap0', 26, 72, 0, '六岁零八个月', 1413726670),
(107, 'h1mb3kt0v3l81oun2mod0rghg7', 26, 71, 0, '', 1414028574);

-- --------------------------------------------------------

--
-- 表的结构 `wj_condition`
--

CREATE TABLE IF NOT EXISTS `wj_condition` (
  `oid` int(11) NOT NULL,
  `qid` int(11) NOT NULL,
  `jump` int(11) NOT NULL,
  PRIMARY KEY (`oid`),
  UNIQUE KEY `oid` (`oid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `wj_condition`
--

INSERT INTO `wj_condition` (`oid`, `qid`, `jump`) VALUES
(59, 21, 22),
(60, 21, 24),
(61, 21, 25),
(62, 21, 24);

-- --------------------------------------------------------

--
-- 表的结构 `wj_question`
--

CREATE TABLE IF NOT EXISTS `wj_question` (
  `qid` int(11) NOT NULL AUTO_INCREMENT COMMENT '问题id',
  `parent_qid` int(11) NOT NULL COMMENT '上一题id',
  `sid` int(11) NOT NULL COMMENT '所属调查id',
  `type` varchar(10) CHARACTER SET utf8 NOT NULL COMMENT '问题类型',
  `title` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '问题标题',
  `subtitle` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '问题提示',
  `isNecessary` tinyint(1) NOT NULL DEFAULT '1' COMMENT '必答',
  `require` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '需要条件',
  PRIMARY KEY (`qid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- 转存表中的数据 `wj_question`
--

INSERT INTO `wj_question` (`qid`, `parent_qid`, `sid`, `type`, `title`, `subtitle`, `isNecessary`, `require`) VALUES
(9, 0, 3, 'checkbox', '题目还没写呢', '', 0, ''),
(10, 0, 27, 'checkbox', '多选题再这里', '', 0, ''),
(11, 0, 27, 'text', '填空题的的的', '', 0, ''),
(12, 0, 29, 'radio', '你朋友圈好友多吗', '', 1, ''),
(13, 0, 29, 'checkbox', '你平时转发什么内容', '', 1, ''),
(14, 0, 29, 'text', '给我们提一些建议吧', '随便说说你的意见', 0, ''),
(15, 0, 32, 'radio', '你信吗', '随意回答', 0, ''),
(16, 0, 32, 'checkbox', '你转发过什么内容', '你想想d ', 0, ''),
(17, 0, 34, 'checkbox', '大学生课多吗sad', '', 0, ''),
(18, 0, 34, 'radio', '你吃饭了吗', '', 1, ''),
(19, 0, 34, 'text', '你有什么意见反馈吗的', '', 0, ''),
(20, 0, 37, 'checkbox', '平时玩什么', '随便回答一下啦', 1, ''),
(21, 0, 37, 'radio', '平时睡多少小时', '根据实际情况啦', 1, ''),
(22, 0, 37, 'text', '有什么建议或者意见吗d', '随便回答一下了啊', 0, ''),
(24, 0, 37, 'checkbox', '你喜欢下面那些东西', '', 0, ''),
(26, 0, 41, 'radio', '今年多大了', '', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `wj_question_option`
--

CREATE TABLE IF NOT EXISTS `wj_question_option` (
  `oid` int(11) NOT NULL AUTO_INCREMENT,
  `qid` int(11) NOT NULL COMMENT '所处问题id',
  `isRadio` int(11) NOT NULL COMMENT '是否单选',
  `isCheckbox` int(11) NOT NULL COMMENT '是否多选',
  `isText` int(11) NOT NULL COMMENT '是否文本',
  `isMixed` int(11) NOT NULL COMMENT '是否复合',
  `content` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '选项内容',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`oid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

--
-- 转存表中的数据 `wj_question_option`
--

INSERT INTO `wj_question_option` (`oid`, `qid`, `isRadio`, `isCheckbox`, `isText`, `isMixed`, `content`, `ctime`) VALUES
(1, 1, 1, 0, 0, 1, '一碗', '0000-00-00 00:00:00'),
(2, 1, 1, 0, 0, 1, '两碗', '0000-00-00 00:00:00'),
(3, 1, 1, 0, 0, 1, '很多碗', '0000-00-00 00:00:00'),
(4, 2, 1, 0, 0, 1, '吃饭', '0000-00-00 00:00:00'),
(5, 2, 1, 0, 0, 1, '睡觉', '0000-00-00 00:00:00'),
(6, 2, 1, 0, 0, 1, '放屁', '0000-00-00 00:00:00'),
(7, 4, 0, 1, 0, 1, '不用填', '0000-00-00 00:00:00'),
(8, 5, 0, 1, 0, 1, '又来吗', '0000-00-00 00:00:00'),
(9, 6, 0, 1, 0, 1, '新题目噢', '0000-00-00 00:00:00'),
(10, 4, 0, 1, 0, 1, '不需要填任何东西', '0000-00-00 00:00:00'),
(11, 4, 0, 1, 0, 1, '随便填啦', '0000-00-00 00:00:00'),
(26, 7, 1, 0, 0, 0, '我的新的', '0000-00-00 00:00:00'),
(27, 7, 1, 0, 0, 0, 'asdf ', '0000-00-00 00:00:00'),
(28, 8, 0, 1, 0, 1, '新选项', '0000-00-00 00:00:00'),
(29, 8, 1, 0, 0, 1, '还没设置内容的', '0000-00-00 00:00:00'),
(30, 9, 0, 1, 0, 1, '新内容', '0000-00-00 00:00:00'),
(31, 9, 0, 1, 0, 1, '不错的内容', '0000-00-00 00:00:00'),
(32, 9, 0, 1, 0, 0, '多选这个', '0000-00-00 00:00:00'),
(34, 0, 0, 1, 0, 0, '是安抚', '0000-00-00 00:00:00'),
(35, 10, 0, 1, 0, 0, '阿斯顿', '0000-00-00 00:00:00'),
(36, 10, 0, 1, 0, 0, '唉撒旦发', '0000-00-00 00:00:00'),
(37, 12, 1, 0, 0, 0, '很多', '0000-00-00 00:00:00'),
(38, 12, 1, 0, 0, 0, '不是很多', '0000-00-00 00:00:00'),
(39, 12, 1, 0, 0, 0, '一般', '0000-00-00 00:00:00'),
(40, 13, 0, 1, 0, 0, '好看的', '0000-00-00 00:00:00'),
(41, 13, 0, 1, 0, 0, '好听的', '0000-00-00 00:00:00'),
(42, 13, 0, 1, 0, 0, '好玩的', '0000-00-00 00:00:00'),
(43, 13, 0, 1, 0, 1, '其他', '0000-00-00 00:00:00'),
(45, 15, 0, 1, 0, 0, '不信', '0000-00-00 00:00:00'),
(46, 16, 0, 1, 0, 1, '好吃得', '0000-00-00 00:00:00'),
(47, 16, 0, 1, 0, 0, '好玩的', '0000-00-00 00:00:00'),
(48, 16, 0, 1, 0, 0, '好看的', '0000-00-00 00:00:00'),
(49, 16, 0, 1, 0, 0, '谣言', '0000-00-00 00:00:00'),
(50, 17, 0, 1, 0, 1, '多', '0000-00-00 00:00:00'),
(51, 17, 0, 1, 0, 1, '不多', '0000-00-00 00:00:00'),
(52, 17, 0, 1, 0, 1, '一般', '0000-00-00 00:00:00'),
(53, 17, 0, 1, 0, 0, '不告诉你', '0000-00-00 00:00:00'),
(54, 18, 0, 1, 0, 1, '吃了', '0000-00-00 00:00:00'),
(55, 18, 0, 1, 0, 1, '还没', '0000-00-00 00:00:00'),
(56, 20, 0, 1, 0, 0, '吃饭', '0000-00-00 00:00:00'),
(57, 20, 0, 1, 0, 0, '睡觉', '0000-00-00 00:00:00'),
(58, 20, 0, 1, 0, 0, '打游戏', '0000-00-00 00:00:00'),
(59, 21, 1, 0, 0, 1, '1小时', '0000-00-00 00:00:00'),
(60, 21, 0, 1, 0, 1, '2小时', '0000-00-00 00:00:00'),
(61, 21, 0, 1, 0, 1, '7小时', '0000-00-00 00:00:00'),
(62, 21, 0, 1, 0, 1, '超过7小时', '0000-00-00 00:00:00'),
(63, 23, 0, 1, 0, 0, '吃饭', '0000-00-00 00:00:00'),
(64, 24, 0, 1, 0, 0, '吃饭', '0000-00-00 00:00:00'),
(65, 24, 0, 1, 0, 0, '睡觉', '0000-00-00 00:00:00'),
(66, 24, 0, 1, 0, 0, '打豆豆', '0000-00-00 00:00:00'),
(69, 26, 1, 0, 0, 1, '3', '0000-00-00 00:00:00'),
(70, 26, 1, 0, 0, 1, '4', '0000-00-00 00:00:00'),
(71, 26, 1, 0, 0, 1, '5', '0000-00-00 00:00:00'),
(72, 26, 1, 0, 0, 1, '6', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `wj_survey`
--

CREATE TABLE IF NOT EXISTS `wj_survey` (
  `sid` int(11) NOT NULL AUTO_INCREMENT COMMENT '调查id',
  `owner_uid` int(11) NOT NULL COMMENT '所有者',
  `isEncrypt` int(11) NOT NULL COMMENT '是否加密',
  `expires` int(11) NOT NULL COMMENT '有效天数，0为永久有效',
  `startdate` datetime NOT NULL COMMENT '开始时间',
  `title` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '标题',
  `subtitle` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '副标题',
  `type` int(11) NOT NULL COMMENT '问卷类型',
  `ctime` datetime NOT NULL COMMENT '创建时间',
  `participate` int(11) NOT NULL COMMENT '回答次数',
  `isdel` int(11) NOT NULL COMMENT '是否删除',
  `security_code` varchar(254) CHARACTER SET utf8 NOT NULL COMMENT '加密密码',
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

--
-- 转存表中的数据 `wj_survey`
--

INSERT INTO `wj_survey` (`sid`, `owner_uid`, `isEncrypt`, `expires`, `startdate`, `title`, `subtitle`, `type`, `ctime`, `participate`, `isdel`, `security_code`) VALUES
(37, 0, 0, 0, '0000-00-00 00:00:00', '关于我的大学生问卷调查', '', 0, '0000-00-00 00:00:00', 9, 0, ''),
(41, 0, 0, 0, '0000-00-00 00:00:00', 'nenenfadfdsa', 'adfsfas', 0, '0000-00-00 00:00:00', 3, 0, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
