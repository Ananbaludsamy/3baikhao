<!-- ================= ໜ້າ: ຕັ້ງຄ່າລະບົບ ================= -->
<div id="page-settings" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-2xl mx-auto space-y-5">

        <!-- ຟອມຕັ້ງຄ່າຮ້ານ -->
        <div class="page-card">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-store text-primary text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm">ຂໍ້ມູນຮ້ານ</h3>
                </div>
            </div>
            <form id="settings-form" onsubmit="app.saveSettings(event)" class="p-5 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="form-label">ຊື່ຮ້ານ</label>
                        <input type="text" id="setting-shop-name" class="form-input" placeholder="ຊື່ຮ້ານ">
                    </div>
                    <div>
                        <label class="form-label">ເບີໂທລະສັບ</label>
                        <input type="text" id="setting-shop-phone" class="form-input" placeholder="020-XXXX-XXXX">
                    </div>
                    <div>
                        <label class="form-label">ທີ່ຢູ່ຮ້ານ</label>
                        <input type="text" id="setting-shop-address" class="form-input" placeholder="ທີ່ຢູ່ຮ້ານ">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="btn-primary" style="padding:10px 24px;">
                        <i class="fa-solid fa-save"></i> ບັນທຶກຂໍ້ມູນຮ້ານ
                    </button>
                </div>
            </form>
        </div>

        <!-- ຕັ້ງຄ່າ POS + ຄະແນນ -->
        <div class="page-card">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-star text-purple-600 text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm">ຕັ້ງຄ່າ POS & ຄະແນນສະສົມ</h3>
                </div>
            </div>
            <form id="pos-settings-form" onsubmit="app.savePosSettings(event)" class="p-5 space-y-5">

                <!-- POS general -->
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">ທົ່ວໄປ</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">ຈໍານວນໂຕະທັງໝົດ</label>
                            <input type="number" id="setting-tables-count" class="form-input" placeholder="20" min="1" max="100">
                            <p class="text-xs text-slate-400 mt-1.5">ສູງສຸດ 100 ໂຕະ</p>
                        </div>
                    </div>
                </div>

                <!-- Points earn/redeem -->
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">ການສະສົມ & ແລກຄະແນນ</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">ໃຊ້ຈ່າຍ X ₭ → 1 ຄະແນນ</label>
                            <input type="number" id="setting-earn-rate" class="form-input" placeholder="10000" min="1">
                            <p class="text-xs text-slate-400 mt-1.5">ຊື້ ₭X ໄດ້ 1 ຄະ</p>
                        </div>
                        <div>
                            <label class="form-label">1 ຄະແນນ = X ₭ ສ່ວນຫຼຸດ</label>
                            <input type="number" id="setting-point-value" class="form-input" placeholder="1000" min="1">
                            <p class="text-xs text-slate-400 mt-1.5">ມູນຄ່າເວລາແລກຄະ</p>
                        </div>
                    </div>
                </div>

                <!-- Level thresholds -->
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">ເງື່ອນໄຂ Upgrade ລະດັບ (ຍອດໃຊ້ຈ່າຍສະສົມ)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label"><span class="badge-vip mr-1">VIP</span> ຍອດສະສົມຮອດ (₭)</label>
                            <input type="number" id="setting-vip-threshold" class="form-input" placeholder="500000" min="1">
                        </div>
                        <div>
                            <label class="form-label"><span class="badge-gold mr-1">Gold</span> ຍອດສະສົມຮອດ (₭)</label>
                            <input type="number" id="setting-gold-threshold" class="form-input" placeholder="2000000" min="1">
                        </div>
                    </div>
                </div>

                <!-- Level discounts -->
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3">ສ່ວນຫຼຸດແຕ່ລະລະດັບ</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label"><span class="badge-vip mr-1">VIP</span> ສ່ວນຫຼຸດ (%)</label>
                            <input type="number" id="setting-vip-discount" class="form-input" placeholder="5" min="0" max="100">
                        </div>
                        <div>
                            <label class="form-label"><span class="badge-gold mr-1">Gold</span> ສ່ວນຫຼຸດ (%)</label>
                            <input type="number" id="setting-gold-discount" class="form-input" placeholder="10" min="0" max="100">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary" style="padding:10px 24px;">
                        <i class="fa-solid fa-save"></i> ບັນທຶກການຕັ້ງຄ່າ
                    </button>
                </div>
            </form>
        </div>

        <!-- ຂໍ້ມູນລະບົບ -->
        <div class="page-card p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-info text-blue-500 text-sm"></i>
                <h4 class="font-bold text-slate-700 text-sm">ຂໍ້ມູນລະບົບ</h4>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">ເວີຊັ່ນ</p>
                    <p class="font-black text-slate-700 mt-1 text-lg">v1.0.0</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">ຖານຂໍ້ມູນ</p>
                    <p class="font-black text-slate-700 mt-1 text-lg">MySQL</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wide">ພັດທະນາໂດຍ</p>
                    <p class="font-bold text-slate-700 mt-1 text-xs">ສ.ນ ອານັນ ບາລັດສະມີ</p>
                </div>
            </div>
        </div>

    </div>
</div>
