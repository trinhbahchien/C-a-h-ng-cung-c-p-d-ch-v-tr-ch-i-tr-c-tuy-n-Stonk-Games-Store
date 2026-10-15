-- ==============================================================================
-- DATABASE: stonk_games_store
-- DU AN: Website Kinh Doanh Game Ban Quyen va Tin Tuc (StonkGamesStore)
-- PHONG CACH: Steam va Epic Games Store (Gaming Dark Theme)
-- MO HINH: Pure PHP MVC - PDO Prepared Statements - Tuong thich XAMPP MariaDB/MySQL
-- CHUAN MA HOA: 100% UTF8mb4 Unicode (utf8mb4_unicode_ci)
-- QUY TAC DULIEU: 
--   1. Du lieu Games va Tin tuc (News) duoc luu tru bang tieng Anh chuan (English)
--   2. He thong (Buttons, Labels, Navigations) ho tro chuyen ngu Tieng Viet / English
--   3. Tuyet doi khong su dung icon hay emoji trong code, module, va nhan du lieu
-- ==============================================================================

CREATE DATABASE IF NOT EXISTS `stonk_games_store` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `stonk_games_store`;

SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;
SET CHARACTER SET utf8mb4;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `game_analytics`;
DROP TABLE IF EXISTS `system_requirements`;
DROP TABLE IF EXISTS `game_categories`;
DROP TABLE IF EXISTS `games`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `developers`;
DROP TABLE IF EXISTS `users`;
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------------------------
-- 1. BANG USERS (12 Users: Admin, Moderator, and Verified Gamers)
-- ------------------------------------------------------------------------------
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL COMMENT 'BCrypt Hashed Password',
  `role` ENUM('admin', 'user') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `isdelete` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0: active, 1: deleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default password for all test accounts: 123456 (BCrypt)
INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@stonkgames.vn', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'admin', '2026-01-01 08:00:00'),
(2, 'moderator_huy', 'huy.mod@stonkgames.vn', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'admin', '2026-01-15 09:30:00'),
(3, 'gamer01', 'gamer01@gmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-02-10 14:20:00'),
(4, 'hoang_long_pro', 'long.hoang@gmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-02-14 18:45:00'),
(5, 'trang_streamer', 'trang.gaming@hotmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-03-01 20:10:00'),
(6, 'cyber_ninja99', 'ninja99@yahoo.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-03-12 11:15:00'),
(7, 'dark_souls_fan', 'tuan.darksouls@outlook.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-03-25 16:30:00'),
(8, 'minh_tri_fps', 'tri.fps@gmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-04-05 21:00:00'),
(9, 'cloud_strife_vn', 'cloud.vn@gmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-04-18 10:40:00'),
(10, 'geralt_rivia', 'geralt.witcher@gmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-05-02 15:25:00'),
(11, 'lan_anh_cozy', 'lananh.cozy@gmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-05-14 19:50:00'),
(12, 'viet_anh_hardcore', 'vietanh.gamer@gmail.com', '$2y$10$7cJ.rdeDidsahSULcdwHJu7Jzz55iTSDahK7YGzqPxK5KJKclSMha', 'user', '2026-05-20 22:10:00');

-- ------------------------------------------------------------------------------
-- 2. BANG DEVELOPERS (12 World-Class Studios)
-- ------------------------------------------------------------------------------
CREATE TABLE `developers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `logo` VARCHAR(255) DEFAULT NULL,
  `description` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `developers` (`id`, `name`, `slug`, `logo`, `description`) VALUES
(1, 'FromSoftware Inc.', 'fromsoftware', 'fromsoftware.png', 'Legendary Japanese studio behind the genre-defining Souls series, Bloodborne, Sekiro, and Elden Ring.'),
(2, 'CD Projekt Red', 'cd-projekt-red', 'cdpr.png', 'Renowned Polish development studio acclaimed for story-driven RPGs including The Witcher 3 and Cyberpunk 2077.'),
(3, 'Santa Monica Studio', 'santa-monica', 'santa_monica.png', 'Premier Sony PlayStation studio dedicated to crafting cinematic epics including God of War and God of War Ragnarok.'),
(4, 'EA Sports', 'ea-sports', 'ea_sports.png', 'Global industry leader in authentic interactive sports entertainment, developing EA SPORTS FC 24.'),
(5, 'miHoYo / HoYoverse', 'hoyoverse', 'hoyoverse.png', 'Innovative creator of global open-world cross-platform phenomenons including Genshin Impact and Honkai Star Rail.'),
(6, 'Capcom', 'capcom', 'capcom.png', 'Japanese gaming powerhouse renowned for blockbuster franchises including Resident Evil, Monster Hunter, and Devil May Cry.'),
(7, 'Rockstar Games', 'rockstar-games', 'rockstar.png', 'Groundbreaking creator of living open worlds including Grand Theft Auto V and the masterpiece Red Dead Redemption 2.'),
(8, 'Ubisoft Entertainment', 'ubisoft', 'ubisoft.png', 'Leading French publisher producing expansive adventure universes such as Assassin’s Creed, Far Cry, and Rainbow Six.'),
(9, 'Square Enix', 'square-enix', 'square_enix.png', 'Iconic Japanese developer and publisher celebrated worldwide for Final Fantasy, Dragon Quest, and Kingdom Hearts.'),
(10, 'Valve Corporation', 'valve', 'valve.png', 'Visionary gaming company behind the Steam platform as well as historic franchises Counter-Strike, Half-Life, and Dota 2.'),
(11, 'Bandai Namco Entertainment', 'bandai-namco', 'bandai_namco.png', 'Major Japanese publisher delivering premier fighting and RPG titles including Tekken, Tales of, and publishing Elden Ring.'),
(12, 'Larian Studios', 'larian-studios', 'larian.png', 'Masterful Belgian RPG developer awarded Game of the Year 2023 for the groundbreaking Baldur’s Gate 3.');

-- ------------------------------------------------------------------------------
-- 3. BANG CATEGORIES (12 Core Game Genres)
-- ------------------------------------------------------------------------------
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` (`id`, `name`, `slug`, `description`) VALUES
(1, 'Action', 'action', 'Fast-paced combat, real-time reflexes, and high-intensity physical challenges.'),
(2, 'Role-Playing (RPG)', 'rpg', 'Deep character progression, tactical decisions, statistical growth, and compelling branching narratives.'),
(3, 'Open World', 'open-world', 'Expansive non-linear environments offering boundless exploration and systemic emergent gameplay.'),
(4, 'Sports', 'sports', 'Authentic athletic simulations and competitive team sports spanning global leagues and tournaments.'),
(5, 'Strategy', 'strategy', 'Tactical planning, strategic resource allocation, unit maneuvering, and grand command.'),
(6, 'Free-to-Play', 'free-to-play', 'Full game experiences accessible at no initial purchase cost with thriving online communities.'),
(7, 'Horror', 'horror', 'Survival dread, psychological tension, unsettling atmosphere, and heart-pounding encounters.'),
(8, 'Shooter (FPS / TPS)', 'shooter', 'First-person and third-person ballistic combat demanding aim precision and tactical teamwork.'),
(9, 'Adventure', 'adventure', 'Narrative-driven exploration, environmental problem solving, and unforgettable character journeys.'),
(10, 'Survival', 'survival', 'Resource scavenging, tool crafting, environmental threat mitigation, and relentless perseverance.'),
(11, 'Sci-Fi', 'sci-fi', 'Futuristic technology, cybernetic augmentation, space exploration, and dystopian societies.'),
(12, 'Racing', 'racing', 'High-speed vehicular competition featuring licensed supercars on challenging tracks and scenic highways.');

