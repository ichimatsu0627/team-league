CREATE TABLE `t_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `description` text NOT NULL DEFAULT '',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '作成日時',
  `modified` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日時',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
