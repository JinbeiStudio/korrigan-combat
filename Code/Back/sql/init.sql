/*
 Navicat Premium Data Transfer

 Source Server         : Korrigans (local)
 Source Server Type    : MySQL
 Source Server Version : 80023
 Source Host           : localhost:3306
 Source Schema         : korrigans

 Target Server Type    : MySQL
 Target Server Version : 80023
 File Encoding         : 65001

 Date: 21/01/2021 02:13:54
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for __globals
-- ----------------------------
DROP TABLE IF EXISTS `__globals`;
CREATE TABLE `__globals` (
  `db_version` varchar(10) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- ----------------------------
-- Records of __globals
-- ----------------------------
BEGIN;
INSERT INTO `__globals` VALUES ('0.4.1');
COMMIT;

-- ----------------------------
-- Table structure for players
-- ----------------------------
DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'Login is unique',
  `enabled` enum('y','n') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'n' COMMENT 'Login granted only if enabled = y',
  `password` varchar(66) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'Encrypted (from plain text pass) with a trigger except if a star (*) at first char.',
  `town_level` int unsigned NOT NULL DEFAULT '1' COMMENT 'Updated from trigger on level up Tavern (buildings)',
  `level` int unsigned NOT NULL DEFAULT '0' COMMENT 'Updated from trigger on XP',
  `xp` int unsigned NOT NULL DEFAULT '0',
  `power` int unsigned NOT NULL DEFAULT '0',
  `gold` int unsigned NOT NULL DEFAULT '0' COMMENT 'Gold coins',
  `gems` int unsigned NOT NULL COMMENT 'In game money',
  `last_cnx` datetime NOT NULL COMMENT 'Not self updated',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Players';

-- ----------------------------
-- Records of players
-- ----------------------------
BEGIN;
INSERT INTO `players` VALUES (1, 'korrigans', 'y', '*36da2d17d423e73ce3cf7087d5b862f3810f26586c9c0b9663a9b1b550eda75e', 2, 3, 200, 0, 970000, 0, '2020-05-26 17:44:12');
COMMIT;

-- ----------------------------
-- Table structure for tokens
-- ----------------------------
DROP TABLE IF EXISTS `tokens`;
CREATE TABLE `tokens` (
  `player_id` int unsigned NOT NULL,
  `token` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `expiration` datetime DEFAULT NULL,
  PRIMARY KEY (`token`) USING BTREE,
  KEY `fk_tokens_player` (`player_id`),
  CONSTRAINT `fk_tokens_player` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Connection tokens';

-- ----------------------------
-- Records of tokens
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Table structure for xxx
-- ----------------------------
DROP TABLE IF EXISTS `xxx`;
CREATE TABLE `xxx` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int unsigned NOT NULL,
  `field_1` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `field_2` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- ----------------------------
-- Records of xxx
-- ----------------------------
BEGIN;
COMMIT;

-- ----------------------------
-- Function structure for make_password
-- ----------------------------
DROP FUNCTION IF EXISTS `make_password`;
delimiter ;;
CREATE FUNCTION `make_password`(pass TEXT)
 RETURNS varchar(66) CHARSET latin1
  DETERMINISTIC
BEGIN
   RETURN CONCAT("*", SHA2(pass, 256));
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table players
-- ----------------------------
DROP TRIGGER IF EXISTS `players_xp_ins`;
delimiter ;;
CREATE TRIGGER `players_xp_ins` BEFORE INSERT ON `players` FOR EACH ROW BEGIN
   SET NEW.level = xp2level(NEW.xp);
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table players
-- ----------------------------
DROP TRIGGER IF EXISTS `players_password_ins`;
delimiter ;;
CREATE TRIGGER `players_password_ins` BEFORE INSERT ON `players` FOR EACH ROW BEGIN
   IF NEW.password IS NOT NULL AND LEFT(NEW.password, 1) <> "*" THEN
      SET NEW.password = make_password(NEW.password);
   END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table players
-- ----------------------------
DROP TRIGGER IF EXISTS `players_xp_upd`;
delimiter ;;
CREATE TRIGGER `players_xp_upd` BEFORE UPDATE ON `players` FOR EACH ROW BEGIN
   IF NEW.xp <> OLD.xp THEN
			SET NEW.level = xp2level(NEW.xp);
	 END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table players
-- ----------------------------
DROP TRIGGER IF EXISTS `players_password_upd`;
delimiter ;;
CREATE TRIGGER `players_password_upd` BEFORE UPDATE ON `players` FOR EACH ROW BEGIN
   IF NEW.password IS NOT NULL AND LEFT(NEW.password, 1) <> "*" AND (NEW.password <> OLD.password OR OLD.password IS NULL) THEN
      SET NEW.password = make_password(NEW.password);
   END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table players
-- ----------------------------
DROP TRIGGER IF EXISTS `players_town_level_upd`;
delimiter ;;
CREATE TRIGGER `players_town_level_upd` BEFORE UPDATE ON `players` FOR EACH ROW BEGIN
   IF NEW.town_level <> OLD.town_level THEN
	    SELECT b.level
			INTO @level
			FROM buildings AS b
			INNER JOIN types_buildings AS tb ON tb.id = b.type_id
			WHERE b.player_id = NEW.id AND tb.name = "tavern";
      SET NEW.town_level = @level;
   END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tokens
-- ----------------------------
DROP TRIGGER IF EXISTS `token_ins`;
delimiter ;;
CREATE TRIGGER `token_ins` BEFORE INSERT ON `tokens` FOR EACH ROW BEGIN
   IF NEW.expiration IS NULL THEN
      SET NEW.expiration = NOW();
   END IF;
END
;;
delimiter ;

-- ----------------------------
-- Triggers structure for table tokens
-- ----------------------------
DROP TRIGGER IF EXISTS `token_upd`;
delimiter ;;
CREATE TRIGGER `token_upd` BEFORE UPDATE ON `tokens` FOR EACH ROW BEGIN
   IF NEW.expiration IS NULL THEN
      SET NEW.expiration = NOW();
   END IF;
END
;;
delimiter ;

SET FOREIGN_KEY_CHECKS = 1;
