--This is for those who have ClinicCases 3 installed and wish to upgrade to ClinicCases 5.  Run the following SQL:

ALTER TABLE `cm` ADD `notes` TEXT NOT NULL AFTER `case_name` ;

ALTER TABLE `cm` ADD `email` VARCHAR( 100 ) NOT NULL AFTER `phone2` ;

ALTER TABLE `cm` DROP `phone3` ;

ALTER TABLE `cm` ADD `m_initial` VARCHAR( 5 ) NOT NULL AFTER `first_name` ;

ALTER TABLE `cm_users` ADD `pref_case_prof` VARCHAR( 10 ) NOT NULL DEFAULT 'on' COMMENT 'does professor work on cases';

ALTER TABLE `cm` ADD `referral` VARCHAR( 100 ) NOT NULL ;

CREATE TABLE `cm_referral` (`id` INT( 7 ) NOT NULL AUTO_INCREMENT ,`referral` VARCHAR( 200 ) NOT NULL ,PRIMARY KEY ( `id` )) ENGINE = MYISAM

ALTER TABLE `cm_users` ADD `evals` TEXT NOT NULL ;

CREATE TABLE IF NOT EXISTS cm_board (
  id int(10) NOT NULL auto_increment,
  title varchar(150) NOT NULL,
  body text NOT NULL,
  created_by varchar(100) NOT NULL,
  last_modified_by varchar(100) NOT NULL,
  created datetime NOT NULL,
  last_modified datetime default NULL,
  attachment text NOT NULL,
  locked varchar(10) NOT NULL,
  hidden varchar(10) NOT NULL,
  is_comment varchar(10) NOT NULL,
  orig_post_id int(10) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE `cm_users` ADD `private_key` VARCHAR( 50 ) NOT NULL ;

ALTER TABLE `cm_messages` ADD `ccs` VARCHAR( 100 ) NULL AFTER `from` ;

ALTER TABLE `cm_messages` DROP `temp_id`;

ALTER TABLE `cm_messages` CHANGE `to` `to` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `ccs` `ccs` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL

ALTER TABLE `cm_messages` CHANGE `read` `read` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ,
CHANGE `archive` `archive` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL

ALTER TABLE `cm_messages` ADD `starred` TEXT NOT NULL ;

ALTER TABLE `cm` CHANGE `ssn` `ssn` VARCHAR( 15 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
