/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : localhost:3306
 Source Schema         : test

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 01/04/2021 09:31:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for system_crontab
-- ----------------------------
DROP TABLE IF EXISTS `system_crontab`;
CREATE TABLE `system_crontab`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务标题',
  `type` tinyint(4) NOT NULL DEFAULT 0 COMMENT '任务类型[0请求url,1执行sql,2执行shell]',
  `frequency` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务频率',
  `shell` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '任务脚本',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '任务备注',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序，越大越前',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '任务状态状态[0:禁用;1启用]',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `title`(`title`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_crontab
-- ----------------------------
INSERT INTO `system_crontab` VALUES (1, '输出 tp 版本', 2, '*/3 * * * * *', 'php think version', '每隔3秒 记录 tp 版本信息', 0, 1, 1617009687, 1617240616);

-- ----------------------------
-- Table structure for system_crontab_flow
-- ----------------------------
DROP TABLE IF EXISTS `system_crontab_flow`;
CREATE TABLE `system_crontab_flow`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sid` int(60) NOT NULL COMMENT '任务id',
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '备注',
  `create_time` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `update_time` int(11) NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `create_time`(`create_time`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '定时器任务流水表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of system_crontab_flow
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
