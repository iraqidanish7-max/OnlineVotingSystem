-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: online_voting
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin@ov.com','$2y$10$ltGGh2w0pxXLCJpOYkDq/O6zZw.HFcPdfVp95h0Cir4./I9Rnmy46','2026-02-16 15:37:16');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `candidates`
--

DROP TABLE IF EXISTS `candidates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `candidates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `party` varchar(150) NOT NULL,
  `manifesto` text NOT NULL,
  `photo` varchar(255) NOT NULL,
  `votes_count` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_candidate_user` (`user_id`),
  CONSTRAINT `fk_candidate_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidates`
--

LOCK TABLES `candidates` WRITE;
/*!40000 ALTER TABLE `candidates` DISABLE KEYS */;
INSERT INTO `candidates` VALUES (14,17,'Anjali Mathur','anjali@gmail.com','$2y$10$u.U8sd5XMCeJ.p8jY6LkT.MaGyvV7otsKZwbyneubdIwMMCzZYfxa','United scholars For Progress','Strengthen communication between students and administration through monthly open meetings.\r\nEnsure equal opportunities and fair treatment for students from all departments.\r\nCreate a centralized grievance system with proper follow-up and resolution timelines.\r\nImprove classroom infrastructure, seating, lighting, and basic facilities.\r\nPromote unity and teamwork among students through inter-department activities.\r\nSupport academic excellence through peer mentoring and study groups.\r\nEncourage student participation in decision-making processes.\r\nBuild a respectful and inclusive campus environment. |','candidate_1771277219_candidate2.png',0,1,'2026-02-16 21:26:59'),(15,18,'Angolo Kante','angolo@gmail.com','$2y$10$RMbvAS5c7dTCIECwImdkguHPw4FKb5MnxOEOyNvHlsWeIcOtdSxTS','Progressive Minds Collective','Launch regular tree plantation and campus cleanliness drives.\r\nPromote waste segregation and recycling practices across campus.\r\nReduce single-use plastic through awareness and alternatives.\r\nEncourage eco-friendly celebrations and events.\r\nInstall more dustbins and cleanliness monitoring systems.\r\nOrganize environmental awareness workshops and campaigns.\r\nPromote bicycle usage and green transport initiatives.\r\nBuild a sustainable and environmentally responsible campus.','candidate_1771277410_candidate_1768886812.png',0,1,'2026-02-16 21:30:10'),(16,19,'Alena Lopez','alena@gmail.com','$2y$10$1FZMUbBfdw8NP26awaYWyeKSVfp578h9TGCD627MGb0b3cO9AMBXC','Student Innovation Alliance','Improve hostel facilities and address student accommodation concerns.\r\nEnsure hygienic and affordable food options in canteens.\r\nEstablish mental health support and counseling access for students.\r\nCreate emergency support systems for students in need.\r\nOrganize stress-relief and wellness activities regularly.\r\nAddress safety concerns and strengthen campus security measures.\r\nProvide support for economically weaker students.\r\nMake student welfare the central priority of the union.','candidate_1771277589_candidate_1768886957.png',0,1,'2026-02-16 21:33:09'),(17,20,'Erika montella','erika@gmail.com','$2y$10$NAcbH0uned9IcVLuOkK8eOp8ttUnVoxCSiaX7YDjzMwDyjfcpQSba','United Students Voices','Conduct skill development workshops for communication and leadership.\r\nOrganize placement training and resume-building sessions.\r\nInvite industry professionals for interaction and guidance.\r\nPromote internships and project-based learning opportunities.\r\nSupport entrepreneurship and startup awareness programs.\r\nEncourage confidence-building and personality development activities.\r\nGuide students for competitive exams and career planning.\r\nPrepare students for real-world challenges beyond academics.','candidate_1771277751_candidate_1768887722.png',1,1,'2026-02-16 21:35:52'),(18,21,'Lee Hang','lee@gmail.com','$2y$10$A.7iKNfKV6Zt5at//0iOq.Q4xJgzAdhtj5PuCP/lKCaAcdiBKaQrW','Student Academic Reform','Introduce digital complaint and feedback systems for students.\r\nPromote online portals for notices and announcements.\r\nOrganize coding competitions, hackathons, and tech events.\r\nSupport technical clubs and innovation groups.\r\nEncourage practical learning through hands-on workshops.\r\nImprove Wi-Fi accessibility and digital resources on campus.\r\nPromote digital literacy for all students.\r\nBuild a technology-driven smart campus.','candidate_1771277867_candidate_1768887843.png',0,1,'2026-02-16 21:37:47');
/*!40000 ALTER TABLE `candidates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `election_settings`
--

DROP TABLE IF EXISTS `election_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `election_settings` (
  `id` int(11) NOT NULL,
  `status` enum('draft','published') NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `election_settings`
--

LOCK TABLES `election_settings` WRITE;
/*!40000 ALTER TABLE `election_settings` DISABLE KEYS */;
INSERT INTO `election_settings` VALUES (1,'draft',NULL);
/*!40000 ALTER TABLE `election_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('voter','candidate','admin') NOT NULL DEFAULT 'voter',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (17,'Anjali Mathur','anjali@gmail.com','9876543210','$2y$10$e3ZNe9NSfGkVncWZF3whceT9If3Rdh5TjDOqDyHVrtcYZUdCp4JJK','voter','2026-02-16 21:25:10'),(18,'Angolo Kante','angolo@gmail.com','9898767612','$2y$10$TK1DDNaGX3OoTm1ZEMmNs.A4OlfekuXruEosZ3ycURaTKVc21WpUW','voter','2026-02-16 21:28:51'),(19,'Alena Lopez','alena@gmail.com','8765943012','$2y$10$c.WuTPC8jP0sCZdnEWeKiuqZpHjmm3TlUdc.0iyBtIPtVDSip6b.2','voter','2026-02-16 21:31:14'),(20,'Erika montella','erika@gmail.com','9034215678','$2y$10$CJPGmr3/I.njxIK7NOc9z.I2.V2K.bUTAA5.DFei7BwFlVd7WcLZO','voter','2026-02-16 21:34:42'),(21,'Lee Hang','lee@gmail.com','7896540123','$2y$10$n7.MUoL2NYkopT.qVYDh5.gehjyx0ncSddpbAOA870mJ7ZOrklpwC','voter','2026-02-16 21:36:54'),(22,'Abdul Latif','abdullatifmoallim05@gmail.com','7888249949','$2y$10$yd781ZvcP70/hVVbdBSK6eMKZSVfJNzOox7G9Jupa.qIbqKnLDicq','voter','2026-02-16 21:40:14'),(23,'Danish Iraqi','iraqidanish7@gmail.com','9021751570','$2y$10$W13GWeL8DdkPIFfrJ07GiuzVESuLimh7xUxIM8RK/cKk0RnKcL6be','voter','2026-02-16 21:42:12');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voter_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `voted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `voter_id_unique` (`voter_id`),
  KEY `fk_votes_candidate` (`candidate_id`),
  CONSTRAINT `fk_votes_candidate` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_votes_user` FOREIGN KEY (`voter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
INSERT INTO `votes` VALUES (1,20,17,'2026-02-16 22:09:44');
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-17  3:53:21
