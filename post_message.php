
<?php
require_once __DIR__ . '/../db.php';
cors();
$pdo = pdo_conn();
$in = json_input();

$ticketId = (int)($in['ticketId'] ?? 0);
$body = trim($in['body'] ?? '');
$sender = trim($in['sender'] ?? 'client');
if (!in_array($sender, ['client','admin','system'])) $sender = 'client';

if (!$ticketId || !$body) {
    json_out(['ok'=>false,'error'=>'missing_fields'], 400);
}

$chk = $pdo->prepare("SELECT id FROM consultation_requests WHERE id=?");
$chk->execute([$ticketId]);
if (!$chk->fetch()) json_out(['ok'=>false,'error'=>'ticket_not_found'], 404);

$stmt = $pdo->prepare("INSERT INTO messages (ticket_id, sender, body) VALUES (?, ?, ?)");
$stmt->execute([$ticketId, $sender, $body]);

json_out(['ok'=>true]);
