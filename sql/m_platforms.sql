CREATE TABLE `m_platforms` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) NOT NULL COMMENT '名前',
  `id_name` varchar(100) NOT NULL COMMENT 'プラットフォームのIDの名前',
  `del_flg` tinyint(1) NOT NULL DEFAULT '0' COMMENT '削除フラグ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `m_platforms` (`id`, `name`, `id_name`, `del_flg`)
VALUES
	(1, 'Steam', 'STEAM ID', 0),
	(2, 'PS4', 'PSID', 0),
	(3, 'XBOX', 'GAMER TAG', 0),
	(4, 'Nintendo Switch', 'NINTENDO ACCOUNT', 0);
