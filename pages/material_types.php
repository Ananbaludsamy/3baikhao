<!-- ================= ໜ້າ: ປະເພດວັດຖຸດິບ ================= -->
<div id="page-material-types" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-3xl mx-auto">
        <div class="page-card mb-5">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-primary text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm">ເພີ່ມ / ແກ້ໄຂປະເພດວັດຖຸດິບ</h3>
                </div>
            </div>
            <div class="p-4">
                <input type="hidden" id="edit-mat-type-id">
                <div class="flex gap-3">
                    <input type="text" id="new-mat-type-name" class="form-input flex-1" placeholder="ລະບຸຊື່ປະເພດວັດຖຸດິບ ເຊັ່ນ: ຊີ້ນສັດ, ຜັກ, ເຄື່ອງປຸງ">
                    <button id="mat-type-submit-btn" onclick="app.saveMaterialType()" class="btn-primary">
                        <i class="fa-solid fa-plus"></i> ເພີ່ມປະເພດ
                    </button>
                    <button id="mat-type-cancel-btn" onclick="app.resetMaterialTypeForm()" class="btn-secondary hidden">
                        <i class="fa-solid fa-xmark"></i> ຍົກເລີກ
                    </button>
                </div>
            </div>
        </div>

        <div class="page-card">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list text-slate-400 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">ລາຍການປະເພດວັດຖຸດິບທັງໝົດ</h3>
                </div>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                    <input type="text" id="search-mat-type-input" oninput="app.searchMaterialTypes()"
                        class="form-input pl-8 text-xs" style="width:200px;padding-top:8px;padding-bottom:8px;"
                        placeholder="ຄົ້ນຫາປະເພດວັດຖຸດິບ...">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th>ລະຫັດ</th>
                            <th>ຊື່ປະເພດວັດຖຸດິບ</th>
                            <th class="text-center w-24">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody id="mat-type-table-body"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
