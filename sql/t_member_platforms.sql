CREATE TABLE `t_member_platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `t_member_id` int(11) NOT NULL COMMENT '会員ID',
  `m_platform_id` int(11) NOT NULL COMMENT 'プラットフォーム',
  `pfid` varchar(100) NOT NULL DEFAULT '' COMMENT 'プラットフォームのID',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`id`),
  KEY `t_member_id` (`t_member_id`),
  KEY `platform` (`m_platform_id`,`pfid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
