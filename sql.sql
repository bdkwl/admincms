CREATE TABLE `media` (
  `media_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL COMMENT '用途:avatar|upload|system',
  `url` varchar(100) NOT NULL COMMENT '文件路径',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`media_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='媒体表';

CREATE TABLE `member_info` (
  `info_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(10) unsigned NOT NULL COMMENT 'member主键id',
  `truename` varchar(10) DEFAULT NULL COMMENT '真实姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `idcard_type` tinyint(4) DEFAULT NULL COMMENT '证件类型',
  `idcard_no` varchar(30) DEFAULT NULL COMMENT '证件号码',
  `sex` tinyint(4) DEFAULT NULL COMMENT '性别:1男|2女',
  `avatar` int(11) NOT NULL DEFAULT '1' COMMENT '头像media_id|1:默认空头像',
  `modified_at` int(11) NOT NULL COMMENT '最后修改时间',
  `buy_num` INT DEFAULT 0  NOT NULL   COMMENT '购买产品次数|默认0次',
  PRIMARY KEY (`info_id`),
  UNIQUE KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员信息表';

CREATE TABLE `member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '加密密码',
  `wx_openid` varchar(50) DEFAULT NULL COMMENT '微信openid',
  `stat` tinyint(4) NOT NULL DEFAULT '1' COMMENT '用户状态1:正常|0:被禁用',
  `register_at` int(11) NOT NULL COMMENT '注册时间',
  `member_level` int(11) NOT NULL DEFAULT '0' COMMENT '会员等级:0路人会员|1:正式会员|2:vip会员',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wx_openid` (`wx_openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员主表';

CREATE TABLE `log_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `t` int(11) NOT NULL COMMENT '时间戳',
  `ip` varchar(20) NOT NULL COMMENT '登录ip',
  `log` varchar(255) NOT NULL COMMENT '日志内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员日志表';

CREATE TABLE `user` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户id',
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(50) NOT NULL COMMENT '登录密码',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户角色组id',
  `user_stats` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态:1正常|0禁用',
  `loginnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '登录次数',
  `lastip` varchar(20) NOT NULL COMMENT '最后登录ip',
  `lasttime` int(11) NOT NULL COMMENT '最后登录时间',
  `truename` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `email` varchar(20) DEFAULT NULL COMMENT '邮箱',
  `wx_openid` varchar(50) DEFAULT NULL COMMENT '微信openid',
  `avatar` int(11) NOT NULL DEFAULT '1' COMMENT '头像media_id|1:默认空头像',
  `updated_at` int(11) NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 comment='用户表';

CREATE TABLE `log_user_login` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `username` varchar(20) NOT NULL COMMENT '登录用户',
  `logintime` datetime NOT NULL COMMENT '登录时间',
  `loginip` varchar(20) NOT NULL COMMENT '登录ip',
  `stats` tinyint(4) NOT NULL DEFAULT '0' COMMENT '登录状态:1登录成功|0登录失败',
  `ua` varchar(100) NOT NULL COMMENT 'HTTP_USER_AGENT',
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录日志表';

CREATE TABLE `loancms`.`log_user_do`(
  `logid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志id',
  `ip` VARCHAR(20) NOT NULL COMMENT '操作ip',
  `time` DATETIME NOT NULL COMMENT '操作时间',
  `username` VARCHAR(30) NOT NULL COMMENT '操作者',
  `doing` VARCHAR(100) NOT NULL COMMENT '操作事件内容',
  PRIMARY KEY (`logid`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci
  COMMENT='用户操作日志表';

CREATE TABLE `loancms`.`role`(
  `roleid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户组id',
  `rolename` VARCHAR(20) NOT NULL COMMENT '用户组名称',
  `stats` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态:1正常|0禁用',
  `created_at` INT NOT NULL COMMENT '创建时间戳',
  `updated_at` INT NOT NULL COMMENT '更新时间戳',
  PRIMARY KEY (`roleid`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci
  COMMENT='用户组表';

CREATE TABLE `access` (
  `accessid` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限id',
  `accessname` varchar(20) NOT NULL COMMENT '权限名',
  `title` varchar(50) DEFAULT NULL,
  `stats` tinyint(4) DEFAULT '0' COMMENT '状态:1正常|0禁用',
  PRIMARY KEY (`accessid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限节点表';

CREATE TABLE `loancms`.`role_access`(
  `role_id` INT UNSIGNED NOT NULL COMMENT '用户组id',
  `access_id` INT UNSIGNED NOT NULL COMMENT '权限id',
  `created_at` INT NOT NULL COMMENT '创建时间戳',
  `updated_at` INT NOT NULL COMMENT '更新时间戳',
  `stats` TINYINT NOT NULL DEFAULT 1 COMMENT '状态:1正常|0禁用'
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci
  COMMENT='用户组-权限关联表';

CREATE TABLE `loancms`.`web_config`(
  `configid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置id',
  `title` VARCHAR(20) NOT NULL COMMENT '配置标题',
  `name` VARCHAR(50) NOT NULL COMMENT '配置名称',
  `value` VARCHAR(255) NOT NULL COMMENT '配置值',
  `tips` VARCHAR(255) COMMENT '配置说明',
  `created_at` INT NOT NULL COMMENT '创建时间戳',
  `updated_at` INT NOT NULL COMMENT '更新时间戳',
  `stats` TINYINT DEFAULT 0 COMMENT '状态:1正常|0禁用',
  PRIMARY KEY (`configid`)
) ENGINE=INNODB CHARSET=utf8 COLLATE=utf8_general_ci
  COMMENT='系统配置表';
