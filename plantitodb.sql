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
    price decimal(9,2),
    definition TEXT,
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
    stat_id INT,
    ship_id INT,
    INDEX(user_id),
    CONSTRAINT orderinfo_user_id_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
);

CREATE TABLE shipping (
    ship_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    ship_name varchar(32),
    ship_price decimal(9,2)
);

CREATE TABLE orderstatus(
    stat_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    stat_name varchar(32)
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
    prod_id INT NOT NULL,
    rev_num INT,
    rev_msg TEXT,
    CONSTRAINT review_user_id_fk FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE,
    CONSTRAINT review_prod_id_fk FOREIGN KEY (prod_id) REFERENCES product(prod_id) ON DELETE CASCADE
);

CREATE VIEW order_transaction_details AS
SELECT 
    oi.orderinfo_id,
    oi.date_placed,
    oi.date_shipped,
    CONCAT(u.fname, ' ', u.lname) AS customer_name,
    u.email AS customer_email,
    u.addressline AS shipping_address,
    os.stat_name AS order_status,
    p.prod_id AS product_id,
    p.description AS product_name,
    p.price AS unit_price,
    ol.quantity AS quantity_ordered,
    (p.price * ol.quantity) AS total_price,
    s.ship_price AS shipping_fee,
    ((p.price * ol.quantity) + s.ship_price) AS grand_total
FROM 
    orderinfo oi
JOIN 
    user u ON oi.user_id = u.user_id
JOIN 
    orderstatus os ON oi.stat_id = os.stat_id
JOIN 
    orderline ol ON oi.orderinfo_id = ol.orderinfo_id
JOIN 
    product p ON ol.prod_id = p.prod_id
JOIN 
    shipping s ON s.ship_id = oi.ship_id;

DELIMITER $$
CREATE TRIGGER update_stock_after_order
AFTER INSERT ON orderline
FOR EACH ROW
BEGIN
    DECLARE current_stock INT;

    -- Get the current stock for the product
    SELECT quantity INTO current_stock
    FROM stock
    WHERE prod_id = NEW.prod_id;

    -- Update the stock by subtracting the quantity ordered
    UPDATE stock
    SET quantity = current_stock - NEW.quantity
    WHERE prod_id = NEW.prod_id;
END $$
DELIMITER ;

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

INSERT INTO orderstatus(stat_name)VALUES
('Ongoing'),
('Delivered'),
('Cancelled');

INSERT INTO shipping(ship_name, ship_price)VALUES
('Standard', 40),
('Fast Delivery', 120);


INSERT INTO user(email, password, lname, fname, addressline, phone, pfp_path, role_id)VALUES
('marbellasj@gmail.com', '472abab1382a164fe6f0212122bbb12a215457b8', 'Marbella', 'Sharwin', 'Taguig', '09935312681', 'images/default-avatar-icon.jpg', 1),
('lacortekc@gmail.com', 'f64f01582774b4b4c5eb970d1585d830903c52a5', 'Lacorte', 'Krsmur', 'Taguig', '09937564575', 'images/maki.jpg', 1),
('pequemes@gmail.com', 'c066b73b07369d10887d9570b8278851a28c6164', 'Peque', 'Erica', 'Taguig', '09063354124', 'images/default-avatar-icon.jpg', 2),
('labilabimk@gmail.com', 'c1e93b1f026abeac0e5a356535bfc2e3727c1045', 'Labi-labi', 'Maria', 'Taguig', '09557966852', 'images/default-avatar-icon.jpg', 2),
('elediak@gmail.com', 'e83d6fd53b9d1176232f5131b5b81c035b144b26', 'Eledia', 'Kimberly', 'Taguig', '09125474775', 'images/default-avatar-icon.jpg', 2),
('calungsodmp@gmail.com', 'adc8347c5f07d03e9d9dee22b8888aff349d5af7', 'Calungsod', 'Mary', 'Taguig', '09334567445', 'images/default-avatar-icon.jpg', 2);

