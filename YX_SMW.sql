/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50624
Source Host           : localhost:3306
Source Database       : wms

Target Server Type    : MYSQL
Target Server Version : 50624
File Encoding         : 65001

Date: 2017-04-28 16:34:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`admin_name`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`admin_account`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`admin_password`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`admin_last_login`  datetime NULL DEFAULT NULL ,
`admin_ip_address`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`admin_auth`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`supplier_id`  int(11) NOT NULL DEFAULT 0 ,
PRIMARY KEY (`id`, `admin_account`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='后台管理员'
AUTO_INCREMENT=2

;

-- ----------------------------
-- Records of admin
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES ('1', '超级管理员', 'admin', '4a0894d6e8f3b5c6ee0c519bcb98b6b7fd0affcb343ace3a093f29da4b2535604b61f0aebd60c0f0e49cc53adba3fffb', '2017-04-28 14:54:36', '::1', 'stat,orde,prod,gmes,user,comp,sett', '0');
COMMIT;

-- ----------------------------
-- Table structure for `customer`
-- ----------------------------
DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`customer_code`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`customer_name`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`customer_shortname`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=6

;

-- ----------------------------
-- Records of customer
-- ----------------------------
BEGIN;
INSERT INTO `customer` VALUES ('3', 'JH', '江淮', '2222', '1', '2017-04-27 18:14:07', '1', '2017-04-27 18:18:48', '1'), ('4', 'CC', '长城', null, '1', '2017-04-27 18:25:13', null, '2017-04-27 18:25:13', '1'), ('5', 'JL', '吉利', null, '1', '2017-04-27 18:25:23', '1', '2017-04-27 18:25:27', '0');
COMMIT;

-- ----------------------------
-- Table structure for `goods`
-- ----------------------------
DROP TABLE IF EXISTS `goods`;
CREATE TABLE `goods` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`goods_ccode`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`goods_vcode`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`goods_name`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`vendor_id`  int(10) NOT NULL ,
`stock_id`  int(10) NOT NULL ,
`using_count`  int(10) NOT NULL DEFAULT 0 ,
`everyday_count`  int(10) NOT NULL DEFAULT 0 ,
`safe_days`  int(10) NOT NULL DEFAULT 0 ,
`goods_packing`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`packing_length`  decimal(10,2) NULL DEFAULT 0.00 ,
`packing_width`  decimal(10,2) NULL DEFAULT 0.00 ,
`packing_height`  decimal(10,2) NULL DEFAULT 0.00 ,
`packing_volume`  decimal(10,2) NULL DEFAULT 0.00 ,
`layer_put`  int(10) NULL DEFAULT 0 ,
`goods_layer_height`  int(10) NULL DEFAULT 0 ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of goods
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `model`
-- ----------------------------
DROP TABLE IF EXISTS `model`;
CREATE TABLE `model` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`customer_id`  int(10) NOT NULL ,
`model_code`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`model_name`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`model_alias`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`model_plant`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of model
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `model_goods`
-- ----------------------------
DROP TABLE IF EXISTS `model_goods`;
CREATE TABLE `model_goods` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`model_id`  int(10) NOT NULL ,
`goods_id`  int(10) NOT NULL ,
`goods_count`  decimal(10,2) NULL DEFAULT 0.00 ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of model_goods
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `person`
-- ----------------------------
DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`person_code`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`person_type`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`org_id`  int(10) NOT NULL ,
`person_name`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`person_phone`  varchar(32) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`person_email`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`person_dept`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Records of person
-- ----------------------------
BEGIN;
INSERT INTO `person` VALUES ('1', 'P001', '3', '3', '测试1', '18656060001', '000@188.com', '客服部', '1', '2017-04-28 10:33:57', '1', '2017-04-28 14:54:03', '1'), ('2', 'JH001', '2', '3', '测试2', '18656060002', null, null, '1', '2017-04-28 16:30:42', null, '2017-04-28 16:30:42', '1'), ('3', 'VE001', '1', '1', '测试3', '18656060004', '', '物流部', '1', '2017-04-28 16:31:17', '1', '2017-04-28 16:31:17', '1');
COMMIT;

-- ----------------------------
-- Table structure for `role`
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`role_name`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of role
-- ----------------------------
BEGIN;
INSERT INTO `role` VALUES ('1', '供货商1', '1', '2017-04-25 22:36:39', null, '2017-04-25 22:37:46', '1'), ('2', '供货商2', '1', '2017-04-25 22:39:13', null, '2017-04-25 22:40:05', '1');
COMMIT;

-- ----------------------------
-- Table structure for `role_person`
-- ----------------------------
DROP TABLE IF EXISTS `role_person`;
CREATE TABLE `role_person` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`role_id`  int(10) NOT NULL ,
`person_id`  int(10) NOT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=3

;

-- ----------------------------
-- Records of role_person
-- ----------------------------
BEGIN;
INSERT INTO `role_person` VALUES ('1', '0', '0', '1', '2017-04-25 22:36:39', null, '2017-04-25 22:37:46', '1'), ('2', '0', '0', '1', '2017-04-25 22:39:13', null, '2017-04-25 22:40:05', '1');
COMMIT;

-- ----------------------------
-- Table structure for `stock`
-- ----------------------------
DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`warehouse_id`  int(10) NOT NULL ,
`stock_code`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`stock_name`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`stock_area`  decimal(10,2) NULL DEFAULT 0.00 ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of stock
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `stock_loan`
-- ----------------------------
DROP TABLE IF EXISTS `stock_loan`;
CREATE TABLE `stock_loan` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`stock_id`  int(10) NOT NULL ,
`vendor_id`  int(10) NOT NULL ,
`price`  decimal(10,2) NULL DEFAULT 0.00 ,
`area`  decimal(10,2) NULL DEFAULT 0.00 ,
`remark`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of stock_loan
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `system_login_records`
-- ----------------------------
DROP TABLE IF EXISTS `system_login_records`;
CREATE TABLE `system_login_records` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`account`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`ldate`  datetime NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='后台管理员登录记录'
AUTO_INCREMENT=181

;

-- ----------------------------
-- Records of system_login_records
-- ----------------------------
BEGIN;
INSERT INTO `system_login_records` VALUES ('154', 'admin', '::1', '2017-04-25 17:52:09'), ('155', 'admin', '::1', '2017-04-25 18:00:29'), ('156', 'admin', '::1', '2017-04-25 18:00:58'), ('157', 'admin', '::1', '2017-04-25 18:01:35'), ('158', 'admin', '::1', '2017-04-25 18:01:56'), ('159', 'admin', '::1', '2017-04-25 18:02:29'), ('160', 'admin', '::1', '2017-04-25 18:04:56'), ('161', 'admin', '::1', '2017-04-26 18:08:04'), ('162', 'admin', '::1', '2017-04-27 08:50:50'), ('163', 'admin', '::1', '2017-04-27 08:53:01'), ('164', 'admin', '::1', '2017-04-27 09:30:37'), ('165', 'admin', '::1', '2017-04-27 09:51:47'), ('166', 'admin', '::1', '2017-04-27 11:51:51'), ('167', 'admin', '::1', '2017-04-27 11:55:13'), ('168', 'admin', '::1', '2017-04-27 11:57:28'), ('169', 'admin', '::1', '2017-04-27 17:50:42'), ('170', 'admin', '::1', '2017-04-27 18:16:28'), ('171', 'admin', '::1', '2017-04-27 18:30:35'), ('172', 'admin', '::1', '2017-04-27 18:38:27'), ('173', 'admin', '::1', '2017-04-27 18:41:03'), ('174', 'admin', '::1', '2017-04-27 18:42:32'), ('175', 'admin', '::1', '2017-04-27 18:43:39'), ('176', 'admin', '::1', '2017-04-27 18:46:56'), ('177', 'admin', '::1', '2017-04-28 09:11:29'), ('178', 'admin', '::1', '2017-04-28 12:57:41'), ('179', 'admin', '::1', '2017-04-28 14:47:45'), ('180', 'admin', '::1', '2017-04-28 14:54:36');
COMMIT;

-- ----------------------------
-- Table structure for `system_logs`
-- ----------------------------
DROP TABLE IF EXISTS `system_logs`;
CREATE TABLE `system_logs` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`log_level`  tinyint(2) NULL DEFAULT 0 COMMENT '错误级别' ,
`log_info`  text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '错误信息' ,
`log_url`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`log_time`  datetime NULL DEFAULT NULL ,
`log_ip`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='系统日志'
AUTO_INCREMENT=395

;

-- ----------------------------
-- Records of system_logs
-- ----------------------------
BEGIN;
INSERT INTO `system_logs` VALUES ('345', '0', '登录成功 admin', 'http://localhost', '2017-04-25 17:52:09', '::1'), ('346', '0', '访问错误：控制器 Order 不存在', 'http://localhost', '2017-04-25 17:52:10', '::1'), ('347', '0', '登录成功 admin', 'http://localhost', '2017-04-25 18:00:29', '::1'), ('348', '0', '登录成功 admin', 'http://localhost', '2017-04-25 18:00:59', '::1'), ('349', '0', '登录成功 admin', 'http://localhost', '2017-04-25 18:01:35', '::1'), ('350', '0', '访问错误：控制器 Order 不存在', 'http://localhost', '2017-04-25 18:01:36', '::1'), ('351', '0', '登录成功 admin', 'http://localhost', '2017-04-25 18:01:56', '::1'), ('352', '0', '登录成功 admin', 'http://localhost', '2017-04-25 18:02:29', '::1'), ('353', '0', '登录成功 admin', 'http://localhost', '2017-04-25 18:04:56', '::1'), ('354', '0', '登录成功 admin', 'http://localhost', '2017-04-26 18:08:04', '::1'), ('355', '0', '访问错误：方法不存在 Page->Page() 不存在', 'http://localhost', '2017-04-26 18:10:18', '::1'), ('356', '0', 'Unable to load template file \'modal_modify_vendor.html\' in \'./views/mdata/vendor_list.tpl\'', 'http://localhost', '2017-04-26 18:10:33', '::1'), ('357', '0', 'Unable to load template file \'../modal_modify_vendor.html\' in \'./views/mdata/vendor_list.tpl\'', 'http://localhost', '2017-04-26 18:11:08', '::1'), ('358', '0', 'Unable to load template file \'../modal_modify_vendor.html\' in \'./views/mdata/vendor_list.tpl\'', 'http://localhost', '2017-04-26 18:11:12', '::1'), ('359', '0', 'Unable to load template file \'modal_modify_vendor.html\' in \'./views/mdata/vendor_list.tpl\'', 'http://localhost', '2017-04-26 18:12:22', '::1'), ('360', '0', 'Syntax Error in template &quot;D:\\git\\WMS\\views\\mdata\\modal_modify_vendor.html&quot;  on line 10 &quot;&amp;lt;p ng-if=&amp;quot;{{vendor.id == 0}}&amp;quot;&amp;gt;创建&amp;lt;/p&amp;gt;供货商&quot;  - Unexpected &quot;.&quot;, expected one of: &quot;}&quot; , &quot; &quot; , ATTR', 'http://localhost', '2017-04-26 18:13:04', '::1'), ('361', '0', '访问错误：方法不存在 Vendor->getList() 不存在', 'http://localhost', '2017-04-26 18:15:39', '::1'), ('362', '0', '访问错误：方法不存在 Vendor->getList() 不存在', 'http://localhost', '2017-04-26 18:15:40', '::1'), ('363', '0', '访问错误：控制器 Vendor 不存在', 'http://localhost', '2017-04-26 18:30:04', '::1'), ('364', '0', '访问错误：方法不存在 Vendor->getById() 不存在', 'http://localhost', '2017-04-26 18:30:46', '::1'), ('365', '0', '访问错误：方法不存在 Vendor->edit() 不存在', 'http://localhost', '2017-04-26 18:40:29', '::1'), ('366', '0', '访问错误：方法不存在 Vendor->edit() 不存在', 'http://localhost', '2017-04-26 20:23:37', '::1'), ('367', '0', '访问错误：方法不存在 Vendor->getById() 不存在', 'http://localhost', '2017-04-26 20:24:27', '::1'), ('368', '0', '登录成功 admin', 'http://localhost', '2017-04-27 08:50:50', '::1'), ('369', '0', '登录成功 admin', 'http://localhost', '2017-04-27 08:53:01', '::1'), ('370', '0', '访问错误：方法不存在 Vendor->createOrUpdate() 不存在', 'http://localhost', '2017-04-27 08:56:23', '::1'), ('371', '0', '登录成功 admin', 'http://localhost', '2017-04-27 09:30:37', '::1'), ('372', '0', '登录成功 admin', 'http://localhost', '2017-04-27 09:51:47', '::1'), ('373', '0', '登录成功 admin', 'http://localhost', '2017-04-27 11:51:51', '::1'), ('374', '0', '登录成功 admin', 'http://localhost', '2017-04-27 11:55:13', '::1'), ('375', '0', '登录成功 admin', 'http://localhost', '2017-04-27 11:57:28', '::1'), ('376', '0', '登录成功 admin', 'http://localhost', '2017-04-27 17:50:42', '::1'), ('377', '0', '访问错误：方法不存在 Page->customer() 不存在', 'http://localhost', '2017-04-27 18:11:52', '::1'), ('378', '0', '登录成功 admin', 'http://localhost', '2017-04-27 18:16:28', '::1'), ('379', '0', '登录成功 admin', 'http://localhost', '2017-04-27 18:30:35', '::1'), ('380', '0', '登录成功 admin', 'http://localhost', '2017-04-27 18:38:27', '::1'), ('381', '0', '登录成功 admin', 'http://localhost', '2017-04-27 18:41:03', '::1'), ('382', '0', '登录成功 admin', 'http://localhost', '2017-04-27 18:42:32', '::1'), ('383', '0', '登录成功 admin', 'http://localhost', '2017-04-27 18:43:39', '::1'), ('384', '0', '登录成功 admin', 'http://localhost', '2017-04-27 18:46:56', '::1'), ('385', '0', '登录成功 admin', 'http://localhost', '2017-04-28 09:11:29', '::1'), ('386', '0', '访问错误：方法不存在 Page->person() 不存在', 'http://localhost', '2017-04-28 10:24:00', '::1'), ('387', '0', 'Syntax Error in template &quot;D:\\git\\YX_SMW\\views\\mdata\\modal_modify_person.html&quot;  on line 22 &quot;&amp;lt;option ng-repeat=&amp;quot;ptype in ptypelist&amp;quot; value=&amp;quot;ptype.code&amp;quot;&amp;gt;{{ptype.name}}&amp;lt;/option&amp;gt;&quot;  - Unexpected &quot;.&quot;, expected one of: &quot;}&quot; , &quot; &quot; , ATTR', 'http://localhost', '2017-04-28 10:58:08', '::1'), ('388', '0', 'Syntax Error in template &quot;D:\\git\\YX_SMW\\views\\mdata\\modal_modify_person.html&quot;  on line 22 &quot;&amp;lt;option ng-repeat=&amp;quot;ptype in ptypelist&amp;quot; value=&amp;quot;ptype.code&amp;quot;&amp;gt;{{ptype.name}}&amp;lt;/option&amp;gt;&quot;  - Unexpected &quot;.&quot;, expected one of: &quot;}&quot; , &quot; &quot; , ATTR', 'http://localhost', '2017-04-28 10:58:09', '::1'), ('389', '0', 'Syntax Error in template &quot;D:\\git\\YX_SMW\\views\\mdata\\modal_modify_person.html&quot;  on line 20 &quot;&amp;lt;select class=&amp;quot;form-control&amp;quot; ng-init=&amp;quot;selected_person_type = ptypelist[{{person.person_type}}]&amp;quot;&quot;  - Unexpected &quot;.&quot;, expected one of: &quot;}&quot; , &quot; &quot; , ATTR', 'http://localhost', '2017-04-28 12:25:11', '::1'), ('390', '0', 'Syntax Error in template &quot;D:\\git\\YX_SMW\\views\\mdata\\modal_modify_person.html&quot;  on line 17 &quot;&amp;lt;label&amp;gt;人员类型{{person.person_type}}&amp;lt;/label&amp;gt;&quot;  - Unexpected &quot;.&quot;, expected one of: &quot;}&quot; , &quot; &quot; , ATTR', 'http://localhost', '2017-04-28 12:27:51', '::1'), ('391', '0', 'Syntax Error in template &quot;D:\\git\\YX_SMW\\views\\mdata\\modal_modify_person.html&quot;  on line 17 &quot;&amp;lt;label&amp;gt;人员类型{person.person_type}&amp;lt;/label&amp;gt;&quot;  - Unexpected &quot;.&quot;, expected one of: &quot;}&quot; , &quot; &quot; , ATTR', 'http://localhost', '2017-04-28 12:28:08', '::1'), ('392', '0', '登录成功 admin', 'http://localhost', '2017-04-28 12:57:41', '::1'), ('393', '0', '登录成功 admin', 'http://localhost', '2017-04-28 14:47:45', '::1'), ('394', '0', '登录成功 admin', 'http://localhost', '2017-04-28 14:54:36', '::1');
COMMIT;

-- ----------------------------
-- Table structure for `system_settings`
-- ----------------------------
DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings` (
`key`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' ,
`value`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`last_mod`  datetime NOT NULL ,
`remark`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '无' ,
PRIMARY KEY (`key`),
INDEX `index_key` (`key`) USING BTREE 
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
COMMENT='系统设置表'

;

-- ----------------------------
-- Records of system_settings
-- ----------------------------
BEGIN;
INSERT INTO `system_settings` VALUES ('admin_setting_icon', '', '2017-01-31 19:28:54', '无'), ('admin_setting_qrcode', '', '2017-01-31 19:28:54', '无'), ('auto_envs', '0', '2017-01-31 19:28:54', '无'), ('company_on', '0', '2017-01-31 19:28:54', '无'), ('copyright', '© 亿翔云鸟 版权所有', '2017-01-31 19:28:54', '无'), ('credit_ex', '0.1', '2017-01-31 19:28:54', '无'), ('credit_order_amount', '100', '2017-01-31 19:28:54', '无'), ('dispatch_day', '2,5', '2017-03-10 17:15:20', '无'), ('dispatch_day_zone', '8:00~12:00,14:00~18:00', '2017-03-10 17:15:20', '无'), ('expcompany', 'ems,guotong,shentong,shunfeng,tiantian,yousu,yuantong,yunda,zhongtong', '2015-11-15 00:08:36', ''), ('exp_weight1', '', '2017-03-10 17:15:20', '无'), ('exp_weight2', '', '2017-03-10 17:15:20', '无'), ('order_cancel_day', '30', '2017-01-31 19:28:54', '无'), ('order_confirm_day', '30', '2017-01-31 19:28:54', '无'), ('reci_cont', '', '2017-01-31 19:28:54', '无'), ('reci_exp_open', '0', '2017-01-31 19:28:54', '无'), ('reci_open', '0', '2017-01-31 19:28:54', '无'), ('reci_perc', '', '2017-01-31 19:28:54', '无'), ('reg_credit_default', '', '2017-01-31 19:28:54', '无'), ('shopname', '毛毛虫绘本世界', '2017-01-31 19:28:54', '无'), ('sign_credit', '0', '2017-01-31 19:28:54', '无'), ('sign_daylim', '', '2017-01-31 19:28:54', '无'), ('sitename', '亿翔仓储', '0000-00-00 00:00:00', '无'), ('statcode', '', '2017-01-31 19:28:54', '无'), ('ucenter_background_image', 'uploads/default/350d/bcad0/350dacb52b9192ea83ef19b435df3fb0.jpg', '2017-01-31 19:28:54', '无'), ('welcomegmess', '', '2017-01-31 19:28:54', '无');
COMMIT;

-- ----------------------------
-- Table structure for `truck`
-- ----------------------------
DROP TABLE IF EXISTS `truck`;
CREATE TABLE `truck` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`truck_code`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`truck_type`  varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`truck_length`  decimal(2,2) NULL DEFAULT NULL ,
`truck_desc`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`truck_date`  date NULL DEFAULT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=1

;

-- ----------------------------
-- Records of truck
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for `vendor`
-- ----------------------------
DROP TABLE IF EXISTS `vendor`;
CREATE TABLE `vendor` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`vendor_code`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`vendor_name`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`vendor_shortname`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`vendor_address`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Records of vendor
-- ----------------------------
BEGIN;
INSERT INTO `vendor` VALUES ('1', 'V0001', '供货商1', 'a112是', 'ttt2s', '1', '2017-04-25 22:36:39', '1', '2017-04-27 18:14:40', '1'), ('2', 'V0002', '供货商2', null, null, '1', '2017-04-25 22:39:13', '1', '2017-04-27 10:47:38', '1'), ('3', 'V0003', '供货商3', 'test', null, '1', '2017-04-27 10:47:24', null, '2017-04-27 10:47:24', '1');
COMMIT;

-- ----------------------------
-- Table structure for `warehouse`
-- ----------------------------
DROP TABLE IF EXISTS `warehouse`;
CREATE TABLE `warehouse` (
`id`  int(10) NOT NULL AUTO_INCREMENT ,
`warehouse_code`  varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`warehouse_name`  varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`warehouse_address`  varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL ,
`create_by`  int(10) NULL DEFAULT NULL ,
`create_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ,
`update_by`  int(10) NULL DEFAULT NULL ,
`update_at`  timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ,
`isvalid`  tinyint(1) NULL DEFAULT 1 ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
AUTO_INCREMENT=4

;

-- ----------------------------
-- Records of warehouse
-- ----------------------------
BEGIN;
INSERT INTO `warehouse` VALUES ('1', 'WH01', '亿翔合肥', '合肥', '1', '2017-04-27 23:13:45', null, '2017-04-27 23:13:45', '1'), ('2', 'WH02', '亿翔无锡', '无锡', '1', '2017-04-27 23:14:03', null, '2017-04-27 23:14:03', '1'), ('3', 'WH03', '亿翔上海', '上海', '1', '2017-04-27 23:14:22', '1', '2017-04-27 23:19:28', '1');
COMMIT;

-- ----------------------------
-- View structure for `v_person`
-- ----------------------------
DROP VIEW IF EXISTS `v_person`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_person` AS select `p`.`id` AS `id`,`p`.`person_code` AS `person_code`,`p`.`person_type` AS `person_type`,`p`.`person_name` AS `person_name`,`p`.`person_phone` AS `person_phone`,`p`.`person_email` AS `person_email`,`p`.`person_dept` AS `person_dept`,`v`.`vendor_code` AS `org_code`,`v`.`vendor_name` AS `org_name` from (`person` `p` left join `vendor` `v` on((`p`.`org_id` = `v`.`id`))) where ((`p`.`person_type` = '1') and (`p`.`isvalid` = 1) and (`v`.`isvalid` = 1)) union all select `p`.`id` AS `id`,`p`.`person_code` AS `person_code`,`p`.`person_type` AS `person_type`,`p`.`person_name` AS `person_name`,`p`.`person_phone` AS `person_phone`,`p`.`person_email` AS `person_email`,`p`.`person_dept` AS `person_dept`,`c`.`customer_code` AS `org_code`,`c`.`customer_name` AS `org_name` from (`person` `p` left join `customer` `c` on((`p`.`org_id` = `c`.`id`))) where ((`p`.`person_type` = '2') and (`p`.`isvalid` = 1) and (`c`.`isvalid` = 1)) union all select `p`.`id` AS `id`,`p`.`person_code` AS `person_code`,`p`.`person_type` AS `person_type`,`p`.`person_name` AS `person_name`,`p`.`person_phone` AS `person_phone`,`p`.`person_email` AS `person_email`,`p`.`person_dept` AS `person_dept`,`w`.`warehouse_code` AS `org_code`,`w`.`warehouse_name` AS `org_name` from (`person` `p` left join `warehouse` `w` on((`p`.`org_id` = `w`.`id`))) where ((`p`.`person_type` = '3') and (`p`.`isvalid` = 1) and (`w`.`isvalid` = 1)) ;

-- ----------------------------
-- Auto increment value for `admin`
-- ----------------------------
ALTER TABLE `admin` AUTO_INCREMENT=2;

-- ----------------------------
-- Auto increment value for `customer`
-- ----------------------------
ALTER TABLE `customer` AUTO_INCREMENT=6;

-- ----------------------------
-- Auto increment value for `goods`
-- ----------------------------
ALTER TABLE `goods` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `model`
-- ----------------------------
ALTER TABLE `model` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `model_goods`
-- ----------------------------
ALTER TABLE `model_goods` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `person`
-- ----------------------------
ALTER TABLE `person` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `role`
-- ----------------------------
ALTER TABLE `role` AUTO_INCREMENT=3;

-- ----------------------------
-- Auto increment value for `role_person`
-- ----------------------------
ALTER TABLE `role_person` AUTO_INCREMENT=3;

-- ----------------------------
-- Auto increment value for `stock`
-- ----------------------------
ALTER TABLE `stock` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `stock_loan`
-- ----------------------------
ALTER TABLE `stock_loan` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `system_login_records`
-- ----------------------------
ALTER TABLE `system_login_records` AUTO_INCREMENT=181;

-- ----------------------------
-- Auto increment value for `system_logs`
-- ----------------------------
ALTER TABLE `system_logs` AUTO_INCREMENT=395;

-- ----------------------------
-- Auto increment value for `truck`
-- ----------------------------
ALTER TABLE `truck` AUTO_INCREMENT=1;

-- ----------------------------
-- Auto increment value for `vendor`
-- ----------------------------
ALTER TABLE `vendor` AUTO_INCREMENT=4;

-- ----------------------------
-- Auto increment value for `warehouse`
-- ----------------------------
ALTER TABLE `warehouse` AUTO_INCREMENT=4;
