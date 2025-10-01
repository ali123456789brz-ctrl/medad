
<?php
require_once __DIR__ . '/../db.php';
cors();
$pdo = pdo_conn();
$in = json_input();

$ticketId = (int)($in['ticketId'] ?? 0);
$phone = trim($in['phone'] ?? '');

if (!$ticketId || !$phone) {
    json_out(['ok'=>false,'error'=>'missing_fields'], 400);
}

$stmt = $pdo->prepare("SELECT * FROM consultation_requests WHERE id=? AND phone=?");
$stmt->execute([$ticketId, $phone]);
$ticket = $stmt->fetch();
if (!$ticket) json_out(['ok'=>false,'error'=>'not_found'], 404);

$msgs = $pdo->prepare("SELECT id, sender, body, created_at FROM messages WHERE ticket_id=? ORDER BY id ASC");
$msgs->execute([$ticketId]);
$messages = $msgs->fetchAll();

json_out(['ok'=>true, 'ticket'=>$ticket, 'messages'=>$messages]);
