<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบริหารจัดการ ร้านกล้วยเตียวเรือ3ใบเขา (XAMPP Version)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Prompt', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <script>
        // จองพื้นที่ให้ Object app ทันทีเพื่อให้ปุ่ม onclick ทำงานได้โดยไม่เกิด Error
        window.app = window.app || {};
    </script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: '#7c2d12', secondary: '#b45309', } } }
        }
    </script>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen overflow-x-hidden flex relative">

    <!-- Sidebar / เมนูนำทาง -->
    <aside class="w-20 md:w-64 bg-primary text-white flex flex-col transition-all duration-300 flex-shrink-0 z-30 overflow-y-auto">
        <div class="p-4 flex items-center justify-center md:justify-start gap-3 border-b border-orange-900/50">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-primary font-bold text-xl flex-shrink-0 shadow-lg">
                <i class="fa-solid fa-bowl-food"></i>
            </div>
            <h1 class="font-bold text-lg hidden md:block leading-tight">ร้านกล้วยเตียวเรือ<br>3ใบเขา</h1>
        </div>
        
        <nav class="flex-1 mt-4 overflow-y-auto no-scrollbar">
            <ul class="space-y-2 px-2">
                <?php if ($_SESSION['Role'] == 'admin'): ?>
                    <li class="text-[10px] uppercase text-orange-300/50 font-bold px-3 mt-4 mb-1 hidden md:block">Main POS</li>
                <?php endif; ?>
                
                <li>
                    <button onclick="app.switchPage('pos')" id="nav-pos" class="w-full flex items-center p-3 rounded-lg bg-orange-900/50 text-white transition-colors">
                        <i class="fa-solid fa-cash-register w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">ขายอาหาร (POS)</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('dashboard')" id="nav-dashboard" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-chart-pie w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block"><?php echo ($_SESSION['Role'] == 'admin') ? 'สรุปยอดขาย' : 'รายการรอเสิร์ฟ'; ?></span>
                    </button>
                </li>

                <?php if ($_SESSION['Role'] == 'staff'): ?>
                <li>
                    <button onclick="app.switchPage('material-requisition')" id="nav-material-requisition" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-hand-holding-hand w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">เบิกใช้วัตถุดิบ</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('customers')" id="nav-customers" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-users w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">ข้อมูลลูกค้า</span>
                    </button>
                </li>
                <?php endif; ?>
                
                <?php if ($_SESSION['Role'] == 'admin'): ?>
                <!-- กลุ่มข้อมูลพื้นฐาน -->
                <li class="text-[10px] uppercase text-orange-300/50 font-bold px-3 mt-4 mb-1 hidden md:block">Master Data</li>
                <li>
                    <button onclick="app.switchPage('menu')" id="nav-menu" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-utensils w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">จัดการเมนู</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('product-types')" id="nav-product-types" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-tags w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">ประเภทสินค้า</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('material-types')" id="nav-material-types" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-boxes-stacked w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">ประเภทวัตถุดิบ</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('employees')" id="nav-employees" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-id-card w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">ข้อมูลพนักงาน</span>
                    </button>
                </li>

                <!-- กลุ่มวัตถุดิบ -->
                <li class="text-[10px] uppercase text-orange-300/50 font-bold px-3 mt-4 mb-1 hidden md:block">Inventory</li>
                <li>
                    <button onclick="app.switchPage('materials')" id="nav-materials" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-box-open w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">สต็อกวัตถุดิบ</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('material-order')" id="nav-material-order" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-cart-plus w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">สั่งซื้อวัตถุดิบ</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('material-admit')" id="nav-material-admit" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-truck-ramp-box w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">รับเข้าวัตถุดิบ</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('material-requisition')" id="nav-material-requisition" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-hand-holding-hand w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">เบิกใช้วัตถุดิบ</span>
                    </button>
                </li>

                <!-- กลุ่มการเงินและรายงาน -->
                <li class="text-[10px] uppercase text-orange-300/50 font-bold px-3 mt-4 mb-1 hidden md:block">Finance & Reports</li>
                <li>
                    <button onclick="app.switchPage('finance')" id="nav-finance" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-money-bill-transfer w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">รายรับ - รายจ่าย</span>
                    </button>
                </li>
                <li>
                    <button onclick="app.switchPage('reports')" id="nav-reports" class="w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors">
                        <i class="fa-solid fa-file-contract w-6 text-center text-xl"></i>
                        <span class="ml-3 hidden md:block">ออกรายงาน</span>
                    </button>
                </li>
                <?php endif; ?>

            </ul>
        </nav>
        
        <a href="logout.php" class="p-4 border-t border-orange-900/50 text-orange-200 hover:bg-red-800 hover:text-white transition-all flex items-center justify-center md:justify-start gap-3">
            <i class="fa-solid fa-right-from-bracket text-xl w-6 text-center"></i>
            <span class="hidden md:block font-bold text-sm">ออกจากระบบ</span>
        </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col w-full min-h-screen overflow-x-hidden relative">
        
        <!-- Header -->
        <header class="bg-white shadow-sm px-3 sm:px-4 py-2 sm:py-4 flex justify-between items-center z-10 flex-shrink-0">
            <h2 id="page-title" class="text-lg sm:text-xl font-bold text-gray-800 truncate">จุดรับออร์เดอร์ (POS)</h2>
            <div class="text-gray-500 text-xs sm:text-sm md:text-base whitespace-nowrap ml-2" id="current-time">
                <!-- เวลาจะแสดงที่นี่ -->
            </div>
        </header>

        <!-- Container สำหรับแต่ละหน้า -->
        <div id="content-container" class="flex-1 relative w-full h-full overflow-hidden bg-gray-100">