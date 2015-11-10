CREATE TABLE `s7rh8_booklibrary_lendee` (
 `lendeecode` varchar(8) NOT NULL,
 `fullname` varchar(250) NOT NULL,
 `contactname` varchar(250) NOT NULL,
 `contactemail` varchar(250) NOT NULL,
 `grade` varchar(2) NOT NULL,
 `homeroom` varchar(250) NOT NULL,
 `population` varchar(250) NOT NULL,
 `user_id` int(11) DEFAULT NULL,
 PRIMARY KEY (`lendeecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DELIMITER $$
CREATE TRIGGER update_lendee_hack
AFTER UPDATE ON s7rh8_session
FOR EACH ROW
BEGIN
  UPDATE s7rh8_booklibrary_lend_request
    SET user_name = (select fullname from s7rh8_booklibrary_lendee where s7rh8_booklibrary_lendee.lendeecode = s7rh8_booklibrary_lend_request.lendeecode)
  WHERE isnull(s7rh8_booklibrary_lend_request.user_name) or s7rh8_booklibrary_lend_request.user_name = '';
  UPDATE s7rh8_booklibrary_lend_request
    SET user_email = (select contactemail from s7rh8_booklibrary_lendee where s7rh8_booklibrary_lendee.lendeecode = s7rh8_booklibrary_lend_request.lendeecode)
  WHERE isnull(s7rh8_booklibrary_lend_request.user_email) or s7rh8_booklibrary_lend_request.user_email = '';
  UPDATE s7rh8_booklibrary_lend
    SET user_name = (select fullname from s7rh8_booklibrary_lendee where s7rh8_booklibrary_lendee.lendeecode = s7rh8_booklibrary_lend.lendeecode)
  WHERE isnull(s7rh8_booklibrary_lend.user_name) or s7rh8_booklibrary_lend.user_name = '';
  UPDATE s7rh8_booklibrary_lend
    SET user_email = (select contactemail from s7rh8_booklibrary_lendee where s7rh8_booklibrary_lendee.lendeecode = s7rh8_booklibrary_lend.lendeecode)
  WHERE isnull(s7rh8_booklibrary_lend.user_email) or s7rh8_booklibrary_lend.user_email = '';
  UPDATE s7rh8_booklibrary
    SET lendeecode = (select lendeecode from s7rh8_booklibrary_lend where s7rh8_booklibrary_lend.id = s7rh8_booklibrary.fk_lendid)
  WHERE isnull(s7rh8_booklibrary.lendeecode) or s7rh8_booklibrary.lendeecode = '';
  UPDATE s7rh8_booklibrary
    SET lendeefullname = (select user_name from s7rh8_booklibrary_lend where s7rh8_booklibrary_lend.id = s7rh8_booklibrary.fk_lendid)
  WHERE isnull(s7rh8_booklibrary.lendeefullname) or s7rh8_booklibrary.lendeefullname = '';
  UPDATE s7rh8_booklibrary
    SET lendeecode = NUll
  WHERE s7rh8_booklibrary.fk_lendid = 0;
  UPDATE s7rh8_booklibrary
    SET lendeefullname = NUll
  WHERE s7rh8_booklibrary.fk_lendid = 0;
END $$
DELIMITER ;

ALTER TABLE  `s7rh8_booklibrary` ADD  `lendeefullname` VARCHAR( 250 ) NULL ;
ALTER TABLE  `s7rh8_booklibrary` ADD  `lendeecode` VARCHAR( 8 ) NULL ;

ALTER TABLE  `s7rh8_booklibrary_lend` CHANGE  `fk_userid`  `fk_userid` INT( 11 ) NULL DEFAULT  '0';
ALTER TABLE  `s7rh8_booklibrary_lend` ADD  `user_code` INT NOT NULL ;
ALTER TABLE  `s7rh8_booklibrary_lend` ADD  `lendeecode` VARCHAR( 8 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
/* ALTER TABLE  `s7rh8_booklibrary_lend` CHANGE  `user_code`  `user_code` INT( 11 ) NOT NULL DEFAULT  '0'; */

ALTER TABLE  `s7rh8_booklibrary_lend_request` ADD  `lendeecode` VARCHAR( 8 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (998, "_BOOKLIBRARY_LABEL_LENDEECODE", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (998, 998, 2, "Lendee ID");

INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (997, "_BOOKLIBRARY_LABEL_GRADE", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (997, 997, 2, "Grade");

INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (996, "_BOOKLIBRARY_LABEL_HOMEROOM", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (996, 996, 2, "Homeroom");

INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (995, "_BOOKLIBRARY_LABEL_POPULATION", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (995, 995, 2, "Population");

INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (994, "_BOOKLIBRARY_LABEL_LENDEEFULLNAME", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (994, 994, 2, "Full Name");

INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (993, "_BOOKLIBRARY_LABEL_LENDEE_NOTFOUND", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (993, 993, 2, "Lendee Not Found");

INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (992, "_BOOKLIBRARY_INFOTEXT_JS_LEND_REQ_LENDEECODE", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (992, 992, 2, "Lendee ID Required");
