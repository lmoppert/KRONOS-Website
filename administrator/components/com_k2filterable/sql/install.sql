CREATE TABLE IF NOT EXISTS `#__k2filterable_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `ordering` tinyint(4) NOT NULL DEFAULT '0',
  `extrafields` text NOT NULL,
  `fields_type` tinyint(1) NOT NULL,
  `descends` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;