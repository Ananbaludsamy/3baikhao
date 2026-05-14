<!-- ================= ໜ້າ: ອອກລາຍງານ ================= -->
<div id="page-reports" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <div onclick="app.showDailySalesPanel()" class="page-card p-4 flex items-center gap-4 cursor-pointer hover:shadow-md hover:border-blue-300 border border-transparent transition-all">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-file-invoice-dollar text-blue-600 text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-700 text-sm">ຍອດຂາຍລາຍວັນ</h4>
                <p class="text-xs text-slate-400 mt-0.5">ສະຫຼຸບລາຍການຂາຍແລະລາຍໄດ້ແຍກຕາມບິນ</p>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-300 ml-auto text-sm"></i>
        </div>
        <div onclick="app.showStockReportPanel()" class="page-card p-4 flex items-center gap-4 cursor-pointer hover:shadow-md hover:border-green-300 border border-transparent transition-all">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-box-archive text-green-600 text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-700 text-sm">ລາຍງານສະຕ໋ອກ</h4>
                <p class="text-xs text-slate-400 mt-0.5">ຈໍານວນຄົງເຫຼືອແລະລາຍການໃກ້ໝົດ</p>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-300 ml-auto text-sm"></i>
        </div>
        <div onclick="document.getElementById('monthly-top-products-table').closest('.page-card')?.scrollIntoView({behavior:'smooth'})" class="page-card p-4 flex items-center gap-4 cursor-pointer hover:shadow-md hover:border-orange-300 border border-transparent transition-all">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-chart-line text-orange-500 text-xl"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-700 text-sm">ເມນູຍອດນິຍົມ</h4>
                <p class="text-xs text-slate-400 mt-0.5">ວິເຄາະເມນູທີ່ຂາຍດີທີ່ສຸດ</p>
            </div>
            <i class="fa-solid fa-chevron-right text-slate-300 ml-auto text-sm"></i>
        </div>
    </div>

    <!-- Daily Sales Panel -->
    <div id="daily-sales-panel" class="page-card mb-5 hidden">
        <div class="page-card-header">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-file-invoice-dollar text-blue-600 text-sm"></i>
                </div>
                <h3 class="font-bold text-slate-700 text-sm">ຍອດຂາຍລາຍວັນ</h3>
            </div>
            <div class="flex items-center gap-2">
                <input type="date" id="daily-sales-date" class="form-input text-xs" style="width:auto;padding:7px 10px;"
                    value="<?php echo date('Y-m-d'); ?>">
                <button onclick="app.loadDailySales(document.getElementById('daily-sales-date').value)"
                    class="btn-primary" style="padding:7px 14px;font-size:12px;">
                    <i class="fa-solid fa-eye"></i> ເບິ່ງ
                </button>
                <a id="export-daily-sales-btn" href="#" target="_blank" class="btn-secondary" style="padding:7px 12px;font-size:12px;text-decoration:none;" title="Export CSV">
                    <i class="fa-solid fa-file-csv text-green-600"></i> Export
                </a>
                <button onclick="document.getElementById('daily-sales-panel').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">ຍອດຂາຍ</p>
                    <h4 class="text-xl font-black text-blue-800 mt-1">₭<span id="daily-sales-total">0</span></h4>
                </div>
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">ຈໍານວນບິນ</p>
                    <h4 class="text-xl font-black text-blue-800 mt-1"><span id="daily-sales-count">0</span></h4>
                </div>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th class="w-28">ເລກບິນ</th>
                            <th class="w-16">ໂຕະ</th>
                            <th class="w-32">ລູກຄ້າ</th>
                            <th>ລາຍການ</th>
                            <th class="w-32 text-right">ຍອດ (₭)</th>
                        </tr>
                    </thead>
                    <tbody id="daily-sales-table">
                        <tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-8">ກົດ "ເບິ່ງ" ເພື່ອໂຫຼດຂໍ້ມູນ</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stock Report Panel -->
    <div id="stock-report-panel" class="page-card mb-5 hidden">
        <div class="page-card-header">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-box-archive text-green-600 text-sm"></i>
                </div>
                <h3 class="font-bold text-slate-700 text-sm">ລາຍງານສະຕ໋ອກວັດຖຸດິບ</h3>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="app.loadStockReport()" class="btn-secondary" style="padding:6px 12px;font-size:12px;">
                    <i class="fa-solid fa-rotate"></i> ໂຫຼດໃໝ່
                </button>
                <a href="actions/reports/export_stock_csv.php" target="_blank" class="btn-secondary" style="padding:6px 12px;font-size:12px;text-decoration:none;" title="Export CSV">
                    <i class="fa-solid fa-file-csv text-green-600"></i> Export
                </a>
                <button onclick="document.getElementById('stock-report-panel').classList.add('hidden')"
                    class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div class="bg-red-50 rounded-xl p-3 text-center">
                    <p class="text-xs font-bold text-red-500 uppercase tracking-wide">ສ່ຽງໝົດ (≤5)</p>
                    <h4 class="text-xl font-black text-red-700 mt-1"><span id="stock-low-count">0</span> ລາຍການ</h4>
                </div>
                <div class="bg-yellow-50 rounded-xl p-3 text-center">
                    <p class="text-xs font-bold text-yellow-600 uppercase tracking-wide">ໃກ້ໝົດ (6-10)</p>
                    <h4 class="text-xl font-black text-yellow-700 mt-1"><span id="stock-medium-count">0</span> ລາຍການ</h4>
                </div>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th>ຊື່ວັດຖຸດິບ</th>
                            <th>ປະເພດ</th>
                            <th class="text-right">ຄົງເຫຼືອ</th>
                            <th>ຫົວໜ່ວຍ</th>
                            <th>ສະຖານະ</th>
                        </tr>
                    </thead>
                    <tbody id="stock-report-table">
                        <tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-8">ກໍາລັງໂຫຼດ...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ລາຍງານລາຍເດືອນ -->
    <div class="page-card">
        <div class="page-card-header">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-calendar-days text-primary text-sm"></i>
                </div>
                <h3 class="font-bold text-slate-700 text-sm">ລາຍງານຍອດຂາຍລາຍເດືອນ</h3>
            </div>
            <div class="flex items-center gap-2">
                <select id="report-month" class="form-input text-xs" style="width:auto;padding:7px 10px;">
                    <?php
                    $currentMonth = date('m');
                    $months = [
                        '01'=>'ມັງກອນ','02'=>'ກຸມພາ','03'=>'ມີນາ','04'=>'ເມສາ',
                        '05'=>'ພຶດສະພາ','06'=>'ມິຖຸນາ','07'=>'ກໍລະກົດ','08'=>'ສິງຫາ',
                        '09'=>'ກັນຍາ','10'=>'ຕຸລາ','11'=>'ພະຈິກ','12'=>'ທັນວາ'
                    ];
                    foreach ($months as $num => $name) {
                        $selected = ($num == $currentMonth) ? 'selected' : '';
                        echo "<option value=\"$num\" $selected>$name</option>";
                    }
                    ?>
                </select>
                <select id="report-year" class="form-input text-xs" style="width:auto;padding:7px 10px;">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                        $selected = ($y == $currentYear) ? 'selected' : '';
                        echo "<option value=\"$y\" $selected>$y</option>";
                    }
                    ?>
                </select>
                <button onclick="app.loadMonthlySalesReport()" class="btn-primary" style="padding:7px 14px;font-size:12px;">
                    <i class="fa-solid fa-eye"></i> ເບິ່ງລາຍງານ
                </button>
                <a id="export-monthly-sales-btn" href="#" target="_blank" class="btn-secondary" style="padding:7px 12px;font-size:12px;text-decoration:none;">
                    <i class="fa-solid fa-file-excel text-green-600"></i> Export
                </a>
            </div>
        </div>

        <div class="p-4">
            <!-- KPI ສະຫຼຸບ -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                <div style="background:#eff6ff;border-radius:12px;padding:16px;text-align:center;">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">ຍອດຂາຍລວມ</p>
                    <h4 class="text-2xl font-black text-blue-800 mt-1">₭<span id="monthly-total-sales">0</span></h4>
                </div>
                <div style="background:#eff6ff;border-radius:12px;padding:16px;text-align:center;">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">ຈໍານວນບິນ</p>
                    <h4 class="text-2xl font-black text-blue-800 mt-1"><span id="monthly-total-orders">0</span></h4>
                </div>
                <div style="background:#eff6ff;border-radius:12px;padding:16px;text-align:center;">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wide">ເມນູຂາຍດີທີ່ສຸດ</p>
                    <h4 class="text-base font-black text-blue-800 mt-1 truncate" id="monthly-best-seller">-</h4>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- ຍອດຂາຍແຍກປະເພດ -->
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">ຍອດຂາຍແຍກຕາມປະເພດສິນຄ້າ</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="table-header">
                                    <th>ປະເພດສິນຄ້າ</th>
                                    <th class="text-right">ຍອດຂາຍ (₭)</th>
                                </tr>
                            </thead>
                            <tbody id="monthly-sales-by-type-table"></tbody>
                        </table>
                    </div>
                </div>

                <!-- 10 ອັນດັບເມນູຂາຍດີ -->
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-3">10 ອັນດັບເມນູຂາຍດີປະຈໍາເດືອນ</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="table-header">
                                    <th>ຊື່ເມນູ</th>
                                    <th class="text-right">ຈໍານວນ</th>
                                    <th class="text-right">ລາຍໄດ້ (₭)</th>
                                </tr>
                            </thead>
                            <tbody id="monthly-top-products-table"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
