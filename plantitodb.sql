DROP DATABASE IF EXISTS plantitodb;
CREATE DATABASE plantitodb;
use plantitodb;

CREATE TABLE user (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    email char(64),
    password varchar(64),
    lname varchar(32) NOT NULL,
    fname varchar(32),
    addressline TEXT,
    phone varchar(16),
    role_id INT NOT NULL
);

CREATE TABLE role(
    role_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE item (
    item_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(200) NOT NULL,
    price decimal(7,2),
    cat_id INT NOT NULL,
    img_path varchar(64)
);

CREATE TABLE category(
    cat_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE stock (
    item_id INT NOT NULL PRIMARY KEY,
    quantity INT,
    CONSTRAINT stock_item_id_fk FOREIGN KEY (item_id) REFERENCES item(item_id) ON DELETE CASCADE
);

CREATE TABLE orderinfo (
    orderinfo_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    date_placed date NOT NULL,
    date_shipped date,
    shipping decimal(7,2),
    INDEX(user_id),
    CONSTRAINT orderinfo_user_id_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

CREATE TABLE orderline (
    orderinfo_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity TINYINT,
    PRIMARY KEY (orderinfo_id, item_id),
    CONSTRAINT orderline_orderinfo_id_fk FOREIGN KEY (orderinfo_id) REFERENCES orderinfo(orderinfo_id) ON DELETE CASCADE,
    CONSTRAINT item_item_id_fk FOREIGN KEY (item_id) REFERENCES item(item_id)  ON DELETE CASCADE
);

CREATE TABLE review (
    rev_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    description varchar(200),
    CONSTRAINT review_user_id_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

INSERT INTO role(description)VALUES
('admin'),
('customer');

INSERT INTO user(email, password, lname, fname, addressline, phone, role_id)VALUES
('marbellasj@gmail.com', 'marbella1', 'Marbella', 'Sharwin', 'Taguig', '09935312681', 1),
('lacortekc@gmail.com', 'lacorte1', 'Lacorte', 'Krsmur', 'Taguig', '09937564575', 1),
('pequemes@gmail.com', 'peque1', 'Peque', 'Erica', 'Taguig', '09063354124', 2),
('labilabimk@gmail.com', 'labilabi1', 'Labi-labi', 'Maria', 'Taguig', '09557966852', 2),
('elediak@gmail.com', 'eledia1', 'Eledia', 'Kimberly', 'Taguig', '09125474775', 2),
('calungsodmp@gmail.com', 'calungsod1', 'Calungsod', 'Mary', 'Taguig', '09334567445', 2);