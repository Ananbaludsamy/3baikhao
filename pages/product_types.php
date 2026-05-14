<!-- ================= ໜ້າ: ປະເພດສິນຄ້າ ================= -->
<div id="page-product-types" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-3xl mx-auto">
        <div class="page-card mb-5">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-tags text-primary text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm">ເພີ່ມ / ແກ້ໄຂປະເພດສິນຄ້າ</h3>
                </div>
            </div>
            <div class="p-4">
                <input type="hidden" id="edit-cat-id">
                <div class="flex gap-3">
                    <input type="text" id="new-cat-name" class="form-input flex-1" placeholder="ລະບຸຊື່ປະເພດສິນຄ້າ ເຊັ່ນ: ເຝີ, ເຄື່ອງດື່ມ">
                    <button id="cat-submit-btn" onclick="app.saveCategory()" class="btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        <span id="cat-submit-label">ເພີ່ມປະເພດ</span>
                    </button>
                    <button id="cat-cancel-btn" onclick="app.resetCategoryForm()" class="btn-secondary hidden">
                        <i class="fa-solid fa-xmark"></i> ຍົກເລີກ
                    </button>
                </div>
            </div>
        </div>

        <div class="page-card">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list text-slate-400 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">ລາຍການປະເພດສິນຄ້າທັງໝົດ</h3>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input type="text" id="search-cat-input" oninput="app.searchProductTypes()"
                        class="form-input pl-8 text-xs" style="width:200px;padding-top:8px;padding-bottom:8px;"
                        placeholder="ຄົ້ນຫາປະເພດສິນຄ້າ...">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th>ລະຫັດ</th>
                            <th>ຊື່ປະເພດສິນຄ້າ</th>
                            <th class="text-center w-24">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody id="cat-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
