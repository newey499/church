

drop view if exists menutitles;

CREATE OR REPLACE SQL SECURITY DEFINER 
VIEW menutitles
AS 
select id, itemtype, itemgroup, itemorder, isvisible, prompt, target,
       content, menucol, lastupdate
from menus
where (itemtype = 'MENU_TITLE');

-- CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`cdn`@`localhost` SQL SECURITY DEFINER 
-- VIEW `menutitles` 
-- AS 
-- select `menus`.`id` AS `id`,`menus`.`itemtype` AS `itemtype`,`menus`.`itemgroup` AS `itemgroup`,
--        `menus`.`itemorder` AS `itemorder`,`menus`.`isvisible` AS `isvisible`,`menus`.`prompt` AS `prompt`,
--        `menus`.`target` AS `target`,`menus`.`content` AS `content`,`menus`.`menucol` AS `menucol`,
--        `menus`.`lastupdate` AS `lastupdate` 
-- from `menus` 
-- where (`menus`.`itemtype` = _latin1'MENU_TITLE');



drop view if exists menuitems;

CREATE OR REPLACE SQL SECURITY DEFINER 
VIEW menuitems
AS
select id, itemtype, itemgroup, itemorder, isvisible, prompt, target,
       content, menucol, lastupdate
from menus
where ((itemtype = 'MENU_ITEM') or (itemtype = 'MENU_ITEM_DROP') or (itemtype = 'MENU_ITEM_PAGE'));


-- CREATE OR REPLACE ALGORITHM=UNDEFINED DEFINER=`cdn`@`localhost` SQL SECURITY DEFINER 
-- VIEW `menuitems` 
-- AS 
-- select `menus`.`id` AS `id`,`menus`.`itemtype` AS `itemtype`,`menus`.`itemgroup` AS `itemgroup`,
--        `menus`.`itemorder` AS `itemorder`,`menus`.`isvisible` AS `isvisible`,`menus`.`prompt` AS `prompt`,
--        `menus`.`target` AS `target`,`menus`.`content` AS `content`,`menus`.`menucol` AS `menucol`,
--        `menus`.`lastupdate` AS `lastupdate` 
-- from `menus` 
-- where (
--        (`menus`.`itemtype` = _latin1'MENU_ITEM') or
--        (`menus`.`itemtype` = _latin1'MENU_ITEM_DROP') or 
--        (`menus`.`itemtype` = _latin1'MENU_ITEM_PAGE')
--       );




