CREATE TABLE `t_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `login_id` varchar(20) NOT NULL DEFAULT '' COMMENT 'ユーザー指定ID',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `email` varchar(100) NOT NULL COMMENT 'Eメール',
  `password` varchar(200) NOT NULL DEFAULT '' COMMENT 'パスワード',
  `twitter` varchar(50) NOT NULL DEFAULT '' COMMENT 'TwitterID',
  `discord` varchar(50) NOT NULL DEFAULT '' COMMENT 'Discord tag',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `ban` tinyint(1) NOT NULL DEFAULT '0',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  `created` datetime NOT NULL COMMENT '作成日時',
  `modified` datetime NOT NULL COMMENT '更新日時',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `user_id` (`login_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
