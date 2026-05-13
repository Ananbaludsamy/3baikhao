<?php
// ดึงข้อมูลวัตถุดิบทั้งหมดเพื่อใช้ใน Dropdown ของฟอร์มสั่งซื้อ
$stmt_all_mat = $conn->query("SELECT * FROM material_db ORDER BY Material_name ASC");
$all_materials = $stmt_all_mat->fetchAll();
?>

<!-- ================= หน้า: สั่งซื้อวัตถุดิบ ================= -->
<div id="page-material-order" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-6xl mx-auto">
        
        <!-- ส่วนหัวหน้าจอ และปุ่มเพิ่มรายการ -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fa-solid fa-cart-plus mr-3 text-blue-600"></i>รายการสั่งซื้อวัตถุดิบ
            </h2>
            <button onclick="app.showOrderForm()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg transition-all flex items-center gap-2 active:scale-95">
                <i class="fa-solid fa-plus-circle"></i> สร้างใบสั่งซื้อใหม่
            </button>
        </div>

        <!-- ฟอร์มสร้างใบสั่งซื้อ (ซ่อนไว้เริ่มต้น) -->
        <div id="order-form-container" class="hidden bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-8 transition-all">
            <div class="p-4 border-b border-gray-100 bg-blue-50/50 flex justify-between items-center">
                <h3 class="font-bold text-blue-800"><i class="fa-solid fa-file-invoice mr-2"></i>รายละเอียดใบสั่งซื้อ</h3>
                <button onclick="app.hideOrderForm()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times text-xl"></i></button>
            </div>
            <form id="material-order-form" onsubmit="app.saveMaterialOrder(event)" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">เลขที่ใบสั่งซื้อ (อัตโนมัติ)</label>
                        <input type="text" readonly class="w-full p-2.5 bg-gray-50 border rounded-lg text-gray-500" placeholder="PO-XXXXXX">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ผู้สั่งซื้อ</label>
                        <!-- ดึงชื่อจาก Session มาแสดง และซ่อนรหัสพนักงานไว้ส่งไปบันทึก -->
                        <input type="text" readonly class="w-full p-2.5 bg-gray-50 border rounded-lg text-gray-500" value="<?php echo $_SESSION['Emp_name'] ?? 'ไม่ได้ระบุ'; ?>">
                        <input type="hidden" id="order-emp-id" value="<?php echo $_SESSION['Emp_id'] ?? ''; ?>">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">วันที่สั่งซื้อ</label>
                        <input type="date" id="order-date" required class="w-full p-2.5 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <!-- ตารางรายการสินค้าที่จะสั่ง -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-bold text-gray-700 text-sm">รายการวัตถุดิบ</h4>
                        <button type="button" onclick="app.addOrderRow()" class="text-blue-600 hover:text-blue-800 text-sm font-bold flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i> เพิ่มแถว
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase">
                                    <th class="p-3 border-b">วัตถุดิบ</th>
                                    <th class="p-3 border-b w-32">จำนวน</th>
                                    <th class="p-3 border-b w-40">ราคาต่อหน่วย (₭)</th>
                                    <th class="p-3 border-b w-40 text-right">รวม</th>
                                    <th class="p-3 border-b w-16"></th>
                                </tr>
                            </thead>
                            <tbody id="order-items-body"></tbody>
                        </table>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-end gap-6 border-t pt-6">
                    <div class="w-full md:w-1/2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมายเหตุเพิ่มเติม</label>
                        <textarea id="order-note" rows="2" class="w-full p-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="เช่น สั่งด่วน, ระบุยี่ห้อสินค้า..."></textarea>
                    </div>
                    <div class="w-full md:w-1/3 space-y-2">
                        <div class="flex justify-between text-gray-600">
                            <span>ยอดรวมก่อนภาษี:</span>
                            <span id="order-subtotal">0 ₭</span>
                        </div>
                        <div class="flex justify-between text-2xl font-black text-blue-600">
                            <span>ราคาสุทธิ:</span>
                            <span id="order-grand-total">0 ₭</span>
                        </div>
                        <div class="flex gap-2 pt-2">
                            <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-bold shadow-md transition-all active:scale-95">
                                <i class="fa-solid fa-save mr-2"></i> บันทึกใบสั่งซื้อ
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- ตารางแสดงประวัติการสั่งซื้อ -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-200">
            <div class="p-4 border-b border-gray-200 bg-blue-600 text-white">
                <h3 class="font-bold"><i class="fa-solid fa-list-check mr-2"></i>ประวัติใบสั่งซื้อทั้งหมด</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-100 text-sm font-bold text-gray-600">
                            <th class="p-4 border-b">เลขที่ PO</th>
                            <th class="p-4 border-b">วันที่</th>
                            <th class="p-4 border-b">ผู้สั่งซื้อ</th>
                            <th class="p-4 border-b text-right">ยอดเงินรวม</th>
                            <th class="p-4 border-b text-center">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="order-list-table" class="text-sm">
                        <tr><td colspan="5" class="p-10 text-center text-gray-400 italic">ยังไม่มีประวัติการสั่งซื้อ</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Ensure the app namespace exists
    window.app = window.app || {};

    window.materialOptionsHTML = `
        <option value="">-- เลือกวัตถุดิบ --</option>
        <?php foreach($all_materials as $mat): ?>
            <option value="<?php echo $mat['Material_id']; ?>" data-cost="<?php echo $mat['Material_costprice']; ?>">
                <?php echo htmlspecialchars($mat['Material_name']); ?> (<?php echo $mat['Material_unit']; ?>)
            </option>
        <?php endforeach; ?>
    `;

    app.showOrderForm = () => {
        document.getElementById('order-form-container').classList.remove('hidden');
        document.getElementById('order-items-body').innerHTML = '';
        app.addOrderRow();
    };

    app.hideOrderForm = () => {
        document.getElementById('order-form-container').classList.add('hidden');
    };

    app.saveMaterialOrder = async (e) => {
        e.preventDefault();
        const data = {
            order_date: document.getElementById('order-date').value,
            emp_id: document.getElementById('order-emp-id').value, // ส่งรหัสพนักงานจาก Session
            note: document.getElementById('order-note').value,
            items: []
        };
        
        // ลอจิกการวนลูปดึงข้อมูลจากตารางรายการและส่งข้อมูลไปยัง Backend
        console.log('ข้อมูลที่จะบันทึก:', data);
    };

    app.addOrderRow = () => {
        // โค้ดสำหรับเพิ่มแถว <tr> ในตาราง (เหมือนในหน้า POS หรือ Admit)
    };
</script>