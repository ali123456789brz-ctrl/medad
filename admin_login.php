
<?php
require_once __DIR__ . '/../db.php';
cors();
$pdo = pdo_conn();
$in = json_input();

$username = trim($in['username'] ?? '');
$password = $in['password'] ?? '';

if (!$username || !$password) json_out(['ok'=>false,'error'=>'missing_fields'], 400);

$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username=?");
$stmt->execute([$username]);
$user = $stmt->fetch();
if (!$user || !password_verify($password, $user['password_hash'])) {
    json_out(['ok'=>false,'error'=>'invalid_credentials'], 401);
}

$token = bin2hex(random_bytes(24));
$exp = (new DateTime('+12 hours'))->format('Y-m-d H:i:s');
$ins = $pdo->prepare("INSERT INTO admin_tokens (admin_id, token, expires_at) VALUES (?, ?, ?)");
$ins->execute([$user['id'], $token, $exp]);

json_out(['ok'=>true, 'token'=>$token, 'expires_at'=>$exp]);
