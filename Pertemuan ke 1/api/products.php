<?php
declare(strict_types=1);
require_once __DIR__.'/../inc/db.php';
require_once __DIR__.'/../inc/helpers.php';

$pdo = db();
$st = $pdo->query("SELECT * FROM products");
json_out(['products'=>$st->fetchAll(PDO::FETCH_ASSOC)]);
