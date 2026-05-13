<!-- ================= หน้า: รายรับ - รายจ่าย ================= -->
<div id="page-finance" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                <h3 class="text-gray-500 font-medium">สรุปรายรับ (เดือนนี้)</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">₭<span id="fin-revenue">0</span></p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-500">
                <h3 class="text-gray-500 font-medium">สรุปรายจ่าย (เดือนนี้)</h3>
                <p class="text-3xl font-bold text-red-600 mt-2">₭<span id="fin-expense">0</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- ฟอร์มบันทึก -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-primary text-white">
                        <h3 class="font-bold"><i class="fa-solid fa-hand-holding-dollar mr-2"></i>บันทึก รายรับ/รายจ่าย</h3>
                    </div>
                    <form onsubmit="app.saveFinance(event)" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ประเภท</label>
                            <select id="fin-type" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary bg-white">
                                <option value="1">รายรับ (อื่นๆ)</option>
                                <option value="2">รายจ่าย</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">รายการ</label>
                            <input type="text" id="fin-name" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="เช่น ค่าน้ำ, ค่าเช่าที่">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนเงิน (กีบ)</label>
                            <input type="number" id="fin-amount" required min="1" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="0">
                        </div>
                        <button type="submit" class="w-full bg-primary text-white py-2 rounded-lg font-bold hover:bg-secondary transition-colors">บันทึกข้อมูล</button>
                    </form>
                </div>
            </div>

            <!-- ตารางประวัติ -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">ประวัติการทำรายการล่าสุด</h3>
                        <a href="actions/export_finance_csv.php" target="_blank" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg font-medium transition-colors flex items-center gap-2">
                            <i class="fa-solid fa-file-excel"></i> Export CSV
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead><tr class="bg-gray-100 text-sm">
                                <th class="p-3 border-b">วันที่</th><th class="p-3 border-b">รายการ</th><th class="p-3 border-b text-right">จำนวนเงิน</th>
                            </tr></thead>
                            <tbody id="finance-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>