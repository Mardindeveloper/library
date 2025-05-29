CREATE DATABASE IF NOT EXISTS `library`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `library`;

CREATE TABLE `user` (
  `user_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` varchar(100) NOT NULL,
  `can_loan` TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user` (`user_id`, `fullname`, `username`, `password`, `level`) VALUES
(1, 'Administrator', 'admin', '$2y$10$3Js06eoB6O9SF5toCZEDeeFWWm2gjyo6GcAGHuk2kkE5QO13VcwwG', 'admin');

CREATE TABLE `book_category` (
  `category_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `book_category` (`category_id`, `category_name`) VALUES
(0, 'No categories'),
(1, 'Educational'),
(2, 'Fiction'),
(3, 'Fantasy'),
(4, 'Horror');

CREATE TABLE `book` (
  `book_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `book_title` varchar(100) NOT NULL,
  `year` int NOT NULL,
  `category_id` int NULL,
  `price` int NOT NULL,
  `book_img` varchar(100) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `author_id` int NULL,
  `is_loanable` TINYINT(1) NOT NULL DEFAULT 0,
  `stock` int NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `transaction` (
  `transaction_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` int NULL,
  `buyer_name` varchar(100) NOT NULL,
  `total` int NOT NULL,
  `transaction_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `transaction_detail` (
  `transaction_id` int NULL,
  `book_id` int NULL,
  `quantity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `author` (
  `author_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `book_author` (
  `book_id` int NULL,
  `author_id` int NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `book_copy` (
  `copy_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `book_id` int NULL,
  `status` ENUM('available','on_loan','lost','maintenance') NOT NULL DEFAULT 'available',
  `barcode` varchar(50) UNIQUE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `loan` (
  `loan_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` int NULL,
  `copy_id` int NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_activity` (
  `activity_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT (CURRENT_TIMESTAMP)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `role` (
  `role_id` int PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `user_role` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- transaction
ALTER TABLE `transaction`
  ADD CONSTRAINT `transaction_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD INDEX idx_transaction_user  (`user_id`),
  ADD INDEX idx_transaction_date (`transaction_date`),
  ADD INDEX idx_transaction_user_date (`user_id`, `transaction_date`);


ALTER TABLE `transaction_detail`
  ADD UNIQUE KEY uniq_transaction_book (`transaction_id`, `book_id`);

ALTER TABLE `transaction_detail`
  ADD CONSTRAINT `transaction_detail_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_detail_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD INDEX idx_detail_book (`book_id`),
  ADD INDEX idx_detail_book_txdate (`book_id`, `transaction_id`);

-- Book
ALTER TABLE `book_author`
  ADD UNIQUE KEY uniq_transaction_book (`book_id`, `author_id`),
  ADD CONSTRAINT `book_author_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `book_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `book` ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `book_category` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `book` ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `book_copy` ADD CONSTRAINT `book_copy_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- user
ALTER TABLE `user_role`
  ADD PRIMARY KEY(`user_id`, `role_id`),
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `loan`
  ADD CONSTRAINT `loan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `loan_ibfk_2` FOREIGN KEY (`copy_id`) REFERENCES `book_copy` (`copy_id`) ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE `user_activity` ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Trigger
DELIMITER //

CREATE TRIGGER `loan_before_insert`
BEFORE INSERT ON `loan`
FOR EACH ROW
BEGIN
  IF (SELECT can_loan FROM `user` WHERE user_id = NEW.user_id) = 0 THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'User is not allowed to loan any more';
  END IF;
END;
//

DELIMITER ;
