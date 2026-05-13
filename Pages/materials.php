<!-- ================= หน้า: ข้อมูลสต็อกวัตถุดิบ ================= -->
<div id="page-materials" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- ฟอร์มเพิ่มวัตถุดิบ -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-0">
                <div class="p-4 border-b border-gray-200 bg-primary text-white">
                    <h3 class="font-bold" id="mat-form-title"><i class="fa-solid fa-box-open mr-2"></i>เพิ่มวัตถุดิบใหม่</h3>
                </div>
                <form id="add-material-form" onsubmit="app.saveMaterial(event)" class="p-4 space-y-4">
                    <input type="hidden" id="edit-mat-id">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อวัตถุดิบ</label>
                        <input type="text" id="mat-name" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="เช่น เส้นเล็ก, เนื้อหมู">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ประเภท</label>
                        <select id="mat-type" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary bg-white">
                            <?php foreach($mat_types as $mt): ?>
                                <option value="<?php echo $mt['MaterialType_id']; ?>"><?php echo htmlspecialchars($mt['MaterialType_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ราคาต้นทุน (₭)</label>
                            <input type="number" id="mat-cost" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" value="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">หน่วย</label>
                            <input type="text" id="mat-unit" required class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="กิโล, ห่อ">
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" id="mat-submit-btn" class="flex-1 bg-primary text-white py-2 rounded-lg font-bold hover:bg-secondary transition-colors mt-2">บันทึกข้อมูล</button>
                        <button type="button" id="mat-cancel-btn" onclick="app.resetMaterialForm()" class="hidden flex-none bg-gray-200 text-gray-700 py-2 px-4 rounded-lg font-bold hover:bg-gray-300 mt-2">ยกเลิก</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- ตารางแสดงสต็อก -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-800">รายการวัตถุดิบและสต็อกปัจจุบัน</h3>
                    <button onclick="app.loadMaterials()" class="text-primary hover:text-secondary"><i class="fa-solid fa-rotate"></i> รีเฟรช</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead><tr class="bg-gray-100 text-sm">
                            <th class="p-3 border-b">ชื่อวัตถุดิบ</th>
                            <th class="p-3 border-b text-right">จำนวนคงเหลือ</th>
                            <th class="p-3 border-b">หน่วย</th>
                            <th class="p-3 border-b text-right">ต้นทุน/หน่วย</th>
                        </tr></thead>
                        <tbody id="material-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>