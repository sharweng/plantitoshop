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
('user');

INSERT INTO category(description)VALUES
('None'),
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