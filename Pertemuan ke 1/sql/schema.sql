CREATE DATABASE IF NOT EXISTS naakent_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE naakent_store;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  brand VARCHAR(40) NOT NULL,
  name VARCHAR(160) NOT NULL,
  price BIGINT NOT NULL,
  stock INT NOT NULL DEFAULT 0,
  description TEXT,
  image_url TEXT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cart_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  created_at DATETIME NOT NULL,
  UNIQUE KEY uniq_user_product (user_id, product_id),
  CONSTRAINT fk_cart_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_cart_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_code VARCHAR(64) NOT NULL UNIQUE,
  user_id INT NOT NULL,
  status ENUM('diproses','dikirim','selesai') NOT NULL DEFAULT 'diproses',
  subtotal BIGINT NOT NULL,
  shipping_cost BIGINT NOT NULL,
  total BIGINT NOT NULL,
  payment_method VARCHAR(40) NOT NULL,
  shipping_method VARCHAR(40) NOT NULL,
  ship_name VARCHAR(120) NOT NULL,
  ship_phone VARCHAR(40) NOT NULL,
  ship_address TEXT NOT NULL,
  ship_city VARCHAR(80) NOT NULL,
  ship_zip VARCHAR(20) NOT NULL,
  created_at DATETIME NOT NULL,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price BIGINT NOT NULL,
  CONSTRAINT fk_oi_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  CONSTRAINT fk_oi_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
) ENGINE=InnoDB;
