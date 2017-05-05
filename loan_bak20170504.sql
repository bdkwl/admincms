/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.53 : Database - loancms
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`loancms` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `loancms`;

/*Table structure for table `access` */

DROP TABLE IF EXISTS `access`;

CREATE TABLE `access` (
  `accessid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `accessname` varchar(50) DEFAULT NULL COMMENT '权限名(控制器/方法)',
  `title` varchar(50) DEFAULT NULL COMMENT '权限作用',
  `stats` tinyint(4) DEFAULT '1' COMMENT '状态:1正常|0禁用',
  `pid` int(10) unsigned DEFAULT NULL COMMENT '上级权限id',
  PRIMARY KEY (`accessid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='权限节点表';

/*Table structure for table `log_member` */

DROP TABLE IF EXISTS `log_member`;

CREATE TABLE `log_member` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `time` datetime NOT NULL COMMENT '操作时间',
  `ip` varchar(20) NOT NULL COMMENT '操作ip',
  `log` varchar(255) NOT NULL COMMENT '日志内容',
  `ua` varchar(150) NOT NULL COMMENT 'HTTP_USER_AGENT',
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员登录表';

/*Table structure for table `log_user_do` */

DROP TABLE IF EXISTS `log_user_do`;

CREATE TABLE `log_user_do` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `ip` varchar(20) NOT NULL COMMENT '操作ip',
  `time` datetime NOT NULL COMMENT '操作时间',
  `username` varchar(30) NOT NULL COMMENT '操作者',
  `doing` varchar(255) NOT NULL COMMENT '操作内容',
  `action` varchar(50) NOT NULL COMMENT '操作定位',
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8 COMMENT='用户操作日志表';

/*Table structure for table `log_user_login` */

DROP TABLE IF EXISTS `log_user_login`;

CREATE TABLE `log_user_login` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `username` varchar(20) NOT NULL COMMENT '登录用户',
  `logintime` datetime NOT NULL COMMENT '登录时间',
  `loginip` varchar(20) NOT NULL COMMENT '登录ip',
  `stats` tinyint(4) NOT NULL DEFAULT '0' COMMENT '登录状态:1登录成功|0登录失败',
  `ua` varchar(150) NOT NULL COMMENT 'HTTP_USER_AGENT',
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COMMENT='用户登录日志表';

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `mediaid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL COMMENT '用途:avatar|upload|system|qrcode',
  `realpath` varchar(100) NOT NULL COMMENT '文件路径',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `ext` char(4) NOT NULL DEFAULT '' COMMENT '文件类型',
  `size` int(11) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) DEFAULT NULL COMMENT '文件md5编码',
  `sha1` char(40) DEFAULT NULL COMMENT '文件sha1编码',
  `stats` tinyint(1) DEFAULT '1' COMMENT '状态:1正常|0禁用',
  PRIMARY KEY (`mediaid`)
) ENGINE=InnoDB AUTO_INCREMENT=114 DEFAULT CHARSET=utf8 COMMENT='媒体表';

/*Table structure for table `member` */

DROP TABLE IF EXISTS `member`;

CREATE TABLE `member` (
  `memberid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '加密密码',
  `wx_openid` varchar(50) DEFAULT NULL COMMENT '微信openid',
  `stat` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态1:正常|0:被禁用',
  `register_at` int(11) NOT NULL COMMENT '注册时间',
  `member_level` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级:0路人会员|1:正式会员|2:vip会员',
  PRIMARY KEY (`memberid`),
  UNIQUE KEY `wx_openid` (`wx_openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员主表';

/*Table structure for table `member_info` */

DROP TABLE IF EXISTS `member_info`;

CREATE TABLE `member_info` (
  `infoid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL COMMENT 'member表id',
  `truename` varchar(10) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `idcard_type` tinyint(4) DEFAULT NULL COMMENT '证件类型',
  `idcard_no` varchar(30) DEFAULT NULL COMMENT '证件号码',
  `sex` tinyint(4) DEFAULT NULL COMMENT '性别:1男|2女',
  `avatar` int(11) NOT NULL DEFAULT '1' COMMENT '头像media_id|1:默认空头像',
  `modified_at` int(11) NOT NULL COMMENT '最后修改时间',
  `buy_num` int(11) NOT NULL DEFAULT '0' COMMENT '购买产品次数|默认0次',
  PRIMARY KEY (`infoid`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员信息表';

/*Table structure for table `role` */

DROP TABLE IF EXISTS `role`;

CREATE TABLE `role` (
  `roleid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户组id',
  `rolename` varchar(20) NOT NULL COMMENT '用户组名称',
  `stats` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1正常|0禁用',
  `created_at` int(11) NOT NULL COMMENT '创建时间戳',
  `updated_at` int(11) NOT NULL COMMENT '更新时间戳',
  PRIMARY KEY (`roleid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='用户组表';

/*Table structure for table `role_access` */

DROP TABLE IF EXISTS `role_access`;

CREATE TABLE `role_access` (
  `role_id` int(10) unsigned NOT NULL COMMENT '用户组id',
  `access_id` int(10) unsigned NOT NULL COMMENT '权限id',
  `created_at` int(11) NOT NULL COMMENT '创建时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户组-权限关联表';

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '登录密码',
  `role_id` int(10) unsigned NOT NULL COMMENT '用户角色组id|0系统管理员',
  `user_stats` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1正常|0禁用',
  `loginnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `lastip` varchar(20) NOT NULL COMMENT '最后登录ip',
  `lasttime` int(11) NOT NULL COMMENT '最后登录时间',
  `truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `wx_openid` varchar(50) DEFAULT NULL COMMENT '微信openid',
  `updated_at` int(11) NOT NULL COMMENT '最后更新时间',
  `avatar` int(11) NOT NULL DEFAULT '1' COMMENT '头像media_id|1:默认空头像',
  `phone` varchar(15) NOT NULL COMMENT '手机号',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='管理用户表';

/*Table structure for table `web_config` */

DROP TABLE IF EXISTS `web_config`;

CREATE TABLE `web_config` (
  `configid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '配置id',
  `title` varchar(20) NOT NULL COMMENT '配置标题',
  `name` varchar(50) NOT NULL COMMENT '配置名称',
  `value` varchar(255) NOT NULL COMMENT '配置值',
  `tips` varchar(255) DEFAULT NULL COMMENT '配置说明',
  `created_at` int(11) NOT NULL COMMENT '创建时间戳',
  `updated_at` int(11) NOT NULL COMMENT '更新时间戳',
  `stats` tinyint(4) DEFAULT '0' COMMENT '状态:1正常|0禁用',
  `group` int(11) NOT NULL DEFAULT '1' COMMENT '配置分组:1网站配置|2:用户配置|3:会员配置|4:业务配置',
  PRIMARY KEY (`configid`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
