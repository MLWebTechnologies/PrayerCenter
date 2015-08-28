CREATE TABLE IF NOT EXISTS `#__prayercenter` (
id INT(10) unsigned NOT NULL AUTO_INCREMENT,
requesterid INT(11) NOT NULL default '0',
requester varchar(50) NOT NULL default '',
request text NOT NULL,
date date NOT NULL default '0000-00-00',
time time NOT NULL default '00:00:00',
publishstate smallint(1) NOT NULL default '0',
archivestate smallint(1) NOT NULL default '0',
displaystate smallint(1) NOT NULL default '0',
sendto datetime NOT NULL default '0000-00-00 00:00:00',
email varchar(50) NOT NULL default '',
adminsendto datetime NOT NULL default '0000-00-00 00:00:00',
checked_out_time datetime NOT NULL default '0000-00-00 00:00:00',
checked_out int(11) NOT NULL default '0',
sessionid varchar(50) NOT NULL default '',
title varchar(100) NOT NULL default '',
topic int(11) NOT NULL default '0',
hits int(11) NOT NULL default '0',
PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__prayercenter_subscribe` (
id INT(10) unsigned NOT NULL AUTO_INCREMENT,
email varchar(50) NOT NULL default '',
date date NOT NULL default '0000-00-00',
approved smallint(1) NOT NULL default '0',
sessionid varchar(50) NOT NULL default '',
PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `#__prayercenter_devotions` (
id INT(10) unsigned NOT NULL AUTO_INCREMENT,
name varchar(200) NOT NULL default '',
feed varchar(200) NOT NULL default '',
published smallint(1) NOT NULL default '0',
catid INT(11) NOT NULL default '0',
checked_out_time datetime NOT NULL default '0000-00-00 00:00:00',
checked_out int(11) NOT NULL default '0',
ordering int(11) NOT NULL default '0',
PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT IGNORE INTO `#__prayercenter_devotions` (`id`, `name`, `feed`, `published`, `catid`, `checked_out`, `checked_out_time`, `ordering`) VALUES
(1, 'Our Daily Bread Daily Devotional', 'http://www.rbc.org/rss.ashx?id=50398', 1, 0, 0, '0000-00-00 00:00:00', 1),
(2, 'My Utmost for His Highest Daily Devotional', 'http://www.rbc.org/myUtmost.rss', 1, 0, 0, '0000-00-00 00:00:00', 2);
CREATE TABLE IF NOT EXISTS `#__prayercenter_links` (
id INT(10) unsigned NOT NULL AUTO_INCREMENT,
name varchar(200) NOT NULL default '',
url varchar(200) NOT NULL default '',
alias varchar(200) NOT NULL default '',
descrip text NOT NULL,
published smallint(1) NOT NULL default '0',
catid INT(11) NOT NULL,
checked_out_time datetime NOT NULL default '0000-00-00 00:00:00',
checked_out int(11) NOT NULL default '0',
ordering int(11) NOT NULL default '0',
PRIMARY KEY (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT IGNORE INTO `#__prayercenter_links` (`id`, `name`, `url`, `alias`, `descrip`, `published`, `catid`, `checked_out`, `checked_out_time`, `ordering`) VALUES
(1, 'Max Lucado', 'http://www.maxlucado.com', 'Max Lucado', 'UpWords: The Teaching Ministry of Max Lucado', 1, 0, 0, '0000-00-00 00:00:00', 1),
(2, 'Upper Room', 'http://www.upperroom.org', 'Upper Room', 'Upper Room Ministries', 1, 0, 0, '0000-00-00 00:00:00', 2),
(3, 'Samaritan\'s Purse', 'http://www.samaritanspurse.org', 'Samaritan\'s Purse', 'Samaritan\'s Purse International Relief', 1, 0, 0, '0000-00-00 00:00:00', 3),
(4, 'Heifer International', 'http://www.heifer.org', 'Heifer International', 'Heifer International, Ending Hunger; Caring for the Earth', 1, 0, 0, '0000-00-00 00:00:00', 4);