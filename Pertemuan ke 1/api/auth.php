<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/db.php';
require_once __DIR__.'/../inc/helpers.php';

session_start();
$pdo = db();
$action = $_GET['action'] ?? '';

if ($action === 'register') {
  $in = read_json();
  $name = trim((string)($in['name'] ?? ''));
  $email = strtolower(trim((string)($in['email'] ?? '')));
  $password = (string)($in['password'] ?? '');

  if ($name==='' || $email==='' || strlen($password) < 6) json_out(['message'=>'Input tidak valid'], 400);

  try {
    $pdo->prepare("INSERT INTO users(name,email,password_hash,is_admin,created_at) VALUES(?,?,?,?,?)")
      ->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), 0, date('c')]);
    json_out(['ok'=>true]);
  } catch (Throwable $e) {
    json_out(['message'=>'Email sudah terdaftar'], 400);
  }
}

if ($action === 'login') {
  $in = read_json();
  $email = strtolower(trim((string)($in['email'] ?? '')));
  $password = (string)($in['password'] ?? '');

  $st = $pdo->prepare("SELECT id,name,email,password_hash,is_admin FROM users WHERE email=?");
  $st->execute([$email]);
  $u = $st->fetch(PDO::FETCH_ASSOC);
  if (!$u || !password_verify($password, $u['password_hash'])) json_out(['message'=>'Email / password salah'], 400);

  $user = ['id'=>(int)$u['id'], 'name'=>$u['name'], 'email'=>$u['email'], 'is_admin'=>((int)$u['is_admin']===1)];
  $_SESSION['user'] = $user;
  json_out(['user'=>$user]);
}

if ($action === 'logout') {
  session_destroy();
  json_out(['ok'=>true]);
}

if ($action === 'me') {
  $user = $_SESSION['user'] ?? null;
  json_out(['user'=>$user]);
}

json_out(['message'=>'Unknown action'], 400);
