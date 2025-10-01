
<?php
require_once __DIR__ . '/../db.php';
cors();
$pdo = pdo_conn();
$in = json_input();

$admin = bearer_admin($pdo);
if (!$admin) json_out(['ok'=>false,'error'=>'unauthorized'], 401);

$ticketId = (int)($in['ticketId'] ?? 0);
if (!$ticketId) json_out(['ok'=>false,'error'=>'missing_id'], 400);

$upd = $pdo->prepare("UPDATE consultation_requests SET status='closed' WHERE id=?");
$upd->execute([$ticketId]);
json_out(['ok'=>true]);
