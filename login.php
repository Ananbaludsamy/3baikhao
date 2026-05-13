<?php session_start(); ?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - 3ใบเขา</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); }
    </style>
</head>
<body class="h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
        <div class="text-center mb-8">
            <img src="./assets/img/3baikhao_logo.jpg" alt="Logo" class="w-32 h-auto mx-auto mb-4 rounded-lg">
            <h1 class="text-2xl font-bold text-gray-800">ระบบจัดการร้าน 3ใบเขา</h1>
            <p class="text-gray-500">กรุณาเข้าสู่ระบบเพื่อใช้งาน</p>
        </div>

        <?php if(isset($_SESSION['login_error'])): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-4 text-sm">
                <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="actions/auth_login.php" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้งาน (Username)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" required class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Username">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน (Password)</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" required class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none" placeholder="••••••••">
                </div>
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all shadow-lg active:scale-95">
                เข้าสู่ระบบ <i class="fas fa-sign-in-alt ml-2"></i>
            </button>
        </form>
    </div>
</body>
</html>