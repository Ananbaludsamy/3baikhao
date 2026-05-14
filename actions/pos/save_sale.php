<?php
error_reporting(0);
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_login();
require_once '../../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບຂໍ້ມູນລາຍການໃນຕະກ້າ']);
    exit;
}
if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນໄດ້']);
    exit;
}

// Load shop settings for earn_rate + level thresholds
$settingsFile = __DIR__ . '/../config/shop_settings.json';
$settings = ['earn_rate'=>10000,'vip_threshold'=>500000,'gold_threshold'=>2000000];
if (file_exists($settingsFile)) {
    $s = json_decode(file_get_contents($settingsFile), true);
    if ($s) $settings = array_merge($settings, $s);
}
$earn_rate     = max(1, intval($settings['earn_rate']));
$vip_threshold = max(1, intval($settings['vip_threshold']));
$gold_threshold = max(1, intval($settings['gold_threshold']));

try {
    $table_no   = $data['table_no'] ?? '-';
    $status     = 'ຊໍາລະເງິນແລ້ວ';
    $cus_id     = intval($data['cus_id'] ?? 1);
    $emp_id     = $_SESSION['Emp_id'] ?? 1;
    $total      = floatval($data['total'] ?? 0);
    $points_used = max(0, intval($data['points_used'] ?? 0));

    // Check duplicate table order (cover both Lao and legacy Thai statuses)
    if ($table_no !== '-') {
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM saleh_db WHERE Table_no = ? AND TRIM(Sale_status) IN ('ຊໍາລະເງິນແລ້ວ','ชำระเงินแล้ว')");
        $stmt_check->execute([$table_no]);
        if ($stmt_check->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => "ໂຕະ $table_no ມີລາຍການຄ້າງຢູ່ — ກະລຸນາກົດ 'ເສີບແລ້ວ' ໃນໜ້າ Dashboard ກ່ອນ"]);
            exit;
        }
    }

    $conn->beginTransaction();

    // 1. Insert sale header
    $sale_date = date('Y-m-d H:i:s');
    $stmt_h = $conn->prepare("INSERT INTO saleh_db (Sale_date, Cus_id, Emp_id, Sale_sumprice, Sale_status, Table_no) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_h->execute([$sale_date, $cus_id, $emp_id, $total, $status, $table_no]);
    $sale_id = $conn->lastInsertId();

    // 2. Insert sale details
    $stmt_d = $conn->prepare("INSERT INTO saled_db (Sale_id, Product_id, Sale_total, Sale_price, Sale_sumprice) VALUES (?, ?, ?, ?, ?)");
    foreach ($data['cart'] as $item) {
        $stmt_d->execute([$sale_id, $item['id'], $item['qty'], $item['price'], $item['price'] * $item['qty']]);
    }

    // 3. Loyalty points (members only, not walk-in Cus_id=1)
    if ($cus_id > 1) {
        // Get current customer data
        $stmt_cus = $conn->prepare("SELECT Cus_Points, Cus_TotalSpend, Cus_Level FROM customer_db WHERE Cus_id = ?");
        $stmt_cus->execute([$cus_id]);
        $cus = $stmt_cus->fetch();

        $cur_points    = intval($cus['Cus_Points'] ?? 0);
        $cur_spend     = floatval($cus['Cus_TotalSpend'] ?? 0);
        $cur_level     = $cus['Cus_Level'] ?? 'General';

        // Calculate earned points from this sale (based on earn_rate)
        $earned_points = floor($total / $earn_rate);

        // Deduct used points, add earned points (GREATEST prevents negative)
        $new_points = max(0, $cur_points - $points_used) + $earned_points;

        // Update cumulative spend
        $new_spend = $cur_spend + $total;

        // Auto-upgrade level
        $new_level = $cur_level;
        $level_updated = null;
        if ($new_spend >= $gold_threshold && $cur_level !== 'Gold') {
            $new_level = 'Gold';
            $level_updated = date('Y-m-d');
        } elseif ($new_spend >= $vip_threshold && $cur_level === 'General') {
            $new_level = 'VIP';
            $level_updated = date('Y-m-d');
        }

        $stmt_upd = $conn->prepare(
            "UPDATE customer_db SET Cus_Points = ?, Cus_TotalSpend = ?, Cus_Level = ?"
            . ($level_updated ? ", Cus_LevelUpdated = ?" : "")
            . " WHERE Cus_id = ?"
        );
        $params = [$new_points, $new_spend, $new_level];
        if ($level_updated) $params[] = $level_updated;
        $params[] = $cus_id;
        $stmt_upd->execute($params);

        // Log points earned
        if ($earned_points > 0) {
            $stmt_log = $conn->prepare("INSERT INTO point_log_db (Cus_id, Sale_id, Points, Log_type, Log_note) VALUES (?, ?, ?, 'earn', ?)");
            $stmt_log->execute([$cus_id, $sale_id, $earned_points, 'ສະສົມຈາກບິນ ' . 'ORD-' . str_pad($sale_id, 5, '0', STR_PAD_LEFT)]);
        }

        // Log points redeemed
        if ($points_used > 0) {
            $stmt_log2 = $conn->prepare("INSERT INTO point_log_db (Cus_id, Sale_id, Points, Log_type, Log_note) VALUES (?, ?, ?, 'redeem', ?)");
            $stmt_log2->execute([$cus_id, $sale_id, -$points_used, 'ແລກຄະແນນໃນບິນ ' . 'ORD-' . str_pad($sale_id, 5, '0', STR_PAD_LEFT)]);
        }
    }

    $conn->commit();

    $order_id = 'ORD-' . str_pad($sale_id, 5, '0', STR_PAD_LEFT);
    echo json_encode([
        'success'   => true,
        'sale_id'   => $sale_id,
        'order_id'  => $order_id,
        'new_level' => $new_level ?? null,
        'new_points'=> $new_points ?? null
    ]);

} catch (Exception $e) {
    if ($conn && $conn->inTransaction()) $conn->rollBack();
    $msg = $e->getMessage();
    error_log('[save_sale] ' . $msg);
    echo json_encode(['success' => false, 'message' => 'ເກີດຂໍ້ຜິດພາດ: ' . $msg]);
}
