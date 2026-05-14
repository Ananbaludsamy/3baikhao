<!-- ================= ໜ້າ: ເບີກໃຊ້ວັດຖຸດິບ ================= -->
<div id="page-material-requisition" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-2xl mx-auto">
        <div class="page-card">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-hand-holding-hand text-primary text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-700 text-sm">ບັນທຶກການເບີກໃຊ້ວັດຖຸດິບ</h3>
                        <p class="text-xs text-slate-400 mt-0.5">ສະຕ໋ອກຈະຖືກຫັກອອກໂດຍອັດຕະໂນມັດເມື່ອຢືນຢັນ</p>
                    </div>
                </div>
            </div>
            <form id="requisition-form" onsubmit="app.saveRequisition(event)" class="p-5 space-y-4">
                <div>
                    <label class="form-label">ເລືອກວັດຖຸດິບທີ່ຕ້ອງການເບີກ</label>
                    <select id="req-material-id" required class="form-input"></select>
                </div>
                <div>
                    <label class="form-label">ຈໍານວນທີ່ເບີກ</label>
                    <input type="number" id="req-qty" required min="1" class="form-input" placeholder="ລະບຸຈໍານວນ">
                </div>
                <div>
                    <label class="form-label">ໝາຍເຫດ / ເຫດຜົນການເບີກ</label>
                    <textarea id="req-note" rows="3" class="form-input" style="resize:none;" placeholder="ເຊັ່ນ: ເບີກໄປໃຊ້ໜ້າເຕົາປະຈໍາວັນ, ກຽມສະຕ໋ອກເຊົ້າ"></textarea>
                </div>
                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn-primary" style="padding:12px 28px;font-size:14px;">
                        <i class="fa-solid fa-paper-plane"></i> ຢືນຢັນການເບີກໃຊ້
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
