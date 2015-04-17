SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE IF NOT EXISTS `messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


INSERT INTO `messages` (`message_id`, `user_id`, `text`, `time`) VALUES
(1, 3, 'Hello world!!!!!1', '2011-10-13 22:45:34'),
(2, 3, 'Hello again!!!!!', '2011-10-13 22:48:56'),
(3, 4, '<html>\r\n<title>Logout</title>\r\n\r\n<?php\r\nsession_start();\r\nsession_destroy();\r\necho "You are logged out. You will be redirected in 3 seconds";\r\n  header("refresh: 3; index.php");\r\n?>\r\n</html>', '2011-10-13 23:27:14'),
(4, 4, 'Study the code below.', '2011-10-13 23:31:49'),
(5, 2, 'enter your message here', '2011-10-13 23:44:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`) VALUES
(2, 'user', '5f4dcc3b5aa765d61d8327deb882cf99'),
(3, 'Raymond', '5f4dcc3b5aa765d61d8327deb882cf99'),
(4, 'Phyllis', 'e28d371b504fef9f553f979c4bcc8cfc');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
