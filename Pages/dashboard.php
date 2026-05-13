<!-- ================= หน้า 2: Dashboard (รายงาน) ================= -->
<div id="page-dashboard" class="absolute inset-0 px-2 sm:p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300 w-full h-full">
    <?php if ($_SESSION['Role'] == 'admin'): ?>
    <!-- แถวตัวเลขสรุป (KPIs) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
        <!-- ยอดขาย -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-blue-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">ยอดขายวันนี้</span>
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-sack-dollar"></i></div>
            </div>
            <h3 class="text-2xl font-black text-gray-800">₭<span id="dash-total-sales">0</span></h3>
            <p class="text-xs text-blue-500 mt-2 font-medium"><i class="fa-solid fa-receipt mr-1"></i> <span id="dash-total-orders">0</span> บิลวันนี้</p>
        </div>
        <!-- กำไรสุทธิ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-green-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">กำไรสุทธิวันนี้</span>
                <div class="w-10 h-10 bg-green-50 text-green-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-chart-line"></i></div>
            </div>
            <h3 class="text-2xl font-black text-gray-800">₭<span id="dash-net-profit">0</span></h3>
            <p class="text-xs text-gray-400 mt-2">คำนวณจาก ยอดขาย - รายจ่าย</p>
        </div>
        <!-- เมนูขายดี -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-orange-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">เมนูยอดนิยม</span>
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-fire"></i></div>
            </div>
            <h3 class="text-lg font-bold text-gray-800 truncate" id="dash-best-seller">-</h3>
            <p class="text-xs text-gray-400 mt-2">เมนูที่ถูกสั่งมากที่สุดวันนี้</p>
        </div>
        <!-- สต็อกวัตถุดิบ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-red-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">แจ้งเตือนสต็อก</span>
                <div class="w-10 h-10 bg-red-50 text-red-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-box-open"></i></div>
            </div>
            <h3 class="text-2xl font-black text-gray-800"><span id="dash-low-stock">0</span> รายการ</h3>
            <p class="text-xs text-red-500 mt-2 font-medium">วัตถุดิบที่ใกล้จะหมดสต็อก</p>
        </div>
    </div>

    <!-- แถวกราฟวิเคราะห์ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- กราฟเส้นแนวโน้มยอดขาย -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h4 class="font-bold text-gray-800">แนวโน้มยอดขาย (7 วันล่าสุด)</h4>
            </div>
            <div class="h-64">
                <canvas id="weeklySalesChart"></canvas>
            </div>
        </div>
        <!-- กราฟวงกลมแยกหมวดหมู่ -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h4 class="font-bold text-gray-800 mb-6">สัดส่วนยอดขายตามหมวดหมู่</h4>
            <div class="h-64">
                <canvas id="categoryDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- รายการขายล่าสุด -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm overflow-hidden border border-gray-100">
            <div class="p-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                <h3 class="font-bold text-gray-800"><i class="fa-solid fa-history mr-2 text-primary"></i>ประวัติการขายล่าสุด</h3>
                <button onclick="app.updateDashboard()" class="text-xs bg-white border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                    <i class="fa-solid fa-rotate mr-1"></i> รีเฟรช
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider">
                            <th class="p-4 border-b">รหัสบิล</th>
                            <th class="p-4 border-b">วันที่/เวลา</th>
                            <th class="p-4 border-b">รายการ</th>
                            <th class="p-4 border-b text-right">ยอดรวม</th>
                        </tr>
                    </thead>
                    <tbody id="order-history-table" class="text-sm"></tbody>
                </table>
            </div>
        </div>
        <!-- สินค้าขายดี 5 อันดับ -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h4 class="font-bold text-gray-800 mb-4">5 อันดับเมนูขายดี</h4>
            <div id="top-products-list" class="space-y-4">
                <!-- ข้อมูลจะถูกโหลดโดย JS -->
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- ส่วนสรุปสำหรับพนักงาน (Staff Summary Header) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-orange-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">ออเดอร์รอเสิร์ฟ</span>
                <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-bell-concierge"></i></div>
            </div>
            <h3 class="text-2xl font-black text-gray-800"><span id="staff-pending-count">0</span> รายการ</h3>
            <p class="text-xs text-gray-400 mt-2">รายการอาหารที่กำลังปรุงหรือรอพนักงานไปเสิร์ฟ</p>
        </div>
        <div class="bg-white rounded-2xl shadow-sm p-6 border-b-4 border-blue-500 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-gray-400 uppercase tracking-wider">พนักงานที่เข้ากะ</span>
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center"><i class="fa-solid fa-user-check"></i></div>
            </div>
            <h3 class="text-xl font-bold text-gray-800 truncate"><?php echo htmlspecialchars($_SESSION['Emp_name']); ?></h3>
            <p class="text-xs text-gray-400 mt-2">สิทธิ์การใช้งาน: <?php echo $_SESSION['Role']; ?></p>
        </div>
    </div>
    <?php endif; ?>

    <div class="max-w-6xl mx-auto <?php echo ($_SESSION['Role'] == 'admin') ? 'mt-10' : ''; ?>">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fa-solid fa-bell-concierge mr-3 text-orange-500"></i>รายการอาหารรอเสิร์ฟ
            </h2>
            <button onclick="app.updateStaffDashboard()" class="bg-white border border-gray-200 px-4 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 transition-all flex items-center gap-2">
                <i class="fa-solid fa-rotate"></i> รีเฟรชรายการ
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="serving-list-container">
            <!-- รายการออเดอร์ที่ยังไม่เสิร์ฟจะถูกโหลดมาแสดงที่นี่ -->
            <div class="col-span-full p-20 text-center text-gray-400 bg-white rounded-2xl border border-dashed border-gray-300">
                <i class="fa-solid fa-check-circle text-5xl mb-4 text-green-200"></i>
                <p>ขณะนี้ไม่มีรายการอาหารค้างเสิร์ฟ</p>
            </div>
        </div>
    </div>
