CREATE DATABASE IF NOT EXISTS `movie_booking` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `movie_booking`;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Movies Table
CREATE TABLE IF NOT EXISTS `movies` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `genre` VARCHAR(100) DEFAULT 'Cinema',
  `duration_min` INT DEFAULT 120,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Showtimes Table
CREATE TABLE IF NOT EXISTS `showtimes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `movie_id` INT NOT NULL,
  `show_date` DATE NOT NULL,
  `showtime` VARCHAR(10) NOT NULL, -- e.g., '18:00'
  FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Seats Table
CREATE TABLE IF NOT EXISTS `seats` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `movie_id` INT NOT NULL,
  `showtime_id` INT DEFAULT NULL,
  `seat_no` VARCHAR(10) NOT NULL, -- e.g., 'A1', 'B3'
  `status` ENUM('free', 'booked') DEFAULT 'free',
  `booked_by` INT DEFAULT NULL,
  FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`booked_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  UNIQUE KEY `unique_seat_per_show` (`movie_id`, `showtime_id`, `seat_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;