-- ------------------------------------------------------------------------------
-- 4. BANG GAMES (18 AAA Masterpieces with Authentic English Store Descriptions)
-- ------------------------------------------------------------------------------
CREATE TABLE `games` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `developer_id` INT NOT NULL,
  `price` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `discount_percent` INT NOT NULL DEFAULT 0,
  `short_description` TEXT NOT NULL,
  `description` LONGTEXT NOT NULL,
  `cover_image` VARCHAR(500) NOT NULL,
  `trailer_url` VARCHAR(255) DEFAULT NULL COMMENT 'YouTube Video ID or direct link',
  `is_featured` TINYINT(1) DEFAULT 0 COMMENT '1: Spotlight Carousel',
  `status` TINYINT(1) DEFAULT 1 COMMENT '1: Active, 0: Hidden',
  `release_date` DATE NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`developer_id`) REFERENCES `developers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `games` (`id`, `title`, `slug`, `developer_id`, `price`, `discount_percent`, `short_description`, `description`, `cover_image`, `trailer_url`, `is_featured`, `status`, `release_date`) VALUES
(1, 'Elden Ring', 'elden-ring', 1, 1200000.00, 30, 'Winner of Game of the Year. A vast world where open fields with a variety of situations and huge dungeons with complex designs are seamlessly connected.', 'THE NEW FANTASY ACTION RPG. Rise, Tarnished, and be guided by grace to brandish the power of the Elden Ring and become an Elden Lord in the Lands Between. Explore a vast world full of excitement where open fields and multilayered dungeons are seamlessly connected, created by Hidetaka Miyazaki and George R. R. Martin.', 'elden_ring.jpg', 'AKXiKBnzpBQ', 1, 1, '2022-02-25'),
(2, 'Cyberpunk 2077', 'cyberpunk-2077', 2, 990000.00, 50, 'An open-world, action-adventure RPG set in the dark future of Night City — a dangerous megalopolis obsessed with power, glamour, and body modification.', 'Cyberpunk 2077 is an open-world, action-adventure RPG set in the megalopolis of Night City, where you play as a cyberpunk mercenary wrapped up in a do-or-die fight for survival. Upgraded with next-gen in mind and featuring free additional content, customize your character and playstyle as you take on jobs, build a reputation, and unlock upgrades.', 'cyberpunk.jpg', 'cCkYB6yZ8VI', 1, 1, '2020-12-10'),
(3, 'God of War Ragnarok', 'god-of-war-ragnarok', 3, 1500000.00, 20, 'Embark on an epic and heartfelt journey as Kratos and Atreus struggle with holding on and letting go.', 'From Santa Monica Studio comes the sequel to the critically acclaimed God of War (2018). Fimbulwinter is well underway. Kratos and Atreus must journey to each of the Nine Realms in search of answers as Asgardian forces prepare for a prophesied battle that will end the world.', 'gow.jpg', 'dIQGI36BxDE', 1, 1, '2022-11-09'),
(4, 'EA SPORTS FC 24', 'ea-sports-fc-24', 4, 1100000.00, 60, 'The next chapter in an innovative future of football. Powered by HyperMotionV, PlayStyles optimized by Opta, and an enhanced Frostbite Engine.', 'EA SPORTS FC 24 welcomes you to The World’s Game: the truest-to-football experience ever with HyperMotionV, PlayStyles optimized by Opta, and an enhanced Frostbite Engine reinventing how over 19,000 authentic players move, play, and look in every match.', 'fc24.jpg', 'XhP3Xh4LMA8', 1, 1, '2023-09-29'),
(5, 'Genshin Impact', 'genshin-impact', 5, 0.00, 0, 'Embark on a journey across Teyvat to find your lost sibling and seek answers from the Seven Gods of elemental energy.', 'Step into Teyvat, a vast world teeming with life and flowing with elemental energy. You and your sibling arrived here from another world. Separated by an unknown god, stripped of your powers, and cast into a deep slumber, you now awake to a world very different from when you first arrived.', 'genshin.jpg', 'TAlKhARUcoY', 1, 1, '2020-09-28'),
(6, 'Resident Evil 4 Remake', 'resident-evil-4-remake', 6, 950000.00, 25, 'Survival is only the beginning. Experience a complete reimagining of the groundbreaking 2005 survival horror masterpiece.', 'Six years have passed since the biological disaster in Raccoon City. Agent Leon S. Kennedy, one of the survivors of the incident, has been sent to rescue the president\'s kidnapped daughter. He tracks her to a secluded European village, where there is something terribly wrong with the locals.', 're4.jpg', 'E69tKrfEQag', 0, 1, '2023-03-24'),
(7, 'Grand Theft Auto V', 'grand-theft-auto-v', 7, 750000.00, 65, 'When a young street hustler, a retired bank robber, and a terrifying psychopath find themselves entangled with the criminal underworld.', 'Grand Theft Auto V for PC offers players the option to explore the award-winning world of Los Santos and Blaine County in resolutions of up to 4k and beyond, as well as the chance to experience the game running at 60 frames per second.', 'gta5.jpg', 'QkkoHAzjnUs', 1, 1, '2015-04-14'),
(8, 'Red Dead Redemption 2', 'red-dead-redemption-2', 7, 1350000.00, 67, 'Winner of over 175 Game of the Year Awards, Red Dead Redemption 2 is an epic tale of honor and loyalty at the dawn of the modern age.', 'America, 1899. The end of the wild west era has begun as lawmen hunt down the last remaining outlaw gangs. Arthur Morgan and the Van der Linde gang are forced to flee after a robbery goes wrong in the western town of Blackwater.', 'rdr2.jpg', 'eaW0tYpxyp0', 1, 1, '2019-11-05'),
(9, 'The Witcher 3: Wild Hunt', 'the-witcher-3-wild-hunt', 2, 600000.00, 75, 'You are Geralt of Rivia, mercenary monster slayer. Before you stands a war-torn, monster-infested continent you can explore at will.', 'As war rages throughout the Northern Realms, you take on the greatest contract of your life — tracking down the Child of Prophecy, a living weapon that can alter the shape of the world.', 'witcher3.jpg', 'XHrskkHf958', 0, 1, '2015-05-18'),
(10, 'Baldur\'s Gate 3', 'baldurs-gate-3', 12, 1190000.00, 20, 'Gather your party and return to the Forgotten Realms in a tale of fellowship and betrayal, sacrifice and survival, and the lure of absolute power.', 'Mysterious abilities are awakening inside you, drawn from a mind flayer parasite planted in your brain. Resist, and turn darkness against itself. Or embrace corruption, and become ultimate evil. An expansive RPG set in the universe of Dungeons & Dragons.', 'bg3.jpg', 'OcP0WdH7rTs', 1, 1, '2023-08-03'),
(11, 'Sekiro: Shadows Die Twice', 'sekiro-shadows-die-twice', 1, 1290000.00, 50, 'Carve your own clever path to vengeance in the critically acclaimed adventure from developer FromSoftware.', 'In Sekiro: Shadows Die Twice you are the one-armed wolf, a disgraced and disfigured warrior rescued from the brink of death. Bound to protect a young lord who is the descendant of an ancient bloodline, you become the target of many vicious enemies, including the dangerous Ashina clan.', 'sekiro.jpg', '7Po7INInqzE', 0, 1, '2019-03-22'),
(12, 'Assassin\'s Creed Mirage', 'assassins-creed-mirage', 8, 990000.00, 40, 'Experience the story of Basim, a cunning street thief seeking answers and justice in the vibrant streets of ninth-century Baghdad.', 'Through a mysterious, ancient organization known as the Hidden Ones, he will become a deadly Master Assassin and change his fate in ways he never could have imagined.', 'ac_mirage.jpg', 'x55lAlFtXmw', 0, 1, '2023-10-05'),
(13, 'Final Fantasy VII Remake Intergrade', 'ff7-remake-intergrade', 9, 1400000.00, 50, 'A spectacular reimagining of one of the most visionary games ever, featuring modern graphics, hybrid combat, and expanded narrative.', 'Cloud Strife, an ex-SOLDIER operative, descends on the mako-powered city of Midgar. The world of the timeless classic Final Fantasy VII is reborn, using state-of-the-art graphics technology, a new battle system, and an additional adventure featuring Yuffie Kisaragi.', 'ff7_remake.jpg', 'YwZ8SkxWIQU', 0, 1, '2021-12-16'),
(14, 'Counter-Strike 2', 'counter-strike-2', 10, 0.00, 0, 'The largest technical leap forward in Counter-Strike history, ensuring new features and updates for years to come.', 'For over two decades, Counter-Strike has offered an elite competitive experience shaped by millions of players across the globe. Powered by the Source 2 engine, Counter-Strike 2 features responsive smokes, sub-tick updates, and overhauled audio-visuals.', 'cs2.jpg', 'ExZtISgOxEQ', 0, 1, '2023-09-27'),
(15, 'Monster Hunter: World', 'monster-hunter-world', 6, 700000.00, 67, 'Welcome to a new world! In Monster Hunter: World, enjoy the ultimate hunting experience using everything at your disposal.', 'Battle gigantic monsters in epic locales. As a hunter, you\'ll take on quests to hunt monsters in a variety of habitats. Take down these monsters and receive materials that you can use to craft stronger weapons and armor.', 'mhw.jpg', 'Ro6r15wzp2o', 0, 1, '2018-08-09'),
(16, 'Dark Souls III', 'dark-souls-3', 1, 1100000.00, 50, 'Dark Souls continues to push the boundaries with the latest, ambitious chapter in the critically-acclaimed and genre-defining series.', 'As fires fade and the world falls into ruin, developer FromSoftware and director Hidetaka Miyazaki continue their beloved series with Dark Souls III. Fans and newcomers alike will get lost in the hallmark rewarding gameplay and immersive graphics.', 'ds3.jpg', '_zDZYrIUgKE', 0, 1, '2016-04-12'),
(17, 'Forza Horizon 5', 'forza-horizon-5', 4, 1340000.00, 50, 'Your Ultimate Horizon Adventure awaits! Explore the vibrant and ever-evolving open world landscapes of Mexico.', 'Lead breathtaking expeditions across the vibrant and ever-evolving open world landscapes of Mexico with limitless, fun driving action in hundreds of the world’s greatest cars.', 'fh5.jpg', 'FYH9n37B7Yw', 0, 1, '2021-11-09'),
(18, 'Dota 2', 'dota-2', 10, 0.00, 0, 'Every day, millions of players worldwide enter the battle as one of over a hundred Dota heroes in the premier MOBA experience.', 'Dota is the deepest multi-player action RTS game ever made and there\'s always a new strategy or tactic to discover. It\'s completely free to play and always will be — start picking your hero and battle now.', 'dota2.jpg', '-cSFPIwMEq4', 0, 1, '2013-07-09');

-- ------------------------------------------------------------------------------
-- 5. BANG GAME_CATEGORIES (Junction Table for 18 Games)
-- ------------------------------------------------------------------------------
CREATE TABLE `game_categories` (
  `game_id` INT NOT NULL,
  `category_id` INT NOT NULL,
  PRIMARY KEY (`game_id`, `category_id`),
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `game_categories` (`game_id`, `category_id`) VALUES
(1, 1), (1, 2), (1, 3),        -- Elden Ring: Action, RPG, Open World
(2, 1), (2, 2), (2, 3), (2, 11), -- Cyberpunk: Action, RPG, Open World, Sci-Fi
(3, 1), (3, 2), (3, 9),        -- God of War: Action, RPG, Adventure
(4, 4),                        -- FC 24: Sports
(5, 2), (5, 3), (5, 6),        -- Genshin: RPG, Open World, Free-to-Play
(6, 1), (6, 7), (6, 10),       -- RE4: Action, Horror, Survival
(7, 1), (7, 3), (7, 8),        -- GTA V: Action, Open World, Shooter
(8, 1), (8, 3), (8, 9),        -- RDR 2: Action, Open World, Adventure
(9, 1), (9, 2), (9, 3),        -- Witcher 3: Action, RPG, Open World
(10, 2), (10, 5), (10, 9),     -- Baldur's Gate 3: RPG, Strategy, Adventure
(11, 1), (11, 2), (11, 9),     -- Sekiro: Action, RPG, Adventure
(12, 1), (12, 3), (12, 9),     -- AC Mirage: Action, Open World, Adventure
(13, 1), (13, 2), (13, 11),    -- FF7 Remake: Action, RPG, Sci-Fi
(14, 6), (14, 8),              -- CS2: Free-to-Play, Shooter
(15, 1), (15, 2), (15, 10),    -- Monster Hunter World: Action, RPG, Survival
(16, 1), (16, 2), (16, 7),     -- Dark Souls 3: Action, RPG, Horror
(17, 3), (17, 4), (17, 12),    -- Forza Horizon 5: Open World, Sports, Racing
(18, 5), (18, 6);              -- Dota 2: Strategy, Free-to-Play

-- ------------------------------------------------------------------------------
-- 6. BANG SYSTEM_REQUIREMENTS (Hardware Specifications for 18 Games)
-- ------------------------------------------------------------------------------
CREATE TABLE `system_requirements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `game_id` INT NOT NULL UNIQUE,
  `min_os` VARCHAR(100) NOT NULL,
  `min_cpu` VARCHAR(100) NOT NULL,
  `min_ram` VARCHAR(50) NOT NULL,
  `min_gpu` VARCHAR(100) NOT NULL,
  `min_storage` VARCHAR(50) NOT NULL,
  `rec_os` VARCHAR(100) NOT NULL,
  `rec_cpu` VARCHAR(100) NOT NULL,
  `rec_ram` VARCHAR(50) NOT NULL,
  `rec_gpu` VARCHAR(100) NOT NULL,
  `rec_storage` VARCHAR(50) NOT NULL,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `system_requirements` (`game_id`, `min_os`, `min_cpu`, `min_ram`, `min_gpu`, `min_storage`, `rec_os`, `rec_cpu`, `rec_ram`, `rec_gpu`, `rec_storage`) VALUES
