ALTER TABLE `#__judirectory_categories`   
  CHANGE `show_item` `show_item` TINYINT(3) DEFAULT 1  NOT NULL;

UPDATE
  `#__judirectory_categories`
SET
  show_item = 1
WHERE show_item = - 1
  OR show_item = - 2;

ALTER TABLE `#__judirectory_categories`   
  DROP COLUMN `layout`, 
  DROP COLUMN `layout_listing`;

ALTER TABLE `#__judirectory_listings`
  DROP COLUMN `layout`;