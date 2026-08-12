-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for bsis3a
CREATE DATABASE IF NOT EXISTS `bsis3a` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `bsis3a`;

-- Dumping structure for table bsis3a.course
CREATE TABLE IF NOT EXISTS `course` (
  `id` int(11) NOT NULL,
  `CourseID` varchar(50) DEFAULT NULL,
  `CourseName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bsis3a.enrollment
CREATE TABLE IF NOT EXISTS `enrollment` (
  `id` int(11) NOT NULL,
  `EnrollmentID` varchar(50) DEFAULT NULL,
  `StudentID` varchar(50) DEFAULT NULL,
  `SubjectID` varchar(50) DEFAULT NULL,
  `TeacherID` varchar(50) DEFAULT NULL,
  `Semester` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bsis3a.section
CREATE TABLE IF NOT EXISTS `section` (
  `id` int(11) NOT NULL,
  `SectionID` varchar(50) DEFAULT NULL,
  `SectionName` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bsis3a.students
CREATE TABLE IF NOT EXISTS `students` (
  `StudentID` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Course` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`StudentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

-- Dumping structure for table bsis3a.subject
CREATE TABLE IF NOT EXISTS `subject` (
  `id` int(11) NOT NULL,
  `SubjectID` varchar(50) DEFAULT NULL,
  `SubjectCode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
