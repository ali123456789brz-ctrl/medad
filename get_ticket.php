
<?php
require_once __DIR__ . '/../db.php';
cors();
$pdo = pdo_conn();

$id = (int)($_GET['id'] ?? 0);
if (!$id) json_out(['ok'=>false,'error'=>'missing_id'], 400);

$stmt = $pdo->prepare("SELECT * FROM consultation_requests WHERE id=?");
$stmt->execute([$id]);
$ticket = $stmt->fetch();
if (!$ticket) json_out(['ok'=>false,'error'=>'not_found'], 404);

$msgs = $pdo->prepare("SELECT id, sender, body, created_at FROM messages WHERE ticket_id=? ORDER BY id ASC");
$msgs->execute([$id]);
$messages = $msgs->fetchAll();

json_out(['ok'=>true, 'ticket'=>$ticket, 'messages'=>$messages]);
