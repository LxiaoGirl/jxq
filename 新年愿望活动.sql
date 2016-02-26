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

ALTER TABLE cdb_activity_wish ADD is_prize int DEFAULT 0  COMMENT '是否领奖';
ALTER TABLE cdb_activity_wish ADD prize_level int DEFAULT 0  COMMENT '几等奖';
ALTER TABLE cdb_activity_wish ADD prize int DEFAULT 0  COMMENT '奖';
ALTER TABLE cdb_activity_wish ADD prize_time DECIMAL DEFAULT 0  COMMENT '领奖时间';

#领奖流程说明
#1.活动时间结束 在详情页面出现领奖按钮【都是在是 自己的桌子的情况】
#2.已领奖的 点击显示中奖名单
#3.未领过奖 点击领奖 出现登录弹窗【注册、忘记密码=》重复12步】  登录成功根据排名 显示【一等奖、二等、、幸运奖】样式的拆红包弹窗
#4.点击拆红包 验证必要信息 修改wish表uid prize等字段标识已领取 如果有奖并发放对应金额的红包 如果没将 不发红包
#5.根据中奖情况 已中奖 跳转红包界面  未中奖 显示中奖名单
#注：拆红包验证逻辑
#【根据wish_id uid openid 验证wish_id信息是否存在 是否已领奖 活动是否已结束 openid是否对应等
#验证用户信息是否存在 实名信息等、根据排名获取奖品信息 幸运奖验证剩余红包个数 修改wish记录 验证改用户此类红包发送情况进行红包发送 添加红包记录】
#注：奖品情况：1-5 200元现金红包 6-15 100元现金红包 16-35 20元现金红包 剩下用户20个1-20元的随机红包 先领先得。