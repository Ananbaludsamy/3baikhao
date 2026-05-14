<!-- ================= ໜ້າ: ຈັດການເມນູ ================= -->
<div id="page-menu" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">

    <!-- ຟອມເພີ່ມ/ແກ້ໄຂ -->
    <div class="page-card mb-5">
        <div class="page-card-header">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-utensils text-primary text-sm"></i>
                </div>
                <h3 class="font-bold text-slate-700 text-sm" id="product-form-title">ເພີ່ມເມນູໃໝ່ເຂົ້າຮ້ານ</h3>
            </div>
        </div>
        <form id="add-product-form" onsubmit="app.saveProduct(event)" class="p-4 lg:p-5">
            <input type="hidden" id="edit-product-id">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="form-label">ຊື່ເມນູ</label>
                    <input type="text" id="new-item-name" required class="form-input" placeholder="ເຊັ່ນ: ເຝີເນື້ອດ່ານ">
                </div>
                <div>
                    <label class="form-label">ລາຄາ (₭)</label>
                    <input type="number" id="new-item-price" required min="1" class="form-input" placeholder="ເຊັ່ນ: 50000">
                </div>
                <div>
                    <label class="form-label">ໝວດໝູ່</label>
                    <select id="new-item-cat" required class="form-input">
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['ProductType_id']; ?>"><?php echo htmlspecialchars($cat['ProductType_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">ຮູບພາບສິນຄ້າ</label>
                    <input type="file" id="new-item-img" accept="image/*" class="form-input text-xs" style="padding-top:9px;">
                </div>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" id="product-cancel-btn" onclick="app.resetProductForm()" class="btn-secondary hidden">
                    <i class="fa-solid fa-xmark"></i> ຍົກເລີກ
                </button>
                <button type="submit" id="product-submit-btn" class="btn-primary">
                    <i class="fa-solid fa-save"></i> ບັນທຶກເມນູ
                </button>
            </div>
        </form>
    </div>

    <!-- ຕາລາງລາຍການເມນູ -->
    <div class="page-card">
        <div class="page-card-header">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-list text-slate-400 text-sm"></i>
                <h3 class="font-bold text-slate-700 text-sm">ລາຍການເມນູໃນລະບົບ</h3>
            </div>
            <button onclick="app.renderMenuMgmt()" class="btn-secondary" style="padding:6px 12px;font-size:12px;">
                <i class="fa-solid fa-rotate"></i> ໂຫຼດໃໝ່
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="table-header">
                        <th class="w-14">ຮູບ</th>
                        <th>ຊື່ເມນູ</th>
                        <th class="w-32">ໝວດໝູ່</th>
                        <th class="w-32 text-right">ລາຄາ</th>
                        <th class="w-20 text-center">ຈັດການ</th>
                    </tr>
                </thead>
                <tbody id="menu-mgmt-table"></tbody>
            </table>
        </div>
    </div>
</div>
