<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_login();
require_once '../includes/db_connect.php';

try {
    // SQL Query สำหรับดึงรายการบิลที่รอเสิร์ฟ
    // เราจะดึงข้อมูลหัวบิล JOIN กับข้อมูลลูกค้า และรายละเอียดสินค้า JOIN กับชื่อสินค้า
    $sql = "SELECT h.Sale_id, h.Sale_date, h.Table_no, h.Cus_id, c.Cus_name, d.Sale_total, p.Product_name
            FROM saleh_db h
            LEFT JOIN customer_db c ON h.Cus_id = c.Cus_id
            JOIN saled_db d ON h.Sale_id = d.Sale_id
            LEFT JOIN product_db p ON d.Product_id = p.Product_id
            WHERE TRIM(h.Sale_status) IN ('ชำระเงินแล้ว', 'ຊໍາລະເງິນແລ້ວ')
            ORDER BY h.Sale_id DESC";

    $stmt = $conn->query($sql);
    $results = $stmt->fetchAll();

    // จัดกลุ่มข้อมูลตาม Sale_id เพื่อให้แสดงผลเป็น 1 การ์ดต่อ 1 บิล
    $orders = [];
    foreach ($results as $row) {
        $id = $row['Sale_id'];
        if (!isset($orders[$id])) {
            $orders[$id] = [
                'order_id' => 'ORD-' . str_pad($id, 5, '0', STR_PAD_LEFT),
                'sale_id'  => $id,
                'cus_id'   => (string) $row['Cus_id'],
                'table_no' => $row['Table_no'] ?? '-',
                'customer' => $row['Cus_name'] ?? 'ลูกค้าทั่วไป',
                'time'          => (strtotime($row['Sale_date']) > 0) ? date('H:i', strtotime($row['Sale_date'])) : '--:--',
                'sale_datetime' => $row['Sale_date'],
                'items'         => []
            ];
        }
        
        $orders[$id]['items'][] = [
            'product_name' => $row['Product_name'],
            'qty' => $row['Sale_total']
        ];
    }

    // ส่งค่ากลับไปในรูปแบบ Array ของ Object
    echo json_encode([
        'success' => true, 
        'data' => array_values($orders)
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
    ]);
}