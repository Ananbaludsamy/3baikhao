<!-- ================= หน้า: จัดการเมนู ================= -->
<div id="page-menu" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="p-4 border-b border-gray-200 bg-primary text-white">
            <h3 class="font-bold" id="product-form-title"><i class="fa-solid fa-plus-circle mr-2"></i>เพิ่มเมนูใหม่เข้าร้าน</h3>
        </div>
        <form id="add-product-form" onsubmit="app.saveProduct(event)" class="p-4 lg:p-6">
            <input type="hidden" id="edit-product-id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อเมนู</label>
                    <input type="text" id="new-item-name" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary outline-none transition" placeholder="เช่น ก๋วยเตี๋ยวเนื้อเปื่อย">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ราคา (กีบ)</label>
                    <input type="number" id="new-item-price" required min="1" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary outline-none transition" placeholder="เช่น 50000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่</label>
                    <select id="new-item-cat" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary outline-none transition bg-white">
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['ProductType_id']; ?>"><?php echo htmlspecialchars($cat['ProductType_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รูปภาพสินค้า</label>
                    <input type="file" id="new-item-img" accept="image/*" class="w-full p-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary outline-none transition bg-white text-sm">
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" id="product-submit-btn" class="bg-primary hover:bg-secondary text-white font-medium py-2 px-6 rounded-lg shadow transition-colors flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> บันทึกเมนู
                </button>
                <button type="button" id="product-cancel-btn" onclick="app.resetProductForm()" class="hidden ml-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-2 px-6 rounded-lg transition-colors">
                    ยกเลิก
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden pb-10">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <h3 class="font-bold text-gray-800"><i class="fa-solid fa-list mr-2"></i>รายการเมนูในระบบ</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm">
                        <th class="p-3 border-b w-16">รูป</th>
                        <th class="p-3 border-b">ชื่อเมนู</th>
                        <th class="p-3 border-b">หมวดหมู่</th>
                        <th class="p-3 border-b">ราคา</th>
                        <th class="p-3 border-b text-center w-24">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="menu-mgmt-table"></tbody>
            </table>
        </div>
    </div>
</div>