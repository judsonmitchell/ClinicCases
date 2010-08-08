-- phpMyAdmin SQL Dump
-- version 3.3.2deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 08, 2010 at 12:40 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.2-1ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--

--

-- --------------------------------------------------------

--
-- Table structure for table `cm`
--

CREATE TABLE IF NOT EXISTS `cm` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clinic_id` int(255) NOT NULL DEFAULT '0',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `m_initial` varchar(5) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `date_open` varchar(100) NOT NULL DEFAULT '',
  `date_close` varchar(100) NOT NULL DEFAULT '',
  `case_type` varchar(100) NOT NULL DEFAULT '',
  `professor` varchar(100) NOT NULL DEFAULT '',
  `address1` varchar(200) NOT NULL DEFAULT '',
  `address2` varchar(200) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `phone1` varchar(15) NOT NULL DEFAULT '',
  `phone2` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `ssn` varchar(15) NOT NULL DEFAULT '',
  `dob` varchar(15) NOT NULL DEFAULT '',
  `age` varchar(10) NOT NULL DEFAULT '',
  `gender` varchar(10) NOT NULL DEFAULT '',
  `race` varchar(10) NOT NULL DEFAULT '',
  `income` int(50) NOT NULL,
  `per` varchar(15) NOT NULL,
  `judge` varchar(200) NOT NULL DEFAULT '',
  `pl_or_def` varchar(100) NOT NULL DEFAULT '',
  `court` varchar(200) NOT NULL DEFAULT '',
  `section` varchar(100) NOT NULL DEFAULT '',
  `ct_case_no` varchar(100) NOT NULL DEFAULT '',
  `case_name` varchar(250) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `type1` varchar(100) NOT NULL DEFAULT '',
  `type2` varchar(100) NOT NULL DEFAULT '',
  `dispo` varchar(100) NOT NULL DEFAULT '',
  `close_code` varchar(10) NOT NULL DEFAULT '',
  `close_notes` text NOT NULL,
  `referral` varchar(100) NOT NULL DEFAULT '',
  `opened_by` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1551 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_adverse_parties`
--

CREATE TABLE IF NOT EXISTS `cm_adverse_parties` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `clinic_id` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='used to check for conflicts' AUTO_INCREMENT=300 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_board`
--

CREATE TABLE IF NOT EXISTS `cm_board` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `created_by` varchar(100) NOT NULL DEFAULT '',
  `last_modified_by` varchar(100) NOT NULL DEFAULT '',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_modified` datetime DEFAULT NULL,
  `attachment` text NOT NULL,
  `locked` varchar(10) NOT NULL DEFAULT '',
  `is_form` varchar(10) NOT NULL,
  `hidden` varchar(10) NOT NULL DEFAULT '',
  `is_comment` varchar(10) NOT NULL DEFAULT '',
  `orig_post_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=314 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_bugs`
--

CREATE TABLE IF NOT EXISTS `cm_bugs` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `problem` varchar(200) NOT NULL DEFAULT '',
  `code` varchar(200) NOT NULL DEFAULT '',
  `app` varchar(100) NOT NULL DEFAULT '',
  `version` varchar(200) NOT NULL DEFAULT '',
  `height` varchar(200) NOT NULL DEFAULT '',
  `width` varchar(200) NOT NULL DEFAULT '',
  `platform` varchar(200) NOT NULL DEFAULT '',
  `agent` varchar(200) NOT NULL DEFAULT '',
  `page` varchar(200) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `java` varchar(100) NOT NULL DEFAULT '',
  `env_session` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=201 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_cases_students`
--

CREATE TABLE IF NOT EXISTS `cm_cases_students` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `case_id` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `date_assigned` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_removed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Determine which students assigned to a case' AUTO_INCREMENT=1362 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_case_notes`
--

