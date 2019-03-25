ALTER TABLE `memberby`
  ADD COLUMN `ccountryid` INT (11) DEFAULT NULL,
  ADD COLUMN `cstateid` INT (11) DEFAULT NULL,
  ADD COLUMN `ccityid` INT (11) DEFAULT NULL,
  ADD COLUMN `email` VARCHAR (128) DEFAULT NULL,
  ADD COLUMN `pwd` VARCHAR (128) NOT NULL,
  ADD COLUMN `csort` INT (11) DEFAULT NULL;


ALTER TABLE  `memberby`
  ADD  UNIQUE INDEX `email` (`email`);




ALTER TABLE  `memberby`
  ADD COLUMN `pic_size` INT DEFAULT 0 NULL ;


ALTER TABLE  `bmember`
  ADD COLUMN `pic_size` INT DEFAULT 0 NULL ;



ALTER TABLE  `pics`
ADD COLUMN `pic_size` INT DEFAULT 0 NULL ;









