<!-- ================= หน้า: ระบบเบิกใช้วัตถุดิบ ================= -->
<div id="page-material-requisition" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-orange-600 text-white">
                <h3 class="font-bold"><i class="fa-solid fa-hand-holding-hand mr-2"></i>บันทึกการเบิกใช้วัตถุดิบ</h3>
            </div>
            <form id="requisition-form" onsubmit="app.saveRequisition(event)" class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เลือกวัตถุดิบที่ต้องการเบิก</label>
                        <select id="req-material-id" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-orange-500 bg-white text-lg"></select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">จำนวนที่เบิก</label>
                        <input type="number" id="req-qty" required min="1" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-orange-500 text-lg" placeholder="ระบุจำนวน">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">หมายเหตุ / เหตุผลการเบิก</label>
                    <textarea id="req-note" rows="2" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-orange-500" placeholder="เช่น เบิกไปใช้หน้าเตาประจำวัน"></textarea>
                </div>
                <div class="flex justify-end pt-4">
                    <button type="submit" class="bg-orange-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-orange-700 transition-all shadow-lg active:scale-95">
                        <i class="fa-solid fa-paper-plane mr-2"></i> ยืนยันการเบิกใช้
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>