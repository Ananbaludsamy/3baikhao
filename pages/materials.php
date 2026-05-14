<!-- ================= ໜ້າ: ຂໍ້ມູນສະຕ໋ອກວັດຖຸດິບ ================= -->
<div id="page-materials" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        <!-- ຟອມເພີ່ມ/ແກ້ໄຂ -->
        <div class="xl:col-span-1">
            <div class="page-card sticky top-0">
                <div class="page-card-header">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-box-open text-primary text-sm"></i>
                        </div>
                        <h3 class="font-bold text-slate-700 text-sm" id="mat-form-title">ເພີ່ມວັດຖຸດິບໃໝ່</h3>
                    </div>
                </div>
                <form id="add-material-form" onsubmit="app.saveMaterial(event)" class="p-4 space-y-3">
                    <input type="hidden" id="edit-mat-id">
                    <div>
                        <label class="form-label">ຊື່ວັດຖຸດິບ</label>
                        <input type="text" id="mat-name" required class="form-input" placeholder="ເຊັ່ນ: ເສັ້ນນ້ອຍ, ຊີ້ນໝູ">
                    </div>
                    <div>
                        <label class="form-label">ປະເພດ</label>
                        <select id="mat-type" required class="form-input">
                            <?php foreach($mat_types as $mt): ?>
                                <option value="<?php echo $mt['MaterialType_id']; ?>"><?php echo htmlspecialchars($mt['MaterialType_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="form-label">ລາຄາຕົ້ນທຶນ (₭)</label>
                            <input type="number" id="mat-cost" required class="form-input" value="0">
                        </div>
                        <div>
                            <label class="form-label">ຫົວໜ່ວຍ</label>
                            <input type="text" id="mat-unit" required class="form-input" placeholder="ກກ., ຫໍ່">
                        </div>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" id="mat-submit-btn" class="btn-primary flex-1 justify-center">
                            <i class="fa-solid fa-save"></i> ບັນທຶກຂໍ້ມູນ
                        </button>
                        <button type="button" id="mat-cancel-btn" onclick="app.resetMaterialForm()" class="btn-secondary hidden">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ຕາລາງສະຕ໋ອກ -->
        <div class="xl:col-span-2">
            <div class="page-card">
                <div class="page-card-header">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-warehouse text-slate-400 text-sm"></i>
                        <h3 class="font-bold text-slate-700 text-sm">ລາຍການວັດຖຸດິບແລະສະຕ໋ອກປັດຈຸບັນ</h3>
                    </div>
                    <button onclick="app.loadMaterials()" class="btn-secondary" style="padding:6px 12px;font-size:12px;">
                        <i class="fa-solid fa-rotate"></i> ໂຫຼດໃໝ່
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="table-header">
                                <th>ຊື່ວັດຖຸດິບ</th>
                                <th class="w-32">ປະເພດ</th>
                                <th class="w-28 text-right">ຄົງເຫຼືອ</th>
                                <th class="w-24">ຫົວໜ່ວຍ</th>
                                <th class="w-36 text-right">ຕົ້ນທຶນ/ຫົວໜ່ວຍ</th>
                                <th class="w-20 text-center">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody id="material-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
