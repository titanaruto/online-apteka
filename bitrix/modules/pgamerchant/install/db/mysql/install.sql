CREATE TABLE IF NOT EXISTS `b_pga_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `session_id` varchar(20) DEFAULT NULL,
  `action_to` varchar(100) NOT NULL,
  `action_name` varchar(255) NOT NULL,
  `url` text,
  `request_data` text,
  `response_data` text,
  `registered_ts` int(11) NOT NULL,
  `ip` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
);