INSERT INTO product(description, price, definition, cat_id)VALUES
('Watering Can', 100, 'A lightweight, durable can with a long spout for watering plants, allowing for precise water flow without disturbing the soil.', 1),
('Trowel', 130, 'A small, handheld shovel for digging, transplanting, or loosening soil in pots or gardens.', 1),
('Garden Rake', 170, 'Sturdier with metal tines, perfect for breaking up soil and leveling.', 1),
('Loam Soil', 150, 'Nutrient-rich soil ideal for outdoor gardens and general planting. Often pre-mixed with compost for better growth.', 1),
('Basil', 100, 'A fragrant herb commonly used in Italian dishes, especially in pesto, pastas, and salads.', 2),
('Parsley', 120, 'A versatile herb often used as a garnish or ingredient in soups, salads, and sauces.', 2),
('Rosemary', 200, 'Perfect for seasoning meat, potatoes, and bread.', 2),
('Thyme', 150, 'A fragrant herb ideal for soups, roasts, and stews.', 2),
('Gumamela', 100, 'A tropical favorite with large, showy flowers, available in a wide range of colors.', 3),
('Santan', 200, 'A colorful shrub with red, yellow, or orange flowers, ideal for hedges or as ornamental plants.', 3),
('Bougainvillea', 300, 'Known for its vibrant bracts in various colors, it’s perfect for fences or walls.', 3),
('Yellow Bells', 250, 'A bright yellow flowering shrub that thrives in sunny conditions.', 3),
('Golden Pothos', 110, 'A popular low-maintenance plant, great for beginners.', 4),
('English Ivy', 220, 'Classic choice for both indoor and outdoor vertical greenery.', 4),
('String of Hearts', 310, 'Known for its delicate, heart-shaped leaves and trailing habit.', 4),
('Blue Pea Vine', 170, 'A fast-growing climber with stunning blue flowers, often used in teas.', 4),
('Philodendron Heartleaf', 230, ' A classic climbing plant with vibrant, heart-shaped green leaves that thrive in low to medium light.', 5),
('Jasmine', 250, 'Known for its fragrant white blooms, jasmine is a fast-growing climber perfect for pergolas and trellises, adding an aromatic ambiance to gardens.', 5),
('Philippine Flame Vine', 340, 'A stunning ornamental climber with bright orange, trumpet-shaped flowers, often used to create dramatic vertical gardens.', 5),
('Passion Flower', 400, 'An exotic climber with intricate, eye-catching flowers in various colors, often used as ornamental vines. Some varieties produce edible passion fruits.', 5);

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

