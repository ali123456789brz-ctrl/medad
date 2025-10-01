
<?php
require_once __DIR__ . '/db.php';
cors();
$pdo = pdo_conn();

$username = $_GET['u'] ?? 'admin';
$password = $_GET['p'] ?? 'admin123';


$chk = $pdo->prepare("SELECT id FROM admin_users WHERE username=?");
$chk->execute([$username]);
if ($chk->fetch()) {
    echo "Already exists";
    exit;
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$ins = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
$ins->execute([$username, $hash]);
echo "OK - admin created: $username";
