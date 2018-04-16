ALTER TABLE `mdl_var_blockgroup` ADD COLUMN `varsubspecialityid` BIGINT(10) DEFAULT 0 NOT NULL AFTER `varspecialityid`; 
ALTER TABLE `mdl_var_blockgroup` CHANGE `varblockid` `varblockid` BIGINT(10) NULL, CHANGE `vardepartmentid` `vardepartmentid` BIGINT(10) DEFAULT 0 NULL, CHANGE `varspecialityid` `varspecialityid` BIGINT(10) DEFAULT 0 NULL, CHANGE `varsubspecialityid` `varsubspecialityid` BIGINT(10) DEFAULT 0 NULL, CHANGE `varblockgroupnumcourses` `varblockgroupnumcourses` BIGINT(10) DEFAULT 0 NULL; 

ALTER TABLE `mdl_var_assignmentqueue` CHANGE `varblockid` `varblockid` VARCHAR(255) NOT NULL; 
ALTER TABLE `mdl_var_enroll` ADD COLUMN `varblockcoursegroup` VARCHAR(128) NOT NULL AFTER `varblockid`; 


ALTER TABLE `mdl_var_assignmentqueue` 
	CHANGE `id` `id` bigint(20)   NOT NULL auto_increment first , 
	CHANGE `varblockid` `varblockid` varchar(255)  COLLATE latin1_swedish_ci NOT NULL, 
	CHANGE `varassignmentqueuestatus` `varassignmentqueuestatus` varchar(20)  COLLATE latin1_swedish_ci NOT NULL DEFAULT 'undefined' , 
	CHANGE `varassignmentqueuedata` `varassignmentqueuedata` longblob   NULL , 
	ADD COLUMN `varassignmentqueueobjectivefunction` double   NULL, 
	DROP COLUMN `varassignmentqueueweight` , 
	DROP KEY `mdl_varassi_var_ix` , COMMENT='', DEFAULT CHARSET='latin1', COLLATE ='latin1_swedish_ci' ;


/* Alter table in target */
ALTER TABLE `mdl_var_block` 
	DROP KEY `mdl_varbloc_var2_ix` , 
	DROP KEY `mdl_varbloc_var_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_blockcourse` 
	CHANGE `courseid` `courseid` bigint(10)   NULL , 
	CHANGE `varblockcourserating` `varblockcourserating` bigint(10)   NOT NULL DEFAULT 1 , 
	CHANGE `varblockcoursegroup` `varblockcoursegroup` varchar(128)  COLLATE utf8_unicode_ci NOT NULL DEFAULT '' , 
	DROP KEY `mdl_varbloc_cou_ix` , 
	DROP KEY `mdl_varbloc_var6_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_blockgroup` 
	DROP KEY `mdl_varbloc_var3_ix` , 
	DROP KEY `mdl_varbloc_var4_ix` , 
	DROP KEY `mdl_varbloc_var5_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_enroll` 
	CHANGE `userid` `userid` BIGINT(10)   NOT NULL  , 
	CHANGE `courseid` `courseid` BIGINT(10)   NOT NULL , 
	DROP KEY `mdl_varenro_cou_ix` , 
	DROP KEY `mdl_varenro_use_ix` , 
	DROP KEY `mdl_varenro_var_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_group` 
	DROP KEY `mdl_vargrou_coh_ix` , 
	DROP KEY `mdl_vargrou_var2_ix` , 
	DROP KEY `mdl_vargrou_var3_ix` , 
	DROP KEY `mdl_vargrou_var4_ix` , 
	DROP KEY `mdl_vargrou_var_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_speciality` 
	CHANGE `vardepartmentid` `vardepartmentid` bigint(10)   NOT NULL after `varspecialityname` , 
	CHANGE `varspecialitynotes` `varspecialitynotes` varchar(512)  COLLATE utf8_unicode_ci NULL after `vardepartmentid` , 
	CHANGE `varformid` `varformid` bigint(10)   NULL after `varspecialityedboid` , 
	CHANGE `varlevelid` `varlevelid` bigint(10)   NULL after `varformid` , 
	DROP KEY `mdl_varspec_var2_ix` , 
	DROP KEY `mdl_varspec_var3_ix` , 
	DROP KEY `mdl_varspec_var_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_subspeciality` 
	CHANGE `varspecialityid` `varspecialityid` bigint(10)   NULL after `id` , 
	CHANGE `varsubspecialitytitle` `varsubspecialitytitle` varchar(255)  COLLATE utf8_general_ci NULL after `varspecialityid` , 
	CHANGE `varsubspecialityurl` `varsubspecialityurl` varchar(2048)  COLLATE utf8_general_ci NULL after `varsubspecialitytitle` , 
	DROP KEY `mdl_varsubs_var_ix` , COMMENT='', DEFAULT CHARSET='latin1', COLLATE ='latin1_swedish_ci' ;

