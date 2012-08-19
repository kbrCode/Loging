-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 19 Sie 2012, 03:18
-- Wersja serwera: 5.5.24-log
-- Wersja PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `logowanie`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fx_account`
--

CREATE TABLE IF NOT EXISTS `fx_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user_id` int(11) NOT NULL,
  `fk_spam_ip_id` int(11) NOT NULL,
  `data_blokady` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data_odblokowania` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip_odblokowania` varchar(12) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_spam_ip_id` (`fk_spam_ip_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fx_spam_ip`
--

CREATE TABLE IF NOT EXISTS `fx_spam_ip` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(12) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fx_user`
--

CREATE TABLE IF NOT EXISTS `fx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `haslo` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `aktywne` enum('tak','nie') NOT NULL,
  `role` varchar(15) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Zrzut danych tabeli `fx_user`
--

INSERT INTO `fx_user` (`id`, `login`, `haslo`, `email`, `aktywne`, `role`) VALUES
(1, 'user1', 'pass', 'user1@gmail.com', 'tak', 'administrator');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `id` char(32) NOT NULL,
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Zrzut danych tabeli `session`
--

INSERT INTO `session` (`id`, `modified`, `lifetime`, `data`) VALUES
('br6g7mt2gkped1rs1mdhk7c153', 1345161152, 864000, 'Default|a:4:{s:3:"acl";O:8:"Zend_Acl":6:{s:16:"\0*\0_roleRegistry";O:22:"Zend_Acl_Role_Registry":1:{s:9:"\0*\0_roles";a:7:{s:5:"guest";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"guest";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:8:"someUser";}s:5:"staff";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"staff";}}}s:6:"member";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"member";}s:7:"parents";a:0:{}s:8:"children";a:1:{s:8:"someUser";r:10;}}s:5:"admin";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"admin";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";r:10;s:13:"administrator";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:13:"administrator";}}}s:8:"someUser";a:3:{s:8:"instance";r:10;s:7:"parents";a:3:{s:5:"guest";r:6;s:6:"member";r:15;s:5:"admin";r:21;}s:8:"children";a:0:{}}s:5:"staff";a:3:{s:8:"instance";r:12;s:7:"parents";a:1:{s:5:"guest";r:6;}s:8:"children";a:1:{s:6:"editor";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"editor";}}}s:6:"editor";a:3:{s:8:"instance";r:40;s:7:"parents";a:1:{s:5:"staff";r:12;}s:8:"children";a:0:{}}s:13:"administrator";a:3:{s:8:"instance";r:26;s:7:"parents";a:1:{s:5:"admin";r:21;}s:8:"children";a:0:{}}}}s:13:"\0*\0_resources";a:0:{}s:17:"\0*\0_isAllowedRole";N;s:21:"\0*\0_isAllowedResource";N;s:22:"\0*\0_isAllowedPrivilege";N;s:9:"\0*\0_rules";a:2:{s:12:"allResources";a:2:{s:8:"allRoles";a:2:{s:13:"allPrivileges";a:2:{s:4:"type";s:9:"TYPE_DENY";s:6:"assert";N;}s:13:"byPrivilegeId";a:0:{}}s:8:"byRoleId";a:3:{s:5:"guest";a:2:{s:13:"byPrivilegeId";a:1:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:6:"member";a:2:{s:13:"byPrivilegeId";a:3:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:14:"deleteGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:7:"editSeo";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:5:"admin";a:2:{s:13:"byPrivilegeId";a:0:{}s:13:"allPrivileges";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}}}s:12:"byResourceId";a:0:{}}}s:13:"invalidLogins";i:3;s:11:"showCaptcha";b:1;s:14:"invalidCaptcha";i:2;}__ZF|a:8:{s:50:"Zend_Form_Captcha_06830423f444c4b2a3f3ef20a0bb5f5b";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345160619;}s:50:"Zend_Form_Captcha_21a52677cd4922f10c9fadd202792c76";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345160655;}s:50:"Zend_Form_Captcha_31d1b9be17f124f4e9398691213ce3c4";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345160695;}s:50:"Zend_Form_Captcha_35bfc422fc53128581440afbda27abce";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345160712;}s:50:"Zend_Form_Captcha_db3b56ef1ba08ace39a4c02e3381ad9e";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345160801;}s:50:"Zend_Form_Captcha_63e9ad5a48aa40cdd3add74eca30a7bd";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345160898;}s:50:"Zend_Form_Captcha_a6703a7f577448bc1754bb12a889d938";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345161452;}s:32:"Zend_Form_Element_Hash_salt_csrf";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345161452;}}Zend_Form_Captcha_06830423f444c4b2a3f3ef20a0bb5f5b|a:1:{s:4:"word";s:5:"jaxex";}Zend_Form_Captcha_21a52677cd4922f10c9fadd202792c76|a:1:{s:4:"word";s:5:"simiz";}Zend_Form_Captcha_31d1b9be17f124f4e9398691213ce3c4|a:1:{s:4:"word";s:5:"kofiq";}Zend_Form_Captcha_35bfc422fc53128581440afbda27abce|a:1:{s:4:"word";s:5:"xytij";}Zend_Form_Captcha_a6703a7f577448bc1754bb12a889d938|a:1:{s:4:"word";s:5:"diqec";}Zend_Form_Element_Hash_salt_csrf|a:1:{s:4:"hash";s:32:"fa183dfd96650cf2faf81df5a8613932";}'),
('fcjg89u6fa5tfkvucl05dna9l5', 1345074434, 864000, 'Default|a:1:{s:3:"acl";O:8:"Zend_Acl":6:{s:16:"\0*\0_roleRegistry";O:22:"Zend_Acl_Role_Registry":1:{s:9:"\0*\0_roles";a:7:{s:5:"guest";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"guest";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:8:"someUser";}s:5:"staff";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"staff";}}}s:6:"member";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"member";}s:7:"parents";a:0:{}s:8:"children";a:1:{s:8:"someUser";r:10;}}s:5:"admin";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"admin";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";r:10;s:13:"administrator";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:13:"administrator";}}}s:8:"someUser";a:3:{s:8:"instance";r:10;s:7:"parents";a:3:{s:5:"guest";r:6;s:6:"member";r:15;s:5:"admin";r:21;}s:8:"children";a:0:{}}s:5:"staff";a:3:{s:8:"instance";r:12;s:7:"parents";a:1:{s:5:"guest";r:6;}s:8:"children";a:1:{s:6:"editor";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"editor";}}}s:6:"editor";a:3:{s:8:"instance";r:40;s:7:"parents";a:1:{s:5:"staff";r:12;}s:8:"children";a:0:{}}s:13:"administrator";a:3:{s:8:"instance";r:26;s:7:"parents";a:1:{s:5:"admin";r:21;}s:8:"children";a:0:{}}}}s:13:"\0*\0_resources";a:0:{}s:17:"\0*\0_isAllowedRole";N;s:21:"\0*\0_isAllowedResource";N;s:22:"\0*\0_isAllowedPrivilege";N;s:9:"\0*\0_rules";a:2:{s:12:"allResources";a:2:{s:8:"allRoles";a:2:{s:13:"allPrivileges";a:2:{s:4:"type";s:9:"TYPE_DENY";s:6:"assert";N;}s:13:"byPrivilegeId";a:0:{}}s:8:"byRoleId";a:3:{s:5:"guest";a:2:{s:13:"byPrivilegeId";a:1:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:6:"member";a:2:{s:13:"byPrivilegeId";a:3:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:14:"deleteGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:7:"editSeo";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:5:"admin";a:2:{s:13:"byPrivilegeId";a:0:{}s:13:"allPrivileges";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}}}s:12:"byResourceId";a:0:{}}}}Zend_Auth|a:1:{s:7:"storage";O:8:"stdClass":4:{s:2:"id";s:1:"1";s:5:"login";s:5:"user1";s:5:"email";s:15:"user1@gmail.com";s:7:"aktywne";s:3:"tak";}}'),
('tqijv0nffglsme2r64cd42of72', 1345346096, 864000, 'Default|a:1:{s:3:"acl";O:8:"Zend_Acl":6:{s:16:"\0*\0_roleRegistry";O:22:"Zend_Acl_Role_Registry":1:{s:9:"\0*\0_roles";a:7:{s:5:"guest";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"guest";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:8:"someUser";}s:5:"staff";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"staff";}}}s:6:"member";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"member";}s:7:"parents";a:0:{}s:8:"children";a:1:{s:8:"someUser";r:10;}}s:5:"admin";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"admin";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";r:10;s:13:"administrator";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:13:"administrator";}}}s:8:"someUser";a:3:{s:8:"instance";r:10;s:7:"parents";a:3:{s:5:"guest";r:6;s:6:"member";r:15;s:5:"admin";r:21;}s:8:"children";a:0:{}}s:5:"staff";a:3:{s:8:"instance";r:12;s:7:"parents";a:1:{s:5:"guest";r:6;}s:8:"children";a:1:{s:6:"editor";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"editor";}}}s:6:"editor";a:3:{s:8:"instance";r:40;s:7:"parents";a:1:{s:5:"staff";r:12;}s:8:"children";a:0:{}}s:13:"administrator";a:3:{s:8:"instance";r:26;s:7:"parents";a:1:{s:5:"admin";r:21;}s:8:"children";a:0:{}}}}s:13:"\0*\0_resources";a:0:{}s:17:"\0*\0_isAllowedRole";N;s:21:"\0*\0_isAllowedResource";N;s:22:"\0*\0_isAllowedPrivilege";N;s:9:"\0*\0_rules";a:2:{s:12:"allResources";a:2:{s:8:"allRoles";a:2:{s:13:"allPrivileges";a:2:{s:4:"type";s:9:"TYPE_DENY";s:6:"assert";N;}s:13:"byPrivilegeId";a:0:{}}s:8:"byRoleId";a:3:{s:5:"guest";a:2:{s:13:"byPrivilegeId";a:1:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:6:"member";a:2:{s:13:"byPrivilegeId";a:4:{s:12:"seeUsersList";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:7:"addUser";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:10:"deleteUser";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:14:"lockUnlockUser";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:5:"admin";a:2:{s:13:"byPrivilegeId";a:0:{}s:13:"allPrivileges";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}}}s:12:"byResourceId";a:0:{}}}}');

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `fx_account`
--
ALTER TABLE `fx_account`
  ADD CONSTRAINT `fx_account_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `fx_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
