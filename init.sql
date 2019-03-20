/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : base

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2019-03-20 14:09:54
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8 NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('系统管理员', '1', '1499240608');

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item` (
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `type` int(11) NOT NULL,
  `description` text CHARACTER SET utf8,
  `rule_name` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `data` text CHARACTER SET utf8,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('category/index', '2', null, null, null, '1519629289', '1519629289');
INSERT INTO `auth_item` VALUES ('menu/create', '2', null, null, null, '1517479606', '1517479606');
INSERT INTO `auth_item` VALUES ('menu/delete', '2', null, null, null, '1517479736', '1517479736');
INSERT INTO `auth_item` VALUES ('menu/index', '2', null, null, null, '1517480393', '1517480393');
INSERT INTO `auth_item` VALUES ('menu/update', '2', null, null, null, '1517479658', '1517479658');
INSERT INTO `auth_item` VALUES ('oper-log/index', '2', null, null, null, '1517374880', '1517374880');
INSERT INTO `auth_item` VALUES ('operators/add', '2', null, null, null, '1517370854', '1517370854');
INSERT INTO `auth_item` VALUES ('operators/index', '2', null, null, null, '1517370775', '1517370775');
INSERT INTO `auth_item` VALUES ('operators/reset-pwd', '2', null, null, null, '1517370924', '1517370924');
INSERT INTO `auth_item` VALUES ('operators/update', '2', null, null, null, '1517370901', '1517370901');
INSERT INTO `auth_item` VALUES ('role/create-role', '2', null, null, null, '1517370744', '1517370744');
INSERT INTO `auth_item` VALUES ('role/index', '2', null, null, null, '1517370636', '1517370636');
INSERT INTO `auth_item` VALUES ('role/quanxian', '2', null, null, null, '1517370989', '1517370989');
INSERT INTO `auth_item` VALUES ('普通', '1', '普通', null, null, '1551667308', '1551667308');
INSERT INTO `auth_item` VALUES ('系统管理员', '1', '系统管理员', null, null, null, null);

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) CHARACTER SET utf8 NOT NULL,
  `child` varchar(64) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('普通', 'menu/create');
INSERT INTO `auth_item_child` VALUES ('普通', 'menu/delete');
INSERT INTO `auth_item_child` VALUES ('普通', 'menu/index');
INSERT INTO `auth_item_child` VALUES ('普通', 'menu/update');
INSERT INTO `auth_item_child` VALUES ('普通', 'oper-log/index');
INSERT INTO `auth_item_child` VALUES ('普通', 'operators/add');
INSERT INTO `auth_item_child` VALUES ('普通', 'operators/index');
INSERT INTO `auth_item_child` VALUES ('普通', 'operators/reset-pwd');
INSERT INTO `auth_item_child` VALUES ('普通', 'operators/update');
INSERT INTO `auth_item_child` VALUES ('普通', 'role/create-role');
INSERT INTO `auth_item_child` VALUES ('普通', 'role/index');
INSERT INTO `auth_item_child` VALUES ('普通', 'role/quanxian');

-- ----------------------------
-- Table structure for auth_menu
-- ----------------------------
DROP TABLE IF EXISTS `auth_menu`;
CREATE TABLE `auth_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `route` varchar(128) DEFAULT NULL,
  `act` varchar(256) DEFAULT NULL,
  `sort` varchar(11) DEFAULT '0',
  `data` text,
  `display` varchar(1) DEFAULT '0',
  `icon` varchar(255) DEFAULT NULL,
  `icon_disable` varchar(255) DEFAULT NULL,
  `attr` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`pid`),
  KEY `name` (`name`),
  KEY `route` (`act`(255)),
  KEY `order` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=utf8 COMMENT='系统管理员菜单权限表';

-- ----------------------------
-- Records of auth_menu
-- ----------------------------
INSERT INTO `auth_menu` VALUES ('201', '系统管理', '0', '', '', '1', null, '2', '', null, 'operators,role,oper-log,menu');
INSERT INTO `auth_menu` VALUES ('202', '角色管理', '201', 'role/index', 'role/index', '0', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('203', '角色列表', '202', '', 'role/index', '0', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('204', '添加角色', '202', '', 'role/create-role', '0', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('205', '管理员', '201', 'operators/index', 'operators/index', '1', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('206', '管理员列表', '205', '', 'operators/index', '0', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('207', '管理员添加', '205', '', 'operators/add', '0', null, '1', '', null, '');
INSERT INTO `auth_menu` VALUES ('208', '管理员修改', '205', '', 'operators/update', '0', null, '1', '', null, '');
INSERT INTO `auth_menu` VALUES ('209', '重置密码', '205', '', 'operators/reset-pwd', '0', null, '1', '', null, '');
INSERT INTO `auth_menu` VALUES ('210', '分配权限', '202', '', 'role/quanxian', '0', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('212', '菜单管理', '201', 'menu/index', 'menu/index', '0', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('213', '操作日志', '201', 'oper-log/index', 'oper-log/index', '0', null, '2', '', null, '');
INSERT INTO `auth_menu` VALUES ('216', '创建菜单', '212', '', 'menu/create', '0', null, '1', '', null, '');
INSERT INTO `auth_menu` VALUES ('217', '更新菜单', '212', '', 'menu/update', '0', null, '1', '', null, '');
INSERT INTO `auth_menu` VALUES ('218', '删除菜单', '212', '', 'menu/delete', '0', null, '1', '', null, '');
INSERT INTO `auth_menu` VALUES ('219', '菜单列表', '212', '', 'menu/index', '0', null, '1', '', null, '');

-- ----------------------------
-- Table structure for operators
-- ----------------------------
DROP TABLE IF EXISTS `operators`;
CREATE TABLE `operators` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `operator_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `operator_type` int(255) DEFAULT '0' COMMENT '1:系统管理员 2:旅游公司管理员',
  `role_id` int(10) unsigned DEFAULT '0',
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `wechat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '微信号',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `record_time` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `last_login_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员';

-- ----------------------------
-- Records of operators
-- ----------------------------
INSERT INTO `operators` VALUES ('1', 'admin', '1', '0', '系统管理员', '12345678910', '', '$2y$13$isb1XdtH8yYVfZeQvo1AQu5pVgP2BwcBbRdePyQu4JK30PCdzVZum', '2019-03-20 04:25:04', '2130706433', null);

-- ----------------------------
-- Table structure for oper_log
-- ----------------------------
DROP TABLE IF EXISTS `oper_log`;
CREATE TABLE `oper_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `record_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime DEFAULT CURRENT_TIMESTAMP,
  `user_name` varchar(16) COLLATE utf8_bin DEFAULT NULL,
  `user_type` int(2) DEFAULT '0' COMMENT '0：总管理员，1：管理员',
  `desc` varchar(255) COLLATE utf8_bin DEFAULT '',
  `op_ip` varchar(255) COLLATE utf8_bin DEFAULT '',
  `op_type` int(2) DEFAULT '0' COMMENT '操作类型：0新增类，1，修改类，2删除类，3查询类，9 其他',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='操作日志';

-- ----------------------------
-- Records of oper_log
-- ----------------------------
INSERT INTO `oper_log` VALUES ('1', '2019-03-20 04:24:50', '2019-03-20 04:24:50', 'admin', '1', '修改了管理员admin的密码', '2130706433', '1');
INSERT INTO `oper_log` VALUES ('2', '2019-03-20 06:27:23', '2019-03-20 06:27:23', 'admin', '1', '修改了管理员admin', '2130706433', '1');
