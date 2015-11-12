ALTER TABLE  `s7rh8_booklibrary` ADD  `ddccode` VARCHAR( 7 ) NOT NULL ;
INSERT INTO `s7rh8_booklibrary_const`(`id`, `const`, `sys_type`) VALUES (999, "_BOOKLIBRARY_LABEL_DDCCODE", "Booklibrary");
INSERT INTO `s7rh8_booklibrary_const_languages`(`id`, `fk_constid`, `fk_languagesid`, `value_const`) VALUES (999, 999, 2, "Dewey Decimal");

