<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/db.php';
require_once __DIR__.'/../inc/helpers.php';

$pdo = db();
$action = $_GET['action'] ?? '';

if ($action === 'create') {
  $user = require_login();
  $in = read_json();

  $payment = (string)($in['payment_method'] ?? 'transfer');
  $shipping = $in['shipping'] ?? [];
  $buyNow = $in['buy_now'] ?? null;

  // optional: selected cart item ids
  // JS kirim: selected_cart_ids: ['c1', 'c2'] atau [1,2] tergantung backend
  // Di DB kamu cart_items tidak pakai id (di SELECT kamu cuma product_id, qty).
  // Jadi kita pakai product_id sebagai "selector" yang aman.
  // Maka: selected_cart_ids sebaiknya berisi product_id cart (bukan id row).
  // Tapi karena app.js kamu kirim it.id dari API cart (biasanya itu row id),
  // kita tetap support dua mode:
  // 1) selected_cart_ids dianggap row id (kalau tabel cart_items punya kolom id)
  // 2) kalau tidak ada kolom id, fallback: treat as product_id.
  $selectedIds = $in['selected_cart_ids'] ?? [];
  if (!is_array($selectedIds)) $selectedIds = [];

  // normalize selectedIds -> array of string/int
  $selectedIds = array_values(array_filter($selectedIds, function($v){
    return $v !== null && $v !== '';
  }));

  // collect items: buy_now OR cart
  $items = [];

  if ($buyNow) {
    $pid = (string)($buyNow['product_id'] ?? '');
    $qty = (int)($buyNow['quantity'] ?? 1);
    if ($pid === '') json_out(['message'=>'Produk tidak valid'], 400);
    if ($qty < 1) $qty = 1;

    $st = $pdo->prepare("SELECT id,name,price FROM products WHERE id=?");
    $st->execute([$pid]);
    $p = $st->fetch(PDO::FETCH_ASSOC);
    if (!$p) json_out(['message'=>'Produk tidak ditemukan'], 400);

    $items = [[
      'product_id'=>$p['id'],
      'name'=>$p['name'],
      'price'=>(int)$p['price'],
      'qty'=>$qty,
      'line_total'=>(int)$p['price']*$qty
    ]];

  } else {
    // ========== CART MODE ==========
    // Kita perlu ambil item cart. Kalau selected_cart_ids ada, ambil yang dipilih saja.
    // Masalah: query lama kamu tidak select c.id, berarti kemungkinan tabel cart_items
    // ga punya kolom id atau API cart list tidak expose.
    //
    // Solusi robust:
    // 1) Coba mode "row id": cek apakah kolom id ada dengan PRAGMA table_info.
    // 2) Kalau ada: filter pakai c.id IN (...)
    // 3) Kalau tidak ada: filter pakai c.product_id IN (...)
    //
    // Note: Ini bikin backend tetap jalan di dua struktur DB.

    // cek kolom "id" ada di cart_items atau tidak
    $hasRowId = false;
    try {
      $cols = $pdo->query("PRAGMA table_info(cart_items)")->fetchAll(PDO::FETCH_ASSOC);
      foreach ($cols as $c) {
        if (isset($c['name']) && $c['name'] === 'id') { $hasRowId = true; break; }
      }
    } catch (Throwable $e) {
      // jika DB bukan sqlite / pragma gagal, fallback anggap ada kolom id = false
      $hasRowId = false;
    }

    $sqlBase = "SELECT c.product_id, c.qty, p.name, p.price
                FROM cart_items c JOIN products p ON p.id=c.product_id
                WHERE c.user_id=?";

    $params = [$user['id']];

    if (!empty($selectedIds)) {
      // filter sesuai struktur
      if ($hasRowId) {
        // selected dianggap row id
        $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
        $sqlBase .= " AND c.id IN ($placeholders)";
        $params = array_merge($params, $selectedIds);
      } else {
        // fallback: selected dianggap product_id
        $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
        $sqlBase .= " AND c.product_id IN ($placeholders)";
        $params = array_merge($params, $selectedIds);
      }
    }

    $st = $pdo->prepare($sqlBase);
    $st->execute($params);
    $rows = $st->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
      // kalau selectedIds ada tapi hasil kosong -> berarti user belum pilih item yang valid
      if (!empty($selectedIds)) json_out(['message'=>'Item yang dipilih tidak ada di keranjang'], 400);
      json_out(['message'=>'Keranjang kosong'], 400);
    }

    $items = array_map(function($r){
      $price = (int)$r['price'];
      $qty = (int)$r['qty'];
      return [
        'product_id'=>$r['product_id'],
        'name'=>$r['name'],
        'price'=>$price,
        'qty'=>$qty,
        'line_total'=>$price*$qty
      ];
    }, $rows);
  }

  $subtotal = array_sum(array_map(fn($x)=> (int)$x['line_total'], $items));
  $shipCost = (int)($shipping['cost'] ?? 0);
  $total = $subtotal + $shipCost;

  $orderId = uid('o');
  $orderNo = 'NK-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(2)));

  $pdo->beginTransaction();
  try {
    $pdo->prepare("INSERT INTO orders(id,user_id,order_no,status,total,payment_method,shipping_json,created_at)
                   VALUES(?,?,?,?,?,?,?,?)")
      ->execute([$orderId, $user['id'], $orderNo, 'PROCESSING', $total, $payment, json_encode($shipping, JSON_UNESCAPED_UNICODE), date('c')]);

    $ins = $pdo->prepare("INSERT INTO order_items(order_id,product_id,name,price,qty,line_total) VALUES(?,?,?,?,?,?)");
    foreach ($items as $it) {
      $ins->execute([$orderId, $it['product_id'], $it['name'], $it['price'], $it['qty'], $it['line_total']]);
    }

    // hapus dari cart kalau mode cart
    if (!$buyNow) {
      // kalau selectedIds ada -> hapus yang selected saja
      if (!empty($selectedIds)) {
        // cek lagi kolom id
        $hasRowId = false;
        try {
          $cols = $pdo->query("PRAGMA table_info(cart_items)")->fetchAll(PDO::FETCH_ASSOC);
          foreach ($cols as $c) {
            if (isset($c['name']) && $c['name'] === 'id') { $hasRowId = true; break; }
          }
        } catch (Throwable $e) { $hasRowId = false; }

        $placeholders = implode(',', array_fill(0, count($selectedIds), '?'));
        if ($hasRowId) {
          $sqlDel = "DELETE FROM cart_items WHERE user_id=? AND id IN ($placeholders)";
        } else {
          $sqlDel = "DELETE FROM cart_items WHERE user_id=? AND product_id IN ($placeholders)";
        }
        $pdo->prepare($sqlDel)->execute(array_merge([$user['id']], $selectedIds));
      } else {
        // fallback: hapus semua (behaviour lama)
        $pdo->prepare("DELETE FROM cart_items WHERE user_id=?")->execute([$user['id']]);
      }
    }

    $pdo->commit();
  } catch (Throwable $e) {
    $pdo->rollBack();
    json_out(['message'=>'Gagal membuat pesanan'], 500);
  }

  json_out(['ok'=>true, 'order_id'=>$orderId]);
}

