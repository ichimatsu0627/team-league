CREATE TABLE `t_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` varchar(20) NOT NULL COMMENT 'ユーザー指定ID',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `email` varchar(100) NOT NULL COMMENT 'Eメール',
  `password` varchar(200) NOT NULL DEFAULT '' COMMENT 'パスワード',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `ban` tinyint(1) NOT NULL DEFAULT '0',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
