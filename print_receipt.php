<?php
require_once 'includes/db_connect.php';

if (!isset($_GET['id'])) {
    die("ไม่พบรหัสบิล");
}

$sale_id = $_GET['id'];
$cash_received = $_GET['cash'] ?? 0;
$cash_change = $_GET['change'] ?? 0;

try {
    // ดึงข้อมูลหัวบิล
    $stmt_h = $conn->prepare("SELECT s.*, e.Emp_name, c.Cus_name 
                             FROM saleh_db s 
                             JOIN employee_db e ON s.Emp_id = e.Emp_id 
                             JOIN customer_db c ON s.Cus_id = c.Cus_id 
                             WHERE s.Sale_id = ?");
    $stmt_h->execute([$sale_id]);
    $sale = $stmt_h->fetch();

    if (!$sale) die("ไม่พบข้อมูลบิลนี้ในระบบ");

    // ดึงรายการสินค้า
    $stmt_d = $conn->prepare("SELECT d.*, p.Product_name 
                             FROM saled_db d 
                             JOIN product_db p ON d.Product_id = p.Product_id 
                             WHERE d.Sale_id = ?");
    $stmt_d->execute([$sale_id]);
    $items = $stmt_d->fetchAll();

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ใบเสร็จรับเงิน #<?php echo str_pad($sale['Sale_id'], 5, '0', STR_PAD_LEFT); ?></title>
    <style>
        @media print { @page { margin: 0; } body { margin: 0.5cm; } }
        body { font-family: 'Tahoma', sans-serif; font-size: 12px; width: 80mm; margin: 0 auto; padding: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .line { border-bottom: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        .header { font-weight: bold; font-size: 16px; margin-bottom: 5px; }
        .footer { margin-top: 15px; font-size: 10px; }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 500);">
    <div class="text-center">
        <img src="./assets/img/3baikhao_logo.jpg" alt="Logo" style="width: 50mm; height: auto; margin-bottom: 10px;">
        <div class="header">ร้านกล้วยเตียวเรือ 3 ใบเขา</div>
        <div>โทร: 0XX-XXX-XXXX</div>
        <div class="line"></div>
        <div>บิลเลขที่: ORD-<?php echo str_pad($sale['Sale_id'], 5, '0', STR_PAD_LEFT); ?></div>
        <div>วันที่: <?php echo date('d/m/Y', strtotime($sale['Sale_date'])); ?></div>
        <div>ผู้ขาย: <?php echo $sale['Emp_name']; ?></div>
    </div>
    
    <div class="line"></div>
    
    <table>
        <thead>
            <tr>
                <th align="left">รายการ</th>
                <th class="text-right">จำนวน</th>
                <th class="text-right">รวม</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?php echo $item['Product_name']; ?></td>
                <td class="text-right"><?php echo $item['Sale_total']; ?></td>
                <td class="text-right"><?php echo number_format($item['Sale_sumprice'], 0); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div class="line"></div>
    
    <table>
        <tr>
            <td><strong>ยอดสุทธิ</strong></td>
            <td class="text-right"><strong>₭<?php echo number_format($sale['Sale_sumprice'], 0); ?></strong></td>
        </tr>
        <tr>
            <td>เงินรับ</td>
            <td class="text-right">₭<?php echo number_format($cash_received, 0); ?></td>
        </tr>
        <tr>
            <td>เงินทอน</td>
            <td class="text-right">₭<?php echo number_format($cash_change, 0); ?></td>
        </tr>
    </table>
    
    <div class="line"></div>
    
    <div class="text-center footer">
        ขอบคุณที่ใช้บริการครับ/ค่ะ<br>
        โปรดตรวจสอบเงินทอนและรายการอาหาร
    </div>
</body>
</html>