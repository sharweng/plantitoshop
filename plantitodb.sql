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
    pfp_path varchar(128),
    role_id INT NOT NULL
);

CREATE TABLE role(
    role_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE product(
    prod_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(200) NOT NULL,
    price decimal(7,2),
    cat_id INT
);

CREATE TABLE image(
    img_id INT NOT NULL AUTO_INCREMENT,
    prod_id INT NOT NULL,
    img_path varchar(128) NOT NULL,
    PRIMARY KEY (img_id, prod_id),
    CONSTRAINT image_prod_id_fk FOREIGN KEY (prod_id) REFERENCES product(prod_id) ON DELETE CASCADE
);

CREATE TABLE category(
    cat_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    description varchar(32)
);

CREATE TABLE stock (
    prod_id INT NOT NULL PRIMARY KEY,
    quantity INT,
    CONSTRAINT stock_prod_id_fk FOREIGN KEY (prod_id) REFERENCES product(prod_id) ON DELETE CASCADE
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
    prod_id INT NOT NULL,
    quantity TINYINT,
    PRIMARY KEY (orderinfo_id, prod_id),
    CONSTRAINT orderline_orderinfo_id_fk FOREIGN KEY (orderinfo_id) REFERENCES orderinfo(orderinfo_id) ON DELETE CASCADE,
    CONSTRAINT orderline_prod_id_fk FOREIGN KEY (prod_id) REFERENCES product(prod_id)  ON DELETE CASCADE
);

CREATE TABLE review (
    rev_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    description varchar(200),
    CONSTRAINT review_user_id_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

INSERT INTO role(description)VALUES
('admin'),
('user'),
('deactivated');

INSERT INTO category(description)VALUES
('Miscellaneous'),
('Herbs'),
('Shrubs'),
('Creepers'),
('Climbers');


INSERT INTO user(email, password, lname, fname, addressline, phone, pfp_path, role_id)VALUES
('marbellasj@gmail.com', '472abab1382a164fe6f0212122bbb12a215457b8', 'Marbella', 'Sharwin', 'Taguig', '09935312681', 'images/default-avatar-icon.jpg', 1),
('lacortekc@gmail.com', 'f64f01582774b4b4c5eb970d1585d830903c52a5', 'Lacorte', 'Krsmur', 'Taguig', '09937564575', 'images/default-avatar-icon.jpg', 1),
('pequemes@gmail.com', 'c066b73b07369d10887d9570b8278851a28c6164', 'Peque', 'Erica', 'Taguig', '09063354124', 'images/default-avatar-icon.jpg', 2),
('labilabimk@gmail.com', 'c1e93b1f026abeac0e5a356535bfc2e3727c1045', 'Labi-labi', 'Maria', 'Taguig', '09557966852', 'images/default-avatar-icon.jpg', 2),
('elediak@gmail.com', 'e83d6fd53b9d1176232f5131b5b81c035b144b26', 'Eledia', 'Kimberly', 'Taguig', '09125474775', 'images/default-avatar-icon.jpg', 2),
('calungsodmp@gmail.com', 'adc8347c5f07d03e9d9dee22b8888aff349d5af7', 'Calungsod', 'Mary', 'Taguig', '09334567445', 'images/default-avatar-icon.jpg', 2);

INSERT INTO product(description, price, cat_id)VALUES
('Watering Can', 100, 1),
('Trowel', 130, 1),
('Garden Rake', 170, 1),
('Loam Soil', 150, 1),
('Basil', 100, 2),
('Parsley', 120, 2),
('Rosemery', 200, 2),
('Thyme', 150, 2),
('Cilantro', 100, 3),
('Santan', 200, 3),
('Bougainvillea', 300, 3),
('Yellow Bells', 250, 3),
('Golden Pothos', 110, 4),
('English Ivy', 220, 4),
('String of Hearts', 310, 4),
('Blue Pea Vine', 170, 4),
('Philodendron Hearleaf', 230, 5),
('Jasmine', 250, 5),
('Philippine Flame vine', 340, 5),
('Passion Flower', 400, 5);

INSERT INTO stock(prod_id, quantity)VALUES
(1, 100),
(2, 100),
(3, 100),
(4, 100),
(5, 100),
(6, 100),
(7, 100),
(8, 100),
(9, 100),
(10, 100),
(11, 100),
(12, 100),
(13, 100),
(14, 100),
(15, 100),
(16, 100),
(17, 100),
(18, 100),
(19, 100),
(20, 100);