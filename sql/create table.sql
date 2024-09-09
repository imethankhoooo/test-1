-- 名字叫 php-assginment
CREATE TABLE admin_member (
    admin_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE bookinginformation (
    booking_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_id INT(11) NOT NULL,
    member_id INT(11) NOT NULL,
    paymentAmount FLOAT NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE event (
    event_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    event_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    banner_image BLOB DEFAULT NULL,
    note VARCHAR(200) DEFAULT NULL,
    fee DECIMAL(10, 2) DEFAULT NULL,
    seat INT(4) NOT NULL,
    event_host VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(15) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE item (
    item_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    event_id INT(11) NOT NULL,
    logo BLOB DEFAULT NULL,
    description TEXT DEFAULT NULL
);


CREATE TABLE member (
    member_id INT(11) AUTO_INCREMENT PRIMARY KEY,
    avatar BLOB DEFAULT NULL,
    name VARCHAR(255) DEFAULT NULL,
    username VARCHAR(40) DEFAULT NULL,
    gender CHAR(7) DEFAULT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(20) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    bio TEXT DEFAULT NULL,
    experience VARCHAR(255) DEFAULT NULL,
    socialmedia TEXT DEFAULT NULL
);


CREATE TABLE ticket (
    ticket_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    booking_id INT(10) NOT NULL,
    event_id INT(11) NOT NULL,
    issue_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    member_id INT(10) NOT NULL
);
