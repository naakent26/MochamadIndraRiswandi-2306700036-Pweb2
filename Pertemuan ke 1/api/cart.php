<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/db.php';
require_once __DIR__.'/../inc/helpers.php';

$pdo = db();
$action = $_GET['action'] ?? '';
$user = require_login();

if ($action === 'list') {
  $st = $pdo->prepare("SELECT id, product_id, qty FROM cart_items WHERE user_id=? ORDER BY created_at DESC");
  $st->execute([$user['id']]);
  json_out(['items'=>$st->fetchAll(PDO::FETCH_ASSOC)]);
}

if ($action === 'count') {
  $st = $pdo->prepare("SELECT COALESCE(SUM(qty),0) FROM cart_items WHERE user_id=?");
  $st->execute([$user['id']]);
  json_out(['count'=>(int)$st->fetchColumn()]);
}

if ($action === 'add') {
  $in = read_json();
  $pid = (string)($in['product_id'] ?? '');
  $qty = (int)($in['qty'] ?? 1);
  if ($pid==='' || $qty<=0) json_out(['message'=>'Invalid'], 400);

  // if already exists -> update qty
  $st = $pdo->prepare("SELECT id, qty FROM cart_items WHERE user_id=? AND product_id=?");
  $st->execute([$user['id'], $pid]);
  $row = $st->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $pdo->prepare("UPDATE cart_items SET qty=? WHERE id=?")->execute([(int)$row['qty'] + $qty, $row['id']]);
  } else {
    $id = uid('c');
    $pdo->prepare("INSERT INTO cart_items(id,user_id,product_id,qty,created_at) VALUES(?,?,?,?,?)")
      ->execute([$id, $user['id'], $pid, $qty, date('c')]);
  }
  json_out(['ok'=>true]);
}

if ($action === 'update') {
  $in = read_json();
  $id = (string)($in['id'] ?? '');
  $delta = (int)($in['delta'] ?? 0);
  if ($id==='' || $delta===0) json_out(['message'=>'Invalid'], 400);

  $st = $pdo->prepare("SELECT qty FROM cart_items WHERE id=? AND user_id=?");
  $st->execute([$id, $user['id']]);
  $qty = (int)$st->fetchColumn();
  if ($qty<=0) json_out(['message'=>'Item tidak ditemukan'], 404);

  $newQty = $qty + $delta;
  if ($newQty <= 0) {
    $pdo->prepare("DELETE FROM cart_items WHERE id=? AND user_id=?")->execute([$id, $user['id']]);
  } else {
    $pdo->prepare("UPDATE cart_items SET qty=? WHERE id=? AND user_id=?")->execute([$newQty, $id, $user['id']]);
  }
  json_out(['ok'=>true]);
}

if ($action === 'remove') {
  $in = read_json();
  $id = (string)($in['id'] ?? '');
  $pdo->prepare("DELETE FROM cart_items WHERE id=? AND user_id=?")->execute([$id, $user['id']]);
  json_out(['ok'=>true]);
}

json_out(['message'=>'Unknown action'], 400);
