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




ALTER TABLE member
  ADD COLUMN `getpasstime` TIMESTAMP NULL  ;




ALTER TABLE member
  ADD COLUMN `token` VARCHAR(64) NULL  ;

CREATE
VIEW member_has_message
AS
(
SELECT * FROM member WHERE
  id IN ( SELECT DISTINCT (from_m_id) FROM message  )
OR id IN ( SELECT DISTINCT (to_m_id) FROM message)

);


ALTER TABLE `chinese6_companion`.`member`
  ADD COLUMN `nickname_en` VARCHAR(256) NULL AFTER `id`;






