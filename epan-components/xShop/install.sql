-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 02, 2014 at 03:40 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `xepan1.0.4test`
--

-- --------------------------------------------------------

--
-- Table structure for table `xshop_addblock`
--

CREATE TABLE IF NOT EXISTS `xshop_addblock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_attachments`
--

CREATE TABLE IF NOT EXISTS `xshop_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `attachment_url` varchar(255) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_id` (`product_id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_blockimages`
--

CREATE TABLE IF NOT EXISTS `xshop_blockimages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `block_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_block_id` (`block_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_categories`
--

CREATE TABLE IF NOT EXISTS `xshop_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  `categorygroup_id` int(11) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `description` text,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_categorygroup`
--

CREATE TABLE IF NOT EXISTS `xshop_categorygroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_category_product`
--

CREATE TABLE IF NOT EXISTS `xshop_category_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `is_associate` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_category_id` (`category_id`),
  KEY `fk_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_configuration`
--

CREATE TABLE IF NOT EXISTS `xshop_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text,
  `disqus_code` text,
  `add_custom_button` tinyint(1) DEFAULT NULL,
  `custom_button_text` varchar(255) DEFAULT NULL,
  `custom_button_url` varchar(255) DEFAULT NULL,
  `order_detail_email_subject` varchar(255) DEFAULT NULL,
  `order_detail_email_body` text,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_custom_fields`
--

CREATE TABLE IF NOT EXISTS `xshop_custom_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_id` (`product_id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_custom_fields_value`
--

CREATE TABLE IF NOT EXISTS `xshop_custom_fields_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customefield_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_customefield_id` (`customefield_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_discount_vouchers`
--

CREATE TABLE IF NOT EXISTS `xshop_discount_vouchers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `from` date DEFAULT NULL,
  `to` date DEFAULT NULL,
  `no_person` varchar(255) DEFAULT NULL,
  `discount_amount` varchar(255) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_discount_vouchers_used`
--

CREATE TABLE IF NOT EXISTS `xshop_discount_vouchers_used` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `discountvoucher_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`),
  KEY `fk_discountvoucher_id` (`discountvoucher_id`),
  KEY `fk_member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_group`
--

CREATE TABLE IF NOT EXISTS `xshop_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_manufacturer`
--

CREATE TABLE IF NOT EXISTS `xshop_manufacturer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `website_url` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `office_address` text,
  `zip_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_memberdetails`
--

CREATE TABLE IF NOT EXISTS `xshop_memberdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) DEFAULT NULL,
  `address` text,
  `billing_address` text,
  `shipping_address` text,
  `landmark` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `pincode` varchar(255) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_id` (`users_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_orderDetails`
--

CREATE TABLE IF NOT EXISTS `xshop_orderDetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` decimal(10,2) DEFAULT NULL,
  `unit` decimal(10,2) DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_id` (`order_id`),
  KEY `fk_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_orders`
--

CREATE TABLE IF NOT EXISTS `xshop_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `order_status` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) DEFAULT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `discount_voucher` varchar(255) DEFAULT NULL,
  `discount_voucher_amount` varchar(255) DEFAULT NULL,
  `net_amount` varchar(255) DEFAULT NULL,
  `order_summary` varchar(255) DEFAULT NULL,
  `billing_address` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(255) DEFAULT NULL,
  `order_date` varchar(255) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_productenquiry`
--

CREATE TABLE IF NOT EXISTS `xshop_productenquiry` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epan_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `contact_no` varchar(255) DEFAULT NULL,
  `email_id` varchar(255) DEFAULT NULL,
  `message` text,
  `product_name` varchar(255) DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`),
  KEY `fk_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_products`
--

CREATE TABLE IF NOT EXISTS `xshop_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sku` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `original_price` varchar(255) DEFAULT NULL,
  `sale_price` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_publish` tinyint(1) DEFAULT NULL,
  `search_string` text,
  `show_offer` tinyint(1) DEFAULT NULL,
  `allow_saleable` tinyint(1) DEFAULT NULL,
  `short_description` text,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text,
  `new` tinyint(1) DEFAULT NULL,
  `feature` tinyint(1) DEFAULT NULL,
  `latest` tinyint(1) DEFAULT NULL,
  `mostviewed` tinyint(1) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `manufacturer_id` int(11) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  `tags` text,
  `show_price` tinyint(1) DEFAULT NULL,
  `enquiry_send_to_self` tinyint(1) DEFAULT NULL,
  `enquiry_send_to_supplier` tinyint(1) DEFAULT NULL,
  `enquiry_send_to_manufacturer` tinyint(1) DEFAULT NULL,
  `product_enquiry_auto_reply` tinyint(1) DEFAULT NULL,
  `allow_comments` tinyint(1) DEFAULT NULL,
  `rank_weight` varchar(255) DEFAULT NULL,
  `comment_api` varchar(255) DEFAULT NULL,
  `show_manufacturer_detail` tinyint(1) DEFAULT NULL,
  `show_supplier_detail` tinyint(1) DEFAULT NULL,
  `show_detail` tinyint(1) DEFAULT NULL,
  `allow_attachment` tinyint(1) DEFAULT NULL,
  `allow_enquiry` tinyint(1) DEFAULT NULL,
  `add_custom_button` tinyint(1) DEFAULT NULL,
  `custom_button_text` varchar(255) DEFAULT NULL,
  `custom_button_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_supplier_id` (`supplier_id`),
  KEY `fk_manufacturer_id` (`manufacturer_id`),
  KEY `fk_epan_id` (`epan_id`),
  FULLTEXT KEY `search_string` (`search_string`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xshop_product_images`
--

CREATE TABLE IF NOT EXISTS `xshop_product_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_product_id` (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `xShop_supplier`
--

CREATE TABLE IF NOT EXISTS `xShop_supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `email_id` varchar(255) DEFAULT NULL,
  `phone_no` varchar(255) DEFAULT NULL,
  `mobile_no` varchar(255) DEFAULT NULL,
  `address` text,
  `office_address` text,
  `zip_code` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `epan_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_epan_id` (`epan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
