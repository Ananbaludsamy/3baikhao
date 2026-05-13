<!-- ================= หน้า: ข้อมูลลูกค้า ================= -->
<div id="page-customers" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- ฟอร์มเพิ่ม/แก้ไขลูกค้า -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-0">
                <div class="p-4 border-b border-gray-200 bg-primary text-white">
                    <h3 class="font-bold" id="customer-form-title"><i class="fa-solid fa-user-plus mr-2"></i>เพิ่มลูกค้าใหม่</h3>
                </div>
                <form id="customer-form" onsubmit="app.saveCustomer(event)" class="p-4 space-y-4">
                    <input type="hidden" id="cus-id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อลูกค้า</label>
                        <input type="text" id="cus-name" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="ชื่อ-นามสกุล">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ระดับสมาชิก</label>
                        <select id="cus-level" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary bg-white">
                            <option value="General">General (ทั่วไป)</option>
                            <option value="VIP">VIP (ส่วนลด 5%)</option>
                            <option value="Gold">Gold VIP (ส่วนลด 10%)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" id="cus-tel" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="เบอร์โทรศัพท์">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่</label>
                        <textarea id="cus-address" rows="3" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="ที่อยู่ลูกค้า"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-primary text-white py-2 rounded-lg font-bold hover:bg-secondary transition-colors mt-2" id="customer-submit-btn">บันทึกข้อมูล</button>
                        <button type="button" onclick="app.resetCustomerForm()" class="flex-none bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-bold hover:bg-gray-300 transition-colors mt-2">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- ตารางแสดงลูกค้า -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">รายชื่อลูกค้าทั้งหมด</h3>
                    <button onclick="app.loadCustomers()" class="text-primary hover:text-secondary"><i class="fa-solid fa-rotate"></i> รีเฟรช</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead><tr class="bg-gray-100 text-sm">
                            <th class="p-3 border-b">รหัส</th>
                            <th class="p-3 border-b">ชื่อลูกค้า</th>
                            <th class="p-3 border-b">ระดับ</th>
                            <th class="p-3 border-b text-right">คะแนน</th>
                            <th class="p-3 border-b">เบอร์โทร</th>
                            <th class="p-3 border-b">ที่อยู่</th>
                            <th class="p-3 border-b text-center">จัดการ</th>
                        </tr></thead>
                        <tbody id="customer-table-body">
                            <!-- ข้อมูลจะถูกโหลดโดย JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>