CREATE TABLE IF NOT EXISTS `cm_case_notes` (
  `id` int(90) NOT NULL AUTO_INCREMENT,
  `case_id` varchar(100) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `time` float NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT '',
  `prof` varchar(100) NOT NULL DEFAULT '',
  `datestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38479 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_case_types`
--

CREATE TABLE IF NOT EXISTS `cm_case_types` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_contacts`
--

CREATE TABLE IF NOT EXISTS `cm_contacts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL DEFAULT '',
  `address` varchar(200) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` char(2) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `phone1` varchar(20) NOT NULL DEFAULT '',
  `phone2` varchar(20) NOT NULL DEFAULT '',
  `fax` varchar(15) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `assoc_case` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1102 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_contacts_types`
--

CREATE TABLE IF NOT EXISTS `cm_contacts_types` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_courts`
--

CREATE TABLE IF NOT EXISTS `cm_courts` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `court` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_dispos`
--

CREATE TABLE IF NOT EXISTS `cm_dispos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `dispo` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_documents`
--

CREATE TABLE IF NOT EXISTS `cm_documents` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(200) NOT NULL DEFAULT '',
  `folder` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `case_id` varchar(100) NOT NULL DEFAULT '',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11397 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_events`
--

CREATE TABLE IF NOT EXISTS `cm_events` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `case_id` varchar(100) NOT NULL DEFAULT '',
  `set_by` varchar(100) NOT NULL DEFAULT '',
  `task` varchar(225) NOT NULL DEFAULT '',
  `date_set` date NOT NULL DEFAULT '0000-00-00',
  `date_due` date NOT NULL DEFAULT '0000-00-00',
  `status` varchar(100) NOT NULL DEFAULT '',
  `prof` varchar(200) NOT NULL DEFAULT '',
  `temp_id` varchar(100) NOT NULL DEFAULT '',
  `archived` varchar(10) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1350 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_events_responsibles`
--

CREATE TABLE IF NOT EXISTS `cm_events_responsibles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Linked to cm_events; who is responsible for each event' AUTO_INCREMENT=3108 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_journals`
--

CREATE TABLE IF NOT EXISTS `cm_journals` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `professor` varchar(150) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `temp_id` varchar(100) NOT NULL DEFAULT '',
  `deleted` varchar(10) NOT NULL DEFAULT '',
  `read` varchar(10) NOT NULL DEFAULT '',
  `commented` varchar(10) NOT NULL DEFAULT '',
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=41325 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_logs`
--

CREATE TABLE IF NOT EXISTS `cm_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(20) NOT NULL DEFAULT '',
  `session_id` varchar(200) NOT NULL DEFAULT '',
  `last_ping` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29480 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_messages`
--

CREATE TABLE IF NOT EXISTS `cm_messages` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `thread_id` varchar(100) NOT NULL DEFAULT '',
  `to` text NOT NULL,
  `from` varchar(100) NOT NULL DEFAULT '',
  `ccs` text,
  `subject` varchar(100) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `assoc_case` varchar(100) NOT NULL DEFAULT '',
  `time_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `read` text NOT NULL,
  `archive` text NOT NULL,
  `starred` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9447 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_referral`
--

CREATE TABLE IF NOT EXISTS `cm_referral` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `referral` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_users`
--

CREATE TABLE IF NOT EXISTS `cm_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `mobile_phone` varchar(15) NOT NULL DEFAULT '',
  `office_phone` varchar(25) NOT NULL DEFAULT '',
  `home_phone` varchar(15) NOT NULL DEFAULT '',
  `class` varchar(20) NOT NULL DEFAULT '',
  `username` varchar(25) NOT NULL DEFAULT '',
  `password` varchar(40) NOT NULL DEFAULT '',
  `assigned_prof` varchar(100) NOT NULL DEFAULT '',
  `picture_url` varchar(200) NOT NULL DEFAULT 'people/no_picture.png',
  `timezone_offset` varchar(5) NOT NULL DEFAULT '1',
  `status` varchar(100) NOT NULL DEFAULT 'inactive',
  `new` varchar(20) NOT NULL DEFAULT '',
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pref_case` varchar(10) NOT NULL DEFAULT 'on',
  `pref_journal` varchar(10) NOT NULL DEFAULT '',
  `pref_case_prof` varchar(10) NOT NULL DEFAULT 'on' COMMENT 'does professor work on cases',
  `evals` text NOT NULL,
  `private_key` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=386 ;

--
-- Dumping data for table `cm_users`
--

INSERT INTO `cm_users` (`id`, `first_name`, `last_name`, `email`, `mobile_phone`, `office_phone`, `home_phone`, `class`, `username`, `password`, `assigned_prof`, `picture_url`, `timezone_offset`, `status`, `new`, `date_created`, `pref_case`, `pref_journal`, `pref_case_prof`, `evals`, `private_key`) VALUES
(386, 'Admin', 'Test', '', '', '', '', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'people/no_picture.png', '1', 'active', '', '2010-08-08 13:44:28', 'on', '', 'on', '', '');
