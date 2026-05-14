<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['emp_id']) || empty($data['name']) || empty($data['card']) || empty($data['address']) || empty($data['tel']) || empty($data['gender']) || empty($data['username']) || empty($data['role'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    $emp_id = $data['emp_id'];
    $name = $data['name'];
    $card = $data['card'];
    $address = $data['address'];
    $tel = $data['tel'];
    $gender = $data['gender'];
    $username = $data['username'];
    $role = $data['role'];
    $password = $data['password'] ?? null; // Password is optional for update

    if ($password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE employee_db SET Emp_name = ?, Emp_card = ?, Emp_Address = ?, Emp_Tel = ?, Emp_gender = ?, Username = ?, Password = ?, Role = ? WHERE Emp_id = ?");
        $stmt->execute([$name, $card, $address, $tel, $gender, $username, $hashed_password, $role, $emp_id]);
    } else {
        $stmt = $conn->prepare("UPDATE employee_db SET Emp_name = ?, Emp_card = ?, Emp_Address = ?, Emp_Tel = ?, Emp_gender = ?, Username = ?, Role = ? WHERE Emp_id = ?");
        $stmt->execute([$name, $card, $address, $tel, $gender, $username, $role, $emp_id]);
    }

    echo json_encode(['success' => true, 'message' => 'อัปเดตข้อมูลพนักงานสำเร็จ']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}