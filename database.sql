-- QuickBuy database
CREATE DATABASE IF NOT EXISTS quickbuy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE quickbuy;

-- 1. Registered users
CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Products shown on Home / Products / Search
CREATE TABLE products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(150) NOT NULL,
  description TEXT,
  price       DECIMAL(10,2) NOT NULL,
  image_url   VARCHAR(500) NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. Orders (one row per checkout)
CREATE TABLE orders (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  subtotal   DECIMAL(10,2) NOT NULL,
  shipping   DECIMAL(10,2) NOT NULL,
  total      DECIMAL(10,2) NOT NULL,
  address    TEXT NOT NULL,
  status     VARCHAR(30) NOT NULL DEFAULT 'Pending',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 4. Items inside each order
CREATE TABLE order_items (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  order_id     INT NOT NULL,
  product_id   INT NULL,
  product_name VARCHAR(150) NOT NULL,
  price        DECIMAL(10,2) NOT NULL,
  qty          INT NOT NULL,
  FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- 5. Contact form messages
CREATE TABLE contact_messages (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL,
  message    TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Sample products (same as your HTML pages)
INSERT INTO products (name, description, price, image_url) VALUES
('Smart Watch', 'Smart fitness watch', 2999, 'https://media.istockphoto.com/photos/smartwatch-picture-id469328286?k=20&m=469328286&s=612x612&w=0&h=vtEyrm6aOlka7JtDOhOVbdP9eRVeoZbJ3HLP-Trmrl8='),
('Laptop', 'Powerful everyday laptop', 42999, 'https://i5.walmartimages.com/asr/69056846-b8a1-497a-b4f5-6172ab54b35d.a2102861007e11c433c127f2b63b038a.jpeg'),
('Sneakers', 'Comfortable running sneakers', 1999, 'https://tse3.mm.bing.net/th/id/OIP.LwtzYNJJIDa0QHfsHFi2jgHaE8?rs=1&pid=ImgDetMain&o=7&rm=3'),
('Headphones', 'Wireless headphones', 1499, 'https://tse2.mm.bing.net/th/id/OIP.-J7fq6u9Zm3dJwixcxOVTgHaG-?rs=1&pid=ImgDetMain&o=7&rm=3'),
('Camera', 'SLR camera', 24999, 'https://static.vecteezy.com/system/resources/previews/027/906/091/non_2x/professionalgrapher-captures-nature-sunset-with-old-slr-camera-generated-by-ai-free-photo.jpg'),
('Smartphone', 'Latest smartphone', 19999, 'https://tse2.mm.bing.net/th/id/OIP.A7FSJ4nqzClbC3FC8_87mQHaGW?rs=1&pid=ImgDetMain&o=7&rm=3');
