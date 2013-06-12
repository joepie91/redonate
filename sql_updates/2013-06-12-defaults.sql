ALTER TABLE  `campaigns` ADD  `DefaultCurrency` VARCHAR( 6 ) NOT NULL DEFAULT  'usd',
ADD  `DefaultAmount` DECIMAL( 12, 2 ) NOT NULL DEFAULT  '5';
