-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 22, 2015 at 03:05 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `codecombat`
--

-- --------------------------------------------------------

--
-- Table structure for table `back_pack_array`
--

CREATE TABLE IF NOT EXISTS `back_pack_array` (
  `Space` varchar(200) NOT NULL,
  `Item` varchar(300) NOT NULL,
`int` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `back_pack_array`
--

INSERT INTO `back_pack_array` (`Space`, `Item`, `int`) VALUES
('itermOne', '', 1),
('itermTwo', '', 2),
('itermThree', '', 3),
('itermFour', '', 4),
('itermFive', '', 5),
('itermSix', '', 6);

-- --------------------------------------------------------

--
-- Table structure for table `commands_array`
--

CREATE TABLE IF NOT EXISTS `commands_array` (
  `Command` varchar(500) NOT NULL,
  `Function` varchar(500) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `commands_array`
--

INSERT INTO `commands_array` (`Command`, `Function`, `id`) VALUES
('door.unlock(rightHand);', 'unlockDoor', 1),
('door.unlock(leftHand);', 'unlockDoor', 2),
('System.out.println(leftHand);', 'displayHand', 3),
('System.out.println(rightHand);', 'displayHand', 4),
('bowl =', 'assignToVariables', 5),
('north', 'moveCharacter', 6),
('n', 'moveCharacter', 7),
('moveSouth();', 'moveCharacter', 8),
('south', 'moveCharacter', 9),
('s', 'moveCharacter', 10),
('moveEast();', 'moveCharacter', 11),
('east', 'moveCharacter', 12),
('e', 'moveCharacter', 13),
('moveWest();', 'moveCharacter', 14),
('west', 'moveCharacter', 15),
('w', 'moveCharacter', 16),
('up', 'moveCharacter', 17),
('moveUp();', 'moveCharacter', 18),
('u', 'moveCharacter', 19),
('down', 'moveCharacter', 20),
('moveDown();', 'moveCharacter', 21),
('d', 'moveCharacter', 22),
('reset', 'resetGame', 23),
('leftHand =', 'assignToHand', 24),
('rightHand =', 'assignToHand', 25),
('moveNorth();', 'moveCharacter', 26),
('restart', 'resetGame', 27),
('help', 'helpJunk', 28),
('tablet.show();', 'showTablet', 29),
('tablet.close();', 'hideTablet', 30);

-- --------------------------------------------------------

--
-- Table structure for table `definition_array`
--

CREATE TABLE IF NOT EXISTS `definition_array` (
  `Command` varchar(300) NOT NULL,
  `Definition` varchar(300) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `definition_array`
--

INSERT INTO `definition_array` (`Command`, `Definition`, `id`) VALUES
('moveNorth();', 'north', 1),
('north', 'north', 2),
('n', 'north', 3),
('moveSouth();', 'south', 4),
('south', 'south', 5),
('s', 'south', 6),
('moveEast();', 'east', 7),
('east', 'east', 8),
('e', 'east', 9),
('moveWest();', 'west', 10),
('west', 'west', 11),
('w', 'west', 12),
('moveUp();', 'up', 13),
('up', 'up', 14),
('u', 'up', 15),
('moveDown();', 'down', 16),
('down', 'down', 17),
('d', 'down', 18);

-- --------------------------------------------------------

--
-- Table structure for table `hands_array`
--

CREATE TABLE IF NOT EXISTS `hands_array` (
  `Hand` varchar(200) NOT NULL,
  `Item` varchar(300) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `hands_array`
--

INSERT INTO `hands_array` (`Hand`, `Item`, `id`) VALUES
('leftHand', '', 1),
('rightHand', '', 2);

-- --------------------------------------------------------

--
-- Table structure for table `new_images`
--

CREATE TABLE IF NOT EXISTS `new_images` (
  `Item` varchar(300) NOT NULL,
  `Image` varchar(300) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `new_images`
--

INSERT INTO `new_images` (`Item`, `Image`, `id`) VALUES
('rustyKey', 'library.jpg', 1),
('lambChop', 'kitchen.jpg', 2),
('dog', 'taxidermyRoom.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `object_descriptions`
--

CREATE TABLE IF NOT EXISTS `object_descriptions` (
  `Object` varchar(300) NOT NULL,
  `Description` varchar(5000) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `object_descriptions`
--

INSERT INTO `object_descriptions` (`Object`, `Description`, `id`) VALUES
('rustyKey', 'It''s a dingy rusty key.', 2),
('brassKey', 'It''s a nice and shiny brass key.', 3),
('lambChop', 'It''s a tasty looking lamb chop.', 4),
('dog', 'It''s a sizeable looking dog is sitting by the northern door, watching you alertly.', 5),
('bowl', 'It''s an empty bowl sitting on the floor.', 6),
('footLocker', 'It''s a servant''s simple footLocker chest that is sitting on the floor.', 7),
('lamp', 'It''s an old brass lamp.', 8);

-- --------------------------------------------------------

--
-- Table structure for table `obstacles_array`
--

CREATE TABLE IF NOT EXISTS `obstacles_array` (
  `Room` varchar(300) NOT NULL,
  `Obstacle` varchar(400) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `obstacles_array`
--

INSERT INTO `obstacles_array` (`Room`, `Obstacle`, `id`) VALUES
('loungeEast', 'door', 1),
('loungeEastKey', 'rustyKey', 2),
('taxidermyRoomNorth', 'dog', 3),
('banquetHallEast', 'door', 4),
('taxidermyRoomNorth', 'dog', 5);

-- --------------------------------------------------------

--
-- Table structure for table `room_connections`
--

CREATE TABLE IF NOT EXISTS `room_connections` (
  `Room` varchar(200) NOT NULL,
  `Connection` varchar(300) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

--
-- Dumping data for table `room_connections`
--

INSERT INTO `room_connections` (`Room`, `Connection`, `id`) VALUES
('forestNorth', 'castleEntrance', 1),
('castleEntranceNorth', 'foyer', 2),
('castleEntranceSouth', 'forest', 3),
('foyerSouth', 'castleEntrance', 4),
('foyerNorth', 'tapestryE', 5),
('foyerEast', 'conservatory', 6),
('foyerWest', 'vestibule', 7),
('tapestryESouth', 'foyer', 8),
('tapestryEWest', 'tapestryW', 9),
('tapestryENorth', 'grandHall', 10),
('tapestryWEast', 'tapestryE', 11),
('tapestryWWest', 'study', 12),
('tapestryWNorth', 'taxidermyRoom', 13),
('taxidermyRoomSouth', 'tapestryW', 14),
('taxidermyRoomNorth', 'chessRoom', 15),
('chessRoomSouth', 'taxidermyRoom', 16),
('studyEast', 'tapestryW', 17),
('studySouth', 'library', 18),
('studyNorth', 'artGallery', 19),
('libraryNorth', 'study', 20),
('conservatoryWest', 'foyer', 21),
('conservatoryEast', 'lounge', 22),
('conservatoryNorth', 'banquetHall', 23),
('loungeEast', 'butlersQuarters', 24),
('loungeWest', 'conservatory', 25),
('butlersQuartersWest', 'lounge', 26),
('butlersQuartersNorth', 'kitchen', 27),
('kitchenSouth', 'butlersQuarters', 28),
('kitchenWest', 'banquetHall', 29),
('kitchenNorth', 'pantry', 30),
('kitchenEast', 'courtyard', 31),
('pantrySouth', 'kitchen', 32),
('banquetHallSouth', 'conservatory', 33),
('banquetHallNorth', 'hallway1', 34),
('banquetHallEast', 'kitchen', 35),
('banquetHallWest', 'grandHall', 36),
('hallway1South', 'banquetHall', 37),
('hallway1East', 'servantsQuarters', 38),
('servantsQuartersWest', 'hallway1', 39),
('servantsQuartersNorth', 'eastTower1', 40),
('vestibuleEast', 'foyer', 41),
('vestibuleWest', 'westTower1', 42),
('westTower1East', 'vestibule', 43),
('westTower1Up', 'westTowerTop', 44),
('westTowerTopDown', 'westTower1', 45),
('artGallerySouth', 'study', 46),
('courtyardWest', 'kitchen', 47),
('courtyardEast', 'stables', 48),
('stablesWest', 'courtyard', 49),
('stablesSouth', 'smithery', 50),
('smitheryNorth', 'stables', 51),
('grandHallSouth', 'tapestryE', 52),
('grandHallEast', 'banquetHall', 53),
('grandHallNorth', 'grandStaircase', 54),
('grandStaircaseSouth', 'grandHall', 55),
('grandStaircaseUp', 'grandBalcony', 56),
('eastTower1South', 'servantsQuarters', 57),
('eastTower1Up', 'eastTowerTop', 58),
('eastTowerTopDown', 'eastTower1', 59),
('grandBalconyDown', 'grandStaircase', 60),
('grandBalconyEast', 'drawingRoom', 61),
('drawingRoomWest', 'grandBalcony', 62),
('grandBalconyWest', 'observatory', 63),
('observatoryEast', 'grandBalcony', 64),
('observatorySouth', 'mapRoom', 65),
('mapRoomNorth', 'observatory', 66),
('grandBalconySouth', 'corridor2fn', 67),
('corridor2fnNorth', 'grandBalcony', 68),
('corridor2fnWest', 'mapRoom', 69),
('mapRoomEast', 'corridor2fn', 70),
('corridor2fnSouth', 'corridor2fs', 71),
('corridor2fsNorth', 'corridor2fn', 72),
('billiardsRoomWest', 'corridor2fn', 73),
('corridor2fsWest', 'goldilocksRoom', 74),
('goldilocksRoomEast', 'corridor2fs', 75),
('corridor2fsEast', 'masterBedchambers', 76),
('masterBedchambersWest', 'corridor2fs', 77),
('masterBedchambersNorth', 'gerderobe', 78),
('gerderobeSouth', 'masterBedchambers', 79);

-- --------------------------------------------------------

--
-- Table structure for table `room_descriptions`
--

CREATE TABLE IF NOT EXISTS `room_descriptions` (
  `Room` varchar(200) NOT NULL,
  `Description` varchar(5000) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `room_descriptions`
--

INSERT INTO `room_descriptions` (`Room`, `Description`, `id`) VALUES
('forest', 'You are standing in a forest.  There are trees all around you.  A path leads north.', 1),
('castleEntrance', 'You are at the edge of a forest and are standing at a grand castle.  The castle''s door lies to the north.', 2),
('foyer', 'You are in the castle foyer.', 3),
('tapestryE', 'You are in the east end of a long hall lined with ornate tapestries.  The room continues to the west.', 4),
('tapestryW', 'You are in the west end of a long hall lined with ornate tapestries.  The room continues to the east.', 5),
('study', 'You are in a private study lined with stained glass windows, and an ornately carved desk.  A small note rests on the desk.', 6),
('library', 'You are in a large library with book cases stacked from floor to ceiling.  Intricate murals run along the top of the book cases, and there are carved wood panels in the ceiling.', 7),
('conservatory', 'You are in a beautiful conservatory with many exotic plants and a greenhouse ceiling.', 8),
('lounge', 'You are in a lounge decorated with many paintings, and nice comfortable searting.  There is a door to the east.', 9),
('butlersQuarters', 'You are in the butler''s quarters.  You see stairs that lead to nowhere, and some tables and chairs.  It seems the butler must be a lush since he has an entire tavern in his quarters!', 10),
('kitchen', 'You are in the kitchen.  The smell of freshly cooked meat still lingers heavily in the air.', 11),
('pantry', 'You descend down some stairs into in the kitchen pantry.  The pantry is stocked with many dry goods.', 12),
('banquetHall', 'You are in the banquet hall.', 13),
('hallway1', 'You are in a hallway.', 14),
('servantsQuarters', 'You are in a humble servant''s quarters.  The furniture is meager, and the only item of note is an old wooden footLocker sitting on the floor.', 15),
('taxidermyRoom', 'You are in a trophy room, filled with many mounted exotic animals from all over the world.  The master of the castle must be quite the hunter.  One animal in particular catches your eye, particularly because it is not a taxidermy trophy.  It is a sizeable dog sitting squarely in the way of the northern exit, and he''s watching you intently.  A bowl also sits on the floor nearby.', 16),
('chessRoom', 'This room is pitch black.  You can''t see anything.', 17),
('vestibule', 'You are in a small vestibule.', 18),
('artGallery', 'You are in the castle art gallery.', 19),
('westTower1', 'You are in a circular room with a spiral staircase leading up to the right.', 20),
('grandHall', 'You are in the Grand Hall.', 21),
('grandStaircase', 'You are at a magnificant staircase at the north end of the Grand Hall.', 22),
('eastTower1', 'You are in a circular room with a spiral staircase leading up to the left.', 23),
('courtyard', 'You are in the castle courtyard.', 24),
('stables', 'You are in the stables.', 25),
('smithery', 'You are in a smithery.', 26),
('grandBalcony', 'You are on a grand balcony that is overlooking the Grand Hall below.', 27),
('billiardsRoom', 'You are in a billiards room.', 28),
('mapRoom', 'You are in a strange room with several globes.  The walls are all covered with maps.', 29),
('drawingRoom', 'You are in a room with several musical instruments, an easel, some jars of paint, a tilted table, and various drawing utensils.', 30),
('observatory', 'You are in a run down obervatory.  The walls are peeling, and old drapes cover tall floor to ceiling windows.  An old telescope sits on the floor.', 31),
('masterBedchambers', 'You are in a lavishly decorated bedroom.  A four poster bed covered with crushed velvet blankets and plush pillows sit toward the middle of the room.', 32),
('gerderobe', 'You are in master bathroom that is off the master bed chambers.', 33),
('bedroom1', 'You are in a bedroom with three beds.  A fire crackles in the fireplace, making the room soft, warm, and comfortable.  You see a rocking chair, and a vanity with a mirror.', 34),
('corridor2fn', 'You are in the North End of the corridor. Corridor is a stupid name but Sean wanted to use it.', 35),
('corridor2fs', 'You are in the South End of the corridor. Corridor is a stupid name but Sean wanted to use it.', 36),
('goldilocksRoom', 'You are in a bedroom with three beds.  A fire crackles in the fireplace, making the room soft, warm, and comfortable.', 37),
('westTowerTop', 'You are in the top of a tower.', 38),
('eastTowerTop', 'You are in the top of a tower.', 39);

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE IF NOT EXISTS `room_images` (
  `Room` varchar(200) NOT NULL,
  `Image` varchar(300) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=39 ;

--
-- Dumping data for table `room_images`
--

INSERT INTO `room_images` (`Room`, `Image`, `id`) VALUES
('forest', 'forest.jpg', 1),
('castleEntrance', 'castleEntrance.png', 2),
('foyer', 'foyer.jpg', 3),
('tapestryE', 'tapestryE.jpg', 4),
('tapestryW', 'tapestryW.jpg', 5),
('study', 'study.jpg', 6),
('library', 'library_key.jpg', 7),
('conservatory', 'conservatory.jpg', 8),
('lounge', 'lounge.jpg', 9),
('butlersQuarters', 'butlersQuarters.jpg', 10),
('kitchen', 'kitchen.jpg', 11),
('pantry', 'pantry_key.jpg', 12),
('banquetHall', 'banquetHall.jpg', 13),
('hallway1', 'hallway1.jpg', 14),
('servantsQuarters', 'servantsQuarters.jpg', 15),
('taxidermyRoom', 'taxidermyRoom_dog.jpg', 16),
('chessRoom', 'darkRoom.jpg', 17),
('vestibule', 'vestibule.jpg', 18),
('artGallery', 'artGallery.jpg', 19),
('westTower1', 'westTower1.jpg', 20),
('grandHall', 'grandHall.jpg', 21),
('grandStaircase', 'grandStaircase.jpg', 22),
('eastTower1', 'eastTower1.jpg', 23),
('courtyard', 'courtyard.jpg', 24),
('stables', 'stables.jpg', 25),
('smithery', 'smithery.jpg', 26),
('grandBalcony', 'grandBalcony.jpg', 27),
('observatory', 'observatory.jpg', 28),
('drawingRoom', 'drawingRoom.jpg', 29),
('corridor2fn', 'corridor2fn.jpg', 30),
('corridor2fs', 'corridor2fs.jpg', 31),
('mapRoom', 'mapRoom.jpg', 32),
('billiardsRoom', 'billiardsRoom.jpg', 33),
('goldilocksRoom', 'goldilocksRoom.jpg', 34),
('masterBedchambers', 'masterBedchambers.jpg', 35),
('gerderobe', 'gerderobe.jpg', 36),
('westTowerTop', 'westTowerTop.jpg', 37),
('eastTowerTop', 'eastTowerTop.jpg', 38);

-- --------------------------------------------------------

--
-- Table structure for table `room_objects`
--

CREATE TABLE IF NOT EXISTS `room_objects` (
  `Room` varchar(200) NOT NULL,
  `Object` varchar(300) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `room_objects`
--

INSERT INTO `room_objects` (`Room`, `Object`, `id`) VALUES
('library', 'rustyKey', 1),
('pantry', 'brassKey', 2),
('kitchen', 'lambChop', 3),
('taxidermyRoom', 'bowl', 4),
('servantsQuarters', 'footLocker', 5),
('footLocker', 'lamp', 6);

-- --------------------------------------------------------

--
-- Table structure for table `users_items`
--

CREATE TABLE IF NOT EXISTS `users_items` (
  `Item` varchar(200) NOT NULL,
  `HasItem` varchar(200) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users_items`
--

INSERT INTO `users_items` (`Item`, `HasItem`, `id`) VALUES
('backPack', 'no', 1);

-- --------------------------------------------------------

--
-- Table structure for table `variable_objects`
--

CREATE TABLE IF NOT EXISTS `variable_objects` (
  `variable` varchar(300) NOT NULL,
  `assign` varchar(300) NOT NULL,
`id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `variable_objects`
--

INSERT INTO `variable_objects` (`variable`, `assign`, `id`) VALUES
('bowl', 'lambChop', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `back_pack_array`
--
ALTER TABLE `back_pack_array`
 ADD PRIMARY KEY (`int`);

--
-- Indexes for table `commands_array`
--
ALTER TABLE `commands_array`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `definition_array`
--
ALTER TABLE `definition_array`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hands_array`
--
ALTER TABLE `hands_array`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_images`
--
ALTER TABLE `new_images`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `object_descriptions`
--
ALTER TABLE `object_descriptions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obstacles_array`
--
ALTER TABLE `obstacles_array`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_connections`
--
ALTER TABLE `room_connections`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_descriptions`
--
ALTER TABLE `room_descriptions`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room_objects`
--
ALTER TABLE `room_objects`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_items`
--
ALTER TABLE `users_items`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variable_objects`
--
ALTER TABLE `variable_objects`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `back_pack_array`
--
ALTER TABLE `back_pack_array`
MODIFY `int` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `commands_array`
--
ALTER TABLE `commands_array`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `definition_array`
--
ALTER TABLE `definition_array`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `hands_array`
--
ALTER TABLE `hands_array`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `new_images`
--
ALTER TABLE `new_images`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `object_descriptions`
--
ALTER TABLE `object_descriptions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `obstacles_array`
--
ALTER TABLE `obstacles_array`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `room_connections`
--
ALTER TABLE `room_connections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT for table `room_descriptions`
--
ALTER TABLE `room_descriptions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `room_objects`
--
ALTER TABLE `room_objects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `users_items`
--
ALTER TABLE `users_items`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `variable_objects`
--
ALTER TABLE `variable_objects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
