-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: lin2-sql.loopbyte.com:3306
-- Generation Time: Mar 02, 2019 at 01:27 PM
-- Server version: 10.1.20-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `desired_film`
--

-- --------------------------------------------------------

--
-- Table structure for table `actors`
--

CREATE TABLE `actors` (
  `id` int(11) NOT NULL,
  `actor_name` varchar(200) NOT NULL,
  `actor_picture` varchar(200) NOT NULL,
  `actor_nconst` varchar(250) NOT NULL,
  `birthday` text NOT NULL,
  `place_of_birth` text NOT NULL,
  `biography` text NOT NULL,
  `actor_img_url` text NOT NULL,
  `imdbid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `actor_relations`
--

CREATE TABLE `actor_relations` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `amount` int(100) NOT NULL,
  `amount_plain` varchar(100) NOT NULL,
  `action` varchar(100) NOT NULL,
  `member` text NOT NULL,
  `multi_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `episodes`
--

CREATE TABLE `episodes` (
  `id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `episode_number` varchar(200) NOT NULL,
  `episode_name` varchar(200) NOT NULL,
  `episode_description` text NOT NULL,
  `episode_thumbnail` varchar(500) NOT NULL,
  `episode_source` text NOT NULL,
  `is_embed` int(11) NOT NULL,
  `actor_id` text NOT NULL,
  `season_sub_id` varchar(200) NOT NULL,
  `ratings` varchar(50) NOT NULL,
  `all_starcast` varchar(200) NOT NULL DEFAULT 'no',
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `genre_name` varchar(100) NOT NULL,
  `is_kid_friendly` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `genres_relations`
--

CREATE TABLE `genres_relations` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `genres_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `membership_plan`
--

CREATE TABLE `membership_plan` (
  `id` int(11) NOT NULL,
  `membership_type` varchar(30) NOT NULL,
  `membership_plan` text NOT NULL,
  `price` text NOT NULL,
  `time_period` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `movie_name` varchar(200) NOT NULL,
  `movie_plot` text NOT NULL,
  `movie_year` text NOT NULL,
  `movie_genres` varchar(200) NOT NULL,
  `movie_poster_image` varchar(500) NOT NULL,
  `movie_thumb_image` varchar(500) DEFAULT NULL,
  `movie_plays` int(11) NOT NULL,
  `movie_source` text NOT NULL,
  `movie_rating` varchar(30) NOT NULL,
  `is_embed` int(11) NOT NULL,
  `is_featured` int(11) NOT NULL,
  `is_series` int(11) NOT NULL,
  `last_season` int(11) NOT NULL,
  `is_kid_friendly` int(11) NOT NULL,
  `free_to_watch` int(11) NOT NULL,
  `from_type` varchar(255) NOT NULL DEFAULT 'video',
  `imdbid` varchar(100) DEFAULT NULL,
  `all_starcast` varchar(100) NOT NULL DEFAULT 'no',
  `alternative_titles` text NOT NULL,
  `watch` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `my_list`
--

CREATE TABLE `my_list` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` enum('video','episode') NOT NULL DEFAULT 'video'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `my_watched`
--

CREATE TABLE `my_watched` (
  `id` int(11) NOT NULL,
  `movie_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `type` enum('video','episode') NOT NULL DEFAULT 'video'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `my_watched_episodes`
--

CREATE TABLE `my_watched_episodes` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `episode_id` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `season_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `page_name` varchar(100) NOT NULL,
  `page_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_name` varchar(100) NOT NULL,
  `profile_avatar` int(11) NOT NULL,
  `profile_language` varchar(50) NOT NULL,
  `is_kid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `p_category`
--

CREATE TABLE `p_category` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `added_date` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `seasons`
--

CREATE TABLE `seasons` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `season_number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `session_id` varchar(200) NOT NULL,
  `user_ip` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `language` varchar(50) NOT NULL,
  `is_active` int(11) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `website_name` varchar(200) NOT NULL,
  `website_title` varchar(100) NOT NULL,
  `website_description` text NOT NULL,
  `website_keywords` varchar(200) NOT NULL,
  `theme` varchar(100) NOT NULL,
  `paypal_email` varchar(100) NOT NULL,
  `subscription_price` int(11) NOT NULL,
  `subscription_currency` varchar(20) NOT NULL,
  `subscription_name` varchar(100) NOT NULL,
  `disquis_short_name` varchar(50) NOT NULL,
  `footer_on_content_optimized_pages` int(11) NOT NULL,
  `redirect_after_login` varchar(100) NOT NULL,
  `default_language` varchar(50) NOT NULL,
  `facebook_url` varchar(100) NOT NULL,
  `twitter_url` varchar(100) NOT NULL,
  `show_actors` int(11) NOT NULL,
  `supports_starring` int(11) NOT NULL,
  `kid_profiles` int(11) NOT NULL,
  `show_profiles` int(11) NOT NULL,
  `supports_profiles` int(11) NOT NULL,
  `jwplayer_key` varchar(100) NOT NULL,
  `title1` varchar(255) NOT NULL,
  `link1` varchar(255) NOT NULL,
  `title2` varchar(255) NOT NULL,
  `link2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------
INSERT INTO `settings` (`id`, `website_name`, `website_title`, `website_description`, `website_keywords`, `theme`, `paypal_email`, `subscription_price`, `subscription_currency`, `subscription_name`, `disquis_short_name`, `footer_on_content_optimized_pages`, `redirect_after_login`, `default_language`, `facebook_url`, `twitter_url`, `show_actors`, `supports_starring`, `kid_profiles`, `show_profiles`, `supports_profiles`, `jwplayer_key`, `title1`, `link1`, `title2`, `link2`) VALUES
(1, 'Films', 'Films', 'Movies and Series ', 'Movies and Series', 'flixer', 'payopal@yahoo.co.uk', 5, 'GBP', 'Premium', '', 0, 'select_profile', 'english', '', '', 1, 1, 1, 1, 1, 'xsgkzu5wjGCCbjQvUIVVWy0p4i2og7S05JDAKA==', 'Video Download Co r ', 'test.com', 'Apowersoft r', 'test.com');

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL,
  `name` varchar(200) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `phone_country_code` varchar(30) NOT NULL,
  `last_profile` int(11) NOT NULL,
  `last_profile_name` varchar(50) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `is_subscriber` int(11) NOT NULL,
  `subscription_expiration` int(30) DEFAULT NULL,
  `is_suspended` varchar(255) DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Dumping data for table `users`
--


--
-- Table structure for table `vedios`
--

CREATE TABLE `vedios` (
  `id` int(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumbnail` varchar(255) NOT NULL,
  `img_url` varchar(255) NOT NULL,
  `category_id` varchar(11) NOT NULL,
  `added_date` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `watched_corner_tag`
--

CREATE TABLE `watched_corner_tag` (
  `id` int(11) NOT NULL,
  `user_id` varchar(10) DEFAULT NULL,
  `movie_id` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actor_relations`
--
ALTER TABLE `actor_relations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `genres_relations`
--
ALTER TABLE `genres_relations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `membership_plan`
--
ALTER TABLE `membership_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_list`
--
ALTER TABLE `my_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_watched`
--
ALTER TABLE `my_watched`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `my_watched_episodes`
--
ALTER TABLE `my_watched_episodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `p_category`
--
ALTER TABLE `p_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seasons`
--
ALTER TABLE `seasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `watched_corner_tag`
--
ALTER TABLE `watched_corner_tag`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actors`
--
ALTER TABLE `actors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37379;

--
-- AUTO_INCREMENT for table `actor_relations`
--
ALTER TABLE `actor_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103591;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `episodes`
--
ALTER TABLE `episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2724;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `genres_relations`
--
ALTER TABLE `genres_relations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14432;

--
-- AUTO_INCREMENT for table `membership_plan`
--
ALTER TABLE `membership_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1306;

--
-- AUTO_INCREMENT for table `my_list`
--
ALTER TABLE `my_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `my_watched`
--
ALTER TABLE `my_watched`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;

--
-- AUTO_INCREMENT for table `my_watched_episodes`
--
ALTER TABLE `my_watched_episodes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `p_category`
--
ALTER TABLE `p_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2267;

--
-- AUTO_INCREMENT for table `seasons`
--
ALTER TABLE `seasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=462;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `watched_corner_tag`
--
ALTER TABLE `watched_corner_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
