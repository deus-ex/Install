--
-- Table structure for table `{db-prefix}customers`
--

CREATE TABLE IF NOT EXISTS `{db-prefix}customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `company` varchar(50) NOT NULL,
  `addr_1` varchar(100) NOT NULL,
  `addr_2` varchar(100) NOT NULL,
  `city` varchar(20) NOT NULL,
  `state` varchar(20) NOT NULL,
  `country` varchar(30) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `website` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `additional_info` text NOT NULL,
  `registered` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE={db-engine} DEFAULT CHARSET={db-charset} COLLATE={db-collation} AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{db-prefix}products`
--

CREATE TABLE IF NOT EXISTS `{db-prefix}products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `version` varchar(10) NOT NULL,
  `type` enum('Demo','Full') NOT NULL,
  `url` varchar(200) NOT NULL,
  `key` varchar(255) NOT NULL,
  `status` enum('0','1') NOT NULL,
  `added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE={db-engine} DEFAULT CHARSET={db-charset} COLLATE = {db-collation} AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `{db-prefix}prices`
--

CREATE TABLE IF NOT EXISTS `{db-prefix}prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `desc` text NOT NULL,
  `license_type` varchar(20) NOT NULL,
  `amount` float NOT NULL,
  `currency` varchar(4) NOT NULL,
  `duration` int(11) NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE={db-engine} DEFAULT CHARSET={db-charset} COLLATE = {db-collation} AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gfk_users`
--

CREATE TABLE IF NOT EXISTS `{db-prefix}users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session` varchar(255) NOT NULL,
  `name` varchar(60) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `old_pass` varchar(60) NOT NULL,
  `fname` varchar(60) NOT NULL,
  `lname` varchar(60) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `access_level` enum('1','2','3','4','5') NOT NULL DEFAULT '2',
  `auth_code` varchar(200) NOT NULL,
  `temp_pass` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `baseurl` varchar(200) NOT NULL,
  `lang` varchar(6) NOT NULL DEFAULT 'en-us',
  `page_list` int(11) NOT NULL DEFAULT '10',
  `last_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_online` enum('0','1') NOT NULL DEFAULT '0',
  `is_active` enum('0','1','2') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `is_online` (`is_online`),
  KEY `is_active` (`is_active`),
  KEY `type` (`access_level`)
) ENGINE={db-engine}  DEFAULT CHARSET={db-charset} COLLATE = {db-collation} AUTO_INCREMENT=1 ;