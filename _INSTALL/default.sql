-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 19, 2012 at 11:40 AM
-- Server version: 5.1.63
-- PHP Version: 5.3.6-13ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `default`
--

-- --------------------------------------------------------

--
-- Table structure for table `cm`
--

CREATE TABLE IF NOT EXISTS `cm` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `clinic_id` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `middle_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `organization` varchar(250) NOT NULL,
  `date_open` varchar(100) NOT NULL DEFAULT '',
  `date_close` varchar(100) NOT NULL DEFAULT '',
  `case_type` varchar(100) NOT NULL DEFAULT '',
  `clinic_type` varchar(200) NOT NULL,
  `professor` varchar(100) NOT NULL DEFAULT '',
  `address1` varchar(200) NOT NULL DEFAULT '',
  `address2` varchar(200) NOT NULL DEFAULT '',
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` varchar(100) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `ssn` varchar(15) NOT NULL DEFAULT '',
  `dob` varchar(15) NOT NULL DEFAULT '',
  `age` varchar(10) NOT NULL DEFAULT '',
  `gender` varchar(10) NOT NULL DEFAULT '',
  `race` varchar(10) NOT NULL DEFAULT '',
  `income` int(50) DEFAULT NULL,
  `per` varchar(15) NOT NULL,
  `judge` varchar(200) NOT NULL DEFAULT '',
  `pl_or_def` varchar(100) NOT NULL DEFAULT '',
  `court` varchar(200) NOT NULL DEFAULT '',
  `section` varchar(100) NOT NULL DEFAULT '',
  `ct_case_no` varchar(100) NOT NULL DEFAULT '',
  `case_name` varchar(250) NOT NULL DEFAULT '',
  `adverse_parties` text NOT NULL,
  `notes` text NOT NULL,
  `dispo` varchar(100) NOT NULL DEFAULT '',
  `close_notes` text NOT NULL,
  `referral` varchar(100) NOT NULL DEFAULT '',
  `assigned_users` text NOT NULL,
  `opened_by` varchar(50) NOT NULL,
  `time_opened` datetime NOT NULL,
  `closed_by` varchar(50) NOT NULL,
  `time_closed` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `professor` (`professor`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

--
-- Table structure for table `cm_adverse_parties`
--

CREATE TABLE IF NOT EXISTS `cm_adverse_parties` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `case_id` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='used to check for conflicts' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--


--
-- Table structure for table `cm_case_assignees`
--

CREATE TABLE IF NOT EXISTS `cm_case_assignees` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `case_id` varchar(100) NOT NULL DEFAULT '',
  `status` varchar(50) NOT NULL DEFAULT '',
  `date_assigned` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_removed` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Determine which students assigned to a case' AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_case_types`
--

CREATE TABLE IF NOT EXISTS `cm_case_types` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL DEFAULT '',
  `case_type_code` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cm_case_types`
--

INSERT INTO `cm_case_types` (`id`, `type`, `case_type_code`) VALUES
(1, 'Criminal', 'CRM'),
(2, 'Adoption', 'ADO'),
(3, 'SSI', 'SSI'),
(4, 'Divorce', 'DIV');

-- --------------------------------------------------------

--
-- Table structure for table `cm_clinic_type`
--

CREATE TABLE IF NOT EXISTS `cm_clinic_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clinic_name` text NOT NULL,
  `clinic_code` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cm_clinic_type`
--

INSERT INTO `cm_clinic_type` (`id`, `clinic_name`, `clinic_code`) VALUES
(1, 'Family', 'FAM'),
(2, 'Consumer', 'CON'),
(3, 'Health Law', 'HLT'),
(4, 'Criminal', 'CRM');

-- --------------------------------------------------------

--
-- Table structure for table `cm_columns`
--

CREATE TABLE IF NOT EXISTS `cm_columns` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `db_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `include_in_case_table` varchar(10) NOT NULL COMMENT 'Should this column be included into the data sent to the main case table?',
  `input_type` varchar(10) NOT NULL,
  `select_options` text NOT NULL,
  `display_by_default` varchar(10) NOT NULL COMMENT 'Should this column be displayed to the case table user by default?',
  `required` int(11) NOT NULL DEFAULT '0' COMMENT 'ClinicCases cannot function without this field',
  `display_order` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Defines the columns to be used in ClinicCases cases table' AUTO_INCREMENT=45 ;

--
-- Dumping data for table `cm_columns`
--

INSERT INTO `cm_columns` (`id`, `db_name`, `display_name`, `include_in_case_table`, `input_type`, `select_options`, `display_by_default`, `required`, `display_order`) VALUES
(2, 'id', 'Id', 'true', 'text', '', 'false', 1, 2),
(3, 'clinic_id', 'Case Number', 'true', 'text', '', 'false', 1, 3),
(4, 'first_name', 'First Name', 'true', 'text', '', 'true', 1, 4),
(5, 'middle_name', 'Middle Name', 'true', 'text', '', 'false', 1, 5),
(6, 'last_name', 'Last Name', 'true', 'text', '', 'true', 1, 6),
(7, 'organization', 'Organization', 'true', 'text', '', 'false', 1, 7),
(8, 'date_open', 'Date Open', 'true', 'date', '', 'true', 1, 8),
(9, 'date_close', 'Date Close', 'true', 'date', '', 'true', 1, 41),
(10, 'case_type', 'Case Type', 'true', 'select', 'a:5:{s:4:"MISD";s:22:"Criminal - Misdemeanor";s:3:"ADO";s:8:"Adoption";s:4:"CHSP";s:13:"Child Support";s:3:"FEL";s:17:"Criminal - Felony";s:3:"DIV";s:7:"Divorce";}', 'true', 1, 10),
(44, 'assigned_users', 'Assigned Users', 'true', 'text', '', 'false', 0, 41),
(41, 'adverse_parties', 'Adverse Party', 'true', 'multi-text', '', 'false', 1, 33),
(11, 'clinic_type', 'Clinic Type', 'true', 'select', 'a:8:{s:3:"MSD";s:18:"Misdemeanor Clinic";s:3:"CMJ";s:17:"Community Justice";s:3:"CRM";s:8:"Criminal";s:3:"FAM";s:6:"Family";s:3:"IMM";s:11:"Immigration";s:3:"LTT";s:25:"Litigation and Technology";s:3:"TST";s:11:"Test Clinic";s:3:"WJS";s:17:"Workplace Justice";}', 'false', 0, 11),
(12, 'address1', 'Address 1', 'false', 'text', '', 'false', 0, 12),
(13, 'address2', 'Address 2', 'false', 'text', '', 'false', 0, 13),
(14, 'city', 'City', 'false', 'text', '', 'false', 0, 14),
(15, 'state', 'State', 'false', 'select', 'a:51:{s:2:"AL";s:7:"Alabama";s:2:"AK";s:6:"Alaska";s:2:"AZ";s:7:"Arizona";s:2:"AR";s:8:"Arkansas";s:2:"CA";s:10:"California";s:2:"CO";s:8:"Colorado";s:2:"CT";s:11:"Connecticut";s:2:"DE";s:8:"Delaware";s:2:"DC";s:20:"District Of Columbia";s:2:"FL";s:7:"Florida";s:2:"GA";s:7:"Georgia";s:2:"HI";s:6:"Hawaii";s:2:"ID";s:5:"Idaho";s:2:"IL";s:8:"Illinois";s:2:"IN";s:7:"Indiana";s:2:"IA";s:4:"Iowa";s:2:"KS";s:6:"Kansas";s:2:"KY";s:8:"Kentucky";s:2:"LA";s:9:"Louisiana";s:2:"ME";s:5:"Maine";s:2:"MD";s:8:"Maryland";s:2:"MA";s:13:"Massachusetts";s:2:"MI";s:8:"Michigan";s:2:"MN";s:9:"Minnesota";s:2:"MS";s:11:"Mississippi";s:2:"MO";s:8:"Missouri";s:2:"MT";s:7:"Montana";s:2:"NE";s:8:"Nebraska";s:2:"NV";s:6:"Nevada";s:2:"NH";s:13:"New Hampshire";s:2:"NJ";s:10:"New Jersey";s:2:"NM";s:10:"New Mexico";s:2:"NY";s:8:"New York";s:2:"NC";s:14:"North Carolina";s:2:"ND";s:12:"North Dakota";s:2:"OH";s:4:"Ohio";s:2:"OK";s:8:"Oklahoma";s:2:"OR";s:6:"Oregon";s:2:"PA";s:12:"Pennsylvania";s:2:"RI";s:12:"Rhode Island";s:2:"SC";s:14:"South Carolina";s:2:"SD";s:12:"South Dakota";s:2:"TN";s:9:"Tennessee";s:2:"TX";s:5:"Texas";s:2:"UT";s:4:"Utah";s:2:"VT";s:7:"Vermont";s:2:"VA";s:8:"Virginia";s:2:"WA";s:10:"Washington";s:2:"WV";s:13:"West Virginia";s:2:"WI";s:9:"Wisconsin";s:2:"WY";s:7:"Wyoming";}', 'false', 0, 15),
(16, 'zip', 'Zip', 'false', 'text', '', 'false', 0, 16),
(17, 'phone', 'Phone', 'true', 'dual', 'a:5:{s:4:"home";s:4:"Home";s:4:"work";s:4:"Work";s:6:"mobile";s:6:"Mobile";s:3:"fax";s:3:"Fax";s:5:"other";s:5:"Other";}', 'false', 1, 17),
(19, 'email', 'Email', 'true', 'dual', 'a:3:{s:4:"Home";s:4:"Home";s:4:"Work";s:4:"Work";s:5:"Other";s:5:"Other";}', 'false', 1, 19),
(20, 'ssn', 'SSN', 'true', 'text', '', 'false', 0, 20),
(21, 'dob', 'DOB', 'true', 'text', '', 'false', 0, 21),
(22, 'age', 'Age', 'true', 'text', '', 'false', 0, 22),
(23, 'gender', 'Gender', 'true', 'select', 'a:2:{s:1:"M";s:4:"Male";s:1:"F";s:6:"Female";}', 'false', 0, 23),
(24, 'race', 'Race', 'true', 'select', 'a:5:{s:2:"AA";s:16:"African-American";s:1:"W";s:5:"White";s:1:"H";s:8:"Hispanic";s:1:"A";s:5:"Asian";s:1:"O";s:5:"Other";}', 'false', 0, 24),
(25, 'income', 'Income', 'false', 'text', '', 'false', 0, 25),
(26, 'per', 'Per', 'false', 'select', 'a:4:{s:3:"day";s:3:"Day";s:4:"week";s:4:"Week";s:5:"month";s:5:"Month";s:4:"year";s:4:"Year";}', 'false', 0, 26),
(27, 'judge', 'Judge', 'false', 'text', '', 'false', 0, 27),
(28, 'pl_or_def', 'Plaintiff/Defendant', 'false', 'select', 'a:3:{s:9:"plaintiff";s:9:"Plaintiff";s:9:"defendant";s:9:"Defendant";s:5:"other";s:5:"Other";}', 'false', 0, 28),
(29, 'court', 'Court', 'true', 'select', 'a:2:{i:0;s:38:"Federal Court for the Eastern District";i:1;s:14:"Superior Court";}', 'false', 0, 29),
(30, 'section', 'Section', 'false', 'text', '', 'false', 0, 30),
(31, 'ct_case_no', 'Court Case Number', 'false', 'text', '', 'false', 0, 31),
(32, 'case_name', 'Case Name', 'false', 'text', '', 'false', 0, 32),
(33, 'notes', 'Notes', 'false', 'textarea', '', 'false', 0, 33),
(36, 'dispo', 'Disposition', 'true', 'select', 'a:5:{i:0;s:11:"Advice Only";i:1;s:16:"Judgment Granted";i:2;s:6:"Guilty";i:3;s:10:"Not Guilty";i:4;s:15:"Client Withdrew";}', 'true', 0, 42),
(38, 'close_notes', 'Closing Notes', 'false', 'textarea', '', 'false', 0, 44),
(39, 'referral', 'Referred By', 'true', 'select', 'a:3:{i:1;s:15:"Social Services";i:2;s:3:"LSC";i:3;s:16:"Legal Aid Bureau";}', 'false', 0, 39),
(40, 'opened_by', 'Opened By', 'true', 'text', '', 'true', 1, 40);

-- --------------------------------------------------------

--
-- Table structure for table `cm_contacts`
--

CREATE TABLE IF NOT EXISTS `cm_contacts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL DEFAULT '',
  `last_name` varchar(100) NOT NULL DEFAULT '',
  `organization` varchar(200) NOT NULL,
  `type` varchar(50) NOT NULL DEFAULT '',
  `address` text NOT NULL,
  `city` varchar(100) NOT NULL DEFAULT '',
  `state` char(2) NOT NULL DEFAULT '',
  `zip` varchar(10) NOT NULL DEFAULT '',
  `phone` text NOT NULL,
  `email` text NOT NULL,
  `url` text NOT NULL,
  `notes` text NOT NULL,
  `assoc_case` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_contacts_types`
--

CREATE TABLE IF NOT EXISTS `cm_contacts_types` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `cm_contacts_types`
--

INSERT INTO `cm_contacts_types` (`id`, `type`) VALUES
(1, 'Witness'),
(2, 'Opposing Counsel'),
(3, 'Opposing Party'),
(4, 'Client Relative/Friend'),
(5, 'Expert'),
(6, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `cm_courts`
--

CREATE TABLE IF NOT EXISTS `cm_courts` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `court` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cm_courts`
--

INSERT INTO `cm_courts` (`id`, `court`) VALUES
(1, 'Federal Court for the Eastern District'),
(2, 'Superior Court ');

-- --------------------------------------------------------

--
-- Table structure for table `cm_dispos`
--

CREATE TABLE IF NOT EXISTS `cm_dispos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `dispo` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cm_dispos`
--

INSERT INTO `cm_dispos` (`id`, `dispo`) VALUES
(1, 'Advice Only'),
(2, 'Judgment Granted'),
(3, 'Guilty'),
(4, 'Not Guilty'),
(5, 'Client Withdrew');

-- --------------------------------------------------------

--
-- Table structure for table `cm_documents`
--

CREATE TABLE IF NOT EXISTS `cm_documents` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `local_file_name` varchar(200) NOT NULL DEFAULT '',
  `extension` varchar(10) NOT NULL,
  `folder` varchar(100) NOT NULL DEFAULT '',
  `containing_folder` varchar(100) NOT NULL,
  `text` text NOT NULL,
  `write_permission` varchar(500) NOT NULL,
  `username` varchar(100) NOT NULL DEFAULT '',
  `case_id` varchar(100) NOT NULL DEFAULT '',
  `date_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `start` datetime NOT NULL,
  `start_text` varchar(200) NOT NULL,
  `end_text` varchar(200) NOT NULL,
  `end` datetime DEFAULT NULL,
  `all_day` tinyint(1) NOT NULL,
  `status` varchar(100) NOT NULL DEFAULT '',
  `notes` text NOT NULL,
  `location` text NOT NULL,
  `prof` varchar(200) NOT NULL DEFAULT '',
  `archived` varchar(10) NOT NULL DEFAULT 'n',
  `time_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_events_responsibles`
--

CREATE TABLE IF NOT EXISTS `cm_events_responsibles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `event_id` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `time_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='Linked to cm_events; who is responsible for each event' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_groups`
--

CREATE TABLE IF NOT EXISTS `cm_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) NOT NULL,
  `group_title` varchar(100) NOT NULL,
  `group_description` text NOT NULL,
  `allowed_tabs` varchar(500) NOT NULL COMMENT 'An object which controls which tabs the user is allowed to see.',
  `add_cases` int(2) NOT NULL,
  `delete_cases` int(2) NOT NULL,
  `edit_cases` int(2) NOT NULL,
  `close_cases` int(2) NOT NULL,
  `view_all_cases` int(2) NOT NULL COMMENT 'User can view all cases or only cases to which they are assigned',
  `assign_cases` int(2) NOT NULL COMMENT 'Can the user assign cases to users?',
  `view_users` int(2) NOT NULL,
  `add_users` int(2) NOT NULL,
  `delete_users` int(2) NOT NULL,
  `edit_users` int(2) NOT NULL,
  `activate_users` int(2) NOT NULL,
  `add_case_notes` int(2) NOT NULL,
  `edit_case_notes` int(2) NOT NULL,
  `delete_case_notes` int(2) NOT NULL,
  `documents_upload` int(2) NOT NULL,
  `documents_modify` int(2) NOT NULL,
  `add_events` int(2) NOT NULL,
  `edit_events` int(2) NOT NULL,
  `delete_events` int(2) NOT NULL,
  `add_contacts` int(2) NOT NULL,
  `edit_contacts` int(2) NOT NULL,
  `delete_contacts` int(2) NOT NULL,
  `post_in_board` int(2) NOT NULL,
  `view_board` int(2) NOT NULL,
  `edit_posts` int(2) NOT NULL,
  `reads_journals` int(2) NOT NULL,
  `writes_journals` int(2) NOT NULL,
  `change_permissions` int(2) NOT NULL,
  `can_configure` int(2) NOT NULL,
  `supervises` int(2) NOT NULL COMMENT 'The user has other users under him who he supervises, e.g, students, associates',
  `is_supervised` int(2) NOT NULL COMMENT 'This user works on cases,but is supervised by another user',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COMMENT='Allows admin to create user groups and set definitions' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cm_groups`
--

INSERT INTO `cm_groups` (`id`, `group_name`, `group_title`, `group_description`, `allowed_tabs`, `add_cases`, `delete_cases`, `edit_cases`, `close_cases`, `view_all_cases`, `assign_cases`, `view_users`, `add_users`, `delete_users`, `edit_users`, `activate_users`, `add_case_notes`, `edit_case_notes`, `delete_case_notes`, `documents_upload`, `documents_modify`, `add_events`, `edit_events`, `delete_events`, `add_contacts`, `edit_contacts`, `delete_contacts`, `post_in_board`, `view_board`, `edit_posts`, `reads_journals`, `writes_journals`, `change_permissions`, `can_configure`, `supervises`, `is_supervised`) VALUES
(1, 'super', 'Super User', 'The super user can access all ClinicCases functions and add, edit, and delete all data.  Most importantly, only the super user can change permissions for all users.\r\nSuper User access should be restricted to a limited number of users.', 'a:8:{i:0;s:4:"Home";i:1;s:5:"Cases";i:2;s:5:"Group";i:3;s:5:"Users";i:4;s:8:"Journals";i:5;s:5:"Board";i:6;s:9:"Utilities";i:7;s:8:"Messages";}', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 0),
(2, 'admin', 'Administrator', 'The administrator can access all ClinicCases functions and view,edit, and delete all data.  By default, the administrator is the only user who can add new files or authorize new users.\r\n\r\nThe administrator cannot change group permissions.', 'a:6:{i:0;s:4:"Home";i:1;s:5:"Cases";i:2;s:5:"Users";i:3;s:5:"Board";i:4;s:9:"Utilities";i:5;s:8:"Messages";}', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 0, 0),
(3, 'student', 'Student', 'Students can only access the cases to which they have been assigned by a professor.', 'a:6:{i:0;s:4:"Home";i:1;s:5:"Cases";i:2;s:8:"Journals";i:3;s:5:"Board";i:4;s:9:"Utilities";i:5;s:8:"Messages";}', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 0, 0, 1),
(4, 'prof', 'Professor', 'Professors supervise students.  By default, they can assign students to cases and view, edit, and delete all data in cases to which they are assigned.', 'a:7:{i:0;s:4:"Home";i:1;s:5:"Cases";i:2;s:5:"Group";i:3;s:8:"Journals";i:4;s:5:"Board";i:5;s:9:"Utilities";i:6;s:8:"Messages";}', 0, 0, 1, 1, 0, 1, 0, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cm_journals`
--

CREATE TABLE IF NOT EXISTS `cm_journals` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL DEFAULT '',
  `reader` text NOT NULL,
  `text` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `archived` text NOT NULL,
  `read` text NOT NULL,
  `commented` varchar(10) NOT NULL DEFAULT '',
  `comments` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `type` varchar(200) NOT NULL DEFAULT '',
  `last_msg_check` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `to_text` text NOT NULL,
  `cc_text` text NOT NULL,
  `assoc_case_text` text NOT NULL,
  `time_sent_text` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cm_referral`
--

CREATE TABLE IF NOT EXISTS `cm_referral` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `referral` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cm_referral`
--

INSERT INTO `cm_referral` (`id`, `referral`) VALUES
(1, 'Social Services'),
(2, 'LSC'),
(3, 'Legal Aid Bureau');

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
  `grp` varchar(20) NOT NULL DEFAULT '',
  `username` varchar(25) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `supervisors` text NOT NULL,
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
  `force_new_password` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cm_users`
--

INSERT INTO `cm_users` (`id`, `first_name`, `last_name`, `email`, `mobile_phone`, `office_phone`, `home_phone`, `grp`, `username`, `password`, `supervisors`, `picture_url`, `timezone_offset`, `status`, `new`, `date_created`, `pref_case`, `pref_journal`, `pref_case_prof`, `evals`, `private_key`, `force_new_password`) VALUES
(1, 'Temp', 'Admin', '', '', '', '', 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'people/no_picture.png', '1', 'active', '', '2012-07-02 14:57:44', 'on', '', 'on', '', '', 1);

--
-- Table structure for table `cm_board`
--

CREATE TABLE IF NOT EXISTS `cm_board` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) NOT NULL,
  `body` text NOT NULL,
  `color` varchar(50) NOT NULL,
  `author` varchar(250) NOT NULL,
  `time_added` datetime NOT NULL,
  `time_edited` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Table structure for table `cm_board_attachments`
--

CREATE TABLE IF NOT EXISTS `cm_board_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(500) NOT NULL,
  `local_file_name` varchar(200) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `username` varchar(200) NOT NULL,
  `post_id` int(11) NOT NULL,
  `time_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `cm_board_viewers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `viewer` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

