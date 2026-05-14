<?php
require_once '../includes/db_connect.php';

if (!isset($_GET['id'])) {
    die("ບໍ່ພົບລະຫັດບິນ");
}

$sale_id = intval($_GET['id']);
$cash_received = floatval($_GET['cash'] ?? 0);
$cash_change = floatval($_GET['change'] ?? 0);
$table_no = $_GET['table'] ?? '-';

// Load shop settings
$settings = ['shop_name' => 'ຮ້ານເຝີເຮືອ 3ໃບເຂົາ', 'shop_phone' => '020-XXXX-XXXX', 'shop_address' => 'ນະຄອນຫຼວງວຽງຈັນ'];
$settingsFile = __DIR__ . '/config/shop_settings.json';
if (file_exists($settingsFile)) {
    $loaded = json_decode(file_get_contents($settingsFile), true);
    if ($loaded) $settings = array_merge($settings, $loaded);
}

try {
    $stmt_h = $conn->prepare("SELECT s.*, e.Emp_name, c.Cus_name, c.Cus_Level, c.Cus_Points, c.Cus_id
                              FROM saleh_db s
                              JOIN employee_db e ON s.Emp_id = e.Emp_id
                              JOIN customer_db c ON s.Cus_id = c.Cus_id
                              WHERE s.Sale_id = ?");
    $stmt_h->execute([$sale_id]);
    $sale = $stmt_h->fetch();

    if (!$sale) die("ບໍ່ພົບຂໍ້ມູນບິນນີ້ໃນລະບົບ");

    $stmt_d = $conn->prepare("SELECT d.*, p.Product_name
                              FROM saled_db d
                              JOIN product_db p ON d.Product_id = p.Product_id
                              WHERE d.Sale_id = ?");
    $stmt_d->execute([$sale_id]);
    $items = $stmt_d->fetchAll();

    // ດຶງຄະແນນທີ່ໄດ້ຮັບ / ໃຊ້ ໃນບິນນີ້ (ສະມາຊິກເທົ່ານັ້ນ)
    $earned_points = 0;
    $points_used   = 0;
    if (intval($sale['Cus_id']) > 1) {
        $stmt_pts = $conn->prepare("SELECT Log_type, ABS(Points) as Points FROM point_log_db WHERE Sale_id = ?");
        $stmt_pts->execute([$sale_id]);
        foreach ($stmt_pts->fetchAll() as $log) {
            if ($log['Log_type'] === 'earn')   $earned_points += intval($log['Points']);
            if ($log['Log_type'] === 'redeem') $points_used   += intval($log['Points']);
        }
    }

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$order_id = 'ORD-' . str_pad($sale['Sale_id'], 5, '0', STR_PAD_LEFT);
$sale_date = date('d/m/Y H:i', strtotime($sale['Sale_date']));
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ໃບເສດຮັບເງິນ #<?php echo $order_id; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @media print { @page { size: 80mm auto; margin: 0; } body { margin: 0.2cm; } .no-print { display:none; } }
        body { font-family: 'Phetsarath', 'Tahoma', sans-serif; font-size: 11px; width: 80mm; margin: 0 auto; padding: 6px; color: #1a1a1a; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .dash { border-bottom: 1px dashed #555; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 1px 0; font-size: 11px; }
        .shop-name { font-weight: bold; font-size: 14px; margin-bottom: 2px; }
        .order-id { font-size: 12px; font-weight: bold; margin: 2px 0; }
        .total-row td { font-size: 13px; font-weight: bold; }
        .footer { margin-top: 8px; font-size: 10px; }
        .btn-print { display:block; margin: 8px auto; padding: 6px 20px; background:#7c2d12; color:white; border:none; border-radius:8px; cursor:pointer; font-family:inherit; font-size:13px; }
    </style>
</head>
<body>
    <div class="text-center">
        <?php if (file_exists(__DIR__ . '/assets/img/logo/3baikhao_logo.png')): ?>
        <img src="./assets/img/logo/3baikhao_logo.png" alt="Logo" style="width:28mm;height:auto;margin-bottom:3px;mix-blend-mode:multiply;">
        <?php endif; ?>
        <div class="shop-name"><?php echo htmlspecialchars($settings['shop_name']); ?></div>
        <div>ໂທ: <?php echo htmlspecialchars($settings['shop_phone']); ?></div>
        <?php if (!empty($settings['shop_address'])): ?>
        <div style="font-size:11px;color:#555;"><?php echo htmlspecialchars($settings['shop_address']); ?></div>
        <?php endif; ?>
    </div>

    <div class="dash"></div>

    <div class="text-center">
        <div class="order-id">ບິນເລກທີ: <?php echo $order_id; ?></div>
        <div>ວັນທີ: <?php echo $sale_date; ?></div>
        <div>ຜູ້ຂາຍ: <?php echo htmlspecialchars($sale['Emp_name']); ?></div>
        <div>ລູກຄ້າ: <?php echo htmlspecialchars($sale['Cus_name']); ?>
            <?php if ($sale['Cus_Level'] !== 'General'): ?>
            (<?php echo htmlspecialchars($sale['Cus_Level']); ?>)
            <?php endif; ?>
        </div>
        <div style="font-size:14px;font-weight:bold;margin-top:2px;">ໂຕະ: <?php echo htmlspecialchars($table_no); ?></div>
    </div>

    <div class="dash"></div>

    <table>
        <thead>
            <tr>
                <th align="left">ລາຍການ</th>
                <th class="text-right">ຈຳນວນ</th>
                <th class="text-right">ລວມ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['Product_name']); ?></td>
                <td class="text-right"><?php echo $item['Sale_total']; ?></td>
                <td class="text-right">₭<?php echo number_format($item['Sale_sumprice'], 0); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="dash"></div>

    <table>
        <tr class="total-row">
            <td>ຍອດສຸດທິ</td>
            <td class="text-right">₭<?php echo number_format($sale['Sale_sumprice'], 0); ?></td>
        </tr>
        <tr>
            <td>ເງິນຮັບ</td>
            <td class="text-right">₭<?php echo number_format($cash_received, 0); ?></td>
        </tr>
        <tr>
            <td>ເງິນທອນ</td>
            <td class="text-right">₭<?php echo number_format($cash_change, 0); ?></td>
        </tr>
    </table>

    <?php if (intval($sale['Cus_id']) > 1): ?>
    <div class="dash"></div>
    <table>
        <tr>
            <td colspan="2" style="font-size:11px;font-weight:bold;padding-bottom:3px;">⭐ ຄະແນນສະສົມ</td>
        </tr>
        <?php if ($points_used > 0): ?>
        <tr>
            <td style="font-size:11px;">ຄະແນນທີ່ໃຊ້ຄັ້ງນີ້</td>
            <td class="text-right" style="font-size:11px;color:#b45309;">-<?php echo number_format($points_used); ?> ຄະແນນ</td>
        </tr>
        <?php endif; ?>
        <?php if ($earned_points > 0): ?>
        <tr>
            <td style="font-size:11px;">ຄະແນນທີ່ໄດ້ຮັບຄັ້ງນີ້</td>
            <td class="text-right" style="font-size:11px;color:#15803d;">+<?php echo number_format($earned_points); ?> ຄະແນນ</td>
        </tr>
        <?php endif; ?>
        <tr>
            <td style="font-size:12px;font-weight:bold;">ຄະແນນສະສົມທັງໝົດ</td>
            <td class="text-right" style="font-size:12px;font-weight:bold;"><?php echo number_format(intval($sale['Cus_Points'])); ?> ຄະແນນ</td>
        </tr>
    </table>
    <?php endif; ?>

    <div class="dash"></div>

    <div class="text-center footer">
        ຂອບໃຈທີ່ໃຊ້ບໍລິການ<br>
        ກະລຸນາກວດສອບເງິນທອນແລະລາຍການ
    </div>

    <button class="btn-print no-print" onclick="window.print()">🖨 ພິມໃບເສດ</button>

    <script>window.onload = function() { window.print(); setTimeout(() => window.close(), 1000); };</script>
</body>
</html>
