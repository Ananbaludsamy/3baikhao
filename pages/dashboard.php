<!-- ================= ໜ້າ 2: Dashboard (ລາຍງານ) ================= -->
<div id="page-dashboard" class="absolute inset-0 px-2 sm:p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300 w-full h-full">
    <?php if ($_SESSION['Role'] == 'admin'): ?>
    <!-- ແຖວຕົວເລກສະຫຼຸບ (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- ຍອດຂາຍ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-blue-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">ຍອດຂາຍວັນນີ້</span>
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-sack-dollar"></i></div>
            </div>
            <h3 class="text-2xl font-black text-gray-800">₭<span id="dash-total-sales">0</span></h3>
            <p class="text-xs text-blue-500 mt-2 font-medium"><i class="fa-solid fa-receipt mr-1"></i> <span id="dash-total-orders">0</span> ບິນວັນນີ້</p>
        </div>
        <!-- ກຳໄລສຸດທິ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-green-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">ກຳໄລສຸດທິວັນນີ້</span>
                <div class="w-10 h-10 bg-green-50 text-green-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-chart-line"></i></div>
            </div>
            <h3 class="text-2xl font-black text-gray-800">₭<span id="dash-net-profit">0</span></h3>
            <p class="text-xs text-gray-400 mt-2">ຄຳນວນຈາກ ຍອດຂາຍ - ລາຍຈ່າຍ</p>
        </div>
        <!-- ເມນູຂາຍດີ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-orange-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">ເມນູຍອດນິຍົມ</span>
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-fire"></i></div>
            </div>
            <h3 class="text-lg font-bold text-gray-800 truncate" id="dash-best-seller">-</h3>
            <p class="text-xs text-gray-400 mt-2">ເມນູທີ່ຖືກສັ່ງຫຼາຍທີ່ສຸດວັນນີ້</p>
        </div>
        <!-- ສະຕ໋ອກວັດຖຸດິບ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-red-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">ແຈ້ງເຕືອນສະຕ໋ອກ</span>
                <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-box-open"></i></div>
            </div>
            <h3 class="text-2xl font-black text-gray-800"><span id="dash-low-stock">0</span> ລາຍການ</h3>
            <p class="text-xs text-red-500 mt-2 font-medium">ວັດຖຸດິບໃກ້ຈະໝົດສະຕ໋ອກ</p>
        </div>
    </div>

    <!-- ແຖວກຣາຟວິເຄາະ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- ກຣາຟເສັ້ນທ່າອ່ຽງຍອດຂາຍ -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-bold text-gray-800">ທ່າອ່ຽງຍອດຂາຍ (7 ວັນລ່າສຸດ)</h4>
            </div>
            <div class="h-64">
                <canvas id="weeklySalesChart"></canvas>
            </div>
        </div>
        <!-- ກຣາຟວົງກົມແຍກໝວດໝູ່ -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h4 class="font-bold text-gray-800 mb-6">ສັດສ່ວນຍອດຂາຍຕາມໝວດໝູ່</h4>
            <div class="h-64">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- ລາຍການຂາຍລ່າສຸດ -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800"><i class="fa-solid fa-history mr-2 text-primary"></i>ປະຫວັດການຂາຍລ່າສຸດ</h3>
                <button onclick="app.updateDashboard()" class="text-xs bg-white border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="fa-solid fa-rotate mr-1"></i> ໂຫຼດໃໝ່
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider bg-gray-50/80 sticky top-0 z-10">
                            <th class="p-4 border-b w-28 whitespace-nowrap">ລະຫັດບິນ</th>
                            <th class="p-4 border-b w-36 whitespace-nowrap">ວັນທີ/ເວລາ</th>
                            <th class="p-4 border-b">ລາຍການ</th>
                            <th class="p-4 border-b w-32 text-right whitespace-nowrap">ຍອດລວມ</th>
                        </tr>
                    </thead>
                </table>
                <div style="max-height:340px;overflow-y:auto;">
                    <table class="w-full text-left border-collapse">
                        <colgroup>
                            <col style="width:7rem">
                            <col style="width:9rem">
                            <col>
                            <col style="width:8rem">
                        </colgroup>
                        <tbody id="order-history-table" class="text-sm"></tbody>
                    </table>
                </div>
                <div id="sales-pagination"></div>
            </div>
        </div>
        <!-- ສິນຄ້າຂາຍດີ 5 ອັນດັບ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-4">5 ອັນດັບເມນູຂາຍດີ</h4>
            <div id="top-products-list" class="space-y-4"></div>
        </div>
    </div>

    <?php else: ?>
    <!-- Greeting Banner -->
    <div class="bg-gradient-to-r from-orange-500 via-orange-500 to-amber-400 rounded-2xl p-5 mb-5 text-white shadow-lg shadow-orange-100 relative overflow-hidden">
        <div class="absolute -top-8 -right-8 w-36 h-36 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-10 left-1/3 w-28 h-28 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="flex justify-between items-center relative">
            <div>
                <p class="text-orange-100 text-sm mb-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-hand-sparkles"></i> ສະບາຍດີ!
                </p>
                <h2 class="text-2xl font-black tracking-tight"><?php echo htmlspecialchars($_SESSION['Emp_name']); ?></h2>
                <span class="inline-flex items-center gap-1.5 bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full mt-2">
                    <i class="fa-solid fa-id-badge"></i> ພະນັກງານ (Staff)
                </span>
            </div>
            <div class="text-right">
                <div class="text-3xl font-black tracking-tight tabular-nums" id="staff-live-clock">--:--</div>
                <div class="text-orange-100 text-xs mt-1" id="staff-live-date"></div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
        <!-- ລໍຖ້າເສີບ -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -top-5 -right-5 w-16 h-16 bg-orange-50 rounded-full pointer-events-none"></div>
            <div class="relative">
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center text-orange-500 mb-3">
                    <i class="fa-solid fa-bell-concierge"></i>
                </div>
                <div class="flex items-end gap-1">
                    <h3 class="text-3xl font-black text-gray-800 leading-none" id="kpi-pending">-</h3>
                    <span class="text-xs text-gray-400 mb-0.5">ລາຍການ</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 font-medium">ລໍຖ້າເສີບ</p>
            </div>
            <div id="pending-ping" class="absolute top-3 right-3 hidden">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                </span>
            </div>
        </div>

        <!-- ເສີບແລ້ວວັນນີ້ -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -top-5 -right-5 w-16 h-16 bg-green-50 rounded-full pointer-events-none"></div>
            <div class="relative">
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center text-green-500 mb-3">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div class="flex items-end gap-1">
                    <h3 class="text-3xl font-black text-gray-800 leading-none" id="kpi-served">-</h3>
                    <span class="text-xs text-gray-400 mb-0.5">ບິນ</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 font-medium">ເສີບແລ້ວວັນນີ້</p>
            </div>
        </div>

        <!-- ບິນທັງໝົດວັນນີ້ -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -top-5 -right-5 w-16 h-16 bg-blue-50 rounded-full pointer-events-none"></div>
            <div class="relative">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-500 mb-3">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div class="flex items-end gap-1">
                    <h3 class="text-3xl font-black text-gray-800 leading-none" id="kpi-total">-</h3>
                    <span class="text-xs text-gray-400 mb-0.5">ບິນ</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 font-medium">ບິນທັງໝົດວັນນີ້</p>
            </div>
        </div>

        <!-- ສະຕ໋ອກໃກ້ໝົດ -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-all relative overflow-hidden">
            <div class="absolute -top-5 -right-5 w-16 h-16 bg-red-50 rounded-full pointer-events-none"></div>
            <div class="relative">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center text-red-500 mb-3">
                    <i class="fa-solid fa-box-open"></i>
                </div>
                <div class="flex items-end gap-1">
                    <h3 class="text-3xl font-black text-gray-800 leading-none" id="kpi-stock">-</h3>
                    <span class="text-xs text-gray-400 mb-0.5">ລາຍການ</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5 font-medium">ສະຕ໋ອກໃກ້ໝົດ</p>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-2xl p-5 shadow-sm mb-5 border border-gray-100">
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-bold text-gray-700 text-sm flex items-center gap-2">
                <i class="fa-solid fa-chart-simple text-primary text-sm"></i>
                ຄວາມຄືບໜ້າການເສີບວັນນີ້
            </h4>
            <span class="text-xs font-bold text-gray-500 bg-gray-100 px-3 py-1 rounded-full" id="staff-progress-text">ກໍາລັງໂຫຼດ...</span>
        </div>
        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
            <div id="staff-progress-bar" class="bg-gradient-to-r from-green-400 to-emerald-500 h-3 rounded-full transition-all duration-700 ease-out" style="width:0%"></div>
        </div>
        <div class="flex justify-between mt-2 text-[10px] text-gray-400 font-medium">
            <span>ເລີ່ມກະ</span>
            <span id="staff-progress-pct" class="font-black text-emerald-500">0%</span>
            <span>ຄົບທຸກບິນ</span>
        </div>
    </div>
    <?php endif; ?>

    <div class="<?php echo ($_SESSION['Role'] == 'admin') ? 'max-w-6xl mx-auto mt-8' : ''; ?>">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-utensils text-orange-500 text-sm"></i>
                </div>
                ລາຍການລໍຖ້າເສີບ
                <span id="serving-count-badge" class="bg-orange-500 text-white text-xs font-black px-2.5 py-1 rounded-full min-w-[28px] text-center">0</span>
            </h2>
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-400 hidden sm:block">ອັບເດດ: <span id="staff-last-updated">--:--</span></span>
                <button onclick="app.updateStaffDashboard()" id="staff-refresh-btn" class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 transition-all flex items-center gap-2 text-gray-600">
                    <i class="fa-solid fa-rotate" id="staff-refresh-icon"></i>
                    <span class="hidden sm:inline">ໂຫຼດໃໝ່</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4" id="serving-list-container">
            <div class="col-span-full p-16 text-center text-gray-400 bg-white rounded-2xl border border-dashed border-gray-300">
                <i class="fa-solid fa-check-circle text-5xl mb-4 text-green-200 block"></i>
                <p class="font-medium">ຂະນະນີ້ບໍ່ມີລາຍການອາຫານຄ້າງເສີບ</p>
                <p class="text-sm mt-1 text-gray-300">ທຸກອໍເດີ້ເສີບຄົບແລ້ວ 🎉</p>
            </div>
        </div>
    </div>
</div>

<!-- ເພີ່ມ Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    window.app = window.app || {};

    document.addEventListener('DOMContentLoaded', function() {
        app.updateStaffDashboard();

        // Staff live clock
        function updateStaffClock() {
            const clockEl = document.getElementById('staff-live-clock');
            const dateEl  = document.getElementById('staff-live-date');
            if (!clockEl) return;
            const now = new Date();
            clockEl.innerText = now.toLocaleTimeString('lo-LA', { hour: '2-digit', minute: '2-digit' });
            dateEl.innerText  = now.toLocaleDateString('lo-LA', { weekday: 'long', day: 'numeric', month: 'long' });
        }
        updateStaffClock();
        setInterval(updateStaffClock, 1000);

        setInterval(() => {
            const dashboardPage = document.getElementById('page-dashboard');
            if (dashboardPage && !dashboardPage.classList.contains('hidden')) {
                app.updateStaffDashboard();
            }
        }, 10000);
    });

    let _prevPendingCount = null;

    app.updateStaffDashboard = async function() {
        const container  = document.getElementById('serving-list-container');
        const userRole   = '<?php echo $_SESSION['Role']; ?>';

        // Spin the refresh icon
        const icon = document.getElementById('staff-refresh-icon');
        if (icon) icon.classList.add('fa-spin');

        try {
            const requests = [fetch('actions/get_pending_orders.php')];
            if (userRole === 'staff') requests.push(fetch('actions/get_staff_stats.php'));

            const responses  = await Promise.all(requests);
            const [ordersResult, statsResult] = await Promise.all(responses.map(r => r.json()));

            if (userRole === 'staff' && statsResult?.success) {
                const s = statsResult;
                const set = (id, v) => { const el = document.getElementById(id); if (el) el.innerText = v; };
                set('kpi-pending', s.pending);
                set('kpi-served',  s.served_today);
                set('kpi-total',   s.total_today);
                set('kpi-stock',   s.low_stock);

                const pingEl = document.getElementById('pending-ping');
                if (pingEl) pingEl.classList.toggle('hidden', s.pending === 0);

                const pct  = s.total_today > 0 ? Math.round((s.served_today / s.total_today) * 100) : 0;
                const bar  = document.getElementById('staff-progress-bar');
                const txt  = document.getElementById('staff-progress-text');
                const pctEl= document.getElementById('staff-progress-pct');
                if (bar)   bar.style.width   = pct + '%';
                if (txt)   txt.innerText     = `${s.served_today} / ${s.total_today} ບິນ`;
                if (pctEl) pctEl.innerText   = pct + '%';
                if (bar) {
                    bar.className = bar.className.replace(/from-\S+ to-\S+/, '');
                    bar.classList.add(...(pct >= 100 ? ['from-emerald-400','to-emerald-500'] : pct >= 50 ? ['from-green-400','to-emerald-500'] : ['from-yellow-400','to-orange-400']));
                }
            }

            const orderCount = (ordersResult.success && ordersResult.data) ? ordersResult.data.length : 0;
            const badge = document.getElementById('serving-count-badge');
            if (badge) badge.innerText = orderCount;

            // Update last-updated time
            const updEl = document.getElementById('staff-last-updated');
            if (updEl) updEl.innerText = new Date().toLocaleTimeString('lo-LA', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            // Visual alert when new orders arrive
            if (_prevPendingCount !== null && orderCount > _prevPendingCount) {
                const header = document.querySelector('#serving-list-container')?.closest('div');
                if (header) {
                    header.classList.add('ring-2', 'ring-orange-400', 'ring-offset-2', 'rounded-2xl');
                    setTimeout(() => header.classList.remove('ring-2', 'ring-orange-400', 'ring-offset-2', 'rounded-2xl'), 3000);
                }
                if (typeof window.app.showToast === 'function') {
                    window.app.showToast(`ມີ ${orderCount - _prevPendingCount} ອໍເດີ້ໃໝ່ເຂົ້າມາ!`, 'info');
                }
            }
            _prevPendingCount = orderCount;

            if (!ordersResult.success || orderCount === 0) {
                container.innerHTML = `
                    <div class="col-span-full p-16 text-center text-gray-400 bg-white rounded-2xl border border-dashed border-gray-300">
                        <i class="fa-solid fa-check-circle text-5xl mb-4 text-green-200 block"></i>
                        <p class="font-medium">ຂະນະນີ້ບໍ່ມີລາຍການອາຫານຄ້າງເສີບ</p>
                        <p class="text-sm mt-1 text-gray-300">ທຸກອໍເດີ້ເສີບຄົບແລ້ວ 🎉</p>
                    </div>`;
                return;
            }

            container.innerHTML = ordersResult.data.map(order => {
                const urgency  = app.getOrderUrgency(order.sale_datetime);
                const elapsed  = app.getElapsedTime(order.sale_datetime);
                const urgencyCfg = {
                    low:    { border: 'border-green-200',  badge: 'bg-green-50 text-green-600',   dot: 'bg-green-400' },
                    medium: { border: 'border-yellow-200', badge: 'bg-yellow-50 text-yellow-600', dot: 'bg-yellow-400' },
                    high:   { border: 'border-red-200',    badge: 'bg-red-50 text-red-600',       dot: 'bg-red-400 animate-pulse' }
                };
                const cfg = urgencyCfg[urgency];

                return `
                <div class="bg-white rounded-2xl shadow-sm border-2 ${cfg.border} p-5 flex flex-col justify-between hover:shadow-md transition-all">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex flex-col gap-1.5">
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2.5 py-0.5 rounded-md w-fit">${order.order_id}</span>
                                <span class="${cfg.badge} text-[10px] font-bold px-2.5 py-0.5 rounded-md w-fit flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full ${cfg.dot} inline-block flex-shrink-0"></span>
                                    ${elapsed}
                                </span>
                            </div>
                            <div class="bg-gradient-to-br from-orange-400 to-orange-600 text-white px-3 py-2 rounded-xl shadow-md text-center min-w-[60px]">
                                <p class="text-[9px] font-bold uppercase opacity-80 leading-none mb-0.5">ໂຕະ</p>
                                <p class="text-3xl font-black leading-none">${order.table_no}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-100">
                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 flex-shrink-0">
                                <i class="fa-solid fa-user text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800 leading-none">${order.customer}</p>
                                <p class="text-[10px] text-gray-400 mt-0.5">${order.items.length} ເມນູ</p>
                            </div>
                        </div>

                        <div class="space-y-1.5 mb-5">
                            ${order.items.map((item, i) => `
                                <div class="flex justify-between items-center ${i % 2 === 0 ? 'bg-gray-50' : 'bg-white'} px-3 py-2 rounded-lg">
                                    <span class="text-gray-700 font-medium text-xs">${item.product_name}</span>
                                    <span class="bg-white border border-gray-200 px-2 py-0.5 rounded-lg text-primary font-bold text-xs shadow-sm">x${item.qty}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <button onclick="app.serveOrder(${order.sale_id})"
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 rounded-xl shadow-md shadow-orange-100 transition-all active:scale-95 flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-utensils"></i> ເສີບແລ້ວ
                    </button>
                </div>`;
            }).join('');

        } catch (error) {
            console.error('Staff dashboard error:', error);
        } finally {
            if (icon) icon.classList.remove('fa-spin');
        }
    };

    app.getOrderUrgency = function(datetimeStr) {
        if (!datetimeStr) return 'low';
        const diffMin = (Date.now() - new Date(datetimeStr).getTime()) / 60000;
        if (diffMin > 15) return 'high';
        if (diffMin > 5)  return 'medium';
        return 'low';
    };

    app.getElapsedTime = function(datetimeStr) {
        if (!datetimeStr) return '--:--';
        const diffSec = Math.floor((Date.now() - new Date(datetimeStr).getTime()) / 1000);
        if (diffSec < 60)  return 'ຫາກສັ່ງ';
        const min = Math.floor(diffSec / 60);
        if (min < 60) return `ລໍມາ ${min} ນາທີ`;
        return `ລໍມາ ${Math.floor(min / 60)} ຊມ. ${min % 60} ນາທີ`;
    };

    app.serveOrder = async function(saleId) {
        const btn = document.querySelector(`button[onclick="app.serveOrder(${saleId})"]`);
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>ກໍາລັງອັບເດດ...'; }

        try {
            const response = await fetch('actions/update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sale_id: saleId })
            });
            const result = await response.json();
            if (result.success) {
                app.showToast('ເສີບອາຫານສໍາເລັດ! 🍜');
                app.updateStaffDashboard();
            } else {
                app.showToast(result.message || 'ເກີດຂໍ້ຜິດພາດ', 'error');
                if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-utensils mr-2"></i>ເສີບແລ້ວ'; }
            }
        } catch (error) {
            console.error('Update error:', error);
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-utensils mr-2"></i>ເສີບແລ້ວ'; }
        }
    };
</script>
