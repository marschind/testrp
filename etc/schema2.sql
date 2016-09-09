
--
-- Table structure for table `callbacks`
--

CREATE TABLE `callbacks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cbtype` enum('order','file') DEFAULT NULL,
  `objid` int(11) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `status` enum('active','inactive','failed') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `orderid` int(11) DEFAULT NULL,
  `sourcelang` int(11) DEFAULT NULL,
  `targetlang` int(11) DEFAULT NULL,
  `filename` varchar(64) DEFAULT NULL,
  `filetype` varchar(64) DEFAULT NULL,
  `encoding` varchar(16) DEFAULT NULL,
  `uploaded` datetime DEFAULT NULL,
  `status` enum('new','preparation','inprogress','questionwait','complete') DEFAULT NULL,
  `clientref` varchar(32) DEFAULT NULL,
  `callback` varchar(80) DEFAULT NULL,
  `comments` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;


--
-- Table structure for table `languagepairs`
--

CREATE TABLE `languagepairs` (
  `src` int(11) DEFAULT NULL,
  `tgt` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `code3` varchar(10) DEFAULT NULL,
  `description` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=457 DEFAULT CHARSET=latin1;


--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `usergroup` int(11) DEFAULT NULL,
  `requestid` varchar(10) DEFAULT NULL,
  `quoteid` varchar(10) DEFAULT NULL,
  `orderid` varchar(10) DEFAULT NULL,
  `name` varchar(128) DEFAULT NULL,
  `comments` text,
  `status` enum('new','preparation','quoted','approved','inprogress','questionwait','complete') DEFAULT NULL,
  `duedate` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;


--
-- Table structure for table `revocations`
--

CREATE TABLE `revocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `iat` int(11) DEFAULT NULL,
  `comment` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Table structure for table `usergroups`
--

CREATE TABLE `usergroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plunetid` varchar(16) DEFAULT NULL,
  `type` enum('customer','vendor','internal','partner') DEFAULT NULL,
  `company` varchar(150) DEFAULT NULL,
  `contactfirst` varchar(50) DEFAULT NULL,
  `contactlast` varchar(50) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(16) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `url` varchar(128) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `lastactivity` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=395 DEFAULT CHARSET=latin1;


--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plunetid` varchar(16) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  passwdstate enum('activated','pending','reset') default 'reset',
  `issiteadmin` tinyint(1) DEFAULT '0',
  `iscompanyadmin` tinyint(1) DEFAULT '0',
  `apienable` tinyint(1) DEFAULT '0',
  `portalenable` tinyint(1) DEFAULT '0',
  `usergroup` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14818 DEFAULT CHARSET=latin1;


create table tokens (
  id int not null primary key auto_increment,
  user int not null references users(id),
  value varchar(512),
  status enum('active','revoked')
);