/* Alter table in target */
ALTER TABLE `mdl_var_subspecialityblock` 
	CHANGE `vardepartmentid` `vardepartmentid` bigint(10)   NOT NULL after `varlevelid` , 
	CHANGE `varspecialityid` `varspecialityid` bigint(10)   NOT NULL after `vardepartmentid` , 
	CHANGE `varsubspecialityblockisarchive` `varsubspecialityblockisarchive` smallint(4)   NOT NULL after `varspecialityid` , 
	DROP KEY `mdl_varsubs_var2_ix` , 
	DROP KEY `mdl_varsubs_var3_ix` , 
	DROP KEY `mdl_varsubs_var4_ix` , 
	DROP KEY `mdl_varsubs_var5_ix` , COMMENT='' ;

/* Alter table in target */
ALTER TABLE `mdl_var_subspecialityenroll` 
	CHANGE `userid` `userid` bigint(10)   NOT NULL after `id` , 
	CHANGE `varsubspecialityid` `varsubspecialityid` bigint(10)   NOT NULL after `userid` , 
	ADD COLUMN `vargroupid` bigint(10)   NOT NULL after `varsubspecialityid` , 
	DROP COLUMN `varsubspecialityblockid` , 
	DROP KEY `mdl_varsubs_use_ix` , 
	DROP KEY `mdl_varsubs_var6_ix` , 
	DROP KEY `mdl_varsubs_var7_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_userblockcourse` 
	CHANGE `courseid` `courseid` bigint(10)   NOT NULL after `varblockid` , 
	CHANGE `userid` `userid` bigint(10)   NOT NULL DEFAULT 0 after `varuserblockcourserating` , 
	DROP KEY `mdl_varuser_cou_ix` , 
	DROP KEY `mdl_varuser_use_ix` , 
	DROP KEY `mdl_varuser_var_ix` ;

/* Alter table in target */
ALTER TABLE `mdl_var_usersubspecialityrating` 
	CHANGE `varsubspecialityid` `varsubspecialityid` bigint(10)   NOT NULL after `varsubspecialityblockid` , 
	CHANGE `usersubspecialityblockdatetime` `usersubspecialityblockdatetime` datetime   NULL after `usersubspecialityblockrating` , 
	CHANGE `userid` `userid` bigint(10)   NOT NULL DEFAULT 0 after `usersubspecialityblockdatetime` , 
	DROP KEY `mdl_varuser_use2_ix` , 
	DROP KEY `mdl_varuser_var2_ix` , 
	DROP KEY `mdl_varuser_var3_ix` , COMMENT='variatives, rating of each subspeciality assigned by each student' ;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;



