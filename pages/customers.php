<!-- ================= ໜ້າ: ຂໍ້ມູນລູກຄ້າ ================= -->
<div id="page-customers" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        <!-- ຟອມເພີ່ມ/ແກ້ໄຂ -->
        <div class="xl:col-span-1">
            <div class="page-card sticky top-0">
                <div class="page-card-header">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-user-plus text-primary text-sm"></i>
                        </div>
                        <h3 class="font-bold text-slate-700 text-sm" id="customer-form-title">ເພີ່ມລູກຄ້າໃໝ່</h3>
                    </div>
                </div>
                <form id="customer-form" onsubmit="app.saveCustomer(event)" class="p-4 space-y-3">
                    <input type="hidden" id="cus-id">
                    <div>
                        <label class="form-label">ຊື່ລູກຄ້າ</label>
                        <input type="text" id="cus-name" required class="form-input" placeholder="ຊື່-ນາມສະກຸນ">
                    </div>
                    <div>
                        <label class="form-label">ລະດັບສະມາຊິກ</label>
                        <select id="cus-level" required class="form-input">
                            <option value="General">General (ທົ່ວໄປ)</option>
                            <option value="VIP">VIP</option>
                            <option value="Gold">Gold VIP</option>
                        </select>
                        <p class="text-[10px] text-slate-400 mt-1">ລະດັບຈະ upgrade ອັດຕະໂນມັດຕາມຍອດໃຊ້ຈ່າຍສະສົມ</p>
                    </div>
                    <div>
                        <label class="form-label">ເບີໂທລະສັບ</label>
                        <input type="text" id="cus-tel" required class="form-input" placeholder="020-XXXX-XXXX">
                    </div>
                    <div>
                        <label class="form-label">ທີ່ຢູ່</label>
                        <textarea id="cus-address" rows="3" required class="form-input" style="resize:none;" placeholder="ທີ່ຢູ່ລູກຄ້າ"></textarea>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" id="customer-submit-btn" class="btn-primary flex-1 justify-center">
                            <i class="fa-solid fa-save"></i> ບັນທຶກ
                        </button>
                        <button type="button" onclick="app.resetCustomerForm()" class="btn-secondary">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </form>

                <!-- Level Thresholds Info Card -->
                <div class="mx-4 mb-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">ເງື່ອນໄຂ Upgrade</p>
                    <div class="space-y-1.5 text-xs text-slate-600">
                        <div class="flex justify-between">
                            <span class="badge-vip">VIP</span>
                            <span id="info-vip-threshold" class="font-bold text-purple-700">≥ ₭500,000</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="badge-gold">Gold</span>
                            <span id="info-gold-threshold" class="font-bold text-yellow-700">≥ ₭2,000,000</span>
                        </div>
                        <div class="flex justify-between mt-2 border-t border-slate-200 pt-1.5">
                            <span class="text-slate-400">ສ່ວນຫຼຸດ VIP</span>
                            <span id="info-vip-discount" class="font-bold text-purple-600">5%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">ສ່ວນຫຼຸດ Gold</span>
                            <span id="info-gold-discount" class="font-bold text-yellow-600">10%</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">1 ຄະແນນໄດ້ຈາກ</span>
                            <span id="info-earn-rate" class="font-bold text-blue-600">₭10,000</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ຕາລາງລາຍຊື່ລູກຄ້າ -->
        <div class="xl:col-span-2">
            <div class="page-card">
                <div class="page-card-header">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-users text-slate-400 text-sm"></i>
                        <h3 class="font-bold text-slate-700 text-sm">ລາຍຊື່ລູກຄ້າທັງໝົດ</h3>
                        <span class="text-xs text-slate-400 font-normal" id="cus-count"></span>
                    </div>
                    <button onclick="app.loadCustomers()" class="btn-secondary" style="padding:6px 12px;font-size:12px;">
                        <i class="fa-solid fa-rotate"></i> ໂຫຼດໃໝ່
                    </button>
                </div>
                <div class="flex gap-2 px-4 py-3 border-b border-slate-100">
                    <div class="relative flex-1">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                        <input type="text" id="cus-search" oninput="app.filterCustomers()" placeholder="ຄົ້ນຫາຊື່, ເບີໂທ..." class="form-input pl-8" style="padding-top:7px;padding-bottom:7px;font-size:13px;">
                    </div>
                    <select id="cus-level-filter" onchange="app.filterCustomers()" class="form-input" style="width:130px;padding-top:7px;padding-bottom:7px;font-size:13px;">
                        <option value="">ທຸກລະດັບ</option>
                        <option value="General">General</option>
                        <option value="VIP">VIP</option>
                        <option value="Gold">Gold</option>
                    </select>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="table-header">
                                <th>ຊື່ລູກຄ້າ</th>
                                <th class="w-24">ລະດັບ</th>
                                <th class="w-36 text-right">ຍອດໃຊ້ຈ່າຍ</th>
                                <th class="w-28 text-right">ຄະແນນ</th>
                                <th>ທີ່ຢູ່</th>
                                <th class="w-20 text-center">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody id="customer-table-body"></tbody>
                    </table>
                </div>
                <div id="cus-pagination"></div>
            </div>
        </div>
    </div>
</div>

<!-- Point History Modal -->
<div id="point-history-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4" style="background:rgba(0,0,0,0.5);">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[80vh] flex flex-col">
        <div class="flex items-center justify-between p-5 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-slate-700" id="point-history-title">ປະຫວັດຄະແນນ</h3>
                <p class="text-xs text-slate-400 mt-0.5" id="point-history-subtitle"></p>
            </div>
            <button onclick="document.getElementById('point-history-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-times text-xl"></i>
            </button>
        </div>
        <!-- Summary strip -->
        <div class="grid grid-cols-3 gap-3 p-4 bg-slate-50 border-b border-slate-100" id="point-history-summary">
        </div>
        <!-- Progress to next level -->
        <div id="point-history-progress" class="px-4 py-3 border-b border-slate-100 hidden">
        </div>
        <!-- Log table -->
        <div class="flex-1 overflow-y-auto p-4">
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th>ວັນທີ</th>
                            <th>ປະເພດ</th>
                            <th>ໝາຍເຫດ</th>
                            <th class="text-right">ຄະແນນ</th>
                        </tr>
                    </thead>
                    <tbody id="point-history-table">
                        <tr class="table-row"><td colspan="4" class="text-center text-slate-400 py-6">ກໍາລັງໂຫຼດ...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Manual adjust (admin) -->
        <?php if ($_SESSION['Role'] === 'admin'): ?>
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">ປັບຄະແນນ (Admin)</p>
            <div class="flex gap-2">
                <input type="hidden" id="adjust-cus-id">
                <input type="number" id="adjust-points" class="form-input text-sm" style="padding:8px 12px;"
                    placeholder="ຈໍານວນ (+/-)" step="1">
                <input type="text" id="adjust-note" class="form-input text-sm" style="padding:8px 12px;"
                    placeholder="ເຫດຜົນ">
                <button onclick="app.adjustPoints()" class="btn-primary" style="padding:8px 16px;font-size:12px;white-space:nowrap;">
                    <i class="fa-solid fa-sliders"></i> ປັບ
                </button>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
