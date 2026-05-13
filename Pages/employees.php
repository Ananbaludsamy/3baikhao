<!-- ================= หน้า: ข้อมูลพนักงาน ================= -->
<div id="page-employees" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- ฟอร์มเพิ่ม/แก้ไขพนักงาน -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-0">
                <div class="p-4 border-b border-gray-200 bg-primary text-white">
                    <h3 class="font-bold" id="employee-form-title"><i class="fa-solid fa-user-plus mr-2"></i>เพิ่มพนักงานใหม่</h3>
                </div>
                <form id="employee-form" onsubmit="app.saveEmployee(event)" class="p-4 space-y-4">
                    <input type="hidden" id="emp-id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อ-นามสกุล</label>
                        <input type="text" id="emp-name" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="ชื่อพนักงาน">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รหัสบัตรประชาชน</label>
                        <input type="text" id="emp-card" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="รหัสบัตรประชาชน">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ที่อยู่</label>
                        <input type="text" id="emp-address" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="ที่อยู่">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" id="emp-tel" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="เบอร์โทรศัพท์">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เพศ</label>
                        <select id="emp-gender" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary bg-white">
                            <option value="">-- เลือกเพศ --</option>
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                            <option value="อื่นๆ">อื่นๆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อผู้ใช้งาน (Username)</label>
                        <input type="text" id="emp-username" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="Username สำหรับเข้าสู่ระบบ">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">รหัสผ่าน (Password)</label>
                        <input type="password" id="emp-password" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="กรอกเมื่อต้องการเปลี่ยนรหัสผ่าน">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">สิทธิ์การใช้งาน (Role)</label>
                        <select id="emp-role" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary bg-white">
                            <option value="staff">พนักงาน</option>
                            <option value="admin">ผู้ดูแลระบบ</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-primary text-white py-2 rounded-lg font-bold hover:bg-secondary transition-colors mt-2" id="employee-submit-btn">บันทึกพนักงาน</button>
                        <button type="button" onclick="app.resetEmployeeForm()" class="flex-none bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-bold hover:bg-gray-300 transition-colors mt-2">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- ตารางแสดงพนักงาน -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">รายชื่อพนักงานทั้งหมด</h3>
                    <button onclick="app.loadEmployees()" class="text-primary hover:text-secondary"><i class="fa-solid fa-rotate"></i> รีเฟรช</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead><tr class="bg-gray-100 text-sm">
                            <th class="p-3 border-b">ชื่อพนักงาน</th><th class="p-3 border-b">เบอร์โทร</th><th class="p-3 border-b">Username</th><th class="p-3 border-b">สิทธิ์</th><th class="p-3 border-b text-center">จัดการ</th>
                        </tr></thead>
                        <tbody id="employee-table-body">
                            <!-- ข้อมูลจะถูกโหลดโดย JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>