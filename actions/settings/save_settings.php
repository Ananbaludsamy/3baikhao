<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) { echo json_encode(['success' => false, 'message' => 'ຂໍ້ມູນບໍ່ຖືກຕ້ອງ']); exit; }

$dir  = __DIR__ . '/../config';
$file = $dir . '/shop_settings.json';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$defaults = [
    'shop_name'=>'ຮ້ານເຝີເຮືອ 3ໃບເຂົາ','shop_phone'=>'020-XXXX-XXXX','shop_address'=>'',
    'point_value'=>1000,'earn_rate'=>10000,'tables_count'=>20,
    'vip_threshold'=>500000,'gold_threshold'=>2000000,'vip_discount'=>5,'gold_discount'=>10
];
$existing = $defaults;
if (file_exists($file)) {
    $loaded = json_decode(file_get_contents($file), true);
    if ($loaded) $existing = array_merge($existing, $loaded);
}

$str_fields = ['shop_name','shop_phone','shop_address'];
$int_fields = ['point_value','earn_rate','tables_count','vip_threshold','gold_threshold'];
$pct_fields = ['vip_discount','gold_discount'];

foreach ($str_fields as $f) { if (isset($data[$f])) $existing[$f] = trim($data[$f]); }
foreach ($int_fields as $f) { if (isset($data[$f])) $existing[$f] = max(1, (int)$data[$f]); }
foreach ($pct_fields as $f) { if (isset($data[$f])) $existing[$f] = max(0, min(100, (int)$data[$f])); }

// Validate: gold threshold must be > vip threshold
if ($existing['gold_threshold'] <= $existing['vip_threshold']) {
    $existing['gold_threshold'] = $existing['vip_threshold'] + 1;
}
// tables_count max 100
$existing['tables_count'] = min(100, $existing['tables_count']);

file_put_contents($file, json_encode($existing, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo json_encode(['success' => true, 'message' => 'ບັນທຶກການຕັ້ງຄ່າສໍາເລັດ', 'settings' => $existing]);
