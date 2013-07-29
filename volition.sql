-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 13, 2013 at 02:49 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `volition`
--

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE IF NOT EXISTS `interests` (
  `interestID` int(11) NOT NULL AUTO_INCREMENT,
  `portID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`interestID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`interestID`, `portID`, `userID`) VALUES
(53, 106, 116);

-- --------------------------------------------------------

--
-- Table structure for table `lance`
--

CREATE TABLE IF NOT EXISTS `lance` (
  `lanceID` int(11) NOT NULL AUTO_INCREMENT,
  `lanceDisplayName` varchar(50) NOT NULL,
  `lanceDisplayImage` varchar(255) NOT NULL,
  `profTitle` varchar(40) NOT NULL,
  `lanceExp` text,
  `lanceBio` text,
  `lanceInterest` int(11) DEFAULT NULL,
  `lanceSite` varchar(50) DEFAULT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`lanceID`),
  KEY `lanceDisplayName` (`lanceDisplayName`),
  KEY `profTitle` (`profTitle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

--
-- Dumping data for table `lance`
--

INSERT INTO `lance` (`lanceID`, `lanceDisplayName`, `lanceDisplayImage`, `profTitle`, `lanceExp`, `lanceBio`, `lanceInterest`, `lanceSite`, `userID`) VALUES
(57, 'jOBS', 'train-repeat-429.gif', 'Film and Media', '', '', 1, '', 113),
(58, 'Gandalf the Grey', 'anigif_enhanced-buzz-21180-1330030142-53.gif', 'Graphic Design', '', '', 1, '', 114),
(59, 'Mickey Mouse', 'cinemagraph_ondinegoldswain1.gif', 'Photography', '', '', 1, '', 115);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `pageID` int(11) NOT NULL AUTO_INCREMENT,
  `pageName` varchar(40) NOT NULL,
  `pageTitle` varchar(20) NOT NULL,
  `pageHeading` varchar(40) NOT NULL,
  `pageDescription` text NOT NULL,
  PRIMARY KEY (`pageID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`pageID`, `pageName`, `pageTitle`, `pageHeading`, `pageDescription`) VALUES
(1, 'home', 'Home', 'My Profile', 'Volition.net.nz is New Zealand''s newest job site. Here at Volition we give user''s the freedom to show, browse and inquire their works. Register now as either a freelancer offering your services, or a seeker looking for one-off contracts.'),
(2, 'login', 'Login', 'Login', ''),
(3, 'logout', 'Logout', 'Logout', ''),
(4, 'register', 'Register', 'Register', 'Register at Volition and show your work to everyone.'),
(5, 'registerSeek', 'Register', 'Register as a Seeker', 'Registering as a Seeker allows you to view people\\''s profiles and make contact with them if you wish to hire them.'),
(6, 'registerLance', 'Register', 'Register as a Lancer', 'Registering as a Lancer allows you to make a profile that people can look at. You can also upload an on-line portfolio.'),
(7, 'editInfo', 'Edit Information', 'Edit your Information', ''),
(8, 'changePassword', 'Change Password', 'Change Password', ''),
(9, 'deleteAcc', 'Delete Your Account', 'Delete Your Account', ''),
(10, 'addImage', 'Volition - Add an Im', 'Add an Image to Your Portfolio', ''),
(12, 'browse', 'Browse', 'Browse', 'Browse through all the lancers portfolio images. If you\\''re registered, you can login and contact the lancer who made an image and get them to work for you.'),
(13, 'deletePortfolio', 'Delete an Image from', 'Delete an Image from Your Portfolio', ''),
(14, 'profile', 'User Profile', 'Profile', ''),
(15, 'about', 'About', 'About', 'What is Volition all about?'),
(16, 'contact', 'Contact', 'Contact', 'Contact Volition if you have any issues or ways to improve the site.'),
(17, 'editPortfolio', 'Edit your Portfolio', 'Edit your Portfolio', ''),
(18, '404', '404 - Page Not Found', '404 - Page Not Found', '404 - Page Not Found'),
(19, 'search', 'Search Results', 'Search Results', '');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE IF NOT EXISTS `portfolio` (
  `portID` int(11) NOT NULL AUTO_INCREMENT,
  `portName` varchar(255) NOT NULL,
  `portDescription` text,
  `portImage` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`portID`),
  KEY `portName` (`portName`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`portID`, `portName`, `portDescription`, `portImage`, `userID`) VALUES
