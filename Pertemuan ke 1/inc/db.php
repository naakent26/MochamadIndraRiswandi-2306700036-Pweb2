<?php
declare(strict_types=1);

require_once __DIR__.'/config.php';

function db(): PDO {
  static $pdo = null;
  if ($pdo) return $pdo;

  if (!is_dir(dirname(DB_PATH))) @mkdir(dirname(DB_PATH), 0777, true);

  $pdo = new PDO('sqlite:' . DB_PATH);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // migrate
  $pdo->exec("
    CREATE TABLE IF NOT EXISTS users(
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      name TEXT NOT NULL,
      email TEXT NOT NULL UNIQUE,
      password_hash TEXT NOT NULL,
      is_admin INTEGER NOT NULL DEFAULT 0,
      created_at TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS products(
      id TEXT PRIMARY KEY,
      brand TEXT NOT NULL,
      name TEXT NOT NULL,
      price INTEGER NOT NULL,
      stock INTEGER NOT NULL,
      description TEXT NOT NULL,
      image TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS cart_items(
      id TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL,
      product_id TEXT NOT NULL,
      qty INTEGER NOT NULL,
      created_at TEXT NOT NULL,
      FOREIGN KEY(user_id) REFERENCES users(id),
      FOREIGN KEY(product_id) REFERENCES products(id)
    );

    CREATE TABLE IF NOT EXISTS orders(
      id TEXT PRIMARY KEY,
      user_id INTEGER NOT NULL,
      order_no TEXT NOT NULL,
      status TEXT NOT NULL,
      total INTEGER NOT NULL,
      payment_method TEXT NOT NULL,
      shipping_json TEXT NOT NULL,
      created_at TEXT NOT NULL,
      FOREIGN KEY(user_id) REFERENCES users(id)
    );

    CREATE TABLE IF NOT EXISTS order_items(
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      order_id TEXT NOT NULL,
      product_id TEXT NOT NULL,
      name TEXT NOT NULL,
      price INTEGER NOT NULL,
      qty INTEGER NOT NULL,
      line_total INTEGER NOT NULL,
      FOREIGN KEY(order_id) REFERENCES orders(id)
    );
  ");

  // seed admin if missing
  $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email=?");
  $stmt->execute(['admin@naakent.com']);
  if ((int)$stmt->fetchColumn() === 0) {
    $pdo->prepare("INSERT INTO users(name,email,password_hash,is_admin,created_at) VALUES(?,?,?,?,?)")
      ->execute(['Admin', 'admin@naakent.com', password_hash('admin123', PASSWORD_DEFAULT), 1, date('c')]);
  }

  // ✅ seed 15 products if empty
  $countP = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
  if ($countP === 0) {
    $insP = $pdo->prepare("INSERT OR IGNORE INTO products(id,brand,name,price,stock,description,image)
                           VALUES(?,?,?,?,?,?,?)");

    $seed = [
      ['ip1','iphone','iPhone 15 Pro Max',24999000,8,'Chip A17 Pro, Titanium Design, 48MP Camera, USB-C 3.0, Dynamic Island, IP68 Rating, 120Hz Display','https://images.unsplash.com/photo-1592286927505-1fed6c6d03d5?w=400&h=400&fit=crop'],
      ['ip2','iphone','iPhone 15',16999000,12,'Chip A16 Bionic, Dynamic Island, 48MP Main Camera, USB-C, Night Mode Ultra','https://images.unsplash.com/photo-1511275539165-cc3641b266b6?w=400&h=400&fit=crop'],
      ['ip3','iphone','iPhone 14 Pro',18999000,5,'Chip A16 Bionic, Always-On Display, ProMotion 120Hz, Pro Camera System','https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=400&fit=crop'],
      ['ip4','iphone','iPhone 14',14999000,15,'Chip A15 Bionic, Crash Detection, Action Mode, Ceramic Shield','https://images.unsplash.com/photo-1559056199-641a0ac8b3f4?w=400&h=400&fit=crop'],
      ['ip5','iphone','iPhone SE 2022',8999000,20,'Chip A15 Bionic, Touch ID, 4.7" Retina Display, Wireless Charging','https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=400&fit=crop'],

      ['ss1','samsung','Samsung S24 Ultra',23999000,6,'Snapdragon 8 Gen 3, S Pen Integrated, 200MP Camera, AI Zoom 100x, IP68','https://images.unsplash.com/photo-1512941691920-25bda36fb6fd?w=400&h=400&fit=crop'],
      ['ss2','samsung','Samsung S24+',17999000,10,'Snapdragon 8 Gen 3, 50MP Camera, 120Hz AMOLED, AI Features','https://images.unsplash.com/photo-1511275539165-cc3641b266b6?w=400&h=400&fit=crop'],
      ['ss3','samsung','Samsung S24',14999000,14,'Snapdragon 8 Gen 3, Compact Design, Galaxy AI, Night Photography','https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=400&fit=crop'],
      ['ss4','samsung','Samsung Z Fold 5',28999000,3,'Foldable 7.6" AMOLED, Flex Mode, Lightweight Titanium, IPX8','https://images.unsplash.com/photo-1559056199-641a0ac8b3f4?w=400&h=400&fit=crop'],
      ['ss5','samsung','Samsung A54 5G',6499000,25,'Exynos 1380, 50MP OIS Camera, Super AMOLED 120Hz, IP67','https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=400&fit=crop'],

      ['xm1','xiaomi','Xiaomi 14 Ultra',18999000,7,'Snapdragon 8 Gen 3, Leica Camera System, 90W HyperCharge, IP68','https://images.unsplash.com/photo-1592286927505-1fed6c6d03d5?w=400&h=400&fit=crop'],
      ['xm2','xiaomi','Xiaomi 14',12999000,11,'Snapdragon 8 Gen 3, Leica Lens, 120W Charging, Camera Master Mode','https://images.unsplash.com/photo-1511275539165-cc3641b266b6?w=400&h=400&fit=crop'],
      ['xm3','xiaomi','Poco F6 Pro',7999000,18,'Snapdragon 8 Gen 2, 120W Charging, LiquidCool 4.0, Gorilla Glass','https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=400&fit=crop'],
      ['xm4','xiaomi','Redmi Note 13 Pro+',5499000,30,'200MP Camera with OIS, 120W Charging, AMOLED 120Hz, IP54','https://images.unsplash.com/photo-1559056199-641a0ac8b3f4?w=400&h=400&fit=crop'],
      ['xm5','xiaomi','Redmi Note 13',3299000,35,'Snapdragon 685, 108MP Camera, 33W Fast Charging, Budget Friendly','https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=400&fit=crop']
    ];

    foreach ($seed as $p) $insP->execute($p);
  }

  return $pdo;
}

function uid(string $prefix='id'): string {
  return $prefix . '_' . time() . '_' . bin2hex(random_bytes(4));
}
