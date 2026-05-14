<!-- ================= ໜ້າ: ຮັບເຂົ້າວັດຖຸດິບ ================= -->
<div id="page-material-admit" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-2xl mx-auto">
        <div class="page-card">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-truck-ramp-box text-green-600 text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-700 text-sm">ບັນທຶກການຮັບເຂົ້າວັດຖຸດິບ</h3>
                        <p class="text-xs text-slate-400 mt-0.5">ສະຕ໋ອກເພີ່ມ + <span class="text-red-500 font-bold">ບັນທຶກລາຍຈ່າຍໃນລາຍຮັບ-ລາຍຈ່າຍ</span> ອັດຕະໂນມັດ</p>
                    </div>
                </div>
            </div>
            <form id="admit-form" onsubmit="app.saveAdmit(event)" class="p-5 space-y-4">
                <div>
                    <label class="form-label">ເລືອກວັດຖຸດິບທີ່ຮັບເຂົ້າ</label>
                    <select id="admit-material-id" required class="form-input"></select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">ຈໍານວນທີ່ຮັບເຂົ້າ</label>
                        <input type="number" id="admit-qty" required min="1" class="form-input" placeholder="0">
                    </div>
                    <div>
                        <label class="form-label">ລາຄາຊື້ຕໍ່ຫົວໜ່ວຍ (₭)</label>
                        <input type="number" id="admit-price" required min="0" class="form-input" placeholder="0">
                    </div>
                </div>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 14px;font-size:12px;color:#15803d;display:flex;align-items:center;gap:8px;">
                    <i class="fa-solid fa-circle-info"></i>
                    ລາຄາຊື້ທີ່ບັນທຶກຈະອັບເດດລາຄາຕົ້ນທຶນຂອງວັດຖຸດິບນີ້ໃນລະບົບໂດຍອັດຕະໂນມັດ
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn-primary" style="background:#16a34a;padding:12px 28px;font-size:14px;">
                        <i class="fa-solid fa-plus-circle"></i> ຢືນຢັນຮັບເຂົ້າສະຕ໋ອກ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
