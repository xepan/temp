-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 02, 2014 at 02:08 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xepan-marketing`
--

-- --------------------------------------------------------

--
-- Table structure for table `xai_blocks`
--

CREATE TABLE IF NOT EXISTS `xai_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `dimension_id` int(11) DEFAULT NULL,
  `iblock_id` varchar(255) DEFAULT NULL,
  `parent_iblock_id` varchar(255) DEFAULT NULL,
  `subpage` varchar(255) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`),
  KEY `fk_dimension_id` (`dimension_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xai_config`
--

CREATE TABLE IF NOT EXISTS `xai_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `keep_site_constant_for_session` tinyint(1) DEFAULT NULL,
  `send_data_frequency` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xai_data`
--

CREATE TABLE IF NOT EXISTS `xai_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `name` text,
  PRIMARY KEY (`id`),
  KEY `fk_session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xai_dimension`
--

CREATE TABLE IF NOT EXISTS `xai_dimension` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xai_information`
--

CREATE TABLE IF NOT EXISTS `xai_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) DEFAULT NULL,
  `data_id` int(11) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `meta_information_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_session_id` (`session_id`),
  KEY `fk_data_id` (`data_id`),
  KEY `fk_meta_information_id` (`meta_information_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xai_informationextractor`
--

CREATE TABLE IF NOT EXISTS `xai_informationextractor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_data_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` text,
  `order` int(11) DEFAULT NULL,
  `repetation_handler` varchar(255) DEFAULT NULL,
  `mark_triggering_information` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_meta_data_id` (`meta_data_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `xai_informationextractor`
--

INSERT INTO `xai_informationextractor` (`id`, `meta_data_id`, `name`, `code`, `order`, `repetation_handler`, `mark_triggering_information`) VALUES
(1, 1, 'Country', '$loc_info = @file_get_contents(''http://ipinfo.io/''.$_SERVER[''REMOTE_ADDR''].''/json'');\r\n$loc_info = json_decode($loc_info,true);\r\n$result=$loc_info[''country''];', 0, 'discard_if_repeated_key', 1),
(2, 1, 'City', '$result=$loc_info[''city''];', 1, 'discard_if_repeated_key', 1),
(3, 1, 'Browser', '$browser = $this->add(''xAi/Controller_Utility'')->getBrowserOS();\r\n$result=$browser[''browser''];', 2, 'discard_if_repeated_key', 1),
(4, 1, 'OS', '$result=$browser[''platform''];', 3, 'discard_if_repeated_key', 1),
(5, 1, 'Landing Page', '$result=$this->api->page_requested;', 7, 'discard_if_repeated_key', 1),
(6, 1, 'Exit Page', '$result = $this->api->page_requested;', 8, 'update_last_value', 0),
(7, 1, 'Total Visit', '$result = $isBot?0:1;', 5, 'discard_if_repeated_key', 0),
(8, 1, 'Returning Visit', '$result = isset( $_COOKIE[''web''] ) ? 1 : 0 ;', 6, 'discard_if_repeated_key', 1),
(9, 43, 'iblock', '$temp = $this->add(''xAi/Model_IBlockContent'');\r\n$temp->addCondition(''iblock_id'',$current_data_value);\r\n$temp->tryLoadAny();\r\n$result = $temp->id;', 0, 'always_add', 0),
(10, 1, 'isBot', '$isBot=isset($_SERVER[''HTTP_USER_AGENT'']) && preg_match(''/bot|crawl|slurp|spider/i'', $_SERVER[''HTTP_USER_AGENT''])?1:0;\r\n$result=$isBot;', 4, 'discard_if_repeated_key', 0),
(11, 37, 'Referrer', '$self_host = $_SERVER[''HTTP_HOST''];\r\n$self_host = parse_url($self_host);\r\n$self_host = $self_host[''host''];\r\n\r\n$ref=$current_data_value;\r\n$ref_host = parse_url($ref);\r\n$ref_host = $ref_host[''host''];\r\n\r\nif($ref_host == $self_host){\r\n$result=null;\r\n}else{\r\n$result = $current_data_value;\r\n}', 0, 'discard_if_repeated_key', 0);

-- --------------------------------------------------------

--
-- Table structure for table `xai_meta_data`
--

CREATE TABLE IF NOT EXISTS `xai_meta_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `last_value` text,
  `description` text,
  `action` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=63 ;

--
-- Dumping data for table `xai_meta_data`
--

INSERT INTO `xai_meta_data` (`id`, `from`, `name`, `last_value`, `description`, `action`) VALUES
(1, 'ALWAYS', 'ALWAYS', 'RUN', NULL, '2'),
(2, 'SERVER', 'HTTP_HOST', 'localhost', NULL, '-1'),
(3, 'SERVER', 'HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:32.0) Gecko/20100101 Firefox/32.0', NULL, '-1'),
(4, 'SERVER', 'HTTP_ACCEPT', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', NULL, '-1'),
(5, 'SERVER', 'HTTP_ACCEPT_LANGUAGE', 'en-US,en;q=0.5', NULL, '-1'),
(6, 'SERVER', 'HTTP_ACCEPT_ENCODING', 'gzip, deflate', NULL, '-1'),
(7, 'SERVER', 'HTTP_COOKIE', 'owa_u=admin; owa_p=e034fb6b66aacc1d48f445ddfb08da98; owa_v=cdh%3D%3Ecad54798%7C%7C%7Cvid%3D%3E1412002036761815615%7C%7C%7Cfsts%3D%3E1412002036%7C%7C%7Cdsfs%3D%3E0%7C%7C%7Cnps%3D%3E2; owa_s=cdh%3D%3Ecad54798%7C%7C%7Clast_req%3D%3E1412005334%7C%7C%7Csid%3D%3E1412005316669589375%7C%7C%7Cdsps%3D%3E0%7C%7C%7Creferer%3D%3E%28none%29%7C%7C%7Cmedium%3D%3Edirect%7C%7C%7Csource%3D%3E%28none%29%7C%7C%7Csearch_terms%3D%3E%28none%29; _ga=GA1.1.1803361123.1413127967; web=lr6okb3qvu73vkm543tg91t8l6', NULL, '-1'),
(8, 'SERVER', 'HTTP_CONNECTION', 'keep-alive', NULL, '-1'),
(9, 'SERVER', 'HTTP_CACHE_CONTROL', 'max-age=0', NULL, '-1'),
(10, 'SERVER', 'PATH', '/usr/local/bin:/usr/bin:/bin', NULL, '-1'),
(11, 'SERVER', 'SERVER_SIGNATURE', '<address>Apache/2.2.22 (Ubuntu) Server at localhost Port 80</address>\n', NULL, '-1'),
(12, 'SERVER', 'SERVER_SOFTWARE', 'Apache/2.2.22 (Ubuntu)', NULL, '-1'),
(13, 'SERVER', 'SERVER_NAME', 'localhost', NULL, '-1'),
(14, 'SERVER', 'SERVER_ADDR', '127.0.0.1', NULL, '-1'),
(15, 'SERVER', 'SERVER_PORT', '80', NULL, '-1'),
(16, 'SERVER', 'REMOTE_ADDR', '127.0.0.1', NULL, '-1'),
(17, 'SERVER', 'DOCUMENT_ROOT', '/var/www', NULL, '-1'),
(18, 'SERVER', 'SERVER_ADMIN', 'webmaster@localhost', NULL, '-1'),
(19, 'SERVER', 'SCRIPT_FILENAME', '/var/www/xepanAi/index.php', NULL, '-1'),
(20, 'SERVER', 'REMOTE_PORT', '47015', NULL, '-1'),
(21, 'SERVER', 'GATEWAY_INTERFACE', 'CGI/1.1', NULL, '-1'),
(22, 'SERVER', 'SERVER_PROTOCOL', 'HTTP/1.1', NULL, '-1'),
(23, 'SERVER', 'REQUEST_METHOD', 'GET', NULL, '-1'),
(24, 'SERVER', 'QUERY_STRING', '', NULL, '-1'),
(25, 'SERVER', 'REQUEST_URI', '/xepanAi/', NULL, '-1'),
(26, 'SERVER', 'SCRIPT_NAME', '/xepanAi/index.php', NULL, '-1'),
(27, 'SERVER', 'PHP_SELF', '/xepanAi/index.php', NULL, '-1'),
(28, 'SERVER', 'REQUEST_TIME', '1414144917', NULL, '-1'),
(29, 'COOKIE', 'owa_u', '', '', '-1'),
(30, 'COOKIE', 'owa_p', '', '', '-1'),
(31, 'COOKIE', 'owa_v', '', '', '-1'),
(32, 'COOKIE', 'owa_s', '', '', '-1'),
(33, 'COOKIE', '_ga', '', '', '-1'),
(34, 'COOKIE', 'web', '', '', '-1'),
(35, 'SERVER', 'CONTENT_TYPE', 'application/x-www-form-urlencoded; charset=UTF-8', NULL, '-1'),
(36, 'SERVER', 'HTTP_X_REQUESTED_WITH', 'XMLHttpRequest', NULL, '-1'),
(37, 'SERVER', 'HTTP_REFERER', 'http://localhost/xepanAi/', '', '1'),
(38, 'SERVER', 'CONTENT_LENGTH', '79', NULL, '-1'),
(39, 'SERVER', 'HTTP_PRAGMA', 'no-cache', NULL, '-1'),
(40, 'GET', 'page', 'xAi_page_recordContentVisits', NULL, '-1'),
(41, 'POST', 'iblock_id', '3fac7528-6e54-4e46-91e9-bffd964a394f', NULL, '-1'),
(42, 'POST', 'dimension_id', '2', NULL, '-1'),
(43, 'POST', 'eventname', 'Visible', '', '2'),
(44, 'GET', 'cut_page', '1', NULL, '-1'),
(50, 'SERVER', 'HTTP_ORIGIN', 'http://localhost', NULL, '-1'),
(51, 'SERVER', 'UNIQUE_ID', 'VE4MHn8AAQEAABO3OUkAAAAA', NULL, '-1'),
(52, 'SERVER', 'REQUEST_SCHEME', 'http', NULL, '-1'),
(53, 'SERVER', 'CONTEXT_PREFIX', '', NULL, '-1'),
(54, 'SERVER', 'CONTEXT_DOCUMENT_ROOT', '/var/www', NULL, '-1'),
(55, 'SERVER', 'REQUEST_TIME_FLOAT', '1414401054.961', NULL, '-1'),
(56, 'GET', 'epan', 'web', NULL, '-1'),
(57, 'GET', 'subpage', 'home', NULL, '-1'),
(58, 'GET', 'email', 'gowravvishwakarma@gmail.com', NULL, '-1'),
(59, 'GET', 'category_id', '14', NULL, '-1'),
(60, 'SERVER', 'HTTP_VIA', '1.1 mail (squid/3.3.8)', NULL, '-1'),
(61, 'SERVER', 'HTTP_X_FORWARDED_FOR', '192.168.1.104', NULL, '-1'),
(62, 'GET', 'xEnquiryNSubscription_categrory_id', '14', NULL, '-1');

-- --------------------------------------------------------

--
-- Table structure for table `xai_meta_information`
--

CREATE TABLE IF NOT EXISTS `xai_meta_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_triggering` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `xai_meta_information`
--

INSERT INTO `xai_meta_information` (`id`, `name`, `is_triggering`) VALUES
(1, 'Country', 1),
(2, 'City', 1),
(3, 'Browser', 1),
(4, 'OS', 1),
(5, 'Landing Page', 1),
(6, 'Exit Page', 0),
(7, 'Total Visit', 0),
(8, 'Returning Visit', 1),
(9, 'iblock', 0),
(10, 'isBot', 0),
(11, 'Referrer', 0);

-- --------------------------------------------------------

--
-- Table structure for table `xai_sales_executive`
--

CREATE TABLE IF NOT EXISTS `xai_sales_executive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xai_session`
--

CREATE TABLE IF NOT EXISTS `xai_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `is_goal_achieved` tinyint(1) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xai_visual_analytic`
--

CREATE TABLE IF NOT EXISTS `xai_visual_analytic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `push_to_main_dashboard` tinyint(1) DEFAULT NULL,
  `push_to_analytic_dashboard` tinyint(1) DEFAULT NULL,
  `group_by` varchar(255) DEFAULT NULL,
  `visual_style` varchar(255) DEFAULT NULL,
  `use_gloabl_timespan` tinyint(1) DEFAULT NULL,
  `limit_top` varchar(255) DEFAULT NULL,
  `chart_title` varchar(255) DEFAULT NULL,
  `chart_sub_title` varchar(255) DEFAULT NULL,
  `grid_group_by_meta_information_id` int(11) DEFAULT NULL,
  `grid_value` varchar(255) DEFAULT NULL,
  `main_dashboard_order` int(11) DEFAULT NULL,
  `span_on_main_dashboard` int(11) DEFAULT NULL,
  `analytic_dashboard_order` int(11) DEFAULT NULL,
  `span_on_analytic_dashboard` int(11) DEFAULT NULL,
  `push_to_live_dashboard` tinyint(1) DEFAULT NULL,
  `live_dashboard_order` int(11) DEFAULT NULL,
  `span_on_live_dashboard` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_grid_group_by_meta_information_id` (`grid_group_by_meta_information_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `xai_visual_analytic`
--

INSERT INTO `xai_visual_analytic` (`id`, `name`, `push_to_main_dashboard`, `push_to_analytic_dashboard`, `group_by`, `visual_style`, `use_gloabl_timespan`, `limit_top`, `chart_title`, `chart_sub_title`, `grid_group_by_meta_information_id`, `grid_value`, `main_dashboard_order`, `span_on_main_dashboard`, `analytic_dashboard_order`, `span_on_analytic_dashboard`, `push_to_live_dashboard`, `live_dashboard_order`, `span_on_live_dashboard`) VALUES
(1, 'Total Visitors', 1, 1, 'Date', 'line', 1, '20', 'Total Visitors', '', NULL, NULL, 1, 6, 1, 6, 0, 0, 0),
(2, 'Top Landing Pages', 1, 1, NULL, 'grid', 1, '10', 'Landing Pages', '', 5, 'WEIGHTSUM', 2, 3, 2, 2, 1, 2, 2),
(3, 'Top Browsers', 0, 1, NULL, 'grid', 1, '10', 'Browsers', '', 3, 'COUNT', 0, 0, 3, 2, 0, 0, 0),
(4, 'Top Exit Page', 1, 1, NULL, 'grid', 1, '10', 'Exit Pages', '', 6, 'COUNT', 4, 3, 4, 3, 0, 0, 0),
(5, 'Top Referrer Sites', 0, 1, NULL, 'grid', 1, '10', 'Referrers', '', 11, 'WEIGHTSUM', 0, 0, 3, 2, 0, 0, 0),
(6, 'Users Live On Site', 0, 0, NULL, 'grid', 1, '20', 'Visitors', '', 7, 'COUNT', 0, 0, 0, 0, 1, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `xai_visual_analytic_series`
--

CREATE TABLE IF NOT EXISTS `xai_visual_analytic_series` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meta_information_id` int(11) DEFAULT NULL,
  `visual_analytic_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_meta_information_id` (`meta_information_id`),
  KEY `fk_visual_analytic_id` (`visual_analytic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `xai_visual_analytic_series`
--

INSERT INTO `xai_visual_analytic_series` (`id`, `meta_information_id`, `visual_analytic_id`, `name`) VALUES
(1, 7, 1, 'SUM');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
