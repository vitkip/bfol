<?php
/**
 * BFOL Production Setup — one-time use, delete after done.
 */
declare(strict_types=1);

const SETUP_PASSWORD = 'bfol2025setup';

// ── Resolve app root ─────────────────────────────────────────────
// Standard layout  : public/index.php  → dirname(__DIR__) has vendor/
// Shared hosting   : public_html/      → ../bfol has vendor/
function resolveAppRoot(): string|false
{
    $standard = dirname(__DIR__);
    if (is_dir($standard . '/vendor') && is_file($standard . '/artisan')) {
        return $standard;
    }
    $shared = realpath(__DIR__ . '/../bfol');
    if ($shared && is_dir($shared . '/vendor') && is_file($shared . '/artisan')) {
        return $shared;
    }
    return false;
}

$appRoot = resolveAppRoot();

// ── Session / auth ───────────────────────────────────────────────
session_start();
$submitted = $_POST['password'] ?? '';
$action    = $_POST['action']   ?? '';

if ($submitted === SETUP_PASSWORD) {
    $_SESSION['bfol_setup_ok'] = true;
}
$authed = !empty($_SESSION['bfol_setup_ok']);

// ── Laravel bootstrap (once per request) ─────────────────────────
function bootLaravel(string $appRoot): void
{
    static $booted = false;
    if ($booted) return;

    require_once $appRoot . '/vendor/autoload.php';
    $app = require_once $appRoot . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    $booted = true;
}

// ── Run artisan command, return output string ─────────────────────
function runArtisan(string $appRoot, string $command, array $params = []): string
{
    bootLaravel($appRoot);
    ob_start();
    try {
        \Illuminate\Support\Facades\Artisan::call($command, $params);
    } finally {
        ob_end_clean();
    }
    $out = \Illuminate\Support\Facades\Artisan::output();
    return trim($out) ?: '(completed — no output)';
}

// ── Create storage symlink ────────────────────────────────────────
function doStorageLink(string $appRoot): string
{
    $target = $appRoot . '/storage/app/public';
    $link   = __DIR__ . '/storage';

    if (!is_dir($target)) {
        return err("ບໍ່ພົບ $target");
    }

    if (is_link($link)) {
        return ok('Storage symlink ມີຢູ່ແລ້ວ → ' . readlink($link));
    }

    if (is_dir($link)) {
        $items = scandir($link);
        $items = $items ? array_diff($items, ['.', '..']) : [];
        if (!empty($items)) {
            return warn('public/storage ເປັນ folder ທີ່ມີໄຟລ໌ — ລຶບດ້ວຍ File Manager ກ່ອນ');
        }
        rmdir($link);
    } elseif (file_exists($link)) {
        unlink($link);
    }

    return symlink($target, $link)
        ? ok("Storage symlink ສ້າງສຳເລັດ → $target")
        : err('symlink() ລົ້ມເຫລວ — ໃຫ້ໃຊ້ Terminal: ln -s ' . $target . ' ' . $link);
}

// ── App info table ────────────────────────────────────────────────
function doAppInfo(string $appRoot): string
{
    bootLaravel($appRoot);
    $rows = [
        'APP_ROOT'    => $appRoot,
        'APP_ENV'     => env('APP_ENV', '—'),
        'APP_URL'     => env('APP_URL', '—'),
        'APP_DEBUG'   => env('APP_DEBUG') ? 'true ⚠️' : 'false ✅',
        'DB_DATABASE' => env('DB_DATABASE', '—'),
        'PHP'         => PHP_VERSION,
    ];
    $html = '<table style="width:100%;border-collapse:collapse">';
    foreach ($rows as $k => $v) {
        $html .= '<tr>'
            . '<td style="padding:5px 14px 5px 0;color:#94a3b8;white-space:nowrap">' . $k . '</td>'
            . '<td style="color:#e2e8f0">' . htmlspecialchars((string)$v) . '</td>'
            . '</tr>';
    }
    return $html . '</table>';
}

// ── Helper span builders ──────────────────────────────────────────
function ok(string $msg): string   { return "<span style='color:#4ade80'>✅ $msg</span>"; }
function warn(string $msg): string { return "<span style='color:#fbbf24'>⚠️ $msg</span>"; }
function err(string $msg): string  { return "<span style='color:#f87171'>❌ $msg</span>"; }

// ── Handle POST action ────────────────────────────────────────────
$output = '';