</div>

<!-- เพิ่ม Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Ensure the app namespace exists
    window.app = window.app || {};

    // ตรวจสอบ Role ก่อนรัน Script ของ Admin เพื่อไม่ให้เกิด Error
    document.addEventListener('DOMContentLoaded', function() {
        const userRole = '<?php echo $_SESSION['Role']; ?>';
        if (userRole === 'admin') {
            // โค้ดสำหรับรันกราฟและสรุปยอดขาย
            // app.initAdminCharts(); 
        }

        // เรียกใช้ฟังก์ชันดึงรายการรอเสิร์ฟเสมอ ไม่ว่าจะ Role ไหน
        app.updateStaffDashboard();

        // ตั้งค่า Auto-Refresh ทุกๆ 5 วินาที เพื่อให้เห็นออเดอร์ใหม่เร็วขึ้น
        setInterval(() => {
            const dashboardPage = document.getElementById('page-dashboard');
            // ตรวจสอบว่าหน้า Dashboard กำลังแสดงผลอยู่หรือไม่ (ไม่มีคลาส hidden) ก่อนดึงข้อมูลใหม่
            if (dashboardPage && !dashboardPage.classList.contains('hidden')) {
                app.updateStaffDashboard();
            }
        }, 5000);
    });

    app.updateStaffDashboard = async function() {
        const container = document.getElementById('serving-list-container');
        
        try {
            const response = await fetch('actions/get_pending_orders.php');
            const result = await response.json();
            console.log("Pending Orders Data:", result); // ตรวจสอบข้อมูลใน Console (F12)

            // อัปเดตตัวเลขจำนวนรายการรอเสิร์ฟในส่วนหัว (ถ้ามี element นี้)
            const pendingCountEl = document.getElementById('staff-pending-count');
            if (pendingCountEl) {
                pendingCountEl.innerText = (result.success && result.data) ? result.data.length : 0;
            }

            if (!result.success || result.data.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full p-20 text-center text-gray-400 bg-white rounded-2xl border border-dashed border-gray-300">
                        <i class="fa-solid fa-check-circle text-5xl mb-4 text-green-200"></i>
                        <p>ขณะนี้ไม่มีรายการอาหารค้างเสิร์ฟ</p>
                    </div>`;
                return;
            }

            container.innerHTML = result.data.map(order => `
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col justify-between hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex flex-col">
                                <span class="bg-blue-50 text-blue-600 text-[10px] font-black px-2 py-0.5 rounded-md mb-1 w-fit">${order.order_id}</span>
                                <span class="text-[10px] text-gray-400 font-medium"><i class="fa-regular fa-clock mr-1"></i> ${order.time}</span>
                            </div>
                            <div class="bg-gradient-to-br from-orange-400 to-orange-600 text-white px-4 py-2 rounded-xl shadow-md border border-orange-700 text-center min-w-[80px]">
                                <p class="text-[10px] font-bold uppercase leading-none mb-1 opacity-80">โต๊ะ</p>
                                <p class="text-3xl font-black leading-none">${order.table_no}</p>
                            </div>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fa-solid fa-circle-user text-gray-300 mr-2 text-lg"></i> ${order.customer}
                        </h4>
                        <div class="space-y-2 mb-6">
                            ${order.items.map(item => `
                                <div class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded-lg">
                                    <span class="text-gray-700 font-medium">${item.product_name}</span>
                                    <span class="bg-white border px-2 py-0.5 rounded text-primary font-bold">x ${item.qty}</span>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                    <button onclick="app.serveOrder(${order.sale_id})" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-orange-100 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-utensils"></i> เสิร์ฟแล้ว
                    </button>
                </div>
            `).join('');

        } catch (error) {
            console.error('Fetch error:', error);
        }
    };

    app.serveOrder = async function(saleId) {
        try {
            const response = await fetch('actions/update_order_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ sale_id: saleId })
            });
            const result = await response.json();
            if (result.success) app.updateStaffDashboard();
        } catch (error) { console.error('Update error:', error); }
    };
</script>