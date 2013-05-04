-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 08, 2013 at 01:01 PM
-- Server version: 5.5.28
-- PHP Version: 5.3.10-1ubuntu3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pwaq`
--

-- --------------------------------------------------------

--
-- Table structure for table `vass_playlist`
--

CREATE TABLE IF NOT EXISTS `vass_playlist` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `playlist` tinyint(1) NOT NULL DEFAULT '0',
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `song_id` varchar(10) NOT NULL DEFAULT '',
  `song_name` varchar(50) NOT NULL DEFAULT '',
  `song_artist` varchar(50) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`,`song_id`),
  UNIQUE KEY `song_id_2` (`song_id`,`user_id`),
  KEY `song_id` (`song_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `vass_playlist`
--

INSERT INTO `vass_playlist` (`id`, `playlist`, `user_id`, `song_id`, `song_name`, `song_artist`, `created_on`) VALUES
(18, 0, 27, '48', 'On fire', 'Eminem', '2013-01-06 02:29:01'),
(19, 0, 27, '55', 'Diamonds', 'Rihanna', '2013-01-06 22:49:43'),
(16, 0, 0, '24', 'Ca fait plaisir', 'Rohff', '2013-01-06 01:30:23'),
(17, 0, 27, '52', 'Sexy bitch', 'David Guetta / Akon', '2013-01-06 02:17:57'),
(26, 0, 0, '44', 'Only girl', 'Rihanna', '2013-01-07 23:37:01'),
(15, 0, 27, '23', 'Zone Internationale', 'Rohff', '2013-01-05 23:26:16'),
(13, 0, 27, '24', 'Ca fait plaisir', 'Rohff', '2013-01-04 01:41:29'),
(20, 0, 27, '50', 'Not Afreid', 'Eminem', '2013-01-06 22:49:47'),
(21, 0, 27, '51', 'Lose yourself', 'Eminem', '2013-01-06 22:49:48'),
(22, 0, 27, '59', 'Too close', 'Alex clare', '2013-01-06 22:49:52'),
(23, 0, 27, '58', 'Somebody That I Used To Know', 'Gotye', '2013-01-06 22:49:56'),
(24, 0, 27, '54', 'Locket out the heaven', 'Bruno Mars', '2013-01-06 22:50:07'),
(25, 0, 27, '56', ' Ho Hey ', 'The Lumineers ', '2013-01-06 22:50:12'),
(27, 0, 0, '55', 'Diamonds', 'Rihanna', '2013-01-07 23:37:03');

-- --------------------------------------------------------

--
-- Table structure for table `vass_songs`
--

CREATE TABLE IF NOT EXISTS `vass_songs` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `song_title` varchar(50) NOT NULL,
  `song_album` varchar(50) NOT NULL,
  `song_artist` varchar(50) NOT NULL,
  `played` int(9) NOT NULL DEFAULT '0',
  `url` varchar(250) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `song_title` (`song_title`,`song_album`,`song_artist`),
  FULLTEXT KEY `song_title_2` (`song_title`),
  FULLTEXT KEY `song_album` (`song_album`),
  FULLTEXT KEY `song_artist` (`song_artist`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

--
-- Dumping data for table `vass_songs`
--

INSERT INTO `vass_songs` (`id`, `song_title`, `song_album`, `song_artist`, `played`, `url`) VALUES
(24, 'Ca fait plaisir', 'En mode', 'Rohff', 0, 'http://muz-time.ru/player/download/rapmp3/rap10/05_Rohff_2004_CD1_Ca_Fait_Plaisir.mp3'),
(23, 'Zone Internationale', 'En mode', 'Rohff', 0, 'http://muz-time.ru/player/download/rapmp3/rap10/10_Rohff_2004_CD2_Zone_Internationale.mp3'),
(48, 'On fire', 'Recovery', 'Eminem', 0, 'http://brandonblattner.com/home/audio/Eminem%20-%20Recovery/03-eminem-on_fire.mp3'),
(50, 'Not Afreid', 'Recovery', 'Eminem', 0, 'http://o5wap.ru/content/mp3/full/9/c/2/Eminem_-_Not_Afraid.mp3'),
(44, 'Only girl', 'In the world', 'Rihanna', 0, 'http://www.pwaq.com/demo/uploads/rihanna-only-girl-0144712d.mp3'),
(51, 'Lose yourself', 'Curtain', 'Eminem', 0, 'http://www.andydenton.com/audio/lose.mp3'),
(52, 'Sexy bitch', 'Sexy bitch', 'David Guetta / Akon', 0, 'http://spm77.free.fr/Music/David%20Guetta%20-%20One%20More%20Love%20(2010)%20-%20Dance/103-david_guetta_feat_akon-sexy_bitch.mp3'),
(53, 'Gagnam style', 'Gagnam Style', 'Psy', 0, 'http://promodj.com/source/3608444/Psy_Gangnam_style_DJ_Pasha_lee_DJ_Vitaco_remix.mp3'),
(54, 'Locket out the heaven', 'Unorthodox juckbox', 'Bruno Mars', 0, 'http://freshmp3music.ru/music/10.2012/bruno_mars_-_locked_out_of_heaven_(www.freshmp3music.ru).mp3'),
(55, 'Diamonds', 'Diamonds', 'Rihanna', 0, 'http://mp3.mp3pulse.ru/Rihanna_-_Diamonds_[mp3pulse.ru].mp3'),
(56, ' Ho Hey ', 'The Lumineers ', 'The Lumineers ', 0, 'http://a.tumblr.com/tumblr_m61lmhl76k1r8o4gfo1.mp3'),
(57, 'all i want for christmas is you', 'Triumphant', 'Mariah carey', 0, 'http://www.giftsbycinnamon.net/Cards/All_I_Want.mp3'),
(58, 'Somebody That I Used To Know', 'Making Mirrors', 'Gotye', 0, 'http://portasound.ru/download.php?action=download&ID=ZmlsZS9kNGUxMTlmODMyZjkwYjBkMDEyZDE5MmIxYzdhOWIzNw&filename=gotye_feat_kimbra_-_gotye_feat_kimbra_-_somebody_that_i_used_to_know_bastian_van_shield_remix_soronbaev_ruslan.mp3&doo=2'),
(59, 'Too close', 'The Lateness Of The Hour', 'Alex clare', 0, 'http://kengu.fm/content/272/wap_kengu_ru_-_alex_clare_-_too_close_distance_remix_1549.mp3'),
(60, 'Diamonds', 'Run this town', 'Kanye West', 0, 'http://mp3.mynet.tj/3/mzstan20090131050548.mp3'),
(61, 'Je me souviens', 'Booba', 'Booba', 0, 'http://tips69.free.fr/Mp3/Rap/Booba%20feat%20Kenedy%20-%20Jme%20souviens.mp3'),
(62, 'La roue tourne', 'La roue tourne', 'Zaho', 0, 'http://f6.media.v4.skyrock.net/music/f6c/c16/f6cc16aa6572d53b3f113188c3eb99c9.mp3'),
(63, 'Hey papi', 'Hey papi', 'Zaho', 0, 'http://www.pwaq.com/demo/uploads/zaho-hey-papi-0144712d.mp3'),
(64, 'Iam still over you', 'Rihanna', 'Rihanna', 0, 'http://individual.utoronto.ca/yuhin/yuhin.mp3'),
(65, 'Disturbia', 'Rihanna', 'Rihanna', 0, 'http://users1.ml.mindenkilapja.hu/users/zerox/uploads/Rihanna-Disturbia.mp3'),
(66, 'Rude Boy', 'Rihanna', 'Rihanna', 0, 'http://www.dj-tronic.com/dancefloormayhem.com/music/2010/July/Rihanna-RudeBoy_Ranny_and_Bryan_Reyes_Club_Mix.mp3'),
(67, 'Only girl', 'Rihanna', 'Rihanna', 0, 'http://www.dj-tronic.com/dancefloormayhem.com/music/2010/October/Rihanna-OnlyGirl_RannyClubMix.mp3'),
(68, 'You do one', 'Rihanna', 'Rihanna', 0, 'http://work84.pbworks.com/w/file/fetch/52437862/Rihanna%20-%20You%20Da%20One%20-%20Remix.mp3'),
(69, 'Bad romance', 'Bad romance', 'Rihanna', 0, 'http://www.eternalnyc.com/boomchik/Bad%20Romance%20(Chew%20Fu%20H1N1%20Mix).mp3'),
(70, 'Lady gaga', 'Bad romance', 'Rihanna', 0, 'http://jakesallstarkaraoke.com/media/Lady_GaGa_-_Love_Games.mp3'),
(71, 'Lady gaga', 'Bad romance', 'Lady gaga', 0, 'http://jakesallstarkaraoke.com/media/Lady_GaGa_-_Love_Games.mp3'),
(72, 'Back to me', 'Elissa', 'Elissa', 0, 'http://www.djdelirious.ca/remixes/Elissa%20-%20Back%20To%20Me%20(DJ%20Delirious%20Remix).mp3');

-- --------------------------------------------------------

--
-- Table structure for table `vass_update`
--

CREATE TABLE IF NOT EXISTS `vass_update` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) NOT NULL DEFAULT '0',
  `song_id` varchar(10) NOT NULL DEFAULT '',
  `song_name` varchar(50) NOT NULL DEFAULT '',
  `song_artist` varchar(50) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id_2` (`user_id`,`song_id`),
  UNIQUE KEY `song_id_2` (`song_id`,`user_id`),
  KEY `song_id` (`song_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vass_users`
--

CREATE TABLE IF NOT EXISTS `vass_users` (
  `password` varchar(32) NOT NULL DEFAULT '',
  `username` varchar(40) NOT NULL,
  `user_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `role` tinyint(1) NOT NULL,
  `reg_date` datetime DEFAULT NULL,
  `last_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `vass_users`
--

INSERT INTO `vass_users` (`password`, `username`, `user_id`, `role`, `reg_date`, `last_date`) VALUES
('547639396834ea26408d80ca15914c2c', 'faouzi2', 27, 1, '2012-02-24 20:13:44', '2013-01-08 12:25:58'),
('ac501ebb42c4de9f7eb510f468e27590', 'faouzi4', 31, 0, '2013-01-01 03:45:33', '2013-01-01 03:46:41'),
('21232f297a57a5a743894a0e4a801fc3', 'admin', 35, 1, '2013-01-08 12:58:27', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
