<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ລະບົບຈັດການ - ຮ້ານເຝີເຮືອ 3ໃບເຂົາ</title>
    <link href="dist/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Phetsarath:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Phetsarath', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .form-input { width:100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 15px; outline: none; transition: border-color .2s, box-shadow .2s; background: white; font-family: 'Phetsarath', sans-serif; color: #1e293b; }
        .form-input:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,.12); }
        .form-label { display:block; font-size:12px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px; }
        .btn-primary { background:#7c2d12; color:white; font-weight:700; padding:10px 20px; border-radius:12px; transition:all .2s; font-size:14px; display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:'Phetsarath',sans-serif; }
        .btn-primary:hover { background:#b45309; }
        .btn-secondary { background:#f1f5f9; color:#334155; font-weight:700; padding:10px 16px; border-radius:12px; transition:all .2s; font-size:14px; display:inline-flex; align-items:center; gap:6px; border:none; cursor:pointer; font-family:'Phetsarath',sans-serif; }
        .btn-secondary:hover { background:#e2e8f0; }
        .page-card { background:white; border-radius:20px; border: 1px solid #f1f5f9; box-shadow: 0 1px 4px rgba(0,0,0,.04); overflow:hidden; }
        .page-card-header { padding:16px 20px; border-bottom: 1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; }
        .table-header th { padding:12px 16px; font-size:12px; font-weight:700; color:#475569; text-transform:uppercase; letter-spacing:.05em; border-bottom: 1px solid #f1f5f9; text-align:left; white-space:nowrap; }
        .table-row td { padding:14px 16px; font-size:14px; border-bottom: 1px solid #f8fafc; color:#334155; white-space:nowrap; }
        table { border-collapse:collapse; }
        .table-row:hover { background:#fafafa; }
        .table-row:last-child td { border-bottom: none; }
        .badge-admin { background:#f3e8ff; color:#6d28d9; font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; }
        .badge-staff { background:#dbeafe; color:#1d4ed8; font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; }
        .badge-general { background:#f1f5f9; color:#475569; font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; }
        .badge-gold { background:#fef9c3; color:#92400e; font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; }
        .badge-vip { background:#f5f3ff; color:#5b21b6; font-size:11px; font-weight:700; padding:3px 9px; border-radius:20px; }
    </style>
    <script>window.app = window.app || {};</script>
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen flex">

<!-- ===== SIDEBAR ===== -->
<aside class="w-14 md:w-60 bg-primary flex flex-col flex-shrink-0 z-30 overflow-hidden shadow-xl">

    <!-- Brand -->
    <div class="flex items-center gap-3 px-3 py-4 border-b border-white/10">
        <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center text-primary font-black text-lg flex-shrink-0 shadow">
            <i class="fa-solid fa-bowl-food"></i>
        </div>
        <div class="hidden md:block leading-tight">
            <p class="text-white font-black text-sm">ຮ້ານເຝີເຮືອ</p>
            <p class="text-orange-200 text-xs">3ໃບເຂົາ</p>
        </div>
    </div>

    <!-- User Info -->
    <div class="hidden md:flex items-center gap-2.5 px-3 py-3 border-b border-white/10 bg-black/10">
        <div class="w-8 h-8 bg-orange-400 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
            <?php echo mb_strtoupper(mb_substr($_SESSION['Emp_name'], 0, 1, 'UTF-8')); ?>
        </div>
        <div class="min-w-0 flex-1">
            <p class="text-white text-xs font-bold truncate"><?php echo htmlspecialchars($_SESSION['Emp_name']); ?></p>
            <?php if ($_SESSION['Role'] == 'admin'): ?>
                <span class="badge-admin mt-0.5 inline-block">⚙ ຜູ້ດູແລ</span>
            <?php else: ?>
                <span class="badge-staff mt-0.5 inline-block">👤 ພະນັກງານ</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Nav -->
    <nav class="flex-1 overflow-y-auto no-scrollbar px-2 py-3 space-y-0.5">

        <!-- MAIN -->
        <p class="hidden md:block text-[9px] font-black text-orange-300/60 uppercase tracking-widest px-2 pt-2 pb-1">ຫຼັກ</p>
        <button onclick="app.switchPage('pos')" id="nav-pos"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/20 text-white font-bold text-sm">
            <i class="fa-solid fa-cash-register w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ຂາຍອາຫານ (POS)</span>
        </button>
        <button onclick="app.switchPage('dashboard')" id="nav-dashboard"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block"><?php echo ($_SESSION['Role'] == 'admin') ? 'ສະຫຼຸບຍອດຂາຍ' : 'ລາຍການລໍຖ້າເສີບ'; ?></span>
        </button>
        <button onclick="app.switchPage('customers')" id="nav-customers"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-users w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ຂໍ້ມູນລູກຄ້າ</span>
        </button>

        <?php if ($_SESSION['Role'] == 'staff'): ?>
        <button onclick="app.switchPage('material-requisition')" id="nav-material-requisition"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-hand-holding-hand w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ເບີກໃຊ້ວັດຖຸດິບ</span>
        </button>
        <?php endif; ?>

        <?php if ($_SESSION['Role'] == 'admin'): ?>
        <!-- MASTER DATA -->
        <p class="hidden md:block text-[9px] font-black text-orange-300/60 uppercase tracking-widest px-2 pt-4 pb-1">ຂໍ້ມູນຫຼັກ</p>
        <button onclick="app.switchPage('menu')" id="nav-menu"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-utensils w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ຈັດການເມນູ</span>
        </button>
        <button onclick="app.switchPage('product-types')" id="nav-product-types"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-tags w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ປະເພດສິນຄ້າ</span>
        </button>
        <button onclick="app.switchPage('material-types')" id="nav-material-types"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ປະເພດວັດຖຸດິບ</span>
        </button>
        <button onclick="app.switchPage('employees')" id="nav-employees"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-id-card w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ຂໍ້ມູນພະນັກງານ</span>
        </button>

        <!-- INVENTORY -->
        <p class="hidden md:block text-[9px] font-black text-orange-300/60 uppercase tracking-widest px-2 pt-4 pb-1">ສາງສິນຄ້າ</p>
        <button onclick="app.switchPage('materials')" id="nav-materials"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-box-open w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ສະຕ໋ອກວັດຖຸດິບ</span>
        </button>
        <button onclick="app.switchPage('material-order')" id="nav-material-order"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-cart-plus w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ສັ່ງຊື້ວັດຖຸດິບ</span>
        </button>
        <button onclick="app.switchPage('material-admit')" id="nav-material-admit"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-truck-ramp-box w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ຮັບເຂົ້າວັດຖຸດິບ</span>
        </button>
        <button onclick="app.switchPage('material-requisition')" id="nav-material-requisition"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-hand-holding-hand w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ເບີກໃຊ້ວັດຖຸດິບ</span>
        </button>

        <!-- FINANCE -->
        <p class="hidden md:block text-[9px] font-black text-orange-300/60 uppercase tracking-widest px-2 pt-4 pb-1">ການເງິນ & ລາຍງານ</p>
        <button onclick="app.switchPage('finance')" id="nav-finance"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-money-bill-transfer w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ລາຍຮັບ - ລາຍຈ່າຍ</span>
        </button>
        <button onclick="app.switchPage('reports')" id="nav-reports"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-file-contract w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ລາຍງານ</span>
        </button>

        <!-- SETTINGS -->
        <p class="hidden md:block text-[9px] font-black text-orange-300/60 uppercase tracking-widest px-2 pt-4 pb-1">ລະບົບ</p>
        <button onclick="app.switchPage('settings')" id="nav-settings"
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors">
            <i class="fa-solid fa-gear w-5 text-center flex-shrink-0"></i>
            <span class="hidden md:block">ຕັ້ງຄ່າລະບົບ</span>
        </button>
        <?php endif; ?>

    </nav>

    <!-- Logout -->
    <a href="logout.php" class="flex items-center gap-3 px-3 py-3.5 border-t border-white/10 text-orange-200 hover:bg-red-900/50 hover:text-white transition-all">
        <i class="fa-solid fa-right-from-bracket w-5 text-center flex-shrink-0"></i>
        <span class="hidden md:block text-sm font-medium">ອອກຈາກລະບົບ</span>
    </a>
</aside>

<!-- ===== MAIN ===== -->
<main class="flex-1 flex flex-col min-h-screen overflow-x-hidden">

    <!-- Top Header -->
    <header class="bg-white border-b border-slate-100 px-4 sm:px-6 py-3 flex justify-between items-center z-10 flex-shrink-0">
        <h2 id="page-title" class="text-base sm:text-lg font-bold text-slate-800 truncate">ຂາຍອາຫານ (POS)</h2>
        <div class="flex items-center gap-3 ml-2">
            <!-- Stock Alert Bell -->
            <div class="relative" id="stock-alert-wrapper">
                <button onclick="app.toggleStockAlert()" id="stock-alert-btn"
                    class="relative w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-xl flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-bell text-slate-500 text-sm" id="stock-bell-icon"></i>
                    <span id="stock-alert-badge"
                        style="display:none;position:absolute;top:-5px;right:-5px;min-width:18px;height:18px;background:#ef4444;color:white;font-size:10px;font-weight:900;border-radius:9999px;align-items:center;justify-content:center;padding:0 4px;line-height:1;white-space:nowrap;"></span>
                </button>
                <!-- Dropdown -->
                <div id="stock-alert-dropdown"
                    class="hidden absolute right-0 top-11 w-72 bg-white rounded-2xl shadow-2xl border border-slate-100 z-50 overflow-hidden">
                    <div id="stock-alert-content">
                        <div class="p-4 text-center text-slate-400 text-sm">ກໍາລັງໂຫຼດ...</div>
                    </div>
                </div>
            </div>
            <div class="text-slate-500 text-xs sm:text-sm whitespace-nowrap font-medium" id="current-time"></div>
        </div>
    </header>

    <!-- Page Container -->
    <div id="content-container" class="flex-1 relative w-full overflow-hidden bg-slate-100">
