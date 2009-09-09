-- phpMyAdmin SQL Dump
-- version 2.11.9.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 09, 2009 at 04:01 AM
-- Server version: 4.1.25
-- PHP Version: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `a0028019`
--

-- --------------------------------------------------------

--
-- Table structure for table `cm`
--

CREATE TABLE `cm` (
  `id` int(10) NOT NULL auto_increment,
  `clinic_id` int(255) NOT NULL default '0',
  `first_name` varchar(100) NOT NULL default '',
  `m_initial` varchar(5) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `date_open` varchar(100) NOT NULL default '',
  `date_close` varchar(100) NOT NULL default '',
  `case_type` varchar(100) NOT NULL default '',
  `professor` varchar(100) NOT NULL default '',
  `professor2` varchar(100) NOT NULL default '',
  `address1` varchar(200) NOT NULL default '',
  `address2` varchar(200) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `state` varchar(100) NOT NULL default '',
  `zip` varchar(10) NOT NULL default '',
  `phone1` varchar(15) NOT NULL default '',
  `phone2` varchar(15) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `ssn` varchar(15) NOT NULL default '',
  `dob` varchar(15) NOT NULL default '',
  `age` varchar(10) NOT NULL default '',
  `gender` varchar(10) NOT NULL default '',
  `race` varchar(10) NOT NULL default '',
  `judge` varchar(200) NOT NULL default '',
  `pl_or_def` varchar(100) NOT NULL default '',
  `court` varchar(200) NOT NULL default '',
  `section` varchar(100) NOT NULL default '',
  `ct_case_no` varchar(100) NOT NULL default '',
  `case_name` varchar(250) NOT NULL default '',
  `notes` text NOT NULL,
  `type1` varchar(100) NOT NULL default '',
  `type2` varchar(100) NOT NULL default '',
  `dispo` varchar(100) NOT NULL default '',
  `close_code` varchar(10) NOT NULL default '',
  `close_notes` text NOT NULL,
  `referral` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_adverse_parties`
--

CREATE TABLE `cm_adverse_parties` (
  `id` int(7) NOT NULL auto_increment,
  `clinic_id` varchar(100) NOT NULL default '',
  `name` varchar(250) NOT NULL default '',
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='used to check for conflicts';

-- --------------------------------------------------------

--
-- Table structure for table `cm_board`
--

CREATE TABLE `cm_board` (
  `id` int(10) NOT NULL auto_increment,
  `title` varchar(150) NOT NULL default '',
  `body` text NOT NULL,
  `created_by` varchar(100) NOT NULL default '',
  `last_modified_by` varchar(100) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_modified` datetime default NULL,
  `attachment` text NOT NULL,
  `locked` varchar(10) NOT NULL default '',
  `hidden` varchar(10) NOT NULL default '',
  `is_comment` varchar(10) NOT NULL default '',
  `orig_post_id` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_bugs`
--

CREATE TABLE `cm_bugs` (
  `id` int(100) NOT NULL auto_increment,
  `date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `problem` varchar(200) NOT NULL default '',
  `code` varchar(200) NOT NULL default '',
  `app` varchar(100) NOT NULL default '',
  `version` varchar(200) NOT NULL default '',
  `height` varchar(200) NOT NULL default '',
  `width` varchar(200) NOT NULL default '',
  `platform` varchar(200) NOT NULL default '',
  `agent` varchar(200) NOT NULL default '',
  `page` varchar(200) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `java` varchar(100) NOT NULL default '',
  `env_session` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_cases_students`
--

CREATE TABLE `cm_cases_students` (
  `id` int(100) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `case_id` varchar(100) NOT NULL default '',
  `status` varchar(50) NOT NULL default '',
  `date_assigned` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `date_removed` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Determine which students assigned to a case';

-- --------------------------------------------------------

--
-- Table structure for table `cm_case_notes`
--

CREATE TABLE `cm_case_notes` (
  `id` int(90) NOT NULL auto_increment,
  `case_id` varchar(100) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `time` float NOT NULL default '0',
  `description` text NOT NULL,
  `username` varchar(100) NOT NULL default '',
  `prof` varchar(100) NOT NULL default '',
  `datestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_case_types`
--

CREATE TABLE `cm_case_types` (
  `id` int(7) NOT NULL auto_increment,
  `type` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_contacts`
--

CREATE TABLE `cm_contacts` (
  `id` int(10) NOT NULL auto_increment,
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `type` varchar(50) NOT NULL default '',
  `address` varchar(200) NOT NULL default '',
  `city` varchar(100) NOT NULL default '',
  `state` char(2) NOT NULL default '',
  `zip` varchar(10) NOT NULL default '',
  `phone1` varchar(20) NOT NULL default '',
  `phone2` varchar(20) NOT NULL default '',
  `fax` varchar(15) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `notes` text NOT NULL,
  `assoc_case` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_contacts_types`
--

CREATE TABLE `cm_contacts_types` (
  `id` int(7) NOT NULL auto_increment,
  `type` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_courts`
--

CREATE TABLE `cm_courts` (
  `id` int(7) NOT NULL auto_increment,
  `court` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_dispos`
--

CREATE TABLE `cm_dispos` (
  `id` int(10) NOT NULL auto_increment,
  `dispo` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_documents`
--

CREATE TABLE `cm_documents` (
  `id` int(100) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL default '',
  `folder` varchar(100) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `case_id` varchar(100) NOT NULL default '',
  `date_modified` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_drafts`
--

CREATE TABLE `cm_drafts` (
  `userId` mediumint(9) NOT NULL auto_increment,
  `text` text,
  PRIMARY KEY  (`userId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_events`
--

CREATE TABLE `cm_events` (
  `id` int(10) NOT NULL auto_increment,
  `case_id` varchar(100) NOT NULL default '',
  `set_by` varchar(100) NOT NULL default '',
  `task` varchar(225) NOT NULL default '',
  `date_set` date NOT NULL default '0000-00-00',
  `date_due` date NOT NULL default '0000-00-00',
  `status` varchar(100) NOT NULL default '',
  `prof` varchar(200) NOT NULL default '',
  `temp_id` varchar(100) NOT NULL default '',
  `archived` varchar(10) NOT NULL default 'n',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_events_responsibles`
--

CREATE TABLE `cm_events_responsibles` (
  `id` int(10) NOT NULL auto_increment,
  `event_id` varchar(100) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Linked to cm_events; who is responsible for each event';

-- --------------------------------------------------------

--
-- Table structure for table `cm_journals`
--

CREATE TABLE `cm_journals` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `professor` varchar(150) NOT NULL default '',
  `text` text NOT NULL,
  `date_added` datetime NOT NULL default '0000-00-00 00:00:00',
  `temp_id` varchar(100) NOT NULL default '',
  `deleted` varchar(10) NOT NULL default '',
  `read` varchar(10) NOT NULL default '',
  `commented` varchar(10) NOT NULL default '',
  `comments` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_logs`
--

CREATE TABLE `cm_logs` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `ip` varchar(20) NOT NULL default '',
  `session_id` varchar(200) NOT NULL default '',
  `last_ping` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_messages`
--

CREATE TABLE `cm_messages` (
  `id` int(10) NOT NULL auto_increment,
  `thread_id` varchar(100) NOT NULL default '',
  `to` text NOT NULL,
  `from` varchar(100) NOT NULL default '',
  `ccs` text,
  `subject` varchar(100) NOT NULL default '',
  `body` text NOT NULL,
  `assoc_case` varchar(100) NOT NULL default '',
  `time_sent` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `read` text NOT NULL,
  `archive` text NOT NULL,
  `starred` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_referral`
--

CREATE TABLE `cm_referral` (
  `id` int(7) NOT NULL auto_increment,
  `referral` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cm_users`
--

CREATE TABLE `cm_users` (
  `id` int(10) NOT NULL auto_increment,
  `first_name` varchar(100) NOT NULL default '',
  `last_name` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `mobile_phone` varchar(15) NOT NULL default '',
  `office_phone` varchar(25) NOT NULL default '',
  `home_phone` varchar(15) NOT NULL default '',
  `class` varchar(20) NOT NULL default '',
  `username` varchar(25) NOT NULL default '',
  `password` varchar(40) NOT NULL default '',
  `assigned_prof` varchar(100) NOT NULL default '',
  `picture_url` varchar(200) NOT NULL default 'people/no_picture.png',
  `timezone_offset` varchar(5) NOT NULL default '1',
  `status` varchar(100) NOT NULL default 'inactive',
  `new` varchar(20) NOT NULL default '',
  `date_created` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `pref_case` varchar(10) NOT NULL default 'on',
  `pref_journal` varchar(10) NOT NULL default '',
  `pref_case_prof` varchar(10) NOT NULL default 'on' COMMENT 'does professor work on cases',
  `evals` text NOT NULL,
  `private_key` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


INSERT INTO `cm_users` (
`id` ,
`first_name` ,
`last_name` ,
`email` ,
`mobile_phone` ,
`office_phone` ,
`home_phone` ,
`class` ,
`username` ,
`password` ,
`assigned_prof` ,
`picture_url` ,
`timezone_offset` ,
`status` ,
`new` ,
`date_created` ,
`pref_case` ,
`pref_journal`
)
VALUES (
NULL , 'ClinicCases', 'Administrator', '', '', '', '', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'people/no_picture.png', '1', 'active', '',
CURRENT_TIMESTAMP , 'on', ''
);

