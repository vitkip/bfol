<?php
define('WEBHOOK_SECRET', '7f3k9mP2xQnL8vRd4wEj');
define('PROJECT_PATH',   '/home/csfangko/bfol');
define('BRANCH',         'main');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); exit('Method Not Allowed');
}

$payload   = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$expected  = 'sha256=' . hash_hmac('sha256', $payload, WEBHOOK_SECRET);

if (!hash_equals($expected, $signature)) {
    http_response_code(403); exit('Forbidden');
}

$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';
if ($event !== 'push') {
    exit('ignored: ' . $event);
}

$data   = json_decode($payload, true);
$branch = basename($data['ref'] ?? '');
if ($branch !== BRANCH) {
    exit('ignored branch: ' . $branch);
}

$git    = trim(shell_exec('which git') ?: '/usr/bin/git');
$cmd    = "cd " . escapeshellarg(PROJECT_PATH) . " && $git pull origin " . BRANCH . " 2>&1";
$output = shell_exec($cmd);

$log = date('Y-m-d H:i:s') . " | branch: $branch\n$output\n---\n";
file_put_contents(PROJECT_PATH . '/storage/logs/deploy.log', $log, FILE_APPEND | LOCK_EX);

http_response_code(200);
echo "OK\n" . htmlspecialchars($output);
