<?php
declare(strict_types=1);

function json_out($arr, int $code=200): void {
  http_response_code($code);
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($arr, JSON_UNESCAPED_UNICODE);
  exit;
}

function read_json(): array {
  $raw = file_get_contents('php://input');
  $data = json_decode($raw ?: '[]', true);
  return is_array($data) ? $data : [];
}

function require_login(): array {
  session_start();
  if (!isset($_SESSION['user'])) json_out(['message'=>'Unauthorized'], 401);
  return $_SESSION['user'];
}

function require_admin(): array {
  $u = require_login();
  if (empty($u['is_admin'])) json_out(['message'=>'Forbidden'], 403);
  return $u;
}