INSERT INTO image(prod_id, img_path)VALUES
(1, 'images/Miscellaneous/wateringcan1.png'),
(1, 'images/Miscellaneous/wateringcan2.png'),
(1, 'images/Miscellaneous/wateringcan3.png'),
(1, 'images/Miscellaneous/wateringcan4.png'),
(1, 'images/Miscellaneous/wateringcan5.png'),
(2, 'images/Miscellaneous/trowel1.png'),
(2, 'images/Miscellaneous/trowel2.png'),
(2, 'images/Miscellaneous/trowel3.png'),
(2, 'images/Miscellaneous/trowel4.png'),
(2, 'images/Miscellaneous/trowel5.png'),
(3, 'images/Miscellaneous/rake1.png'),
(3, 'images/Miscellaneous/rake2.png'),
(3, 'images/Miscellaneous/rake3.png'),
(3, 'images/Miscellaneous/rake4.png'),
(3, 'images/Miscellaneous/rake5.png'),
(4, 'images/Miscellaneous/soil1.png'),
(4, 'images/Miscellaneous/soil2.png'),
(4, 'images/Miscellaneous/soil3.png'),
(4, 'images/Miscellaneous/soil4.png'),
(4, 'images/Miscellaneous/soil5.png'),
(5, 'images/Herbs/basil1.png'),
(5, 'images/Herbs/basil2.png'),
(5, 'images/Herbs/basil3.png'),
(5, 'images/Herbs/basil4.png'),
(5, 'images/Herbs/basil5.png'),
(6, 'images/Herbs/parsley1.png'),
(6, 'images/Herbs/parsley2.png'),
(6, 'images/Herbs/parsley3.png'),
(6, 'images/Herbs/parsley4.png'),
(6, 'images/Herbs/parsley5.png'),
(7, 'images/Herbs/rosemary1.png'),
(7, 'images/Herbs/rosemary2.png'),
(7, 'images/Herbs/rosemary3.png'),
(7, 'images/Herbs/rosemary4.png'),
(7, 'images/Herbs/rosemary5.png'),
(8, 'images/Herbs/thyme1.png'),
(8, 'images/Herbs/thyme2.png'),
(8, 'images/Herbs/thyme3.png'),
(8, 'images/Herbs/thyme4.png'),
(8, 'images/Herbs/thyme5.png'),
(9, 'images/Shrubs/gumamela1.png'),
(9, 'images/Shrubs/gumamela2.png'),
(9, 'images/Shrubs/gumamela3.png'),
(9, 'images/Shrubs/gumamela4.png'),
(9, 'images/Shrubs/gumamela5.png'),
(10, 'images/Shrubs/santan1.png'),
(10, 'images/Shrubs/santan2.png'),
(10, 'images/Shrubs/santan3.png'),
(10, 'images/Shrubs/santan4.png'),
(10, 'images/Shrubs/santan5.png'),
(11, 'images/Shrubs/bougainvillea1.png'),
(11, 'images/Shrubs/bougainvillea2.png'),
(11, 'images/Shrubs/bougainvillea3.png'),
(11, 'images/Shrubs/bougainvillea4.png'),
(11, 'images/Shrubs/bougainvillea5.png'),
(12, 'images/Shrubs/yellowbells1.png'),
(12, 'images/Shrubs/yellowbells2.png'),
(12, 'images/Shrubs/yellowbells3.png'),
(12, 'images/Shrubs/yellowbells4.png'),
(12, 'images/Shrubs/yellowbells5.png'),
(13, 'images/Creepers/golden1.png'),
(13, 'images/Creepers/golden2.png'),
(13, 'images/Creepers/golden3.png'),
(13, 'images/Creepers/golden4.png'),
(13, 'images/Creepers/golden5.png'),
(14, 'images/Creepers/english1.png'),
(14, 'images/Creepers/english2.png'),
(14, 'images/Creepers/english3.png'),
(14, 'images/Creepers/english4.png'),
(14, 'images/Creepers/english5.png'),
(15, 'images/Creepers/string1.png'),
(15, 'images/Creepers/string2.png'),
(15, 'images/Creepers/string3.png'),
(15, 'images/Creepers/string4.png'),
(15, 'images/Creepers/string5.png'),
(16, 'images/Creepers/blue1.png'),
(16, 'images/Creepers/blue2.png'),
(16, 'images/Creepers/blue3.png'),
(16, 'images/Creepers/blue4.png'),
(16, 'images/Creepers/blue5.png'),
(17, 'images/Climbers/heartleaf1.png'),
(17, 'images/Climbers/heartleaf2.png'),
(17, 'images/Climbers/heartleaf3.png'),
(17, 'images/Climbers/heartleaf4.png'),
(17, 'images/Climbers/heartleaf5.png'),
(18, 'images/Climbers/jasmine1.png'),
(18, 'images/Climbers/jasmine2.png'),
(18, 'images/Climbers/jasmine3.png'),
(18, 'images/Climbers/jasmine4.png'),
(18, 'images/Climbers/jasmine5.png'),
(19, 'images/Climbers/flame1.png'),
(19, 'images/Climbers/flame2.png'),
(19, 'images/Climbers/flame3.png'),
(19, 'images/Climbers/flame4.png'),
(19, 'images/Climbers/flame5.png'),
(20, 'images/Climbers/passion1.png'),
(20, 'images/Climbers/passion2.png'),
(20, 'images/Climbers/passion3.png'),
(20, 'images/Climbers/passion4.png'),
(20, 'images/Climbers/passion5.png');