-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 14, 2024 at 06:08 PM
-- Server version: 10.3.28-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CET300_Project`
--

-- --------------------------------------------------------

--
-- Table structure for table `Files`
--

CREATE TABLE `Files` (
  `FileID` int(11) NOT NULL,
  `FileName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `UploadDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ProjectID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FilePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Files`
--

INSERT INTO `Files` (`FileID`, `FileName`, `Description`, `UploadDate`, `ProjectID`, `UserID`, `FilePath`) VALUES
(1, 'prototype.txt', 'Initial prototype, please test it', '2024-05-14 18:02:22', 3, 1, 'uploads/prototype.txt'),
(2, 'plan.txt', 'here is the plan for the report', '2024-05-14 18:03:29', 3, 5, 'uploads/plan.txt'),
(3, 'plan.txt', 'here is the plan for the report', '2024-05-14 18:03:31', 3, 5, 'uploads/plan.txt');

-- --------------------------------------------------------

--
-- Table structure for table `ProjectNotes`
--

CREATE TABLE `ProjectNotes` (
  `NoteID` int(11) NOT NULL,
  `ProjectID` int(11) DEFAULT NULL,
  `NoteContent` text NOT NULL,
  `NoteDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ProjectNotes`
--

INSERT INTO `ProjectNotes` (`NoteID`, `ProjectID`, `NoteContent`, `NoteDate`, `UserID`) VALUES
(1, 1, 'Project Lead, email for more information', '2024-05-09 14:18:26', 1),
(2, 3, 'Project lead - will focus on report', '2024-05-14 18:05:25', 5);

-- --------------------------------------------------------

--
-- Table structure for table `ProjectReports`
--

CREATE TABLE `ProjectReports` (
  `ReportID` int(11) NOT NULL,
  `ReportTitle` varchar(255) NOT NULL,
  `ReportDescription` text DEFAULT NULL,
  `ReportDate` date DEFAULT NULL,
  `ProjectID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ProjectReports`
--

INSERT INTO `ProjectReports` (`ReportID`, `ReportTitle`, `ReportDescription`, `ReportDate`, `ProjectID`) VALUES
(1, 'Budget Report', 'Â£4000 is authourised for spending and marketing', '2024-05-09', 1),
(2, 'Matty submit plan', 'Need plan in ASAP!!', '2024-05-14', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Projects`
--

CREATE TABLE `Projects` (
  `ProjectID` int(11) NOT NULL,
  `ProjectName` varchar(255) NOT NULL,
  `ProjectDescription` text DEFAULT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `ProjectManagerID` int(11) DEFAULT NULL,
  `ProjectManager` varchar(255) DEFAULT NULL,
  `CreatorUserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Projects`
--

INSERT INTO `Projects` (`ProjectID`, `ProjectName`, `ProjectDescription`, `StartDate`, `EndDate`, `ProjectManagerID`, `ProjectManager`, `CreatorUserID`) VALUES
(1, 'Web Management Portal', 'Design and Build a website to manage a project', '2024-03-09', '2024-05-31', NULL, NULL, 1),
(2, 'Ecommerce Website', 'Create the next amazon', '2024-05-17', '2025-07-19', NULL, NULL, 1),
(3, 'Assignment for Uni', 'Finish the final project of the year.', '2024-04-11', '2024-05-31', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ProjectUsers`
--

CREATE TABLE `ProjectUsers` (
  `ProjectUserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ProjectUsers`
--

INSERT INTO `ProjectUsers` (`ProjectUserID`, `ProjectID`, `UserID`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 1, 2),
(5, 1, 5),
(6, 2, 3),
(7, 2, 4),
(8, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `TaskAssignments`
--

CREATE TABLE `TaskAssignments` (
  `AssignmentID` int(11) NOT NULL,
  `TaskID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProjectID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `TaskAssignments`
--

INSERT INTO `TaskAssignments` (`AssignmentID`, `TaskID`, `UserID`, `ProjectID`) VALUES
(1, 1, 5, 3),
(2, 2, 1, 3),
(3, 3, 1, 3),
(4, 3, 5, 3),
(5, 4, 1, 1),
(6, 4, 5, 1),
(7, 5, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Tasks`
--

CREATE TABLE `Tasks` (
  `TaskID` int(11) NOT NULL,
  `TaskName` varchar(255) NOT NULL,
  `Description` text DEFAULT NULL,
  `DueDate` date DEFAULT NULL,
  `Status` varchar(50) DEFAULT 'To Do',
  `ProjectID` int(11) DEFAULT NULL,
  `AssignedToUserID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Tasks`
--

INSERT INTO `Tasks` (`TaskID`, `TaskName`, `Description`, `DueDate`, `Status`, `ProjectID`, `AssignedToUserID`) VALUES
(1, 'Make Plan', 'show me the first steps of our project', '2024-05-16', 'Done', 3, NULL),
(2, 'Start on prototype', 'design layout of the system', '2024-05-16', 'To Do', 3, NULL),
(3, 'Testing', 'both of us start testing the prototype', '2024-05-25', 'To Do', 3, NULL),
(4, 'Build dashboard', 'make a modern and unique dashboard that is user friendly', '2024-09-14', 'To Do', 1, NULL),
(5, 'Start a marketing plan', 'try and build hype for product, view reports for buget', '2024-06-14', 'To Do', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserID` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`UserID`, `UserName`, `Email`, `Password`, `Notes`) VALUES
(1, 'TaylorN', 'taylor.nich@icloud.com', 'Password', NULL),
(2, 'Craig', 'craig@email.com', 'pass', NULL),
(3, 'Alex', 'lex@email.com', 'pass', NULL),
(4, 'Jack', 'jack@email.com', 'pass', NULL),
(5, 'Matty', 'matty@email.com', 'pass', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Files`
--
ALTER TABLE `Files`
  ADD PRIMARY KEY (`FileID`),
  ADD KEY `ProjectID` (`ProjectID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `ProjectNotes`
--
ALTER TABLE `ProjectNotes`
  ADD PRIMARY KEY (`NoteID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- Indexes for table `ProjectReports`
--
ALTER TABLE `ProjectReports`
  ADD PRIMARY KEY (`ReportID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- Indexes for table `Projects`
--
ALTER TABLE `Projects`
  ADD PRIMARY KEY (`ProjectID`),
  ADD KEY `ProjectManagerID` (`ProjectManagerID`),
  ADD KEY `CreatorUserID` (`CreatorUserID`);

--
-- Indexes for table `ProjectUsers`
--
ALTER TABLE `ProjectUsers`
  ADD PRIMARY KEY (`ProjectUserID`),
  ADD KEY `fk_project_id` (`ProjectID`),
  ADD KEY `fk_user_id` (`UserID`);

--
-- Indexes for table `TaskAssignments`
--
ALTER TABLE `TaskAssignments`
  ADD PRIMARY KEY (`AssignmentID`),
  ADD KEY `TaskID` (`TaskID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ProjectID` (`ProjectID`);

--
-- Indexes for table `Tasks`
--
ALTER TABLE `Tasks`
  ADD PRIMARY KEY (`TaskID`),
  ADD KEY `ProjectID` (`ProjectID`),
  ADD KEY `AssignedToUserID` (`AssignedToUserID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Files`
--
ALTER TABLE `Files`
  MODIFY `FileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ProjectNotes`
--
ALTER TABLE `ProjectNotes`
  MODIFY `NoteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ProjectReports`
--
ALTER TABLE `ProjectReports`
  MODIFY `ReportID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Projects`
--
ALTER TABLE `Projects`
  MODIFY `ProjectID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ProjectUsers`
--
ALTER TABLE `ProjectUsers`
  MODIFY `ProjectUserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `TaskAssignments`
--
ALTER TABLE `TaskAssignments`
  MODIFY `AssignmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Tasks`
--
ALTER TABLE `Tasks`
  MODIFY `TaskID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Files`
--
ALTER TABLE `Files`
  ADD CONSTRAINT `Files_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ProjectID`),
  ADD CONSTRAINT `Files_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`);

--
-- Constraints for table `ProjectNotes`
--
ALTER TABLE `ProjectNotes`
  ADD CONSTRAINT `ProjectNotes_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ProjectID`);

--
-- Constraints for table `ProjectReports`
--
ALTER TABLE `ProjectReports`
  ADD CONSTRAINT `ProjectReports_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ProjectID`);

--
-- Constraints for table `Projects`
--
ALTER TABLE `Projects`
  ADD CONSTRAINT `Projects_ibfk_1` FOREIGN KEY (`ProjectManagerID`) REFERENCES `Users` (`UserID`),
  ADD CONSTRAINT `Projects_ibfk_2` FOREIGN KEY (`CreatorUserID`) REFERENCES `Users` (`UserID`);

--
-- Constraints for table `ProjectUsers`
--
ALTER TABLE `ProjectUsers`
  ADD CONSTRAINT `ProjectUsers_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ProjectID`),
  ADD CONSTRAINT `ProjectUsers_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`),
  ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ProjectID`),
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`);

--
-- Constraints for table `TaskAssignments`
--
ALTER TABLE `TaskAssignments`
  ADD CONSTRAINT `TaskAssignments_ibfk_1` FOREIGN KEY (`TaskID`) REFERENCES `Tasks` (`TaskID`),
  ADD CONSTRAINT `TaskAssignments_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `Users` (`UserID`),
  ADD CONSTRAINT `TaskAssignments_ibfk_3` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ProjectID`);

--
-- Constraints for table `Tasks`
--
ALTER TABLE `Tasks`
  ADD CONSTRAINT `Tasks_ibfk_1` FOREIGN KEY (`ProjectID`) REFERENCES `Projects` (`ProjectID`),
  ADD CONSTRAINT `Tasks_ibfk_2` FOREIGN KEY (`AssignedToUserID`) REFERENCES `Users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
