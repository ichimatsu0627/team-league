CREATE TABLE `t_team_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `t_team_id` int(11) NOT NULL COMMENT 'チームID',
  `t_member_id` int(11) NOT NULL COMMENT '会員ID',
  `role` tinyint(4) NOT NULL DEFAULT '0' COMMENT '役職',
  `kicked` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'キックフラグ',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`id`),
  KEY `t_member_id` (`t_member_id`),
  KEY `t_team_id` (`t_team_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
