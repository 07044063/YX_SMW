/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : wms

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2017-05-15 10:46:06
*/

-- ----------------------------
-- View structure for `v_receive`
-- ----------------------------
DROP VIEW IF EXISTS `v_receive`;

CREATE ALGORITHM = UNDEFINED DEFINER = `root`@`localhost` SQL SECURITY DEFINER VIEW `v_receive`
AS SELECT `r`.`id` AS `id`, `s`.`stock_code` AS `stock_code`, `s`.`stock_name` AS `stock_name`, `g`.`goods_name` AS `goods_name`,
`v`.`vendor_code` AS `vendor_code`, `v`.`vendor_name` AS `vendor_name`, `v`.`vendor_shortname` AS `vendor_shortname`,
`r`.`count` AS `count`, `r`.`receive_date` AS `receive_date`, `r`.`remark` AS `remark` FROM (((`receive` `r` JOIN `stock` `s`)
JOIN `vendor` `v` ) JOIN `goods` `g` ) WHERE ((`r`.`stock_id` = `s`.`id`) AND (`r`.`vendor_id` = `v`.`id`)
AND (`r`.`goods_id` = `g`.`id`) AND (`r`.`isvalid` = 1) AND (`s`.`isvalid` = 1) AND (`v`.`isvalid` = 1) AND (`g`.`isvalid` = 1));
