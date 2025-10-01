
<?php
require_once __DIR__ . '/../db.php';
cors();
$pdo = pdo_conn();
$in = json_input();

$name = trim($in['name'] ?? '');
$phone = trim($in['phone'] ?? '');
$case_type = trim($in['case_type'] ?? '');
$description = trim($in['description'] ?? '');

if (!$name || !$phone || !$case_type || !$description) {
    json_out(['ok'=>false,'error'=>'missing_fields'], 400);
}

$stmt = $pdo->prepare("INSERT INTO consultation_requests (name, phone, case_type, description) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $phone, $case_type, $description]);
$id = (int)$pdo->lastInsertId();

json_out(['ok'=>true, 'id'=>$id]);