(99, 'Life''s a Beach', '', '001-anigif-5931068676_43128ab67c_o.gif', 113),
(100, 'Bright Lights, Big City', '', '003-anigif-tweet_by_ofirabe-d3icgzn.gif', 113),
(101, 'One Swing Too Far', 'Short film about two girls on the wrong side of town.', '17_58_16_365_file.gif', 113),
(102, 'New York in the Winter', 'Documentary on the ''magic'' of New York', '30-rock-500.gif', 113),
(103, 'New York in the Winter', 'Documentary on the ''magic'' of New York', 'anigif_enhanced-buzz-16739-1330115935-92.gif', 113),
(104, 'Atlanta, City by the Sea', 'Never turn your back...', 'anigif_enhanced-buzz-20353-1330034231-14.gif', 113),
(105, 'Sk8er boi', '', 'anigif_enhanced-buzz-30212-1330034493-133.gif', 114),
(106, 'Somewhere only we know...', '', 'chelsea-hotel-4429.gif', 114),
(107, 'Taking a look in the mirror', '#neverhurts', 'driving-cinemagraph.gif', 114),
(108, 'I don''t know this is', '???', 'tumblr_m7gnq0fbI41runceko1_500.gif', 114),
(109, 'Pink Dress', '', 'Cinemagraph11.gif', 115),
(110, 'White Kicks', '', 'Cinemagraph18.gif', 115),
(111, 'White Kicks', '', 'Cinemagraph21.gif', 115),
(112, 'White Kicks', 'alert(''Dam'')', 'Cinemagraph22.gif', 115),
(113, 'Coffee', '#helpswiththeheadache', 'cinemagraph-photo-6.gif', 115),
(114, 'Cow Girl', 'Ain''t no Brokeback', 'fashion-2.gif', 115),
(115, 'Fernando Ceja', '', 'h83or.gif', 115),
(116, 'Like Water', '', 'tumblr_mjde0vky3I1rckzkco1_500.gif', 115);

-- --------------------------------------------------------

--
-- Table structure for table `seek`
--

CREATE TABLE IF NOT EXISTS `seek` (
  `seekID` int(11) NOT NULL AUTO_INCREMENT,
  `seekTitle` varchar(50) NOT NULL,
  `seekCompany` varchar(100) NOT NULL,
  `seekPhNo` int(11) NOT NULL,
  `seekAddress` text NOT NULL,
  `seekInterest` int(11) DEFAULT NULL,
  `userID` int(11) NOT NULL,
  PRIMARY KEY (`seekID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `seek`
--

INSERT INTO `seek` (`seekID`, `seekTitle`, `seekCompany`, `seekPhNo`, `seekAddress`, `seekInterest`, `userID`) VALUES
(23, '', 'Volition', 272364610, '', NULL, 116);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(40) NOT NULL,
  `userPassword` varchar(40) NOT NULL,
  `userAccess` enum('admin','seek','lance') NOT NULL,
  `userEmail` varchar(60) NOT NULL,
  `userArea` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`),
  KEY `userArea` (`userArea`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=117 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `userName`, `userPassword`, `userAccess`, `userEmail`, `userArea`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', 'joshuapost17@hotmail.com', '0'),
(113, 'AppleMan', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'lance', 'mail@mail.com', 'Nelson Bays/Marlborough'),
(114, 'GandalfStyle', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'lance', 'gandalf@middleofthearth.com', 'Otago'),
(115, 'Mickey', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'lance', 'mickey@mouse.com', 'Waikato'),
(116, 'hashtag29', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'seek', 'joshuapost17@hotmail.com', 'Otago');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
