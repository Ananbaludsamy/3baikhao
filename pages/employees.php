<!-- ================= ໜ້າ: ຂໍ້ມູນພະນັກງານ ================= -->
<div id="page-employees" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        <!-- ຟອມເພີ່ມ/ແກ້ໄຂ -->
        <div class="xl:col-span-1">
            <div class="page-card sticky top-0">
                <div class="page-card-header">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-id-card text-primary text-sm"></i>
                        </div>
                        <h3 class="font-bold text-slate-700 text-sm" id="employee-form-title">ເພີ່ມພະນັກງານໃໝ່</h3>
                    </div>
                </div>
                <form id="employee-form" onsubmit="app.saveEmployee(event)" class="p-4 space-y-3">
                    <input type="hidden" id="emp-id">
                    <div>
                        <label class="form-label">ຊື່-ນາມສະກຸນ</label>
                        <input type="text" id="emp-name" required class="form-input" placeholder="ຊື່ພະນັກງານ">
                    </div>
                    <div>
                        <label class="form-label">ລະຫັດບັດປະຊາຊົນ</label>
                        <input type="text" id="emp-card" required class="form-input" placeholder="ລະຫັດບັດປະຊາຊົນ">
                    </div>
                    <div>
                        <label class="form-label">ທີ່ຢູ່</label>
                        <input type="text" id="emp-address" required class="form-input" placeholder="ທີ່ຢູ່">
                    </div>
                    <div>
                        <label class="form-label">ເບີໂທລະສັບ</label>
                        <input type="text" id="emp-tel" required class="form-input" placeholder="ເບີໂທລະສັບ">
                    </div>
                    <div>
                        <label class="form-label">ເພດ</label>
                        <select id="emp-gender" required class="form-input">
                            <option value="">-- ເລືອກເພດ --</option>
                            <option value="ຊາຍ">ຊາຍ</option>
                            <option value="ຍິງ">ຍິງ</option>
                            <option value="ອື່ນໆ">ອື່ນໆ</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">ຊື່ຜູ້ໃຊ້ງານ (Username)</label>
                        <input type="text" id="emp-username" required class="form-input" placeholder="Username">
                    </div>
                    <div>
                        <label class="form-label">ລະຫັດຜ່ານ (Password)</label>
                        <input type="password" id="emp-password" class="form-input" placeholder="ໃສ່ເມື່ອຕ້ອງການປ່ຽນ">
                    </div>
                    <div>
                        <label class="form-label">ສິດທິ໌ການໃຊ້ງານ</label>
                        <select id="emp-role" required class="form-input">
                            <option value="staff">ພະນັກງານ (Staff)</option>
                            <option value="admin">ຜູ້ດູແລລະບົບ (Admin)</option>
                        </select>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" id="employee-submit-btn" class="btn-primary flex-1 justify-center">
                            <i class="fa-solid fa-save"></i> ບັນທຶກ
                        </button>
                        <button type="button" onclick="app.resetEmployeeForm()" class="btn-secondary">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ຕາລາງລາຍຊື່ພະນັກງານ -->
        <div class="xl:col-span-2">
            <div class="page-card">
                <div class="page-card-header">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-users text-slate-400 text-sm"></i>
                        <h3 class="font-bold text-slate-700 text-sm">ລາຍຊື່ພະນັກງານທັງໝົດ</h3>
                    </div>
                    <button onclick="app.loadEmployees()" class="btn-secondary" style="padding:6px 12px;font-size:12px;">
                        <i class="fa-solid fa-rotate"></i> ໂຫຼດໃໝ່
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="table-header">
                                <th>ຊື່ພະນັກງານ</th>
                                <th>ເບີໂທ</th>
                                <th>Username</th>
                                <th>ສິດທິ໌</th>
                                <th class="text-center w-20">ຈັດການ</th>
                            </tr>
                        </thead>
                        <tbody id="employee-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
