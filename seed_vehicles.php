<?php
declare(strict_types=1);

$SECRET = 'seedvehicles2024!';

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    die('<h2 style="font-family:monospace;color:red">403 Forbidden — ?key=seedvehicles2024! ile çağır</h2>');
}

$db_cfg = require __DIR__ . '/config/database.php';

try {
    $pdo = new PDO(
        "mysql:host={$db_cfg['host']};port={$db_cfg['port']};dbname={$db_cfg['name']};charset={$db_cfg['charset']}",
        $db_cfg['username'], $db_cfg['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die('<pre style="color:red">DB Hatası: ' . htmlspecialchars($e->getMessage()) . '</pre>');
}

$csvPath = __DIR__ . '/storage/uploads/turbo_hizinda_veritabani.csv';
if (!file_exists($csvPath)) {
    die('<pre style="color:red">CSV bulunamadı: ' . $csvPath . '</pre>');
}

$startTime = microtime(true);
$log = [];

function addLog(string $msg, string $type = 'ok'): void {
    global $log;
    $log[] = ['msg' => $msg, 'type' => $type];
}

function makeSlug(string $text): string {
    $s = mb_strtolower(trim($text));
    $s = preg_replace('/[^a-z0-9\s-]/', '', $s);
    $s = preg_replace('/[\s-]+/', '-', $s);
    return trim($s, '-');
}

$handle = fopen($csvPath, 'r');
$bom = fread($handle, 3);
if ($bom !== "\xEF\xBB\xBF") {
    rewind($handle);
}
$header = fgetcsv($handle, 0, ';');

$rows = [];
while (($row = fgetcsv($handle, 0, ';')) !== false) {
    if (count($row) >= 5) {
        $rows[] = [
            trim($row[0]),
            trim($row[1]),
            trim($row[2]),
            trim($row[3]),
            trim($row[4]),
        ];
    }
}
fclose($handle);

addLog(count($rows) . ' satır CSV okundu');

$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE `engine_ecus`');
$pdo->exec('TRUNCATE TABLE `engines`');
$pdo->exec('TRUNCATE TABLE `generations`');
$pdo->exec('TRUNCATE TABLE `vehicle_models`');
$pdo->exec('TRUNCATE TABLE `brands`');
$pdo->exec('TRUNCATE TABLE `ecus`');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
addLog('Mevcut tablolar temizlendi');

$brandMap = [];
$brandStmt = $pdo->prepare('INSERT INTO `brands` (`name`, `slug`, `is_active`, `sort_order`) VALUES (?, ?, 1, ?)');
$sortOrder = 0;

foreach ($rows as $r) {
    $brandName = $r[0];
    if (!isset($brandMap[$brandName])) {
        $sortOrder++;
        $brandStmt->execute([$brandName, makeSlug($brandName), $sortOrder]);
        $brandMap[$brandName] = (int) $pdo->lastInsertId();
    }
}
addLog(count($brandMap) . ' marka eklendi');

$modelMap = [];
$modelStmt = $pdo->prepare('INSERT INTO `vehicle_models` (`brand_id`, `name`, `slug`, `is_active`) VALUES (?, ?, ?, 1)');

foreach ($rows as $r) {
    $key = $r[0] . '||' . $r[1];
    if (!isset($modelMap[$key])) {
        $brandId = $brandMap[$r[0]];
        $slug = makeSlug($r[0] . '-' . $r[1]);
        $modelStmt->execute([$brandId, $r[1], $slug]);
        $modelMap[$key] = (int) $pdo->lastInsertId();
    }
}
addLog(count($modelMap) . ' model eklendi');

$genMap = [];
$genStmt = $pdo->prepare('INSERT INTO `generations` (`model_id`, `name`, `is_active`) VALUES (?, ?, 1)');

foreach ($rows as $r) {
    $key = $r[0] . '||' . $r[1] . '||' . $r[2];
    if (!isset($genMap[$key])) {
        $modelKey = $r[0] . '||' . $r[1];
        $modelId = $modelMap[$modelKey];
        $genStmt->execute([$modelId, $r[2]]);
        $genMap[$key] = (int) $pdo->lastInsertId();
    }
}
addLog(count($genMap) . ' generation eklendi');

$engineMap = [];
$engineStmt = $pdo->prepare('INSERT INTO `engines` (`generation_id`, `name`, `is_active`) VALUES (?, ?, 1)');

foreach ($rows as $r) {
    $key = $r[0] . '||' . $r[1] . '||' . $r[2] . '||' . $r[3];
    if (!isset($engineMap[$key])) {
        $genKey = $r[0] . '||' . $r[1] . '||' . $r[2];
        $genId = $genMap[$genKey];
        $engineStmt->execute([$genId, $r[3]]);
        $engineMap[$key] = (int) $pdo->lastInsertId();
    }
}
addLog(count($engineMap) . ' motor eklendi');

$ecuMap = [];
$ecuStmt = $pdo->prepare('INSERT INTO `ecus` (`name`, `brand`, `is_active`) VALUES (?, ?, 1)');

foreach ($rows as $r) {
    $ecuName = $r[4];
    if (!isset($ecuMap[$ecuName])) {
        $parts = explode(' ', $ecuName, 2);
        $ecuBrand = (count($parts) > 1) ? $parts[0] : null;
        $ecuStmt->execute([$ecuName, $ecuBrand]);
        $ecuMap[$ecuName] = (int) $pdo->lastInsertId();
    }
}
addLog(count($ecuMap) . ' ECU eklendi');

$eeStmt = $pdo->prepare('INSERT IGNORE INTO `engine_ecus` (`engine_id`, `ecu_id`) VALUES (?, ?)');
$eeCount = 0;

foreach ($rows as $r) {
    $engineKey = $r[0] . '||' . $r[1] . '||' . $r[2] . '||' . $r[3];
    $ecuName = $r[4];
    if (isset($engineMap[$engineKey]) && isset($ecuMap[$ecuName])) {
        $eeStmt->execute([$engineMap[$engineKey], $ecuMap[$ecuName]]);
        $eeCount++;
    }
}
addLog($eeCount . ' motor-ECU eşleşmesi eklendi');

$elapsed = round(microtime(true) - $startTime, 2);
addLog("Tamamlandı — {$elapsed}s", 'ok');

?><!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Vehicle Seeder</title>
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Courier New',monospace;background:#0d1117;color:#c9d1d9;padding:2rem}
  h1{color:#58a6ff;margin-bottom:1.5rem;font-size:1.4rem}
  .block{background:#161b22;border:1px solid #30363d;border-radius:8px;padding:1rem 1.2rem;margin-bottom:.6rem;display:flex;align-items:center;gap:.75rem}
  .ok{color:#3fb950}.err{color:#f85149}
  .badge{display:inline-block;padding:2px 10px;border-radius:4px;font-size:.75rem;font-weight:bold}
  .b-ok{background:#1a4a1a;color:#3fb950}.b-err{background:#4a1a1a;color:#f85149}
  .notice{background:#1a2a1a;border:1px solid #3fb950;border-radius:6px;padding:1rem;margin-top:1.5rem;color:#3fb950;font-size:.85rem}
  .stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:.6rem;margin-bottom:1.5rem}
  .stat{background:#161b22;border:1px solid #30363d;border-radius:8px;padding:.8rem 1rem;text-align:center}
  .stat strong{display:block;font-size:1.4rem;color:#58a6ff}
  .stat span{font-size:.75rem;color:#8b949e;text-transform:uppercase;letter-spacing:.05em}
</style>
</head>
<body>
<h1>&#128663; Vehicle Data Seeder</h1>

<div class="stats">
    <div class="stat"><strong><?= number_format(count($brandMap)) ?></strong><span>Marka</span></div>
    <div class="stat"><strong><?= number_format(count($modelMap)) ?></strong><span>Model</span></div>
    <div class="stat"><strong><?= number_format(count($genMap)) ?></strong><span>Jenerasyon</span></div>
    <div class="stat"><strong><?= number_format(count($engineMap)) ?></strong><span>Motor</span></div>
    <div class="stat"><strong><?= number_format(count($ecuMap)) ?></strong><span>ECU</span></div>
    <div class="stat"><strong><?= number_format($eeCount) ?></strong><span>Motor-ECU</span></div>
</div>

<?php foreach ($log as $entry): ?>
<div class="block">
    <span class="badge <?= $entry['type'] === 'ok' ? 'b-ok' : 'b-err' ?>"><?= $entry['type'] === 'ok' ? '✓' : '✗' ?></span>
    <span class="<?= $entry['type'] ?>"><?= htmlspecialchars($entry['msg']) ?></span>
</div>
<?php endforeach; ?>

<div class="notice">
    &#9888; İşlem bitti — FTP ile bu dosyayı <strong>sil</strong>: seed_vehicles.php
</div>

</body>
</html>
