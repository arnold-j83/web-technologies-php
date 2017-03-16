-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2017 at 01:25 PM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web_tech`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_associate_term_sp` (IN `@term_id_1` INT, IN `@term_id_2` INT, IN `@rel_id` INT)  NO SQL
INSERT INTO link_to_terms
(term_id_1, term_id_2, rel_id)
VALUES
(`@term_id_1`, `@term_id_2`, `@rel_id`)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `categories_sp` ()  NO SQL
SELECT categories.cat_name, categories.id FROM categories$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_login_sp` (IN `@username` CHAR(8), IN `@password` CHAR(64))  NO SQL
INSERT INTO login_details
(username, password)
VALUES
(`@username`, `@password`)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_reference_sp` (IN `@ref_name` VARCHAR(100), IN `@ref_url` VARCHAR(100), IN `@ref_description` VARCHAR(250))  NO SQL
INSERT INTO refs
(reference_name, reference_url, reference_description)
VALUES
(`@ref_name`, `@ref_url`, `@ref_description`)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_term_cat_sp` (IN `@term_id` INT(8), IN `@cat_id` INT(8))  NO SQL
INSERT INTO cat_link
(term_id, cat_id)
VALUES
(`@term_id`, `@cat_id`)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_term_ref_sp` (IN `@last_term_id` INT, IN `@last_reference_id` INT)  NO SQL
INSERT INTO ref_link
(term_id, ref_id)
VALUES
(`@last_term_id`, `@last_reference_id`)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `create_term_sp` (IN `@term_name` VARCHAR(50), IN `@term_description` TEXT)  NO SQL
    DETERMINISTIC
INSERT INTO web_terms
(term_name, term_description)
VALUES
(`@term_name`, `@term_description`)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_ref_id` (IN `@ref_id` INT)  NO SQL
DELETE FROM refs
WHERE refs.id = `@ref_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_term_cat_sp` (IN `@term_id` INT, IN `@cat_id` INT)  NO SQL
DELETE FROM cat_link
WHERE cat_link.term_id = `@term_id` AND cat_link.cat_id = `@cat_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_term_ref_sp` (IN `@term_id` INT, IN `@ref_id` INT)  NO SQL
DELETE FROM ref_link 
WHERE ref_link.term_id = `@term_id` and ref_link.ref_id = `@ref_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `login_REST_sp` (IN `@access_token` VARCHAR(255))  NO SQL
SELECT * FROM login_details
WHERE login_details.access_token = `@access_token`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `login_sp` (IN `@username` CHAR(8), IN `@password` CHAR(64))  NO SQL
SELECT * FROM login_details
WHERE username = `@username` AND password = `@password`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `newest_reference_sp` ()  NO SQL
SELECT * FROM refs
ORDER BY refs.id DESC
LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `newest_term_sp` ()  NO SQL
SELECT * FROM web_terms
ORDER BY web_terms.id DESC
LIMIT 1$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rel_type_sp` ()  NO SQL
SELECT rel_type.id, rel_type.rel_type_name
FROM rel_type$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_categories_sp` (IN `@catNum` INT)  NO SQL
SELECT web_terms.id, web_terms.term_name, web_terms.term_description, cat_link.term_id, cat_link.cat_id, categories.cat_name FROM web_terms INNER JOIN cat_link ON web_terms.id = cat_link.term_id INNER JOIN categories ON cat_link.cat_id = categories.id 
WHERE categories.id = `@catNum`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_termname_sp` (IN `searchText` VARCHAR(50))  NO SQL
SELECT web_terms.id, web_terms.term_name, web_terms.term_description, cat_link.term_id, cat_link.cat_id, categories.cat_name FROM web_terms INNER JOIN cat_link ON web_terms.id = cat_link.term_id INNER JOIN categories ON cat_link.cat_id = categories.id 
WHERE web_terms.term_name LIKE CONCAT('%',searchText,'%')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_term_with_category_sp` (IN `searchTerm` VARCHAR(50))  NO SQL
SELECT web_terms.id, web_terms.term_name, web_terms.term_description, cat_link.cat_id, categories.cat_name
FROM web_terms LEFT JOIN cat_link ON web_terms.id=cat_link.term_id LEFT JOIN categories on cat_link.cat_id = categories.id
WHERE web_terms.term_name LIKE CONCAT('%',searchTerm,'%') OR web_terms.term_description LIKE CONCAT('%',searchTerm,'%')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `select_terms_all_sp` ()  NO SQL
SELECT web_terms.id, web_terms.term_name, web_terms.term_description FROM web_terms
ORDER BY term_name asc$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `select_terms_sp` (IN `searchTerm` VARCHAR(50))  NO SQL
SELECT web_terms.id, web_terms.term_name, web_terms.term_description FROM web_terms
WHERE web_terms.term_name LIKE CONCAT('%',searchTerm,'%') OR web_terms.term_description LIKE CONCAT('%',searchTerm,'%')$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `show_associated_terms_sp` (IN `@term_id` INT)  NO SQL
SELECT web_terms.term_name, link_to_terms.term_id_1, link_to_terms.term_id_2, link_to_terms.rel_id, rel_type.rel_type_name
FROM web_terms INNER JOIN link_to_terms ON web_terms.id = link_to_terms.term_id_2 INNER JOIN rel_type ON link_to_terms.rel_id = rel_type.id
WHERE link_to_terms.term_id_1 = `@term_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `term_categories_sp` (IN `@term_id` INT)  NO SQL
SELECT cat_link.term_id, cat_link.cat_id, categories.cat_name FROM cat_link INNER JOIN categories ON cat_link.cat_id = categories.id
WHERE cat_link.term_id = `@term_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `term_details_sp` (IN `@term_id` INT)  NO SQL
SELECT web_terms.term_name, web_terms.term_description 
FROM web_terms
WHERE web_terms.id = `@term_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `term_references_sp` (IN `@term_id` INT)  NO SQL
SELECT refs.reference_name, refs.reference_url, refs.reference_description, ref_link.ref_id, ref_link.term_id FROM refs INNER JOIN ref_link ON refs.id = ref_link.ref_id
WHERE ref_link.term_id = `@term_id`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_term_details_sp` (IN `@term_id` INT, IN `@term_name` VARCHAR(100), IN `@term_description` TEXT)  NO SQL
UPDATE web_terms
SET web_terms.term_name = `@term_name`, web_terms.term_description = `@term_description`
WHERE web_terms.id = `@term_id`$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `cat_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `cat_name`) VALUES
(1, 'Hardware'),
(2, 'Software'),
(3, 'Protocol'),
(4, 'Programming'),
(5, 'Web Service'),
(6, 'Object Oriented');

-- --------------------------------------------------------

--
-- Table structure for table `cat_link`
--

CREATE TABLE `cat_link` (
  `term_id` int(11) DEFAULT NULL,
  `cat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cat_link`
--

INSERT INTO `cat_link` (`term_id`, `cat_id`) VALUES
(3, 6),
(12, 5),
(13, 5),
(14, 6),
(15, 4),
(3, 4),
(3, 4),
(15, 6),
(11, 3),
(11, 5),
(6, 5),
(15, 5),
(15, 5),
(16, 5);

-- --------------------------------------------------------

--
-- Table structure for table `link_to_terms`
--

CREATE TABLE `link_to_terms` (
  `term_id_1` int(11) DEFAULT NULL,
  `term_id_2` int(11) DEFAULT NULL,
  `rel_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `link_to_terms`
