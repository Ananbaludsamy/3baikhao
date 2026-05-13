<!-- ================= หน้า: ออกรายงาน ================= -->
<div id="page-reports" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-100 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
            <h4 class="font-bold text-gray-800">รายงานยอดขายประจำวัน</h4>
            <p class="text-sm text-gray-500 mt-2">สรุปรายการขายและรายได้แยกตามบิล</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-100 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <h4 class="font-bold text-gray-800">รายงานสต็อกวัตถุดิบ</h4>
            <p class="text-sm text-gray-500 mt-2">ตรวจสอบจำนวนวัตถุดิบคงเหลือและรายการเบิกใช้</p>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-100 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-2xl mb-4">
                <i class="fa-solid fa-chart-line"></i>
            </div>
            <h4 class="font-bold text-gray-800">รายงานเมนูยอดนิยม</h4>
            <p class="text-sm text-gray-500 mt-2">วิเคราะห์เมนูที่ทำกำไรและขายดีที่สุด</p>
        </div>
    </div>

    <!-- รายงานยอดขายรายเดือน -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100 mt-8">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-calendar-days mr-2 text-primary"></i>รายงานยอดขายรายเดือน</h3>
            <div class="flex items-center gap-2">
                <select id="report-month" class="p-2 border rounded-lg text-sm bg-white">
                    <?php
                    $currentMonth = date('m');
                    $months = [
                        '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน',
                        '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม',
                        '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
                    ];
                    foreach ($months as $num => $name) {
                        $selected = ($num == $currentMonth) ? 'selected' : '';
                        echo "<option value=\"$num\" $selected>$name</option>";
                    }
                    ?>
                </select>
                <select id="report-year" class="p-2 border rounded-lg text-sm bg-white">
                    <?php
                    $currentYear = date('Y');
                    for ($y = $currentYear; $y >= $currentYear - 5; $y--) {
                        $selected = ($y == $currentYear) ? 'selected' : '';
                        echo "<option value=\"$y\" $selected>$y</option>";
                    }
                    ?>
                </select>
                <button onclick="app.loadMonthlySalesReport()" class="bg-primary text-white text-sm px-4 py-2 rounded-lg font-medium hover:bg-secondary transition-colors">
                    <i class="fa-solid fa-eye mr-1"></i> ดูรายงาน
                </button>
                <a id="export-monthly-sales-btn" href="#" target="_blank" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-file-excel"></i> Export CSV
                </a>
            </div>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <p class="text-sm text-blue-700 font-medium">ยอดขายรวม</p>
                    <h4 class="text-2xl font-bold text-blue-800">₭<span id="monthly-total-sales">0</span></h4>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <p class="text-sm text-blue-700 font-medium">จำนวนบิล</p>
                    <h4 class="text-2xl font-bold text-blue-800"><span id="monthly-total-orders">0</span></h4>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg text-center">
                    <p class="text-sm text-blue-700 font-medium">เมนูขายดีที่สุด</p>
                    <h4 class="text-lg font-bold text-blue-800 truncate" id="monthly-best-seller">-</h4>
                </div>
            </div>

            <h4 class="font-bold text-gray-800 mb-3">ยอดขายแยกตามประเภทสินค้า</h4>
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <th class="p-3 border-b">ประเภทสินค้า</th>
                            <th class="p-3 border-b text-right">ยอดขาย (₭)</th>
                        </tr>
                    </thead>
                    <tbody id="monthly-sales-by-type-table" class="text-sm"></tbody>
                </table>
            </div>

            <h4 class="font-bold text-gray-800 mb-3">10 อันดับเมนูขายดีประจำเดือน</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-xs font-bold text-gray-600 uppercase tracking-wider">
                            <th class="p-3 border-b">ชื่อเมนู</th>
                            <th class="p-3 border-b text-right">จำนวนที่ขาย</th>
                            <th class="p-3 border-b text-right">รายได้ (₭)</th>
                        </tr>
                    </thead>
                    <tbody id="monthly-top-products-table" class="text-sm"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>