ALTER TABLE `mdl_var_block` ADD INDEX (`vargroupyear`);
ALTER TABLE `mdl_var_block` ADD INDEX (`varformid`); 
ALTER TABLE `mdl_var_block` ADD INDEX (`varlevelid`); 
ALTER TABLE `mdl_var_blockcourse` ADD INDEX (`varblockid`), ADD INDEX (`courseid`), ADD INDEX (`varblockcoursegroup`); 
ALTER TABLE `mdl_var_blockgroup` ADD INDEX (`varblockid`), ADD INDEX (`vardepartmentid`), ADD INDEX (`varspecialityid`), ADD INDEX (`varsubspecialityid`); 
ALTER TABLE `mdl_var_enroll` ADD INDEX (`userid`), ADD INDEX (`courseid`), ADD INDEX (`varblockid`), ADD INDEX (`varblockcoursegroup`); 
ALTER TABLE `mdl_var_enroll` ADD FOREIGN KEY (`userid`) REFERENCES `mdl_user`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_enroll` ADD FOREIGN KEY (`courseid`) REFERENCES `mdl_course`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_enroll` ADD FOREIGN KEY (`varblockid`) REFERENCES `mdl_var_block`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_group` ADD INDEX (`cohortid`), ADD INDEX (`vardepartmentid`), ADD INDEX (`varspecialityid`), ADD INDEX (`varformid`), ADD INDEX (`varlevelid`); 
ALTER TABLE `mdl_var_group` ADD FOREIGN KEY (`cohortid`) REFERENCES `mdl_cohort`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_group` ADD FOREIGN KEY (`vardepartmentid`) REFERENCES `mdl_var_department`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_speciality` ADD INDEX (`vardepartmentid`), ADD INDEX (`varformid`), ADD INDEX (`varlevelid`); 
ALTER TABLE `mdl_var_speciality` ADD FOREIGN KEY (`vardepartmentid`) REFERENCES `mdl_var_department`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_speciality` ADD FOREIGN KEY (`varformid`) REFERENCES `mdl_var_department`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspeciality` ADD INDEX (`varspecialityid`); 
ALTER TABLE `mdl_var_subspeciality` ADD FOREIGN KEY (`varspecialityid`) REFERENCES `mdl_var_speciality`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspecialityblock` ADD INDEX (`vargroupyear`), ADD INDEX (`varformid`), ADD INDEX (`varlevelid`), ADD INDEX (`vardepartmentid`), ADD INDEX (`varspecialityid`); 
ALTER TABLE `mdl_var_subspecialityblock` ADD FOREIGN KEY (`varformid`) REFERENCES `mdl_var_form`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspecialityblock` ADD FOREIGN KEY (`varlevelid`) REFERENCES `mdl_var_level`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspecialityblock` ADD FOREIGN KEY (`vardepartmentid`) REFERENCES `mdl_var_department`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspecialityblock` ADD FOREIGN KEY (`varspecialityid`) REFERENCES `mdl_var_speciality`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspecialityenroll` ADD INDEX (`userid`), ADD INDEX (`varsubspecialityid`), ADD INDEX (`vargroupid`); 
ALTER TABLE `mdl_var_subspecialityenroll` ADD FOREIGN KEY (`userid`) REFERENCES `mdl_user`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspecialityenroll` ADD FOREIGN KEY (`varsubspecialityid`) REFERENCES `mdl_var_subspeciality`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_subspecialityenroll` ADD FOREIGN KEY (`vargroupid`) REFERENCES `mdl_var_group`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_userblockcourse` ADD INDEX (`varblockid`), ADD INDEX (`courseid`), ADD INDEX (`userid`); 
ALTER TABLE `mdl_var_userblockcourse` ADD FOREIGN KEY (`varblockid`) REFERENCES `mdl_var_block`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_userblockcourse` ADD FOREIGN KEY (`courseid`) REFERENCES `mdl_course`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_userblockcourse` ADD FOREIGN KEY (`userid`) REFERENCES `mdl_user`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_usersubspecialityrating` ADD INDEX (`varsubspecialityblockid`), ADD INDEX (`varsubspecialityid`), ADD INDEX (`userid`); 
ALTER TABLE `mdl_var_usersubspecialityrating` ADD FOREIGN KEY (`varsubspecialityblockid`) REFERENCES `mdl_var_subspecialityblock`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_usersubspecialityrating` ADD FOREIGN KEY (`varsubspecialityid`) REFERENCES `mdl_var_subspeciality`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_usersubspecialityrating` ADD FOREIGN KEY (`userid`) REFERENCES `mdl_user`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_group` ADD FOREIGN KEY (`varformid`) REFERENCES `mdl_var_form`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
ALTER TABLE `mdl_var_group` ADD FOREIGN KEY (`varlevelid`) REFERENCES `mdl_var_level`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
UPDATE `mdl_var_group` SET `varspecialityid` = NULL WHERE `varspecialityid` = 0; 
ALTER TABLE `mdl_var_group` ADD FOREIGN KEY (`varspecialityid`) REFERENCES `mdl_var_speciality`(`id`) ON UPDATE CASCADE ON DELETE CASCADE; 
