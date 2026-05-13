<!-- ================= หน้า: รับเข้าวัตถุดิบ ================= -->
<div id="page-material-admit" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-green-600 text-white">
                <h3 class="font-bold"><i class="fa-solid fa-truck-ramp-box mr-2"></i>บันทึกการรับเข้าวัตถุดิบ (เพิ่มสต็อก)</h3>
            </div>
            <form id="admit-form" onsubmit="app.saveAdmit(event)" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เลือกวัตถุดิบ</label>
                        <select id="admit-material-id" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-green-500 bg-white text-lg"></select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">จำนวนที่รับเข้า</label>
                        <input type="number" id="admit-qty" required min="1" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-green-500 text-lg" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ราคาซื้อต่อหน่วย (₭)</label>
                        <input type="number" id="admit-price" required min="0" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-green-500 text-lg" placeholder="0">
                    </div>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-green-700 transition-all shadow-lg active:scale-95">
                        <i class="fa-solid fa-plus-circle mr-2"></i> ยืนยันรับเข้าสต็อก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>