(1, 'Windows 10 64-bit', 'Intel Core i5-8400 / AMD Ryzen 3 3300X', '12 GB RAM', 'NVIDIA GeForce GTX 1060 3GB / AMD Radeon RX 580 4GB', '60 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7-8700K / AMD Ryzen 5 3600X', '16 GB RAM', 'NVIDIA GeForce GTX 1070 8GB / AMD Radeon RX Vega 56 8GB', '60 GB SSD'),
(2, 'Windows 10 64-bit', 'Intel Core i7-6700 / AMD Ryzen 5 1600', '12 GB RAM', 'NVIDIA GeForce GTX 1060 6GB / AMD Radeon RX 580', '70 GB SSD', 'Windows 10/11 64-bit', 'Intel Core i7-12700 / AMD Ryzen 7 7800X3D', '16 GB RAM', 'NVIDIA GeForce RTX 2060 Super / Radeon RX 5700 XT', '70 GB NVMe SSD'),
(3, 'Windows 10 64-bit', 'Intel Core i5-4670K / AMD FX-6300', '8 GB RAM', 'NVIDIA GeForce GTX 1060 6GB / AMD Radeon RX 570', '190 GB SSD', 'Windows 10/11 64-bit', 'Intel Core i5-8600 / AMD Ryzen 5 3600', '16 GB RAM', 'NVIDIA GeForce RTX 2060 / AMD Radeon RX 5700', '190 GB SSD'),
(4, 'Windows 10 64-bit', 'Intel Core i5-6600K / AMD Ryzen 5 1600', '8 GB RAM', 'NVIDIA GeForce GTX 1050 Ti 4GB / AMD Radeon RX 570', '100 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7-6700 / AMD Ryzen 7 2700X', '12 GB RAM', 'NVIDIA GeForce GTX 1660 / AMD Radeon RX 5600 XT', '100 GB SSD'),
(5, 'Windows 10 64-bit', 'Intel Core i5 equivalent', '8 GB RAM', 'NVIDIA GeForce GT 1030 or higher', '100 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7 equivalent', '16 GB RAM', 'NVIDIA GeForce GTX 1060 6GB or higher', '100 GB SSD'),
(6, 'Windows 10 64-bit', 'Intel Core i5-7500 / AMD Ryzen 3 1200', '8 GB RAM', 'NVIDIA GeForce GTX 1050 Ti 4GB / AMD Radeon RX 560', '70 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7-8700 / AMD Ryzen 5 3600', '16 GB RAM', 'NVIDIA GeForce GTX 1070 / AMD Radeon RX 5700', '70 GB SSD'),
(7, 'Windows 10 64-bit', 'Intel Core 2 Quad CPU Q6600 / AMD Phenom 9850', '4 GB RAM', 'NVIDIA 9800 GT 1GB / AMD HD 4870 1GB', '110 GB available space', 'Windows 10/11 64-bit', 'Intel Core i5 3470 / AMD X8 FX-8350', '8 GB RAM', 'NVIDIA GTX 660 2GB / AMD HD 7870 2GB', '110 GB SSD'),
(8, 'Windows 10 64-bit', 'Intel Core i5-2500K / AMD FX-6300', '8 GB RAM', 'NVIDIA GeForce GTX 770 2GB / AMD Radeon R9 280 3GB', '150 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7-4770K / AMD Ryzen 5 1500X', '12 GB RAM', 'NVIDIA GeForce GTX 1060 6GB / AMD Radeon RX 480 4GB', '150 GB SSD'),
(9, 'Windows 10 64-bit', 'Intel CPU Core i5-2500K / AMD A10-5800K', '6 GB RAM', 'NVIDIA GeForce GTX 660 / AMD Radeon HD 7870', '50 GB available space', 'Windows 10/11 64-bit', 'Intel CPU Core i7 3770 / AMD FX-8350', '8 GB RAM', 'NVIDIA GeForce GTX 770 / AMD Radeon R9 290', '50 GB SSD'),
(10, 'Windows 10 64-bit', 'Intel i5-4690 / AMD FX 8350', '8 GB RAM', 'Nvidia GTX 970 / RX 480 4GB', '150 GB SSD', 'Windows 10/11 64-bit', 'Intel i7 8700K / AMD r5 3600', '16 GB RAM', 'Nvidia 2060 Super / RX 5700 XT 8GB', '150 GB SSD'),
(11, 'Windows 10 64-bit', 'Intel Core i3-2100 / AMD FX-6300', '4 GB RAM', 'NVIDIA GeForce GTX 760 / AMD Radeon HD 7950', '25 GB available space', 'Windows 10/11 64-bit', 'Intel Core i5-2500K / AMD Ryzen 5 1400', '8 GB RAM', 'NVIDIA GeForce GTX 970 / AMD Radeon RX 570', '25 GB SSD'),
(12, 'Windows 10 64-bit', 'Intel Core i7-4790K / AMD Ryzen 5 1600', '8 GB RAM', 'NVIDIA GeForce GTX 1060 6GB / AMD Radeon RX 570 4GB', '40 GB SSD', 'Windows 10/11 64-bit', 'Intel Core i7-8700K / AMD Ryzen 5 3600', '16 GB RAM', 'NVIDIA GeForce RTX 2070 8GB / AMD Radeon RX 5700 XT', '40 GB SSD'),
(13, 'Windows 10 64-bit', 'Intel Core i5-3330 / AMD FX-8350', '8 GB RAM', 'GeForce GTX 780 / Radeon RX 480 3GB', '100 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7-3770 / AMD Ryzen 3 1200', '12 GB RAM', 'GeForce GTX 1060 / Radeon RX 580 6GB', '100 GB SSD'),
(14, 'Windows 10 64-bit', 'Intel Core i5-750 / AMD Phenom II X4', '8 GB RAM', 'DirectX 11 Graphics Card with 1GB VRAM', '85 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7-9700K / AMD Ryzen 7 3700X', '16 GB RAM', 'NVIDIA GeForce RTX 2070 / AMD Radeon RX 5700 XT', '85 GB SSD'),
(15, 'Windows 10 64-bit', 'Intel Core i5-4460 / AMD FX-6300', '8 GB RAM', 'NVIDIA GeForce GTX 760 / AMD Radeon R7 260x', '52 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7 3770 / AMD Ryzen 5 1500X', '8 GB RAM', 'NVIDIA GeForce GTX 1060 3GB / AMD Radeon RX 570 4GB', '52 GB SSD'),
(16, 'Windows 10 64-bit', 'Intel Core i3-2100 / AMD FX-6300', '4 GB RAM', 'NVIDIA GeForce GTX 750 Ti / ATI Radeon HD 7950', '25 GB available space', 'Windows 10/11 64-bit', 'Intel Core i7-3770 / AMD FX-8350', '8 GB RAM', 'NVIDIA GeForce GTX 970 / ATI Radeon R9 series', '25 GB SSD'),
(17, 'Windows 10 64-bit', 'Intel i5-4460 / AMD Ryzen 3 1200', '8 GB RAM', 'NVidia GTX 970 / AMD RX 470 4GB', '110 GB available space', 'Windows 10/11 64-bit', 'Intel i7-10700K / AMD Ryzen 7 3800XT', '16 GB RAM', 'NVidia RTX 3080 / AMD RX 6800 XT 10GB', '110 GB SSD'),
(18, 'Windows 10 64-bit', 'Dual core from Intel or AMD at 2.8 GHz', '4 GB RAM', 'NVIDIA GeForce 8600/9600GT, ATI Radeon HD2600/3600', '60 GB available space', 'Windows 10/11 64-bit', 'Intel Core i5 / AMD Ryzen 5', '8 GB RAM', 'NVIDIA GeForce GTX 1050 4GB', '60 GB SSD');

