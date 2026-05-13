<?php 
session_start();
if (!isset($_SESSION['Emp_id'])) {
    header("Location: login.php");
    exit;
}
include_once 'includes/db_connect.php'; 
?>
<?php
// ดึงข้อมูลหมวดหมู่ทั้งหมด
$stmt_cat = $conn->query("SELECT * FROM producttype_db ORDER BY ProductType_id ASC");
$categories = $stmt_cat->fetchAll();

// ดึงข้อมูลสินค้าทั้งหมด พร้อมชื่อหมวดหมู่
$stmt_prod = $conn->query("SELECT p.*, t.ProductType_name 
                           FROM product_db p 
                           JOIN producttype_db t ON p.ProductType_id = t.ProductType_id 
                           ORDER BY p.Product_id DESC");
$products = $stmt_prod->fetchAll();

// ดึงข้อมูลประเภทวัตถุดิบ
$stmt_mat_type = $conn->query("SELECT * FROM materialtype_db ORDER BY MaterialType_id ASC");
$mat_types = $stmt_mat_type->fetchAll();
?>
<?php include_once 'includes/header.php'; ?>

<script>
    // ส่งข้อมูลจาก PHP ไปยัง JavaScript
    window.serverData = {
        products: <?php echo json_encode($products); ?>,
        categories: <?php echo json_encode($categories); ?>
    };
</script>

<?php
    // หน้าพื้นฐานที่ทั้ง Admin และ Staff เข้าถึงได้
    include_once 'pages/pos.php';
    include_once 'pages/dashboard.php';
    include_once 'pages/customers.php';

    // หน้าเฉพาะ Admin: จำกัดการโหลดไฟล์เพื่อความปลอดภัยและประสิทธิภาพ
    if ($_SESSION['Role'] == 'admin') {
        include_once 'pages/menu.php';
        include_once 'pages/employees.php';
        include_once 'pages/reports.php';
        include_once 'pages/finance.php';
        include_once 'pages/material_types.php';
        include_once 'pages/materials.php';
        include_once 'pages/material_order.php';
        include_once 'pages/material_admit.php';
        include_once 'pages/product_types.php';
    }
    include_once 'pages/material_requisition.php'; 
?>

<?php include_once 'includes/footer.php'; ?>