if ($action === 'list_mine') {
  $user = require_login();

  $st = $pdo->prepare("SELECT o.*, u.name buyer_name, u.email buyer_email
                       FROM orders o JOIN users u ON u.id=o.user_id
                       WHERE o.user_id=? ORDER BY o.created_at DESC");
  $st->execute([$user['id']]);
  $orders = $st->fetchAll(PDO::FETCH_ASSOC);

  $out = [];
  foreach ($orders as $o) {
    $items = $pdo->prepare("SELECT name, price, qty, line_total FROM order_items WHERE order_id=?");
    $items->execute([$o['id']]);
    $o['items'] = $items->fetchAll(PDO::FETCH_ASSOC);
    $o['shipping'] = json_decode($o['shipping_json'] ?: '{}', true);
    $out[] = [
      'id'=>$o['id'],
      'order_no'=>$o['order_no'],
      'status'=>$o['status'],
      'total'=>(int)$o['total'],
      'payment_method'=>$o['payment_method'],
      'created_at'=>$o['created_at'],
      'shipping'=>$o['shipping'],
      'items'=>$o['items']
    ];
  }
  json_out(['orders'=>$out]);
}

if ($action === 'list_all') {
  $admin = require_admin();

  $st = $pdo->query("SELECT o.*, u.name buyer_name, u.email buyer_email
                     FROM orders o JOIN users u ON u.id=o.user_id
                     ORDER BY o.created_at DESC");
  $orders = $st->fetchAll(PDO::FETCH_ASSOC);

  $out = [];
  foreach ($orders as $o) {
    $items = $pdo->prepare("SELECT name, price, qty, line_total FROM order_items WHERE order_id=?");
    $items->execute([$o['id']]);
    $o['items'] = $items->fetchAll(PDO::FETCH_ASSOC);
    $o['shipping'] = json_decode($o['shipping_json'] ?: '{}', true);
    $out[] = [
      'id'=>$o['id'],
      'order_no'=>$o['order_no'],
      'status'=>$o['status'],
      'total'=>(int)$o['total'],
      'payment_method'=>$o['payment_method'],
      'created_at'=>$o['created_at'],
      'buyer_name'=>$o['buyer_name'],
      'buyer_email'=>$o['buyer_email'],
      'shipping'=>$o['shipping'],
      'items'=>$o['items']
    ];
  }
  json_out(['orders'=>$out]);
}

if ($action === 'admin_update_status') {
  $admin = require_admin();
  $in = read_json();
  $id = (string)($in['id'] ?? '');
  $status = strtoupper((string)($in['status'] ?? 'PROCESSING'));
  if ($id==='') json_out(['message'=>'Invalid'], 400);

  $pdo->prepare("UPDATE orders SET status=? WHERE id=?")->execute([$status, $id]);
  json_out(['ok'=>true]);
}

json_out(['message'=>'Unknown action'], 400);