-- ------------------------------------------------------------------------------
-- 7. BANG GAME_ANALYTICS (Steam Charts Sales, Refunds & Ratings)
-- ------------------------------------------------------------------------------
CREATE TABLE `game_analytics` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `game_id` INT NOT NULL UNIQUE,
  `total_sold` INT DEFAULT 0 COMMENT 'Total copies sold',
  `total_refunded` INT DEFAULT 0 COMMENT 'Total copies refunded',
  `refund_rate` DECIMAL(5, 2) DEFAULT 0.00 COMMENT 'Refund percentage',
  `total_reviews` INT DEFAULT 0,
  `positive_reviews` INT DEFAULT 0,
  `positive_rate` DECIMAL(5, 2) DEFAULT 0.00 COMMENT 'Positive review percentage',
  `average_rating` DECIMAL(3, 2) DEFAULT 5.00,
  `revenue` DECIMAL(15, 2) DEFAULT 0.00,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `game_analytics` (`game_id`, `total_sold`, `total_refunded`, `refund_rate`, `total_reviews`, `positive_reviews`, `positive_rate`, `average_rating`, `revenue`) VALUES
(1, 1, 0, 0.00, 8820, 8430, 95.58, 4.92, 840000.00),  -- Elden Ring (Order #1)
(2, 1, 0, 0.00, 6210, 5340, 85.99, 4.55, 495000.00),  -- Cyberpunk 2077 (Order #2)
(3, 1, 0, 0.00, 4900, 4740, 96.73, 4.94, 1200000.00), -- God of War Ragnarok (Order #3)
(4, 1, 0, 0.00, 2420, 1530, 63.22, 3.85, 440000.00),  -- FC 24 (Order #4)
(5, 0, 0, 0.00, 18950, 17100, 90.24, 4.70, 0.00),      -- Genshin Impact (Free)
(6, 1, 0, 0.00, 2800, 2660, 95.00, 4.85, 712500.00),  -- RE4 Remake (Order #5)
(7, 1, 0, 0.00, 12500, 11800, 94.40, 4.88, 262500.00),-- GTA V (Order #6)
(8, 1, 0, 0.00, 9100, 8850, 97.25, 4.96, 445500.00),  -- RDR 2 (Order #7)
(9, 1, 0, 0.00, 7400, 7150, 96.62, 4.91, 150000.00),  -- Witcher 3 (Order #8)
(10, 1, 0, 0.00, 10200, 9880, 96.86, 4.97, 952000.00),-- Baldur's Gate 3 (Order #9)
(11, 1, 0, 0.00, 3100, 2920, 94.19, 4.82, 645000.00), -- Sekiro (Order #10)
(12, 1, 0, 0.00, 1850, 1420, 76.76, 4.10, 594000.00), -- AC Mirage (Order #11)
(13, 1, 0, 0.00, 2300, 2150, 93.48, 4.75, 700000.00), -- FF7 Remake (Order #5)
(14, 0, 0, 0.00, 15400, 13100, 85.06, 4.30, 0.00),    -- CS2 (Free)
(15, 1, 0, 0.00, 4100, 3680, 89.76, 4.60, 231000.00), -- Monster Hunter World (Order #1)
(16, 1, 0, 0.00, 5200, 4890, 94.04, 4.80, 550000.00), -- Dark Souls 3 (Order #3)
(17, 1, 0, 0.00, 3900, 3500, 89.74, 4.58, 670000.00), -- Forza Horizon 5 (Order #11)
(18, 0, 0, 0.00, 22000, 18900, 85.91, 4.40, 0.00);    -- Dota 2 (Free)

-- ------------------------------------------------------------------------------
-- 8. BANG NEWS (12 Gaming Articles in Standard English)
-- ------------------------------------------------------------------------------
CREATE TABLE `news` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `type` ENUM('system', 'game', 'genre') DEFAULT 'system',
  `game_id` INT DEFAULT NULL,
  `category_id` INT DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `news` (`id`, `title`, `slug`, `content`, `image`, `type`, `game_id`, `category_id`, `created_at`) VALUES
(1, 'Summer Sale 2026 Kicks Off: Major AAA Games Discounted Up to 75%', 'summer-sale-2026', 'Welcome the summer gaming heatwave as Stonk Games Store officially launches its biggest Summer Sale of the year. Hundreds of AAA masterpieces from top publishers such as FromSoftware, CD Projekt Red, and EA Sports are now on massive discounts. Do not miss this golden opportunity to expand your digital gaming library!', 'news_sale.jpg', 'system', NULL, NULL, '2026-06-01 10:00:00'),
(2, 'Elden Ring Releases Shadow of the Erdtree Update: Key Changes and Patch Notes', 'elden-ring-shadow-erdtree-update', 'FromSoftware has officially rolled out a major update introducing new maps, weapons, and balance adjustments across character classes. Players can now journey deeper into the Realm of Shadow with brand new challenges and boss encounters.', 'news_elden.jpg', 'game', 1, NULL, '2026-06-02 14:30:00'),
(3, 'Cyberpunk 2077 Announces Project Orion and Ray Tracing Overdrive Graphics Overhaul', 'cyberpunk-orion-announcement', 'CD Projekt Red has unveiled the first technical details regarding the upcoming sequel to Cyberpunk 2077. Meanwhile, the current game continues to receive cutting-edge ray reconstruction optimizations for next-generation GPUs.', 'news_cyberpunk.jpg', 'game', 2, NULL, '2026-06-03 16:15:00'),
(4, 'Top 5 Role-Playing Games (RPGs) You Must Play in 2026', 'top-5-rpg-games-2026', 'The RPG genre continues to dominate player interest thanks to rich world-building and profound narrative depth. We break down the top 5 standout role-playing titles currently captivating gamers worldwide.', 'news_rpg.jpg', 'genre', NULL, 2, '2026-06-04 09:00:00'),
(5, 'Baldur\'s Gate 3 Unveils Official Modding Toolkit and Community Features', 'baldurs-gate-3-mod-tools', 'Larian Studios has released the official modding pipeline for Baldur\'s Gate 3, empowering players and creators to build custom campaigns, subclasses, and scripts directly inside the Dungeons & Dragons ruleset.', 'news_rpg.jpg', 'game', 10, NULL, '2026-06-05 11:20:00'),
(6, 'Rockstar Games Confirms Schedule for Next Grand Theft Auto VI Trailer', 'rockstar-gta6-trailer-news', 'Following intense community speculation, Rockstar Games has officially confirmed its upcoming digital broadcast highlighting advanced volumetric weather systems and next-generation physics simulations.', 'news_cyberpunk.jpg', 'system', NULL, NULL, '2026-06-06 18:00:00'),
(7, 'PC Performance Optimization Guide: How to Achieve 60FPS in Red Dead Redemption 2', 'guide-optimize-pc-rdr2', 'Red Dead Redemption 2 remains one of the most graphically demanding titles on PC. Here is our comprehensive breakdown of essential graphics settings to maximize frame rates without compromising visual fidelity.', 'news_sale.jpg', 'game', 8, NULL, '2026-06-07 13:45:00'),
(8, 'The Evolution of the Souls-like Genre Across the New Decade', 'soulslike-genre-evolution', 'Ever since Demon\'s Souls and Elden Ring, the Souls-like formula has redefined standards for mechanical depth, spatial exploration, and combat precision in modern action gaming.', 'news_elden.jpg', 'genre', NULL, 1, '2026-06-08 08:30:00'),
(9, 'Stonk Games Store Introduces Instant VietQR Checkout and Automated Key Delivery', 'stonk-store-vietqr-launch', 'Designed specifically to streamline digital game purchases for Vietnamese players, Stonk Games Store now supports automated VietQR processing that delivers authentic CD-Keys within seconds.', 'news_sale.jpg', 'system', NULL, NULL, '2026-06-09 15:10:00'),
(10, 'EA SPORTS FC 24 Launches Team of the Season (TOTS) Global Event', 'ea-fc24-tots-event', 'Celebrate the finest football performances of the campaign with upgraded player cards, daily squad building challenges, and exclusive weekend competition rewards.', 'news_01.jpg', 'game', 4, NULL, '2026-06-10 17:00:00'),
(11, 'Counter-Strike 2 Deploys VacNet 3.0 Anti-Cheat Update and Mirage Refinements', 'cs2-update-vacnet3-mirage', 'Valve has shipped a major patch addressing tick-rate synchronization, smoke dispersal physics, and server-side machine learning detection to combat unauthorized third-party software in Premier matchmaking.', 'news_cyberpunk.jpg', 'game', 14, NULL, '2026-06-11 20:30:00'),
(12, 'Survival Horror Spotlight: The Most Gripping Atmospheric Games of the Year', 'horror-games-spotlight-2026', 'From Resident Evil 4 Remake to classic psychological thrillers, survival horror continues to deliver spine-chilling experiences with claustrophobic sound design and resource-constrained encounters.', 'news_02.jpg', 'genre', NULL, 7, '2026-06-12 21:00:00');

-- ------------------------------------------------------------------------------
-- 9. BANG REVIEWS (18 Authentic Gamer Reviews in English)
-- ------------------------------------------------------------------------------
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `game_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `rating` INT NOT NULL CHECK (`rating` BETWEEN 1 AND 5),
  `comment` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` (`id`, `game_id`, `user_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 3, 5, 'Masterpiece of the decade. The Lands Between is staggeringly immense and boss fights are intensely rewarding.', '2026-03-01 10:15:00'),
(2, 1, 7, 5, 'As a longtime FromSoftware enthusiast, Elden Ring delivers perfection across art direction, lore, and combat fluidity.', '2026-03-05 14:20:00'),
(3, 2, 4, 5, 'With Update 2.0 and Phantom Liberty, Cyberpunk 2077 has evolved into an absolute triumph. Night City visual fidelity is unmatched.', '2026-03-10 16:45:00'),
(4, 2, 6, 4, 'Incredible narrative and atmospheric synth soundtrack. Runs flawlessly with FSR on mid-range hardware.', '2026-03-12 19:30:00'),
(5, 3, 3, 5, 'Peak PlayStation storytelling and cinematic combat. Kratos and Atreus journey brought genuine tears during the finale.', '2026-03-18 21:10:00'),
(6, 4, 8, 4, 'HyperMotionV animations make player movement markedly smoother. Ultimate Team with friends is great fun.', '2026-03-22 09:40:00'),
(7, 5, 5, 5, 'Teyvat is visually gorgeous and the orchestral soundtrack is breathtaking. Runs like a dream on max PC settings.', '2026-03-28 15:50:00'),
(8, 6, 7, 5, 'Outstanding remake. Leon Kennedy is at his coolest, and the tense horror pacing never lets up.', '2026-04-02 22:05:00'),
(9, 7, 4, 5, 'A decade later and GTA V still outperforms modern sandbox titles. Pure timeless open-world genius.', '2026-04-08 11:30:00'),
(10, 8, 10, 5, 'Words cannot overstate this game. Arthur Morgan is the most nuanced and compelling protagonist in gaming history.', '2026-04-14 18:25:00'),
(11, 8, 3, 5, 'The frontier world feels truly alive. You can spend dozens of hours simply fishing, hunting, and exploring off the beaten path.', '2026-04-20 20:15:00'),
(12, 9, 10, 5, 'The Witcher 3 remains an unmatched benchmark for storytelling. Side quests have more depth than entire standalone games.', '2026-04-26 13:50:00'),
(13, 10, 9, 5, 'Larian Studios crafted a role-playing marvel. Unparalleled player agency and reactive storytelling across multiple playthroughs.', '2026-05-01 17:40:00'),
(14, 11, 12, 5, 'The rhythm of deflection and posture breaking is deeply addictive. Defeating Sword Saint Isshin is one of gaming greatest feelings.', '2026-05-06 23:10:00'),
(15, 12, 4, 4, 'Ninth-century Baghdad is stunningly realized. Love the focused stealth gameplay returning to the franchise roots.', '2026-05-12 14:15:00'),
(16, 13, 11, 5, 'Visuals and combat choreography are stunning. Hearing One-Winged Angel during the climax gave me absolute chills.', '2026-05-17 19:20:00'),
(17, 14, 8, 4, 'Dynamic volumetric smokes completely change tactical choke points. Crisp gunplay in Premier matchmaking.', '2026-05-22 21:45:00'),
(18, 17, 6, 5, 'Best looking racing title on the market. Cruising across Mexican deserts at sunset with licensed hypercars is pure bliss.', '2026-05-28 16:35:00');

-- ------------------------------------------------------------------------------
-- 10. BANG ORDERS & ORDER_ITEMS (12 Orders & 16 Key Activations)
-- ------------------------------------------------------------------------------
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(100) NOT NULL,
  `total_amount` DECIMAL(12, 2) NOT NULL,
  `payment_method` VARCHAR(50) DEFAULT 'VIETQR',
  `status` ENUM('COMPLETED', 'PENDING') DEFAULT 'COMPLETED',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `game_id` INT NOT NULL,
  `price` DECIMAL(12, 2) NOT NULL,
  `cd_key` VARCHAR(100) NOT NULL COMMENT 'Automated digital license CD-Key',
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`game_id`) REFERENCES `games`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` (`id`, `user_id`, `customer_name`, `customer_email`, `total_amount`, `payment_method`, `status`, `created_at`) VALUES
(1, 3, 'Gamer Nguyen', 'gamer01@gmail.com', 840000.00, 'VIETQR', 'COMPLETED', '2026-03-01 10:10:00'),
(2, 4, 'Hoang Long', 'long.hoang@gmail.com', 495000.00, 'VIETQR', 'COMPLETED', '2026-03-10 16:40:00'),
(3, 5, 'Thu Trang', 'trang.gaming@hotmail.com', 1200000.00, 'VIETQR', 'COMPLETED', '2026-03-18 21:05:00'),
(4, 6, 'Minh Quan', 'ninja99@yahoo.com', 440000.00, 'VIETQR', 'COMPLETED', '2026-03-22 09:35:00'),
(5, 7, 'Tuan Kiet', 'tuan.darksouls@outlook.com', 712500.00, 'VIETQR', 'COMPLETED', '2026-04-02 22:00:00'),
(6, 8, 'Minh Tri', 'tri.fps@gmail.com', 262500.00, 'VIETQR', 'COMPLETED', '2026-04-08 11:25:00'),
(7, 9, 'Hoang Nam', 'cloud.vn@gmail.com', 445500.00, 'VIETQR', 'COMPLETED', '2026-04-14 18:20:00'),
(8, 10, 'Gia Huy', 'geralt.witcher@gmail.com', 150000.00, 'VIETQR', 'COMPLETED', '2026-04-26 13:45:00'),
(9, 11, 'Lan Anh', 'lananh.cozy@gmail.com', 952000.00, 'VIETQR', 'COMPLETED', '2026-05-01 17:35:00'),
(10, 12, 'Viet Anh', 'vietanh.gamer@gmail.com', 645000.00, 'VIETQR', 'COMPLETED', '2026-05-06 23:05:00'),
(11, NULL, 'Guest Customer VIP', 'guest.vip@gmail.com', 1264000.00, 'VIETQR', 'COMPLETED', '2026-05-15 14:10:00'),
(12, 3, 'Gamer Nguyen', 'gamer01@gmail.com', 670000.00, 'VIETQR', 'PENDING', '2026-05-20 18:40:00'),
(13, 3, 'Gamer Nguyen', 'gamer01@gmail.com', 952000.00, 'VIETQR', 'COMPLETED', '2026-05-22 14:15:00'),
(14, 4, 'Hoang Long', 'long.hoang@gmail.com', 840000.00, 'VIETQR', 'COMPLETED', '2026-05-24 19:30:00'),
(15, 5, 'Thu Trang', 'trang.gaming@hotmail.com', 1102000.00, 'VIETQR', 'COMPLETED', '2026-05-27 10:20:00'),
(16, 6, 'Minh Quan', 'ninja99@yahoo.com', 1200000.00, 'VIETQR', 'COMPLETED', '2026-05-29 16:45:00'),
(17, 7, 'Tuan Kiet', 'tuan.darksouls@outlook.com', 1390000.00, 'VIETQR', 'COMPLETED', '2026-06-01 20:10:00'),
(18, 8, 'Minh Tri', 'tri.fps@gmail.com', 702500.00, 'VIETQR', 'COMPLETED', '2026-06-03 11:35:00'),
(19, 9, 'Hoang Nam', 'cloud.vn@gmail.com', 931000.00, 'VIETQR', 'COMPLETED', '2026-06-05 18:00:00'),
(20, 10, 'Gia Huy', 'geralt.witcher@gmail.com', 940500.00, 'VIETQR', 'COMPLETED', '2026-06-07 15:20:00'),
(21, 11, 'Lan Anh', 'lananh.cozy@gmail.com', 1622000.00, 'VIETQR', 'COMPLETED', '2026-06-09 21:40:00'),
(22, 12, 'Viet Anh', 'vietanh.gamer@gmail.com', 1845000.00, 'VIETQR', 'COMPLETED', '2026-06-11 13:10:00'),
(23, NULL, 'Bui Thanh Dat', 'bui.thanh.dat@gmail.com', 840000.00, 'VIETQR', 'COMPLETED', '2026-06-13 09:25:00'),
(24, NULL, 'Nguyen Duc Thanh', 'nguyen.duc.thanh@gmail.com', 712500.00, 'VIETQR', 'COMPLETED', '2026-06-15 17:50:00'),
(25, NULL, 'Pham Minh Khang', 'pham.minh.khang@gmail.com', 708000.00, 'VIETQR', 'COMPLETED', '2026-06-17 12:05:00'),
(26, NULL, 'Le Thi Mai', 'le.thi.mai@gmail.com', 440000.00, 'VIETQR', 'COMPLETED', '2026-06-19 14:40:00'),
(27, NULL, 'Do Hoang Anh', 'do.hoang.anh@outlook.com', 1200000.00, 'VIETQR', 'COMPLETED', '2026-06-21 22:15:00'),
(28, NULL, 'Vu Dinh Trong', 'vu.dinh.trong@yahoo.com', 645000.00, 'VIETQR', 'COMPLETED', '2026-06-23 16:30:00'),
(29, 3, 'Gamer Nguyen', 'gamer01@gmail.com', 2040000.00, 'VIETQR', 'COMPLETED', '2026-06-25 19:10:00'),
(30, 4, 'Hoang Long', 'long.hoang@gmail.com', 952000.00, 'VIETQR', 'COMPLETED', '2026-06-26 10:05:00'),
(31, NULL, 'Tran Quoc Bao', 'quocbao.hubt@gmail.com', 643500.00, 'VIETQR', 'COMPLETED', '2026-06-27 15:45:00'),
(32, 8, 'Minh Tri', 'tri.fps@gmail.com', 1390000.00, 'VIETQR', 'COMPLETED', '2026-06-28 20:50:00');

INSERT INTO `order_items` (`id`, `order_id`, `game_id`, `price`, `cd_key`) VALUES
(1, 1, 1, 840000.00, 'STONK-ELDR-9921-AF3C-7801'),
(2, 2, 2, 495000.00, 'STONK-CYBR-7712-B8E2-1194'),
(3, 3, 3, 1200000.00, 'STONK-GOWR-3310-CC89-6612'),
(4, 4, 4, 440000.00, 'STONK-FC24-5541-DD34-9903'),
(5, 5, 6, 712500.00, 'STONK-RE4R-8832-EA12-4451'),
(6, 6, 7, 262500.00, 'STONK-GTA5-1294-FB77-3319'),
(7, 7, 8, 445500.00, 'STONK-RDR2-4481-99CC-2201'),
(8, 8, 9, 150000.00, 'STONK-WTC3-7729-11AA-5582'),
(9, 9, 10, 952000.00, 'STONK-BG3D-6610-88EE-7723'),
(10, 10, 11, 645000.00, 'STONK-SEKR-2291-55FF-1140'),
(11, 11, 12, 594000.00, 'STONK-ACMR-9934-77BA-4481'),
(12, 11, 17, 670000.00, 'STONK-FH5X-3312-66DD-9914'),
(13, 12, 17, 670000.00, 'STONK-FH5X-8841-22CC-5531'),
(14, 1, 15, 231000.00, 'STONK-MHWX-1109-44AA-8822'),
(15, 3, 16, 550000.00, 'STONK-DS3K-7741-99BB-3311'),
(16, 5, 13, 700000.00, 'STONK-FF7R-4412-88EE-2209'),
(17, 13, 10, 952000.00, 'STONK-BG3D-4412-88EE-7711'),
(18, 14, 1, 840000.00, 'STONK-ELDR-7712-AA43-9901'),
(19, 15, 10, 952000.00, 'STONK-BG3D-9914-BB32-4402'),
(20, 15, 9, 150000.00, 'STONK-WTC3-2210-CC88-5511'),
(21, 16, 3, 1200000.00, 'STONK-GOWR-8831-DD22-6691'),
(22, 17, 1, 840000.00, 'STONK-ELDR-3310-FF44-1188'),
(23, 17, 16, 550000.00, 'STONK-DS3K-5521-EE99-2233'),
(24, 18, 7, 262500.00, 'STONK-GTA5-6671-AA11-4433'),
(25, 18, 4, 440000.00, 'STONK-FC24-1194-BB22-5566'),
(26, 19, 13, 700000.00, 'STONK-FF7R-9922-CC44-7711'),
(27, 19, 15, 231000.00, 'STONK-MHWX-4410-DD55-8822'),
(28, 20, 2, 495000.00, 'STONK-CYBR-3341-EE66-9900'),
(29, 20, 8, 445500.00, 'STONK-RDR2-7712-FF77-1122'),
(30, 21, 10, 952000.00, 'STONK-BG3D-1109-AA88-3344'),
(31, 21, 17, 670000.00, 'STONK-FH5X-5541-BB99-4455'),
(32, 22, 3, 1200000.00, 'STONK-GOWR-2291-CC11-5566'),
(33, 22, 11, 645000.00, 'STONK-SEKR-8832-DD22-6677'),
(34, 23, 1, 840000.00, 'STONK-ELDR-6610-EE33-7788'),
(35, 24, 6, 712500.00, 'STONK-RE4R-4481-FF44-8899'),
(36, 25, 7, 262500.00, 'STONK-GTA5-9934-AA55-9900'),
(37, 25, 8, 445500.00, 'STONK-RDR2-3312-BB66-1122'),
(38, 26, 4, 440000.00, 'STONK-FC24-7741-CC77-2233'),
(39, 27, 3, 1200000.00, 'STONK-GOWR-1109-DD88-3344'),
(40, 28, 2, 495000.00, 'STONK-CYBR-5521-EE99-4455'),
(41, 28, 9, 150000.00, 'STONK-WTC3-8841-FF00-5566'),
(42, 29, 1, 840000.00, 'STONK-ELDR-2291-AA11-6677'),
(43, 29, 3, 1200000.00, 'STONK-GOWR-9934-BB22-7788'),
(44, 30, 10, 952000.00, 'STONK-BG3D-4481-CC33-8899'),
(45, 31, 7, 262500.00, 'STONK-GTA5-3310-DD44-9900'),
(46, 31, 15, 231000.00, 'STONK-MHWX-7741-EE55-1122'),
(47, 31, 9, 150000.00, 'STONK-WTC3-1109-FF66-2233'),
(48, 32, 1, 840000.00, 'STONK-ELDR-8832-AA77-3344'),
(49, 32, 16, 550000.00, 'STONK-DS3K-2291-BB88-4455');

-- ------------------------------------------------------------------------------
-- DONG BO DU LIEU PHAN TICH (ANALYTICS SYNC)
-- Tinh toan total_sold va revenue chuan xac theo so luot ban thuc te tu bang order_items (chi tinh don COMPLETED)
-- ------------------------------------------------------------------------------
UPDATE `game_analytics` ga
SET `total_sold` = (
    SELECT COUNT(oi.id)
    FROM `order_items` oi
    JOIN `orders` o ON oi.order_id = o.id
    WHERE oi.game_id = ga.game_id AND o.status = 'COMPLETED'
),
`revenue` = COALESCE((
    SELECT SUM(oi.price)
    FROM `order_items` oi
    JOIN `orders` o ON oi.order_id = o.id
    WHERE oi.game_id = ga.game_id AND o.status = 'COMPLETED'
), 0.00);
