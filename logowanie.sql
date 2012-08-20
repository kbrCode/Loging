-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 20 Sie 2012, 13:00
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
  `fk_spam_ip_id` int(11) DEFAULT NULL,
  `data_blokady` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_odblokowania` timestamp NULL DEFAULT NULL,
  `ip_odblokowania` varchar(12) DEFAULT NULL,
  `kod_odblokowania` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_spam_ip_id` (`fk_spam_ip_id`),
  KEY `fk_user_id` (`fk_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `fx_account`
--

INSERT INTO `fx_account` (`id`, `fk_user_id`, `fk_spam_ip_id`, `data_blokady`, `data_odblokowania`, `ip_odblokowania`, `kod_odblokowania`) VALUES
(1, 3, NULL, '2012-08-20 10:50:55', '2012-08-20 10:59:13', '127.0.0.1', NULL),
(2, 2, NULL, '2012-08-20 10:51:07', '2012-08-20 10:59:09', '127.0.0.1', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Zrzut danych tabeli `fx_user`
--

INSERT INTO `fx_user` (`id`, `login`, `haslo`, `email`, `aktywne`, `role`) VALUES
(1, 'user1', 'pass', 'user1@mail.com', 'tak', 'administrator'),
(2, 'user2', 'pass', 'user2@mail.com', 'tak', 'user'),
(3, 'user3', 'pass', 'user3@mail.com', 'tak', 'user');

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
('35q14lh3m7suho0a18i38nt5q5', 1345189732, 864000, 'Default|a:2:{s:3:"acl";O:8:"Zend_Acl":6:{s:16:"\0*\0_roleRegistry";O:22:"Zend_Acl_Role_Registry":1:{s:9:"\0*\0_roles";a:7:{s:5:"guest";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"guest";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:8:"someUser";}s:5:"staff";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"staff";}}}s:6:"member";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"member";}s:7:"parents";a:0:{}s:8:"children";a:1:{s:8:"someUser";r:10;}}s:5:"admin";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"admin";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";r:10;s:13:"administrator";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:13:"administrator";}}}s:8:"someUser";a:3:{s:8:"instance";r:10;s:7:"parents";a:3:{s:5:"guest";r:6;s:6:"member";r:15;s:5:"admin";r:21;}s:8:"children";a:0:{}}s:5:"staff";a:3:{s:8:"instance";r:12;s:7:"parents";a:1:{s:5:"guest";r:6;}s:8:"children";a:1:{s:6:"editor";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"editor";}}}s:6:"editor";a:3:{s:8:"instance";r:40;s:7:"parents";a:1:{s:5:"staff";r:12;}s:8:"children";a:0:{}}s:13:"administrator";a:3:{s:8:"instance";r:26;s:7:"parents";a:1:{s:5:"admin";r:21;}s:8:"children";a:0:{}}}}s:13:"\0*\0_resources";a:0:{}s:17:"\0*\0_isAllowedRole";N;s:21:"\0*\0_isAllowedResource";N;s:22:"\0*\0_isAllowedPrivilege";N;s:9:"\0*\0_rules";a:2:{s:12:"allResources";a:2:{s:8:"allRoles";a:2:{s:13:"allPrivileges";a:2:{s:4:"type";s:9:"TYPE_DENY";s:6:"assert";N;}s:13:"byPrivilegeId";a:0:{}}s:8:"byRoleId";a:3:{s:5:"guest";a:2:{s:13:"byPrivilegeId";a:1:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:6:"member";a:2:{s:13:"byPrivilegeId";a:3:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:14:"deleteGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:7:"editSeo";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:5:"admin";a:2:{s:13:"byPrivilegeId";a:0:{}s:13:"allPrivileges";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}}}s:12:"byResourceId";a:0:{}}}s:13:"invalidLogins";i:1;}__ZF|a:1:{s:32:"Zend_Form_Element_Hash_salt_csrf";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345190032;}}Zend_Form_Element_Hash_salt_csrf|a:1:{s:4:"hash";s:32:"8a1c59453113209d6c327a557f93db3e";}'),
('6332av3ulo34jpt8980d7fna00', 1345123165, 864000, 'Default|a:4:{s:3:"acl";O:8:"Zend_Acl":6:{s:16:"\0*\0_roleRegistry";O:22:"Zend_Acl_Role_Registry":1:{s:9:"\0*\0_roles";a:7:{s:5:"guest";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"guest";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:8:"someUser";}s:5:"staff";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"staff";}}}s:6:"member";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"member";}s:7:"parents";a:0:{}s:8:"children";a:1:{s:8:"someUser";r:10;}}s:5:"admin";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"admin";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";r:10;s:13:"administrator";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:13:"administrator";}}}s:8:"someUser";a:3:{s:8:"instance";r:10;s:7:"parents";a:3:{s:5:"guest";r:6;s:6:"member";r:15;s:5:"admin";r:21;}s:8:"children";a:0:{}}s:5:"staff";a:3:{s:8:"instance";r:12;s:7:"parents";a:1:{s:5:"guest";r:6;}s:8:"children";a:1:{s:6:"editor";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"editor";}}}s:6:"editor";a:3:{s:8:"instance";r:40;s:7:"parents";a:1:{s:5:"staff";r:12;}s:8:"children";a:0:{}}s:13:"administrator";a:3:{s:8:"instance";r:26;s:7:"parents";a:1:{s:5:"admin";r:21;}s:8:"children";a:0:{}}}}s:13:"\0*\0_resources";a:0:{}s:17:"\0*\0_isAllowedRole";N;s:21:"\0*\0_isAllowedResource";N;s:22:"\0*\0_isAllowedPrivilege";N;s:9:"\0*\0_rules";a:2:{s:12:"allResources";a:2:{s:8:"allRoles";a:2:{s:13:"allPrivileges";a:2:{s:4:"type";s:9:"TYPE_DENY";s:6:"assert";N;}s:13:"byPrivilegeId";a:0:{}}s:8:"byRoleId";a:3:{s:5:"guest";a:2:{s:13:"byPrivilegeId";a:1:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:6:"member";a:2:{s:13:"byPrivilegeId";a:3:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:14:"deleteGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:7:"editSeo";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:5:"admin";a:2:{s:13:"byPrivilegeId";a:0:{}s:13:"allPrivileges";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}}}s:12:"byResourceId";a:0:{}}}s:13:"invalidLogins";i:3;s:11:"showCaptcha";b:1;s:14:"invalidCaptcha";i:2;}__ZF|a:2:{s:50:"Zend_Form_Captcha_a30737fb4b1f4dc1cb5e4f117c05fb1b";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345122037;}s:50:"Zend_Form_Captcha_5cbb83db89f0633c094619e779c1fc66";a:2:{s:4:"ENNH";i:1;s:3:"ENT";i:1345122308;}}'),
('est7db6an6u2qj44ir68sl3945', 1345467586, 864000, 'Default|a:1:{s:3:"acl";O:8:"Zend_Acl":6:{s:16:"\0*\0_roleRegistry";O:22:"Zend_Acl_Role_Registry":1:{s:9:"\0*\0_roles";a:7:{s:5:"guest";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"guest";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:8:"someUser";}s:5:"staff";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"staff";}}}s:6:"member";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"member";}s:7:"parents";a:0:{}s:8:"children";a:1:{s:8:"someUser";r:10;}}s:5:"admin";a:3:{s:8:"instance";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:5:"admin";}s:7:"parents";a:0:{}s:8:"children";a:2:{s:8:"someUser";r:10;s:13:"administrator";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:13:"administrator";}}}s:8:"someUser";a:3:{s:8:"instance";r:10;s:7:"parents";a:3:{s:5:"guest";r:6;s:6:"member";r:15;s:5:"admin";r:21;}s:8:"children";a:0:{}}s:5:"staff";a:3:{s:8:"instance";r:12;s:7:"parents";a:1:{s:5:"guest";r:6;}s:8:"children";a:1:{s:6:"editor";O:13:"Zend_Acl_Role":1:{s:10:"\0*\0_roleId";s:6:"editor";}}}s:6:"editor";a:3:{s:8:"instance";r:40;s:7:"parents";a:1:{s:5:"staff";r:12;}s:8:"children";a:0:{}}s:13:"administrator";a:3:{s:8:"instance";r:26;s:7:"parents";a:1:{s:5:"admin";r:21;}s:8:"children";a:0:{}}}}s:13:"\0*\0_resources";a:0:{}s:17:"\0*\0_isAllowedRole";r:26;s:21:"\0*\0_isAllowedResource";N;s:22:"\0*\0_isAllowedPrivilege";s:14:"lockUnlockUser";s:9:"\0*\0_rules";a:2:{s:12:"allResources";a:2:{s:8:"allRoles";a:2:{s:13:"allPrivileges";a:2:{s:4:"type";s:9:"TYPE_DENY";s:6:"assert";N;}s:13:"byPrivilegeId";a:0:{}}s:8:"byRoleId";a:3:{s:5:"guest";a:2:{s:13:"byPrivilegeId";a:1:{s:11:"addGestbook";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:6:"member";a:2:{s:13:"byPrivilegeId";a:4:{s:12:"seeUsersList";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:7:"addUser";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:10:"deleteUser";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}s:14:"lockUnlockUser";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}s:13:"allPrivileges";a:2:{s:4:"type";N;s:6:"assert";N;}}s:5:"admin";a:2:{s:13:"byPrivilegeId";a:0:{}s:13:"allPrivileges";a:2:{s:4:"type";s:10:"TYPE_ALLOW";s:6:"assert";N;}}}}s:12:"byResourceId";a:0:{}}}}Zend_Auth|a:1:{s:7:"storage";O:8:"stdClass":5:{s:2:"id";s:1:"1";s:5:"login";s:5:"user1";s:5:"email";s:14:"user1@mail.com";s:7:"aktywne";s:3:"tak";s:4:"role";s:13:"administrator";}}');

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
