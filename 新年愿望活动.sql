#每人2次 80%成功  时间显示  话语  排名-成功次数


#新年愿望 活动 主表
DROP TABLE IF EXISTS `cdb_activity_wish`;
CREATE TABLE `cdb_activity_wish` (
  `wish_id`   INT unsigned NOT NULL AUTO_INCREMENT,
  `uid`       INT unsigned NOT NULL COMMENT '关联user表的uid',
  `wish_type` tinyint(1) NOT NULL COMMENT '愿望类型',
  `wish_name` varchar(50) NOT NULL COMMENT '愿望名称',
  `ranking_value` INT  NOT NULL DEFAULT 0 COMMENT '点击帮助的排名值',
  `openid`    VARCHAR(50) NOT NULL COMMENT '微信openid',
  `add_time`  int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`wish_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#愿望之帮助记录
DROP TABLE IF EXISTS `cdb_activity_wish_log`;
CREATE TABLE `cdb_activity_wish_log` (
  `log_id`   INT unsigned NOT NULL AUTO_INCREMENT,
  `wish_id`  INT unsigned NOT NULL COMMENT '关联wish表的wish_id',
  `weixin_name` varchar(50) NOT NULL COMMENT '帮助者微信昵称',
  `weixin_avatar` varchar(300) NOT NULL COMMENT '帮助者微信头像',
  `openid`  varchar(50) NOT NULL COMMENT '帮助者微信openid',
  `description` varchar(300)  NOT NULL DEFAULT 0 COMMENT '描述性话语',
  `add_time`  int(10) unsigned NOT NULL DEFAULT '0' COMMENT '添加时间',
  `remarks` varchar(300)  NOT NULL DEFAULT 0 COMMENT '失败成功的记录描述',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '失败成功标识 1 成功 0 失败 用于统计每个帮助者帮助的次数',
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
#元宵活动新增
ALTER TABLE cdb_activity_wish ADD weixin_name varchar(50) DEFAULT '' COMMENT '微信昵称';
ALTER TABLE cdb_activity_wish ADD weixin_avatar varchar(300) DEFAULT ''  COMMENT '微信头像';