<!-- ================= ໜ້າ: ລາຍຮັບ - ລາຍຈ່າຍ ================= -->
<div id="page-finance" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex flex-wrap justify-between items-start gap-3 mb-5">
            <div>
                <h2 class="text-base font-black text-slate-800">ລາຍຮັບ - ລາຍຈ່າຍ</h2>
                <p class="text-xs text-slate-400 mt-0.5">ບັນທຶກແລະຕິດຕາມການເງິນຂອງຮ້ານ</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <input type="month" id="fin-month-picker"
                    class="form-input text-sm" style="padding:7px 12px;width:auto;"
                    value="<?php echo date('Y-m'); ?>"
                    onchange="app.loadFinance(this.value)">
                <a href="actions/export_finance_csv.php" target="_blank"
                    class="btn-secondary" style="padding:7px 14px;font-size:12px;text-decoration:none;">
                    <i class="fa-solid fa-file-excel text-green-600 mr-1"></i> Export CSV
                </a>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <!-- ລາຍຮັບ -->
            <div class="page-card p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-arrow-up text-green-600"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">ລາຍຮັບທັງໝົດ</p>
                        <p class="text-xl font-black text-green-600 mt-0.5">₭<span id="fin-revenue">0</span></p>
                        <div class="flex gap-3 mt-1 flex-wrap">
                            <span class="text-xs text-slate-400">POS: <span class="font-bold text-slate-600" id="fin-sales">₭0</span></span>
                            <span class="text-xs text-slate-400">ອື່ນໆ: <span class="font-bold text-slate-600" id="fin-other">₭0</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ລາຍຈ່າຍ -->
            <div class="page-card p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-arrow-down text-red-500"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">ລາຍຈ່າຍທັງໝົດ</p>
                        <p class="text-xl font-black text-red-500 mt-0.5">₭<span id="fin-expense">0</span></p>
                        <p class="text-xs text-slate-400 mt-1">ຄ່າໃຊ້ຈ່າຍດໍາເນີນງານ</p>
                    </div>
                </div>
            </div>

            <!-- ກໍາໄລສຸດທິ -->
            <div class="page-card p-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5" id="fin-profit-icon-box">
                        <i class="fa-solid fa-scale-balanced text-blue-600" id="fin-profit-icon"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">ກໍາໄລສຸດທິ</p>
                        <p class="text-xl font-black mt-0.5" id="fin-profit-amount">₭<span id="fin-profit">0</span></p>
                        <p class="text-xs text-slate-400 mt-1">ລາຍຮັບ − ລາຍຈ່າຍ</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            <!-- ຟອມບັນທຶກ -->
            <div class="lg:col-span-1">
                <div class="page-card">
                    <div class="page-card-header">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-pen-to-square text-primary text-sm"></i>
                            </div>
                            <h3 class="font-bold text-slate-700 text-sm">ບັນທຶກລາຍການໃໝ່</h3>
                        </div>
                    </div>
                    <form onsubmit="app.saveFinance(event)" class="p-4 space-y-3">
                        <div>
                            <label class="form-label">ປະເພດ</label>
                            <select id="fin-type" required class="form-input">
                                <option value="1">+ ລາຍຮັບ (ອື່ນໆ)</option>
                                <option value="2">− ລາຍຈ່າຍ</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">ລາຍການ</label>
                            <input type="text" id="fin-name" required class="form-input" placeholder="ເຊັ່ນ: ຄ່ານ້ຳ, ຄ່າເຊົ່າ, ຄ່າວັດຖຸດິບ">
                        </div>
                        <div>
                            <label class="form-label">ວັນທີ</label>
                            <input type="date" id="fin-date" required class="form-input" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                        <div>
                            <label class="form-label">ຈໍານວນເງິນ (₭)</label>
                            <input type="number" id="fin-amount" required min="1" class="form-input" placeholder="0">
                        </div>
                        <button type="submit" id="fin-submit-btn" class="btn-primary w-full justify-center" style="margin-top:4px;">
                            <i class="fa-solid fa-save"></i> ບັນທຶກຂໍ້ມູນ
                        </button>
                    </form>
                </div>
            </div>

            <!-- ຕາຕະລາງປະຫວັດ -->
            <div class="lg:col-span-2">
                <div class="page-card">
                    <div class="page-card-header flex-wrap gap-2">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-clock-rotate-left text-slate-400 text-sm"></i>
                            <h3 class="font-bold text-slate-700 text-sm">ປະຫວັດລາຍການ</h3>
                        </div>
                        <!-- Filter Tabs -->
                        <div class="flex gap-1">
                            <button id="fin-tab-all" onclick="app.filterFinance('all')"
                                class="px-3 py-1 rounded-lg text-xs font-bold bg-primary text-white transition-colors">
                                ທັງໝົດ
                            </button>
                            <button id="fin-tab-1" onclick="app.filterFinance('1')"
                                class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                                ລາຍຮັບ
                            </button>
                            <button id="fin-tab-2" onclick="app.filterFinance('2')"
                                class="px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                                ລາຍຈ່າຍ
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="table-header">
                                    <th class="w-24">ວັນທີ</th>
                                    <th>ລາຍການ</th>
                                    <th class="w-24 text-center">ປະເພດ</th>
                                    <th class="w-32 text-right">ຈໍານວນ</th>
                                    <th class="w-10 text-center">ລຶບ</th>
                                </tr>
                            </thead>
                            <tbody id="finance-table-body">
                                <tr class="table-row">
                                    <td colspan="5" class="text-center text-slate-400 py-10">ກໍາລັງໂຫຼດ...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="fin-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
