# SQL Manager 2010 for MySQL 4.5.0.9
# ---------------------------------------
# Host     : 50.50.50.153
# Port     : 3306
# Database : Medic


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS=0;

DROP DATABASE IF EXISTS `Medic`;

CREATE DATABASE `Medic`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_bin';

USE `Medic`;

#
# Structure for the `Logs` table : 
#

DROP TABLE IF EXISTS `Logs`;

CREATE TABLE `Logs` (
  `id` varchar(36) NOT NULL,
  `SessionId` varchar(36) DEFAULT NULL,
  `DateTime` datetime DEFAULT NULL,
  `Head` varchar(100) DEFAULT NULL,
  `Message` text,
  PRIMARY KEY (`id`),
  KEY `SessionId` (`SessionId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Manufacturer` table : 
#

DROP TABLE IF EXISTS `Manufacturer`;

CREATE TABLE `Manufacturer` (
  `id` varchar(36) NOT NULL,
  `Name` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Products` table : 
#

DROP TABLE IF EXISTS `Products`;

CREATE TABLE `Products` (
  `id` varchar(36) DEFAULT NULL,
  `Number` varchar(50) DEFAULT NULL,
  `Name` varchar(1000) DEFAULT NULL,
  `ManufacturerId` varchar(36) DEFAULT NULL,
  `Price1` double(15,8) DEFAULT NULL,
  `Price2` double(15,8) DEFAULT NULL,
  `Count` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Session` table : 
#

DROP TABLE IF EXISTS `Session`;

CREATE TABLE `Session` (
  `id` varchar(36) NOT NULL,
  `UserId` varchar(36) DEFAULT NULL,
  `CreateDate` datetime DEFAULT NULL,
  `ExpireDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `UserId` (`UserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#
# Structure for the `Users` table : 
#

DROP TABLE IF EXISTS `Users`;

CREATE TABLE `Users` (
  `id` varchar(36) COLLATE utf8_bin NOT NULL,
  `Login` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Password` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `Hash` tinyint(1) DEFAULT NULL,
  `FirstName` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `LastName` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `Premition` int(1) DEFAULT NULL,
  UNIQUE KEY `idxLogin` (`Login`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users list';

#
# Data for the `Logs` table  (LIMIT 0,500)
#

INSERT INTO `Logs` (`id`, `SessionId`, `DateTime`, `Head`, `Message`) VALUES 
  ('03bc589f-1321-4e43-8436-f207ee2b27e8','none','2014-09-14 14:11:26','index','Togo controller: Account'),
  ('0560753d-d845-4c7b-afee-e61566132625','none','2014-09-14 13:16:07','index','Togo controller: Home'),
  ('05808972-2ec9-41cf-a931-03d6b504a22e','none','2014-09-14 14:14:49','index','Togo controller: Account'),
  ('073793a7-983f-4d3b-b190-c4ba77c2b85c','none','2014-09-14 12:07:44','index','Togo controller: Account'),
  ('079bd226-11a4-4836-8eba-ac75f6653f66','none','2014-09-14 13:29:13','index','Togo controller: Account'),
  ('0a492bf0-4557-4490-93a7-e5f307f3fa19','none','2014-09-14 14:16:21','index','Togo controller: Account'),
  ('0c733cba-7ab9-476a-9bee-ee3f9fa3c117','none','2014-09-14 13:54:07','index','Togo controller: Account'),
  ('0e5f0d44-acbe-42db-8a91-f86dc8fc6826','none','2014-09-14 12:43:37','index','Togo controller: Home'),
  ('11e49107-2375-4575-a3f7-d24fccb1ff00','none','2014-09-14 11:56:49','index','Togo controller: Account'),
  ('11eb488e-0ad7-4fea-ab57-985a2227292b','none','2014-09-14 14:00:35','index','Togo controller: Account'),
  ('147ff57a-76ce-423e-93da-18715e3aef11','none','2014-09-14 11:58:25','index','Togo controller: Account'),
  ('150f059b-7f5d-4792-a7a8-11e6c96ea525','none','2014-09-14 13:51:59','index','Togo controller: Account'),
  ('15c67382-1b9a-4253-989b-f4e7afc488e5','none','2014-09-14 12:18:55','index','Togo controller: Account'),
  ('182db16e-2b92-4e52-b27d-c27ae0f8b8db','none','2014-09-14 11:14:12','index','Togo controller: Home'),
  ('1a427564-31fe-42f4-a1e7-6d19328825aa','none','2014-09-14 13:53:50','index','Togo controller: Home'),
  ('1ce12dcc-686e-4d07-a923-631f58cb048e','none','2014-09-14 13:59:59','index','Togo controller: Home'),
  ('1d12ee80-d655-4dba-98b6-e078c6c7f12d','none','2014-09-14 14:08:56','index','Togo controller: Home'),
  ('1d594119-9a7b-4536-9683-11e1c143e2bd','none','2014-09-14 13:38:21','index','Togo controller: Home'),
  ('1d7e9add-b08f-4354-9720-bc891009b3d4','none','2014-09-14 13:08:18','index','Togo controller: Home'),
  ('1e2afea9-8e70-4eab-ab05-6760b5235665','none','2014-09-14 13:53:55','index','Togo controller: Account'),
  ('209b3026-ca65-4c76-a114-b51623f1ede4','none','2014-09-14 14:14:09','index','Togo controller: Home'),
  ('2152d73e-d955-4e96-8b68-156703960b20','none','2014-09-14 13:54:08','index','Togo controller: Home'),
  ('21afed61-ee61-4e2d-bf70-7b4a615526a2','none','2014-09-14 13:12:08','index','Togo controller: Home'),
  ('22a6b24f-8149-4cf1-836b-0b2f5ac7e690','none','2014-09-14 11:57:25','index','Togo controller: Account'),
  ('236dccd4-079e-4663-974f-84a70a8c610f','none','2014-09-14 13:01:14','index','Togo controller: Home'),
  ('2a2aa6cd-9e1b-49c1-99bf-dbdf95b3803f','none','2014-09-14 13:37:21','index','Togo controller: Account'),
  ('2b4eb536-37cd-427e-8141-5dc8fba9f981','none','2014-09-14 13:14:39','index','Togo controller: Home'),
  ('2ec50502-1376-4b62-b8a8-02567bae578b','none','2014-09-14 13:14:10','index','Togo controller: Home'),
  ('30431270-ba37-4f37-a877-75ee9958af4b','none','2014-09-14 12:07:07','index','Togo controller: Account'),
  ('31dbbbef-7577-4869-b864-0690f059d5ab','none','2014-09-14 14:01:52','index','Togo controller: Home'),
  ('3353caf9-2b7b-4e63-aaa7-e4f5da9d55db','none','2014-09-14 10:58:58','index','Togo controller: Home'),
  ('337a1ee7-f2a7-4ec5-81ad-67c71a43e113','none','2014-09-14 13:20:52','index','Togo controller: Home'),
  ('3422529e-c16b-437d-9ebd-eb2ec7d1753b','none','2014-09-14 12:06:49','index','Togo controller: Account'),
  ('35c933ed-88f3-4641-a5ac-3510aea60943','none','2014-09-14 13:33:45','index','Togo controller: Account'),
  ('369eac48-63f5-4887-b431-9dfdf231d94a','none','2014-09-14 14:05:33','index','Togo controller: Home'),
  ('383f13f1-4aff-490b-9693-fb16d3f71d9c','none','2014-09-14 12:41:59','index','Togo controller: Home'),
  ('38a9b64c-61ed-4d91-8bed-4a5490f77f20','none','2014-09-14 13:00:33','index','Togo controller: Home'),
  ('3a1a3188-e7ba-4f8b-b884-d3d72f6c4bc7','none','2014-09-14 13:19:29','index','Togo controller: Home'),
  ('3b3734b7-826a-4649-8bed-5560696392ee','none','2014-09-14 13:53:57','index','Togo controller: Account'),
  ('401e0246-3827-4b92-9bdf-4e3c759f8a56','none','2014-09-14 11:58:03','index','Togo controller: Account'),
  ('406372f1-d9c2-4fa0-99ed-8bef914f2685','none','2014-09-14 12:22:14','index','Togo controller: Account'),
  ('42c6cd67-2295-4a43-a734-de42fe1f18b1','none','2014-09-14 11:18:49','index','Togo controller: Home'),
  ('44ba0831-cd11-4f91-95d8-6e2a8ad11062','none','2014-09-14 14:14:55','index','Togo controller: Account'),
  ('46aa9bc2-16d7-49bb-939e-7a9cc6fbb9bf','none','2014-09-14 13:31:14','index','Togo controller: Account'),
  ('4c2d805e-c265-453e-b47c-19ed92bb2021','none','2014-09-14 12:06:36','index','Togo controller: Account'),
  ('4cd15c6e-b017-473f-8dc3-9518d36a1bc3','none','2014-09-14 11:12:34','index','Togo controller: Home'),
  ('4d26fbf8-0264-4b5d-a02c-45f16f10ad8b','none','2014-09-14 13:10:09','index','Togo controller: Home'),
  ('4e9191fc-02e7-4a8b-a8d3-4579ddf6e9d8','none','2014-09-14 14:15:54','index','Togo controller: Home'),
  ('5067f95b-3d0a-457b-8c29-e29941818be7','none','2014-09-14 13:21:35','index','Togo controller: Home'),
  ('50cedfc4-c660-4c24-ab7d-f86293e2413b','none','2014-09-14 13:07:31','index','Togo controller: Home'),
  ('51d54c35-d68c-4847-83d5-727bc64dc4ed','none','2014-09-14 14:15:38','index','Togo controller: Home'),
  ('5456c08f-8722-41ce-9135-5ee2f46e0971','none','2014-09-14 13:18:49','index','Togo controller: Home'),
  ('57eff980-ee8e-4b13-97b7-b20fad58dd77','none','2014-09-14 14:11:53','index','Togo controller: Account'),
  ('5ace3787-2413-4928-84e3-8ad41d5d59c9','none','2014-09-14 14:14:11','index','Togo controller: Account'),
  ('5ff0531c-bd0c-48e5-9d8a-0a791c60ed8a','none','2014-09-14 12:56:01','index','Togo controller: Home'),
  ('61172823-1928-48c5-9a6d-a763483b652c','none','2014-09-14 13:31:19','index','Togo controller: Account'),
  ('61d12c22-2e47-48c5-9766-fec1d48f7703','none','2014-09-14 12:30:42','index','Togo controller: Account'),
  ('637a5144-4cbb-4298-b1d4-7f8901fe6ee5','none','2014-09-14 13:59:07','index','Togo controller: Home'),
  ('65ead7fd-3428-486a-ba42-2a41204ce676','none','2014-09-14 14:02:36','index','Togo controller: Home'),
  ('67c95cfb-7555-4f99-adcf-fd174b08a7a1','none','2014-09-14 14:16:05','index','Togo controller: Home'),
  ('6ac9d39a-1fd6-4634-ab67-909f2a1c938e','none','2014-09-14 10:52:57','index','Togo controller: Home'),
  ('6b4504bb-bc83-41b1-b360-3d9971a14db0','none','2014-09-14 13:08:59','index','Togo controller: Home'),
  ('6d8ea04d-1afa-4ffd-ad07-869e1ebf8226','none','2014-09-14 12:24:07','index','Togo controller: Account'),
  ('6fb1f241-d6f0-466a-82cb-53ee5fcd04c5','none','2014-09-14 14:16:07','index','Togo controller: Account'),
  ('7122543c-cc4e-4034-b5c8-9a874ecac5e0','none','2014-09-14 10:56:55','index','Togo controller: Home'),
  ('728d73f3-335a-4d3e-859d-60d7192d97b6','none','2014-09-14 11:14:55','index','Togo controller: Home'),
  ('72b4d432-ded2-4448-91f5-ab1916c4c2ce','none','2014-09-14 13:25:38','index','Togo controller: Home'),
  ('7428cdd3-ddf6-4575-ad72-e840ccbab5c1','none','2014-09-14 13:19:00','index','Togo controller: Home'),
  ('7715bc85-90e6-42b1-9464-b9716c459856','none','2014-09-14 13:53:53','index','Togo controller: Home'),
  ('77d0ce0f-4ee9-4c87-bc66-2273818d94a0','none','2014-09-14 12:53:13','index','Togo controller: Home'),
  ('7c38b492-0c0d-4182-ba66-31bdda755428','none','2014-09-14 13:50:19','index','Togo controller: Account'),
  ('7cad6ddd-c77d-4e99-94e1-00de30be571a','none','2014-09-14 12:45:41','index','Togo controller: Home'),
  ('7d6a9e4b-04f0-4f4a-bf97-bc5e77b35293','none','2014-09-14 14:05:35','index','Togo controller: Account'),
  ('7e04819c-4508-406e-8a97-6c50afd2ddcd','none','2014-09-14 11:57:02','index','Togo controller: Account'),
  ('832a893b-b58e-438d-a84e-839fa1ef9ee9','none','2014-09-14 14:07:16','index','Togo controller: Home'),
  ('86c75dfa-5cb8-4938-aac1-f5928463e7f6','none','2014-09-14 13:26:28','index','Togo controller: Account'),
  ('879ddf26-b032-461c-bba3-bada6f7115f1','none','2014-09-14 13:44:59','index','Togo controller: Account'),
  ('8d5f0882-cf62-4e8e-a647-a0eea0afa0da','none','2014-09-14 13:12:44','index','Togo controller: Home'),
  ('902cb278-0add-4103-8ac2-9ec3c645a9f1','none','2014-09-14 12:42:19','index','Togo controller: Home'),
  ('939e3163-ff87-458e-a11f-cdf8f2c06ee6','none','2014-09-14 13:11:26','index','Togo controller: Home'),
  ('94c77b2e-dc7a-4e4a-986a-7f8aa0072fe7','none','2014-09-14 14:02:39','index','Togo controller: Account'),
  ('94f80cbb-b96c-4493-b381-309fdeb1a5ab','none','2014-09-14 13:43:35','index','Togo controller: Home'),
  ('954651c5-074f-4e08-abb6-c55c629adc76','none','2014-09-14 14:15:41','index','Togo controller: Account'),
  ('96006aa2-32de-417e-91af-4d79ec608ce5','none','2014-09-14 12:04:37','index','Togo controller: Account'),
  ('967c8f7f-a155-45b9-9038-4101c56ce75e','none','2014-09-14 10:53:21','index','Togo controller: Home'),
  ('97542aef-fa22-4caf-9a36-79120cdb6dfd','none','2014-09-14 13:34:43','index','Togo controller: Account'),
  ('99ebb4b1-74dd-4931-bcb1-99ec15d71e0b','none','2014-09-14 13:12:10','index','Togo controller: Home'),
  ('9affacf7-7f78-4755-827f-d60bbad977a2','none','2014-09-14 13:58:48','index','Togo controller: Account'),
  ('9bd8de88-8120-44ea-a6bc-c723ff3e5be3','none','2014-09-14 11:01:31','index','Togo controller: Home'),
  ('9fba043f-573e-426e-bbc8-6c521c33fd18','none','2014-09-14 11:13:09','index','Togo controller: Home'),
  ('a3282135-1a79-4527-a922-db645da96f7d','none','2014-09-14 13:29:28','index','Togo controller: Account'),
  ('a454fa0f-66e6-43d3-b136-5373cd223367','none','2014-09-14 14:14:30','index','Togo controller: Account'),
  ('a783c162-6916-4008-8b3d-9769ba4e2520','none','2014-09-14 12:05:05','index','Togo controller: Account'),
  ('a90e1b48-00cc-4fe0-9086-0ad871dc0fd7','none','2014-09-14 13:12:43','index','Togo controller: Home'),
  ('abdebc9d-51f3-4ee8-8116-43cf9afc3efa','none','2014-09-14 13:50:25','index','Togo controller: Account'),
  ('ac1ddad2-199f-49cf-ab7a-f32d5dc53170','none','2014-09-14 13:10:03','index','Togo controller: Home'),
  ('aeb1d41b-2304-47fc-98bb-104f0e5235a8','none','2014-09-14 14:07:18','index','Togo controller: Account'),
  ('b0410df6-2a28-4aea-9f3b-ccc18b12b371','none','2014-09-14 13:07:43','index','Togo controller: Home'),
  ('b17f738f-1277-4a01-8188-fa2dc7dc46e0','none','2014-09-14 13:44:56','index','Togo controller: Account'),
  ('b4aa2579-9787-4e42-9f7a-b127d4e4cd06','none','2014-09-14 14:00:33','index','Togo controller: Home'),
  ('b4e06a79-db3b-43ce-84ad-26dbbebbf826','none','2014-09-14 13:04:33','index','Togo controller: Home'),
  ('b50edd51-7389-4f91-bab5-f0fd0aba97cc','none','2014-09-14 13:12:30','index','Togo controller: Home'),
  ('b6074a40-a5f2-410a-9577-5f90d673c276','none','2014-09-14 13:09:13','index','Togo controller: Home'),
  ('b6b2688d-a6ca-4587-9aaf-c24718043f32','none','2014-09-14 13:26:14','index','Togo controller: Account'),
  ('bf82e0ac-e412-4a43-8e22-111307d12183','none','2014-09-14 13:44:52','index','Togo controller: Home'),
  ('c3474ecc-c133-4fa4-b2bd-59d7d0a9d90f','none','2014-09-14 13:07:04','index','Togo controller: Home'),
  ('c5afc85b-a753-461f-af49-07c3fb6c505c','none','2014-09-14 13:12:42','index','Togo controller: Home'),
  ('c643c7ad-4d40-481f-8d5f-63858af05c6a','none','2014-09-14 13:31:11','index','Togo controller: Account'),
  ('c65bad3e-abc5-4815-aaa2-b282078667c4','none','2014-09-14 13:51:34','index','Togo controller: Account'),
  ('c84250ac-2b3c-4a3e-89f3-d63ceb914543','none','2014-09-14 13:44:48','index','Togo controller: Home'),
  ('c9710714-0b38-4e43-81bd-673da6d7fa8f','none','2014-09-14 14:14:27','index','Togo controller: Home'),
  ('ca56ce16-d1ff-4f14-b425-582484190be3','none','2014-09-14 14:15:57','index','Togo controller: Account'),
  ('cb29f6da-3fbe-4f95-9db3-8ed82f2b176d','none','2014-09-14 13:58:29','index','Togo controller: Home'),
  ('cc6eb9b5-9deb-466b-8a45-05a814e18185','none','2014-09-14 12:21:54','index','Togo controller: Account'),
  ('cca07de1-c131-4d04-9529-5da51c612b20','none','2014-09-14 14:00:20','index','Togo controller: Home'),
  ('cd1f8d8a-b244-43c2-8245-a74145743b96','none','2014-09-14 13:33:27','index','Togo controller: Account'),
  ('cd831c2b-e3eb-4845-8859-faa2f0d178fd','none','2014-09-14 12:59:24','index','Togo controller: Home'),
  ('ce127290-c1e6-4f90-9895-66763011df0b','none','2014-09-14 14:00:22','index','Togo controller: Account'),
  ('cf89e408-ab8c-46f4-821d-83c7fe3d06ee','none','2014-09-14 13:34:23','index','Togo controller: Home'),
  ('cfa81588-31cd-4a1c-bf20-c9a90f62b39c','none','2014-09-14 13:58:49','index','Togo controller: Home'),
  ('d027bcf7-883f-4def-9f69-97027c79bb8a','none','2014-09-14 13:55:03','index','Togo controller: Account'),
  ('d068f094-eca5-4601-ab7d-00c109e21d21','none','2014-09-14 13:51:35','index','Togo controller: Account'),
  ('d1c87e8c-dad3-46c2-8721-3a10c90496d8','none','2014-09-14 11:56:15','index','Togo controller: Account'),
  ('d3b93ad9-03f5-4103-a7ab-c1d3e04115f3','none','2014-09-14 14:01:55','index','Togo controller: Account'),
  ('d40b7eac-e329-4e16-9ce4-872312929b80','none','2014-09-14 14:11:51','index','Togo controller: Home'),
  ('d5a85c32-872b-42f3-9c5d-cd8167fb31f4','none','2014-09-14 13:15:21','index','Togo controller: Home'),
  ('d7900102-988c-4092-b9c5-a5a0dede541b','none','2014-09-14 13:07:02','index','Togo controller: Home'),
  ('da531b01-6669-41d1-901f-4beb6a2435e1','none','2014-09-14 14:14:46','index','Togo controller: Home'),
  ('dac1c978-6cde-4e1c-b2b6-3961a075cc2f','none','2014-09-14 11:55:16','index','Togo controller: Account'),
  ('dac297af-eb93-45bc-a507-aa34037f20c9','none','2014-09-14 13:17:48','index','Togo controller: Home'),
  ('dd5c0394-e203-4ec7-a6cf-05f795affbb0','none','2014-09-14 13:13:53','index','Togo controller: Home'),
  ('e225937c-3ed5-4753-a52f-d4b62d3fd411','none','2014-09-14 13:50:14','index','Togo controller: Home'),
  ('e33accc6-4774-4d45-b3f5-0cc4f7e2c85c','none','2014-09-14 13:51:31','index','Togo controller: Home'),
  ('e34593c7-89f8-4c8f-b331-fc241e2def0c','none','2014-09-14 13:58:33','index','Togo controller: Account'),
  ('e479bd21-05a0-42d1-b359-8d430f0b2b49','s','2014-09-14 10:50:25','h','Togo controller: Home'),
  ('e56522f2-9950-4e93-ad42-0e4af97efb87','none','2014-09-14 14:08:58','index','Togo controller: Account'),
  ('e704f767-6466-478c-8659-a4331bcbc624','none','2014-09-14 13:59:09','index','Togo controller: Account'),
  ('e767cdf3-2bbc-4411-a24c-a31385013df9','none','2014-09-14 13:52:00','index','Togo controller: Account'),
  ('e7bf712b-a3a8-47bb-8e69-43a7a3b53d30','none','2014-09-14 14:00:01','index','Togo controller: Account'),
  ('e916bf82-52f8-4be6-97b8-b85040bcb7b8','none','2014-09-14 12:05:17','index','Togo controller: Account'),
  ('eae04f2a-3af3-4642-b5a8-1e9d0f8dfd48','none','2014-09-14 13:12:57','index','Togo controller: Home'),
  ('ee4d805d-449a-4d26-ad01-fe48a55cd4bb','none','2014-09-14 11:11:51','index','Togo controller: Home'),
  ('efea9d76-8b57-4241-9ac7-a9e1c64bce36','none','2014-09-14 14:11:24','index','Togo controller: Home'),
  ('f00cccaf-edd3-46eb-80aa-39b9e67bfc3a','none','2014-09-14 13:06:49','index','Togo controller: Home'),
  ('f08115b4-2625-4d47-9e27-4ff635bd2f98','none','2014-09-14 11:55:47','index','Togo controller: Account'),
  ('f136fdb3-2993-4218-aca2-19c8ba4ff47c','none','2014-09-14 14:05:09','index','Togo controller: Home'),
  ('f48e0a75-db2a-4994-b15a-da8019ad690a','none','2014-09-14 13:09:47','index','Togo controller: Home'),
  ('f5e52234-d748-4b29-809d-5dd6eb482637','none','2014-09-14 13:19:42','index','Togo controller: Home'),
  ('f7325d5f-fea3-4490-aa8a-e2ca0a027529','none','2014-09-14 11:16:02','index','Togo controller: Home'),
  ('f8b03a46-d00e-4313-9dfe-4b5643f5b36c','none','2014-09-14 13:09:56','index','Togo controller: Home'),
  ('fc9a98d4-a7b8-4b1a-9829-cdf8a4abf654','none','2014-09-14 12:42:37','index','Togo controller: Home'),
  ('fd869a68-bab3-44e7-97f9-68e0818f8bee','none','2014-09-14 13:25:57','index','Togo controller: Account'),
  ('{$guid}','{$this->$session}','0000-00-00 00:00:00','{$head}','{$message}');
COMMIT;

#
# Data for the `Session` table  (LIMIT 0,500)
#

INSERT INTO `Session` (`id`, `UserId`, `CreateDate`, `ExpireDate`) VALUES 
  ('102d75c6-b463-4686-a1fe-894b60ae08d6','423349ee-6557-4448-bbae-1e5fb4d77c22','2014-09-14 13:58:48','2014-09-14 14:58:48'),
  ('4155ee48-9c8d-4d1d-ac5a-3ca3cddab306','423349ee-6557-4448-bbae-1e5fb4d77c22','2014-09-14 12:30:42','2014-09-14 13:30:42'),
  ('65046c0d-981f-420c-b614-0cd1620ff430','423349ee-6557-4448-bbae-1e5fb4d77c22','2014-09-14 13:34:43','2014-09-14 14:34:43'),
  ('65a44220-204a-4eea-8594-f773da5623a8','423349ee-6557-4448-bbae-1e5fb4d77c22','2014-09-14 13:54:07','2014-09-14 14:54:07'),
  ('982b1a7e-4428-4350-ae7d-b29c3b0ed10c','423349ee-6557-4448-bbae-1e5fb4d77c22','2014-09-14 13:37:21','2014-09-14 14:37:21');
COMMIT;

#
# Data for the `Users` table  (LIMIT 0,500)
#

INSERT INTO `Users` (`id`, `Login`, `Password`, `Hash`, `FirstName`, `LastName`, `Premition`) VALUES 
  ('423349ee-6557-4448-bbae-1e5fb4d77c22','Logf1','pas',0,'f','l',0);
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;