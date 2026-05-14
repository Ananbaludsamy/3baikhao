<?php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login() {
    if (!isset($_SESSION['Emp_id'])) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'ກະລຸນາເຂົ້າສູ່ລະບົບກ່ອນ']);
        exit;
    }
}

function require_admin() {
    require_login();
    if (($_SESSION['Role'] ?? '') !== 'admin') {
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'ບໍ່ມີສິດໃຊ້ງານ — ສະຫງວນໄວ້ສໍາລັບ Admin']);
        exit;
    }
}
