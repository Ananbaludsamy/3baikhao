<?php
require_once 'includes/db_connect.php';

if (!isset($_GET['id'])) die("ບໍ່ພົບລະຫັດໃບສັ່ງຊື້");

$order_id = intval($_GET['id']);

$settings = ['shop_name' => 'ຮ້ານເຝີເຮືອ 3ໃບເຂົາ', 'shop_phone' => '020-XXXX-XXXX', 'shop_address' => 'ນະຄອນຫຼວງວຽງຈັນ'];
$settingsFile = __DIR__ . '/config/shop_settings.json';
if (file_exists($settingsFile)) {
    $loaded = json_decode(file_get_contents($settingsFile), true);
    if ($loaded) $settings = array_merge($settings, $loaded);
}

try {
    $stmt_h = $conn->prepare("
        SELECT oh.*, e.Emp_name
        FROM orderh_db oh
        JOIN employee_db e ON oh.Emp_id = e.Emp_id
        WHERE oh.Order_id = ?
    ");
    $stmt_h->execute([$order_id]);
    $order = $stmt_h->fetch();
    if (!$order) die("ບໍ່ພົບໃບສັ່ງຊື້ນີ້ໃນລະບົບ");

    $stmt_d = $conn->prepare("
        SELECT od.*, m.Material_name, m.Material_unit
        FROM orderd_db od
        JOIN material_db m ON od.Material_id = m.Material_id
        WHERE od.Order_id = ?
    ");
    $stmt_d->execute([$order_id]);
    $items = $stmt_d->fetchAll();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$po_no    = 'PO-' . str_pad($order['Order_id'], 6, '0', STR_PAD_LEFT);
$po_date  = date('d/m/Y', strtotime($order['Order_date']));
$print_date = date('d/m/Y H:i');
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <title>ໃບສັ່ງຊື້ <?php echo $po_no; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page { margin: 1.5cm; }
            .no-print { display: none; }
            body { font-size: 12px; }
        }
        * { box-sizing: border-box; }
        body { font-family: 'Phetsarath', 'Tahoma', sans-serif; font-size: 13px; max-width: 720px; margin: 0 auto; padding: 24px; color: #1a1a1a; background: white; }
        .header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
        .shop-info { flex: 1; }
        .shop-name { font-size: 18px; font-weight: bold; color: #7c2d12; }
        .po-box { text-align: right; }
        .po-number { font-size: 22px; font-weight: bold; color: #1e293b; }
        .po-label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: .05em; }
        .divider { border: none; border-top: 2px solid #e2e8f0; margin: 16px 0; }
        .divider-light { border: none; border-top: 1px dashed #cbd5e1; margin: 12px 0; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px 24px; margin-bottom: 20px; font-size: 12px; }
        .meta-label { color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: .04em; }
        .meta-value { font-weight: bold; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead tr { background: #1e293b; color: white; }
        thead th { padding: 10px 12px; font-size: 11px; text-align: left; font-weight: 700; text-transform: uppercase; letter-spacing: .04em; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:hover { background: #f1f5f9; }
        tbody td { padding: 9px 12px; font-size: 12px; border-bottom: 1px solid #e2e8f0; }
        tfoot tr { background: #f1f5f9; }
        tfoot td { padding: 10px 12px; font-size: 13px; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-box { background: #1e293b; color: white; padding: 12px 16px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .total-label { font-size: 12px; color: #94a3b8; }
        .total-amount { font-size: 22px; font-weight: bold; }
        .sign-row { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 32px; }
        .sign-box { text-align: center; }
        .sign-line { border-top: 1px solid #334155; margin: 40px 16px 6px; }
        .sign-label { font-size: 11px; color: #64748b; }
        .sign-name { font-size: 12px; font-weight: bold; color: #1e293b; margin-top: 2px; }
        .note-box { background: #fefce8; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 14px; font-size: 12px; margin-bottom: 16px; }
        .footer-note { text-align: center; font-size: 10px; color: #94a3b8; margin-top: 24px; }
        .btn-print { display: block; margin: 16px auto 0; padding: 10px 32px; background: #7c2d12; color: white; border: none; border-radius: 8px; cursor: pointer; font-family: inherit; font-size: 14px; font-weight: bold; }
        .status-badge { display: inline-block; background: #fef9c3; color: #92400e; font-size: 10px; font-weight: bold; padding: 2px 8px; border-radius: 20px; border: 1px solid #fde68a; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header-row">
        <div class="shop-info">
            <?php if (file_exists(__DIR__ . '/assets/img/logo/3baikhao_logo.png')): ?>
            <img src="./assets/img/logo/3baikhao_logo.png" alt="Logo" style="height:48px;margin-bottom:6px;mix-blend-mode:multiply;">
            <?php endif; ?>
            <div class="shop-name"><?php echo htmlspecialchars($settings['shop_name']); ?></div>
            <div style="font-size:12px;color:#64748b;margin-top:2px;">ໂທ: <?php echo htmlspecialchars($settings['shop_phone']); ?></div>
            <?php if (!empty($settings['shop_address'])): ?>
            <div style="font-size:12px;color:#64748b;"><?php echo htmlspecialchars($settings['shop_address']); ?></div>
            <?php endif; ?>
        </div>
        <div class="po-box">
            <div class="po-label">ໃບສັ່ງຊື້ວັດຖຸດິບ</div>
            <div class="po-number"><?php echo $po_no; ?></div>
            <div style="margin-top:4px;"><span class="status-badge">ລໍຖ້າຮັບເຂົ້າ</span></div>
        </div>
    </div>

    <hr class="divider">

    <!-- Meta info -->
    <div class="meta-grid">
        <div>
            <div class="meta-label">ວັນທີສັ່ງຊື້</div>
            <div class="meta-value"><?php echo $po_date; ?></div>
        </div>
        <div>
            <div class="meta-label">ວັນທີພິມ</div>
            <div class="meta-value"><?php echo $print_date; ?></div>
        </div>
        <div>
            <div class="meta-label">ຜູ້ສັ່ງຊື້</div>
            <div class="meta-value"><?php echo htmlspecialchars($order['Emp_name']); ?></div>
        </div>
        <div>
            <div class="meta-label">ໝາຍເຫດ</div>
            <div class="meta-value"><?php echo htmlspecialchars($order['Order_note'] ?: '-'); ?></div>
        </div>
    </div>

    <hr class="divider">

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:36px;">#</th>
                <th>ຊື່ວັດຖຸດິບ</th>
                <th class="text-center" style="width:100px;">ຈໍານວນ</th>
                <th class="text-center" style="width:80px;">ຫົວໜ່ວຍ</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
            <tr>
                <td class="text-center" style="color:#94a3b8;"><?php echo $i + 1; ?></td>
                <td style="font-weight:600;"><?php echo htmlspecialchars($item['Material_name']); ?></td>
                <td class="text-center" style="font-weight:bold;"><?php echo number_format($item['Order_total']); ?></td>
                <td class="text-center" style="color:#64748b;"><?php echo htmlspecialchars($item['Material_unit']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-box">
        <div>
            <div class="total-label">ລາຍການທັງໝົດ <?php echo count($items); ?> ລາຍການ</div>
            <div style="font-size:12px;color:#94a3b8;">ຍອດລວມ</div>
        </div>
        <div class="total-amount">₭<?php echo number_format($order['Order_sumprice'], 0); ?></div>
    </div>

    <?php if (!empty($order['Order_note'])): ?>
    <div class="note-box">
        <strong>ໝາຍເຫດ:</strong> <?php echo htmlspecialchars($order['Order_note']); ?>
    </div>
    <?php endif; ?>

    <!-- Signature -->
    <div class="sign-row">
        <div class="sign-box">
            <div class="sign-line"></div>
            <div class="sign-label">ຜູ້ສັ່ງຊື້ / Ordered By</div>
            <div class="sign-name"><?php echo htmlspecialchars($order['Emp_name']); ?></div>
        </div>
        <div class="sign-box">
            <div class="sign-line"></div>
            <div class="sign-label">ຜູ້ອະນຸມັດ / Approved By</div>
            <div class="sign-name">&nbsp;</div>
        </div>
    </div>

    <div class="footer-note">
        ສ້າງໂດຍລະບົບ <?php echo htmlspecialchars($settings['shop_name']); ?> &nbsp;·&nbsp; <?php echo $po_no; ?> &nbsp;·&nbsp; ພິມວັນທີ <?php echo $print_date; ?>
    </div>

    <button class="btn-print no-print" onclick="window.print()">🖨 ພິມໃບສັ່ງຊື້</button>

    <script>window.onload = function() { window.print(); setTimeout(() => window.close(), 1500); };</script>
</body>
</html>
