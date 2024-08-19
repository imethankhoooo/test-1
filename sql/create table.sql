-- 创建 event 表
CREATE TABLE `event` (
    `event_id` INT PRIMARY KEY AUTO_INCREMENT,
    `event_name` VARCHAR(255) NOT NULL,
    `event_date` DATE NOT NULL,
    `time` TIME NOT NULL,
    `location` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `banner_image` BLOB, 
    `note` VARCHAR(200),
    `fee` DECIMAL(10, 2),
    `event_host` VARCHAR(255),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 创建 member 表
CREATE TABLE `member` (
    `member_id` INT PRIMARY KEY AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(20),
    `address` VARCHAR(255),
    `bio` TEXT,
    `socialmedia` TEXT,
    `experience` TEXT,
    `education` TEXT,
    `skill` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
     -- CONSTRAINT `chk_email_format` CHECK (`email` REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'),不知道为什么用不到啊啊啊啊啊
     -- CONSTRAINT `chk_phone_format` CHECK (`phone` REGEXP '^(\+?60|0)1[0-46-9]-*[0-9]{7,8}$'),
);
CREATE TABLE `admin_member` (
    `admin_id` INT PRIMARY KEY AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `phone` VARCHAR(20),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    -- CONSTRAINT `chk_admin_email_format` CHECK (`email` REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$'),
    -- CONSTRAINT `chk_admin_phone_format` CHECK (`phone` REGEXP '^(\+?60|0)1[0-46-9]-*[0-9]{7,8}$')
);

CREATE TABLE `item` (
    `item_id` INT PRIMARY KEY AUTO_INCREMENT,
    `event_id` INT NOT NULL,
    `logo` BLOB,
    `description` TEXT, 
    FOREIGN KEY (`event_id`) REFERENCES `event`(`event_id`) ON DELETE CASCADE
);

CREATE TABLE `event_registration` (
    `registration_id` INT PRIMARY KEY AUTO_INCREMENT,
    `event_id` INT NOT NULL,
    `member_id` INT NOT NULL,
    `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`event_id`) REFERENCES `event`(`event_id`) ON DELETE CASCADE,
    FOREIGN KEY (`member_id`) REFERENCES `member`(`member_id`) ON DELETE CASCADE
);
