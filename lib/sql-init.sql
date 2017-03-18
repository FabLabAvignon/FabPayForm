CREATE TABLE `fab-pay-form` (
  `paymentId` varchar(32) NOT NULL COMMENT 'PayPal payment id. (Usually 19 characters)',
  `gender` int(11) NOT NULL COMMENT '0 => Man, 1 => Woman',
  `lastName` varchar(64) NOT NULL,
  `firstName` varchar(64) NOT NULL,
  `emailAddr` varchar(64) NOT NULL,
  `membershipType` int(11) NOT NULL COMMENT 'Abstract value given by the page to design the membership type to match config.',
  `birthDate` date NOT NULL,
  `address` varchar(128) NOT NULL,
  `city` varchar(64) NOT NULL,
  `postCode` int(11) NOT NULL,
  `country` varchar(64) NOT NULL,
  `phoneNumber` varchar(16) NOT NULL
) ENGINE=InnoDB;

ALTER TABLE `fab-pay-form`
  ADD PRIMARY KEY (`paymentId`);