--

INSERT INTO `link_to_terms` (`term_id_1`, `term_id_2`, `rel_id`) VALUES
(11, 6, 3),
(6, 11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `login_details`
--

CREATE TABLE `login_details` (
  `id` int(11) NOT NULL,
  `username` char(10) DEFAULT NULL,
  `password` char(64) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `last_changed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_details`
--

INSERT INTO `login_details` (`id`, `username`, `password`, `access_token`, `last_changed`) VALUES
(2, 'johntest', 'b29f3cfe6c30881500c00d9acb5a59e87edf54fbf4f5f188f0249cbb22174e86', 'b29f3cfe6c30881500c00d9acb5a59e87edf54fbf4f5f188f0249cbb22174e86', NULL),
(4, 'Amietest', 'b29f3cfe6c30881500c00d9acb5a59e87edf54fbf4f5f188f0249cbb22174e86', NULL, NULL),
(5, 'amietest', 'b29f3cfe6c30881500c00d9acb5a59e87edf54fbf4f5f188f0249cbb22174e86', NULL, NULL),
(6, 'amietest', 'b29f3cfe6c30881500c00d9acb5a59e87edf54fbf4f5f188f0249cbb22174e86', NULL, NULL),
(7, 'amie1234', 'b29f3cfe6c30881500c00d9acb5a59e87edf54fbf4f5f188f0249cbb22174e86', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `refs`
--

CREATE TABLE `refs` (
  `id` int(11) NOT NULL,
  `reference_url` varchar(255) DEFAULT NULL,
  `reference_name` varchar(100) DEFAULT NULL,
  `reference_description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `refs`
--

INSERT INTO `refs` (`id`, `reference_url`, `reference_name`, `reference_description`) VALUES
(1, 'http://docs.oracle.com/javaee/6/tutorial/doc/gijqy.html', 'Oracle.com', 'Oracle.com website'),
(2, 'https://en.wikipedia.org/wiki/Inheritance_(object-oriented_programming)', 'Wikipedia', 'Inheritance (object-oriented programming)'),
(3, 'https://www.w3schools.com/php/php_filter.asp', 'W3 Schools', 'PHP Filters'),
(4, 'https://en.wikipedia.org/wiki/SOAP', 'Wikipedia', 'SOAP'),
(5, 'https://en.wikipedia.org/wiki/SOAP', 'Wikipedia', 'SOAP'),
(6, 'https://en.wikipedia.org/wiki/SOAP', 'Wikipedia', 'SOAP'),
(7, 'https://en.wikipedia.org/wiki/SOAP', 'Wikipedia', 'SOAP'),
(8, 'https://en.wikipedia.org/wiki/SOAP', 'Wikipedia', 'SOAP'),
(11, 'https://en.wikipedia.org/wiki/Inheritance_(object-oriented_programming)', 'Wikipedia', 'Inheritance (object-oriented programming)'),
(12, 'https://en.wikipedia.org/wiki/Inheritance_(object-oriented_programming)', 'Wikipedia', 'Inheritance (object-oriented programming)'),
(13, 'https://en.wikipedia.org/wiki/Inheritance_(object-oriented_programming)', 'Wikipedia', 'Inheritance (object-oriented programming)'),
(16, 'https://www.w3schools.com/xml/xml_soap.asp', 'W3Schools', 'SOAP XML'),
(17, 'https://en.wikipedia.org/wiki/Representational_state_transfer', 'Wikipedia', 'Representational state transfer');

-- --------------------------------------------------------

--
-- Table structure for table `ref_link`
--

CREATE TABLE `ref_link` (
  `term_id` int(11) DEFAULT NULL,
  `ref_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ref_link`
--

INSERT INTO `ref_link` (`term_id`, `ref_id`) VALUES
(13, 1),
(14, 2),
(15, 3),
(0, 4),
(0, 5),
(0, 6),
(0, 7),
(11, 8),
(3, 11),
(3, 12),
(3, 13),
(11, 16),
(6, 17);

-- --------------------------------------------------------

--
-- Table structure for table `rel_type`
--

CREATE TABLE `rel_type` (
  `id` int(11) NOT NULL,
  `rel_type_name` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rel_type`
--

INSERT INTO `rel_type` (`id`, `rel_type_name`) VALUES
(1, 'Parent'),
(2, 'Child'),
(3, 'sibling');

-- --------------------------------------------------------

--
-- Table structure for table `web_terms`
--

CREATE TABLE `web_terms` (
  `id` int(11) NOT NULL,
  `term_name` varchar(50) DEFAULT NULL,
  `term_description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `web_terms`
--

INSERT INTO `web_terms` (`id`, `term_name`, `term_description`) VALUES
(1, 'Object Oriented Programming', 'Objects are modules of code which are (often) defined in classes. Classes are the blueprints for making individual objects, they define methods and fields (variables).'),
(2, 'Http', 'Hyper Text Transfer Protocol'),
(3, 'Inheritance', 'A class can inherit the methods and properties from another class by using the keyword extends in its class declaration. A class can only inherit from one base class'),
(6, 'RESTFul Web Service', 'Representational state transfer (REST) or RESTful Web services are one way of providing interoperability between computer systems on the Internet. REST-compliant Web services allow requesting systems to access and manipulate textual representations of Web resources using a uniform and predefined set of stateless operations.'),
(11, 'SOAP', 'Simple Object Access Protocol It is important for web applications to be able to communicate over the Internet. The best way to communicate between applications is over HTTP, because HTTP is supported by all Internet browsers and servers. SOAP was created to accomplish this.  SOAP provides a way to communicate between applications running on different operating systems, with different technologies and programming languages.'),
(15, 'Sanitize', 'PHP filters are used to validate and sanitize external input. The PHP filter extension has many of the functions needed for checking user input, and is designed to make data validation easier and quicker.  Many web applications receive external input. External input/data can be: User input from a form, Cookies, Web services data, Server variables, Database query results.  You should always validate external data!  Invalid submitted data can lead to security problems and break your webpage!  By using PHP filters you can be sure your application gets the correct input!'),
(16, 'POST Method', 'The POST verb is most-often utilized to **create** new resources');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_details`
--
ALTER TABLE `login_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `refs`
--
ALTER TABLE `refs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rel_type`
--
ALTER TABLE `rel_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `web_terms`
--
ALTER TABLE `web_terms`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `login_details`
--
ALTER TABLE `login_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `refs`
--
ALTER TABLE `refs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `rel_type`
--
ALTER TABLE `rel_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `web_terms`
--
ALTER TABLE `web_terms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