if ($authed && $appRoot && $action) {
    try {
        if ($action === 'about') {
            $output = doAppInfo($appRoot);

        } elseif ($action === 'status') {
            $output = '<pre>' . htmlspecialchars(runArtisan($appRoot, 'migrate:status')) . '</pre>';

        } elseif ($action === 'env') {
            $output = file_exists($appRoot . '/.env')
                ? ok(".env ມີຢູ່ແລ້ວ ($appRoot/.env)")
                : (copy($appRoot . '/.env.production', $appRoot . '/.env')
                    ? ok('Copy .env.production → .env ສຳເລັດ')
                    : err('copy ລົ້ມເຫລວ — ສ້າງ .env ດ້ວຍຕົນເອງໃນ File Manager'));

        } elseif ($action === 'migrate') {
            $output = '<pre>' . htmlspecialchars(runArtisan($appRoot, 'migrate', ['--force' => true])) . '</pre>';

        } elseif ($action === 'optimize') {
            $output = '<pre>' . htmlspecialchars(runArtisan($appRoot, 'optimize')) . '</pre>';

        } elseif ($action === 'storage') {
            $output = doStorageLink($appRoot);

        } elseif ($action === 'all') {
            $output  = '<b style="color:#94a3b8">── Migrate ──</b><br>';
            $output .= '<pre>' . htmlspecialchars(runArtisan($appRoot, 'migrate', ['--force' => true])) . '</pre>';
            $output .= '<b style="color:#94a3b8">── Optimize ──</b><br>';
            $output .= '<pre>' . htmlspecialchars(runArtisan($appRoot, 'optimize')) . '</pre>';
            $output .= '<b style="color:#94a3b8">── Storage Link ──</b><br>';
            $output .= doStorageLink($appRoot);

        } elseif ($action === 'delete') {
            $output = unlink(__FILE__)
                ? ok('ລຶບ setup.php ສຳເລັດ — ປອດໄພແລ້ວ!')
                : err('ລຶບ setup.php ບໍ່ໄດ້ — ລຶບດ້ວຍ File Manager');
        }

    } catch (\Throwable $e) {
        $output = err(htmlspecialchars($e->getMessage()));
    }
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>BFOL Setup</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:system-ui,sans-serif;background:#0f172a;min-height:100vh;display:flex;justify-content:center;align-items:flex-start;padding:32px 16px}
.card{background:#1e293b;border-radius:14px;padding:28px;width:100%;max-width:660px;box-shadow:0 8px 32px rgba(0,0,0,.5);color:#e2e8f0}
h1{font-size:20px;font-weight:700;color:#f1f5f9;margin-bottom:4px}
.sub{font-size:13px;color:#64748b;margin-bottom:20px}
.path{font-family:monospace;font-size:11px;color:#475569;background:#0f172a;border-radius:6px;padding:7px 10px;margin-bottom:20px}
.path b{color:#64748b}
input[type=password]{width:100%;padding:10px 14px;background:#0f172a;border:1px solid #334155;border-radius:8px;font-size:15px;color:#e2e8f0;margin-bottom:10px;outline:none}
input[type=password]:focus{border-color:#3b82f6}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:8px}
button{width:100%;padding:10px;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:opacity .15s}
button:hover{opacity:.85}
.b-gray  {background:#334155;color:#e2e8f0}
.b-blue  {background:#2563eb;color:#fff}
.b-orange{background:#c2410c;color:#fff}
.b-purple{background:#6d28d9;color:#fff}
.b-green {background:#15803d;color:#fff;padding:12px;font-size:14px;margin-bottom:6px}
.b-red   {background:#991b1b;color:#fff;font-size:13px}
.output{margin-top:20px;background:#0f172a;border:1px solid #1e3a5f;border-radius:10px;padding:16px;font-size:13px;line-height:1.6;overflow-x:auto}
.output pre{white-space:pre-wrap;word-break:break-word;color:#cbd5e1}
.warn-box{margin-top:16px;background:#431407;border:1px solid #7c2d12;border-radius:8px;padding:11px 14px;font-size:13px;color:#fdba74}
.err-box{background:#450a0a;border:1px solid #7f1d1d;border-radius:8px;padding:20px;text-align:center;font-size:14px;color:#fca5a5;margin-top:12px}
form{margin:0}
</style>
</head>
<body>
<div class="card">
  <h1>🚀 BFOL Production Setup</h1>
  <p class="sub">ໃຊ້ຄັ້ງດຽວ — ລຶບໄຟລ໌ນີ້ທັນທີຫຼັງໃຊ້</p>

<?php if (!$appRoot): ?>
  <div class="err-box">
    ❌ ຊອກຫາ Laravel app ບໍ່ພົບ<br>
    <small style="color:#94a3b8;margin-top:6px;display:block">
      ກວດສອບວ່າ <code>bfol/vendor/</code> ແລະ <code>bfol/artisan</code> ມີຢູ່ແລ້ວ
    </small>
  </div>

<?php elseif (!$authed): ?>
  <form method="post">
    <input type="password" name="password" placeholder="ໃສ່ລະຫັດ setup" autofocus>
    <button class="b-blue" style="padding:11px">ເຂົ້າສູ່ລະບົບ →</button>
  </form>

<?php else: ?>
  <div class="path"><b>APP ROOT:</b> <?= htmlspecialchars($appRoot) ?></div>

  <div class="grid">
    <form method="post"><input type="hidden" name="action" value="about">
      <button class="b-gray">ℹ️ App Info</button></form>
    <form method="post"><input type="hidden" name="action" value="status">
      <button class="b-gray">🗂 Migration Status</button></form>
    <form method="post"><input type="hidden" name="action" value="migrate">
      <button class="b-blue">🗄 Migrate DB</button></form>
    <form method="post"><input type="hidden" name="action" value="optimize">
      <button class="b-orange">⚡ Optimize</button></form>
    <form method="post"><input type="hidden" name="action" value="storage">
      <button class="b-purple">🔗 Storage Link</button></form>
    <form method="post"><input type="hidden" name="action" value="env">
      <button class="b-gray">📄 Check .env</button></form>
  </div>

  <form method="post">
    <input type="hidden" name="action" value="all">
    <button class="b-green">✅ Run ທຸກຢ່າງ &nbsp;(Migrate + Optimize + Storage)</button>
  </form>

  <form method="post" onsubmit="return confirm('ລຶບ setup.php ດຽວນີ້?')">
    <input type="hidden" name="action" value="delete">
    <button class="b-red">🗑 ລຶບ setup.php ຫຼັງ setup ສຳເລັດ</button>
  </form>

  <?php if ($output): ?>
  <div class="output"><?= $output ?></div>
  <?php endif; ?>

  <div class="warn-box">⚠️ ລຶບໄຟລ໌ setup.php ທັນທີຫຼັງ setup ສຳເລັດ</div>
<?php endif; ?>
</div>
</body>
</html>
