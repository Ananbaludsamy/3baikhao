        </div>
    </main>

    <!-- ระบบแจ้งเตือน (Toast Message) -->
    <div id="toastBox" class="fixed top-5 left-1/2 transform -translate-x-1/2 bg-white px-6 py-3 border-l-4 border-green-500 shadow-xl rounded-md z-50 flex items-center gap-3 transition-all duration-300 opacity-0 pointer-events-none translate-y-[-20px]">
        <i class="fa-solid fa-circle-check text-green-500 text-xl" id="toastIcon"></i>
        <span id="toastMessage" class="font-semibold text-gray-700 whitespace-nowrap">ข้อความแจ้งเตือน</span>
    </div>

    <!-- โค้ด JavaScript จัดการข้อมูลแบบออฟไลน์ (Local Storage) เพื่อให้รันใน XAMPP ได้ง่าย -->
    <script>
        const state = {
            products: [],
            orders: [],
            cart: [],
            currentPage: 'pos',
            isMobileCartOpen: false,
            currentFilter: 'all',
            productTypes: [], // เพิ่มตัวแปรเก็บประเภทสินค้าสำหรับใช้ค้นหา
            materialTypes: [], // เพิ่มตัวแปรเก็บประเภทวัตถุดิบสำหรับใช้ค้นหา
            materials: [] // เพิ่มตัวแปรเก็บข้อมูลวัตถุดิบทั้งหมด
        };

        const mainFunctions = {
            init: function() {
                this.loadDatabase();
                this.updateTime();
                this.loadMaterials(); // โหลดข้อมูลวัตถุดิบเริ่มต้น
                this.renderTableSelection(); // สร้างปุ่มเลือกโต๊ะ
                setInterval(() => this.updateTime(), 1000);
                setInterval(() => this.renderTableSelection(), 10000); // อัปเดตสถานะโต๊ะทุก 10 วินาที
            },

            formatCurrency: function(amount) {
                return Number(amount).toLocaleString('lo-LA', {
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                });
            },

            loadDatabase: function() {
                // ตรวจสอบว่ามีข้อมูลจาก Server หรือไม่
                if (window.serverData && window.serverData.products) {
                    // แปลงชื่อฟิลด์จาก DB ให้ตรงกับที่ JS ใช้งาน
                    state.products = window.serverData.products.map(p => ({
                        id: p.Product_id,
                        name: p.Product_name,
                        price: parseFloat(p.Product_Sale),
                        img: p.Product_img || '', 
                        cat: p.ProductType_id.toString()
                    }));
                }
                
                const storedOrders = localStorage.getItem('noodle_orders');
                if (storedOrders) {
                    state.orders = JSON.parse(storedOrders);
                }

                this.renderProducts();
                this.renderMenuMgmt();
                this.updateDashboard();
            },

            seedInitialProducts: function() {
                const initData = [
                    { id: '1', name: 'ก๋วยเตี๋ยวเรือหมูตุ๋น', price: 50, img: 'https://images.unsplash.com/photo-1552611052-33e04de081de?auto=format&fit=crop&w=300&q=80', cat: 'noodle' },
                    { id: '2', name: 'ก๋วยเตี๋ยวเรือเนื้อเปื่อย', price: 60, img: 'https://images.unsplash.com/photo-1626804475297-41607ea0af49?auto=format&fit=crop&w=300&q=80', cat: 'noodle' },
                    { id: '3', name: 'ก๋วยเตี๋ยวเรือน้ำตกหมู', price: 50, img: 'https://images.unsplash.com/photo-1598514982205-f36b96d1e8dd?auto=format&fit=crop&w=300&q=80', cat: 'noodle' },
                    { id: '4', name: 'แคบหมู', price: 15, img: 'https://images.unsplash.com/photo-1621506289937-a8e4df240d0b?auto=format&fit=crop&w=300&q=80', cat: 'snack' },
                    { id: '5', name: 'ลูกชิ้นปิ้ง', price: 15, img: 'https://images.unsplash.com/photo-1525059696034-4967a8e1dca2?auto=format&fit=crop&w=300&q=80', cat: 'snack' },
                    { id: '6', name: 'ชาดำเย็น', price: 25, img: 'https://images.unsplash.com/photo-1544145945-f90425340c7e?auto=format&fit=crop&w=300&q=80', cat: 'drink' }
                ];
                state.products = initData;
                this.saveProductsToStorage();
            },

            saveProductsToStorage: function() {
                localStorage.setItem('noodle_products', JSON.stringify(state.products));
            },

            saveOrdersToStorage: function() {
                localStorage.setItem('noodle_orders', JSON.stringify(state.orders));
            },

            setFilter: function(cat) {
                state.currentFilter = cat;
                
                // อัปเดต CSS ของปุ่ม Filter ทุกปุ่ม
                const filterButtons = document.querySelectorAll('.filter-btn');
                filterButtons.forEach(btn => {
                    const btnId = btn.id.replace('filter-', '');
                    const isActive = btnId === cat.toString();
                    
                    btn.className = isActive
                        ? 'filter-btn px-4 py-2 bg-primary text-white rounded-full whitespace-nowrap shadow transition-colors'
                        : 'filter-btn px-4 py-2 bg-white text-gray-600 hover:bg-gray-200 rounded-full whitespace-nowrap shadow-sm border border-gray-200 transition-colors';
                });
                this.renderProducts();
            },

            renderProducts: function() {
                const grid = document.getElementById('product-grid');
                grid.innerHTML = '';
                
                const filteredProducts = state.currentFilter === 'all' 
                    ? state.products 
                    : state.products.filter(p => p.cat === state.currentFilter);

                if (filteredProducts.length === 0) {
                    grid.innerHTML = `<div class="col-span-full py-10 text-center text-gray-400">ยังไม่มีรายการอาหารในหมวดหมู่นี้</div>`;
                    return;
                }
                
                filteredProducts.forEach(product => {
                    const el = document.createElement('div');
                    el.className = 'bg-white rounded-lg shadow-sm hover:shadow-md transition-all overflow-hidden cursor-pointer border border-gray-100 flex flex-col group';
                    el.onclick = () => this.addToCart(product.id);
                    el.innerHTML = `
                        <div class="h-20 sm:h-24 md:h-28 bg-gray-200 overflow-hidden relative">
                            <img src="${product.img}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=300&q=80'">
                        </div>
                        <div class="p-2 sm:p-3 flex flex-col flex-1">
                            <h4 class="font-medium text-[10px] sm:text-xs md:text-sm text-gray-800 leading-tight mb-1 flex-1">${product.name}</h4>
                            <div class="flex justify-between items-center mt-auto gap-1">
                                <span class="text-primary font-bold text-xs sm:text-sm">₭${this.formatCurrency(product.price)}</span>
                                <button class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 rounded-full bg-orange-100 text-primary group-hover:bg-primary group-hover:text-white transition-colors flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-plus text-[8px] sm:text-xs"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    grid.appendChild(el);
                });
            },

            addToCart: function(productId) {
                const product = state.products.find(p => p.id == productId);
                const existingItem = state.cart.find(item => item.id == productId);
                if (existingItem) existingItem.qty += 1;
                else state.cart.push({ ...product, qty: 1 });
                
                this.renderCart();
                if(window.innerWidth < 1024 && state.cart.length === 1 && !state.isMobileCartOpen) this.toggleMobileCart();
            },

            decreaseQty: function(productId) {
                const index = state.cart.findIndex(item => item.id == productId);
                if (index !== -1) {
                    if (state.cart[index].qty > 1) state.cart[index].qty -= 1;
                    else state.cart.splice(index, 1);
                    this.renderCart();
                }
            },

            removeFromCart: function(productId) {
                state.cart = state.cart.filter(item => item.id != productId);
                this.renderCart();
            },

            renderCart: function() {
                const container = document.getElementById('cart-items-container');
                const cartBadge = document.getElementById('cart-count-badge');
                const checkoutBtn = document.getElementById('checkout-btn');
                let totalQty = 0, totalPrice = 0;

                if (state.cart.length === 0) {
                    container.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-gray-400 py-10"><i class="fa-solid fa-basket-shopping text-4xl mb-3 text-gray-300"></i><p>ยังไม่มีรายการอาหาร</p></div>`;
                    checkoutBtn.disabled = true;
                } else {
                    checkoutBtn.disabled = false;
                    container.innerHTML = '';
                    state.cart.forEach(item => {
                        totalQty += item.qty; totalPrice += (item.price * item.qty);
                        const el = document.createElement('div');
                        el.className = 'flex justify-between items-center mb-3 p-3 bg-white rounded-lg shadow-sm border border-gray-100';
                        el.innerHTML = `
                            <button onclick="app.removeFromCart('${item.id}')" class="mr-2 text-gray-300 hover:text-red-500 transition-colors">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                            <div class="flex-1 min-w-0 pr-2">
                                <h5 class="text-sm font-medium text-gray-800 truncate">${item.name}</h5>
                                <span class="text-xs text-gray-500">₭${this.formatCurrency(item.price)}</span>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">
                                <div class="flex items-center bg-gray-100 rounded-lg p-1 border border-gray-200">
                                    <button onclick="app.decreaseQty('${item.id}')" class="w-6 h-6 flex items-center justify-center text-primary bg-white hover:bg-gray-50 rounded shadow-sm"><i class="fa-solid fa-minus text-[10px]"></i></button>
                                    <span class="w-6 text-center text-sm font-bold text-gray-700">${item.qty}</span>
                                    <button onclick="app.addToCart('${item.id}')" class="w-6 h-6 flex items-center justify-center text-primary bg-white hover:bg-gray-50 rounded shadow-sm"><i class="fa-solid fa-plus text-[10px]"></i></button>
                                </div>
                                <span class="text-sm font-bold w-12 text-right text-primary">₭${this.formatCurrency(item.price * item.qty)}</span>
                            </div>
                        `;
                        container.appendChild(el);
                    });
                }
                document.getElementById('cart-total-qty').innerText = totalQty;
                document.getElementById('cart-subtotal').innerText = this.formatCurrency(totalPrice);
                document.getElementById('cart-total').innerText = this.formatCurrency(totalPrice);
                cartBadge.innerText = totalQty;
                this.calculateChange(); // คำนวณเงินทอนใหม่ทุกครั้งที่ตะกร้าเปลี่ยน
            },

            renderTableSelection: async function() {
                const grid = document.getElementById('table-grid');
                if (!grid) return;

                try {
                    const response = await fetch('actions/get_pending_orders.php');
                    const result = await response.json();
                    const occupiedTables = result.success ? result.data.map(o => o.table_no.toString().trim()) : [];
                    const currentSelected = document.getElementById('table-number').value;

                    grid.innerHTML = '';
                    // สมมติว่าร้านมี 20 โต๊ะ (สามารถปรับตัวเลขได้ตามจริง)
                    for (let i = 1; i <= 20; i++) {
                        const tableNo = i.toString();
                        const isOccupied = occupiedTables.includes(tableNo);
                        const isSelected = currentSelected === tableNo;

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.onclick = () => isOccupied ? this.showToast('โต๊ะนี้มีออเดอร์ค้างอยู่', 'error') : this.selectTable(tableNo);

                        let bgColor = 'bg-white border-gray-200 text-gray-600 hover:bg-orange-50';
                        if (isOccupied) bgColor = 'bg-red-100 border-red-200 text-red-500 cursor-not-allowed';
                        if (isSelected) bgColor = 'bg-primary border-primary text-white shadow-md scale-95';

                        btn.className = `${bgColor} border p-2 rounded-lg text-sm font-bold transition-all flex flex-col items-center justify-center h-12`;
                        btn.innerHTML = `<span class="leading-none">${tableNo}</span><span class="text-[8px] uppercase mt-1">${isOccupied ? 'ไม่ว่าง' : 'ว่าง'}</span>`;
                        grid.appendChild(btn);
                    }
                } catch (err) { console.error('Table status error:', err); }
            },

            selectTable: function(no) {
                document.getElementById('table-number').value = no;
                this.renderTableSelection();
            },

            setQuickCash: function(amount) {
                document.getElementById('cash-received').value = amount;
                this.calculateChange();
            },

            calculateChange: function() {
                const total = state.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const received = parseFloat(document.getElementById('cash-received').value) || 0;
                const change = received - total;
                
                const changeEl = document.getElementById('cart-change');
                changeEl.innerText = this.formatCurrency(change);
                
                // เปลี่ยนสีเตือนหากเงินรับไม่พอ
                if (change < 0) {
                    changeEl.parentElement.classList.replace('text-orange-600', 'text-red-500');
                } else {
                    changeEl.parentElement.classList.replace('text-red-500', 'text-orange-600');
                }
            },

            checkout: function() {
                if (state.cart.length === 0) return;

                const totalAmount = state.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const received = parseFloat(document.getElementById('cash-received').value) || 0;
                const tableNo = document.getElementById('table-number').value.trim();

                // ตรวจสอบหมายเลขโต๊ะก่อนชำระเงิน
                if (!tableNo) {
                    window.app.showToast('กรุณาระบุหมายเลขโต๊ะก่อนชำระเงิน', 'error');
                    document.getElementById('table-number').focus();
                    return;
                }

                if (received < totalAmount) {
                    window.app.showToast('ยอดเงินรับไม่เพียงพอ', 'error');
                    return;
                }

                const checkoutBtn = document.getElementById('checkout-btn');
                const originalText = checkoutBtn.innerHTML;

                // ปิดปุ่มและแสดงสถานะกำลังประมวลผล เพื่อป้องกันการกดซ้ำหรือเครื่องค้าง
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> กำลังบันทึก...';

                // เตรียมข้อมูลส่งไปที่ Server
                const postData = {
                    cart: state.cart,
                    total: totalAmount,
                    cus_id: 1, // TODO: พัฒนาระบบเลือกลูกค้าในหน้า POS
                    emp_id: window.serverData.user.id,
                    table_no: tableNo
                };

                // ส่งข้อมูลด้วย fetch ไปที่ save_sale.php
                fetch('actions/save_sale.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(postData)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const now = new Date();
                        const orderData = {
                            id: result.sale_id,
                            orderId: result.order_id,
                            items: state.cart.map(i => ({ name: i.name, qty: i.qty, price: i.price })),
                            total: totalAmount,
                            timestamp: now.getTime(),
                            dateStr: now.toLocaleDateString('th-TH'),
                            timeStr: now.toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' })
                        };

                        state.orders.unshift(orderData); 
                        window.app.saveOrdersToStorage(); // เก็บประวัติไว้ในเครื่องด้วยเพื่อความเร็ว

                        state.cart = [];
                        window.app.renderCart();
                        
                        // ล้างค่าเงินรับและเลขโต๊ะ
                        document.getElementById('cash-received').value = '';
                        document.getElementById('table-number').value = '';
                        window.app.renderTableSelection();
                        
                        // เรียกใช้ผ่าน window.app เพื่อให้มั่นใจว่าเรียกฟังก์ชันที่ถูกรวมแล้ว
                        if (typeof window.app.updateStaffDashboard === 'function') {
                            window.app.updateStaffDashboard();
                        }
                        
                        window.app.updateDashboard(); // อัปเดตสถิติ (ถ้าเป็น Admin)
                        if(state.isMobileCartOpen) window.app.toggleMobileCart();
                        window.app.showToast(`ชำระเงินสำเร็จ! รหัสบิล: ${result.order_id}`);
                        
                        // เปิดหน้าพิมพ์ใบเสร็จอัตโนมัติ
                        const change = received - totalAmount;
                        window.open(`print_receipt.php?id=${result.sale_id}&cash=${received}&change=${change}&table=${tableNo}`, '_blank', 'width=400,height=600');

                        // เปลี่ยนหน้าไปยังหน้า รายการอาหารรอเสิร์ฟ (Dashboard) ทันที
                        window.app.switchPage('dashboard');

                        setTimeout(() => {
                            checkoutBtn.disabled = false;
                            checkoutBtn.innerHTML = originalText;
                        }, 2000);
                    } else {
                        window.app.showToast(result.message || 'บันทึกข้อมูลไม่สำเร็จ', 'error');
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.app.showToast('ไม่สามารถเชื่อมต่อฐานข้อมูลได้', 'error');
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = originalText;
                });
            },

            updateDashboard: function() {
                // ตัวแปรเก็บ Chart instances เพื่อทำลายทิ้งก่อนสร้างใหม่ (ป้องกันการซ้อนกัน)
                if (window.salesTrendChart instanceof Chart) window.salesTrendChart.destroy();
                if (window.catDistChart instanceof Chart) window.catDistChart.destroy();

                fetch('actions/get_sales_report.php')
                .then(response => response.json())
                .then(result => {
                    if (!result.success) {
                        console.error(result.message);
                        return;
                    }

                    // ตรวจสอบว่ามี Element ของ Admin หรือไม่ก่อนอัปเดต (ป้องกัน Error สำหรับ Staff)
                    const totalSalesEl = document.getElementById('dash-total-sales');
                    if (totalSalesEl) {
                        totalSalesEl.innerText = this.formatCurrency(result.stats.total_sales);
                        document.getElementById('dash-total-orders').innerText = result.stats.total_orders;
                        document.getElementById('dash-best-seller').innerText = result.stats.best_seller;
                        document.getElementById('dash-net-profit').innerText = this.formatCurrency(result.stats.net_profit);
                        document.getElementById('dash-low-stock').innerText = result.stats.low_stock;
                    }

                    // 2. อัปเดตตารางประวัติการขาย
                    const tbody = document.getElementById('order-history-table');
                    if (tbody && result.history.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="p-8 text-center text-gray-400">ยังไม่มีข้อมูลการขายในระบบ</td></tr>';
                    } else if (tbody) {
                        tbody.innerHTML = '';
                        result.history.forEach(order => {
                            const saleDate = new Date(order.Sale_date).toLocaleDateString('th-TH');
                            const orderId = 'ORD-' + order.Sale_id.toString().padStart(5, '0');
                            
                            const tr = document.createElement('tr');
                            tr.className = 'border-b border-gray-100 hover:bg-gray-50';
                            tr.innerHTML = `
                                <td class="p-3 text-sm font-medium text-gray-800">${orderId}</td>
                                <td class="p-3 text-xs sm:text-sm text-gray-500">${saleDate}</td>
                                <td class="p-3 text-sm text-gray-600 truncate max-w-[150px] lg:max-w-xs" title="${order.items}">${order.items || '-'}</td>
                                <td class="p-3 text-sm font-bold text-green-600 text-right">₭${this.formatCurrency(order.Sale_sumprice)}</td>
                            `;
                            tbody.appendChild(tr);
                        });
                    }

                    // 3. อัปเดตรายการสินค้าขายดี 5 อันดับ
                    const topList = document.getElementById('top-products-list');
                    if (topList) {
                        topList.innerHTML = result.top5.map((p, index) => `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-500">${index + 1}</span>
                                    <span class="text-sm font-medium text-gray-700">${p.Product_name}</span>
                                </div>
                                <span class="text-sm font-bold text-primary">${p.qty} จาน</span>
                            </div>
                        `).join('');
                    }

                    // 4. วาดกราฟแนวโน้มยอดขาย (Line Chart)
                    const trendCtx = document.getElementById('weeklySalesChart')?.getContext('2d');
                    if (trendCtx) {
                        window.salesTrendChart = new Chart(trendCtx, {
                            type: 'line',
                            data: {
                                labels: result.trend.map(t => new Date(t.Sale_date).toLocaleDateString('th-TH', {day:'numeric', month:'short'})),
                                datasets: [{
                                    label: 'ยอดขาย (₭)',
                                    data: result.trend.map(t => t.total),
                                    borderColor: '#3b82f6',
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                        });
                    }

                    // 5. วาดกราฟสัดส่วนหมวดหมู่ (Doughnut Chart)
                    const catCtx = document.getElementById('categoryDistributionChart')?.getContext('2d');
                    if (catCtx) {
                        window.catDistChart = new Chart(catCtx, {
                            type: 'doughnut',
                            data: {
                                labels: result.category_dist.map(c => c.ProductType_name),
                                datasets: [{
                                    data: result.category_dist.map(c => c.total),
                                    backgroundColor: ['#ef4444', '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6']
                                }]
                            },
                            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching dashboard data:', error);
                    });
            },

            saveProduct: function(e) {
                e.preventDefault();
                const id = document.getElementById('edit-product-id').value;
                const name = document.getElementById('new-item-name').value;
                const price = parseFloat(document.getElementById('new-item-price').value);
                const cat = document.getElementById('new-item-cat').value;
                const imgFile = document.getElementById('new-item-img').files[0];
                
                const formData = new FormData();
                if (id) formData.append('id', id);
                formData.append('name', name);
                formData.append('price', price);
                formData.append('cat', cat);
                if (imgFile) formData.append('img', imgFile);

                const url = id ? 'actions/update_product.php' : 'actions/add_product.php';

                fetch(url, {
                    method: 'POST',
                    body: formData // ส่งเป็น FormData เพื่อรองรับไฟล์
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        const prodData = {
                            id: result.product.id,
                            name: result.product.name,
                            price: parseFloat(result.product.price),
                            cat: result.product.cat.toString(),
                            img: result.product.img
                        };

                        if (id) {
                            // กรณีแก้ไข: หาตำแหน่งแล้วแทนที่ข้อมูล
                            const index = state.products.findIndex(p => p.id == id);
                            if (index !== -1) state.products[index] = prodData;
                        } else {
                            // กรณีเพิ่มใหม่: ใส่ไว้บนสุด
                            state.products.unshift(prodData);
                        }

                        this.resetProductForm();
                        this.renderProducts();
                        this.renderMenuMgmt();
                        this.showToast(id ? 'อัปเดตเมนูสำเร็จ' : 'เพิ่มเมนูใหม่สำเร็จ');
                    } else {
                        this.showToast(result.message || 'ดำเนินการไม่สำเร็จ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showToast('ไม่สามารถเชื่อมต่อฐานข้อมูลได้', 'error');
                });
            },

            editProduct: function(id) {
                const product = state.products.find(p => p.id == id);
                if (!product) return;

                document.getElementById('edit-product-id').value = product.id;
                document.getElementById('new-item-name').value = product.name;
                document.getElementById('new-item-price').value = product.price;
                document.getElementById('new-item-cat').value = product.cat;
                
                document.getElementById('product-form-title').innerHTML = '<i class="fa-solid fa-edit mr-2"></i>แก้ไขเมนูอาหาร';
                document.getElementById('product-submit-btn').innerHTML = '<i class="fa-solid fa-save mr-2"></i> บันทึกการแก้ไข';
                document.getElementById('product-cancel-btn').classList.remove('hidden');
                
                // เลื่อนขึ้นไปที่ฟอร์ม
                document.getElementById('page-menu').scrollTo({ top: 0, behavior: 'smooth' });
            },

            resetProductForm: function() {
                document.getElementById('add-product-form').reset();
                document.getElementById('edit-product-id').value = '';
                document.getElementById('product-form-title').innerHTML = '<i class="fa-solid fa-plus-circle mr-2"></i>เพิ่มเมนูใหม่เข้าร้าน';
                document.getElementById('product-submit-btn').innerHTML = '<i class="fa-solid fa-save mr-2"></i> บันทึกเมนู';
                document.getElementById('product-cancel-btn').classList.add('hidden');
            },

            deleteProduct: function(id) {
                if (!confirm('ยืนยันการลบเมนูนี้ออกจากระบบ?')) return;

                fetch('actions/delete_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        state.products = state.products.filter(p => p.id !== id);
                        this.renderProducts();
                        this.renderMenuMgmt();
                        this.showToast('ลบเมนูสำเร็จ');
                    } else {
                        this.showToast(result.message || 'ลบเมนูไม่สำเร็จ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showToast('ไม่สามารถเชื่อมต่อฐานข้อมูลได้', 'error');
                });
            },

            renderMenuMgmt: function() {
                const tbody = document.getElementById('menu-mgmt-table');
                if (!tbody) return; // ป้องกัน Error หากไม่มีตารางในหน้านี้ (เช่น เมื่อ Staff ล็อกอิน)
                tbody.innerHTML = '';
                if (state.products.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">ไม่มีเมนูในระบบ</td></tr>';
                    return;
                }
                
                // สร้าง Object สำหรับแมป ID หมวดหมู่กับชื่อ
                const catMap = {};
                if(window.serverData) window.serverData.categories.forEach(c => catMap[c.ProductType_id] = c.ProductType_name);

                state.products.forEach(p => {
                    const tr = document.createElement('tr');
                    tr.className = 'border-b border-gray-100 hover:bg-gray-50';
                    tr.innerHTML = `
                        <td class="p-2"><img src="${p.img}" class="w-10 h-10 rounded object-cover border border-gray-200"></td>
                        <td class="p-3 text-sm font-medium text-gray-800">${p.name}</td>
                        <td class="p-3 text-sm text-gray-500"><span class="bg-gray-100 px-2 py-1 rounded text-xs border border-gray-200">${catMap[p.cat] || p.cat}</span></td>
                        <td class="p-3 text-sm font-bold text-primary">₭${this.formatCurrency(p.price)}</td>
                        <td class="p-3 text-center flex justify-center gap-1">
                            <button onclick="app.editProduct('${p.id}')" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-full"><i class="fa-solid fa-edit"></i></button>
                            <button onclick="app.deleteProduct('${p.id}')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-full"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            },

            // ================= ส่วนจัดการข้อมูลลูกค้า (Customer Functions) =================
            loadCustomers: function() {
                fetch('actions/get_customers.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.renderCustomers(result.customers);
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading customers:', error);
                    this.showToast('ไม่สามารถโหลดข้อมูลลูกค้าได้', 'error');
                });
            },

            renderCustomers: function(customers) {
                const tbody = document.getElementById('customer-table-body');
                if (!tbody) return;
                if (customers.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">ยังไม่มีข้อมูลลูกค้าในระบบ</td></tr>';
                    return;
                }
                tbody.innerHTML = customers.map(cus => `
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="p-3 text-sm text-gray-500">${cus.Cus_id}</td>
                        <td class="p-3 text-sm font-medium text-gray-800">${cus.Cus_name}</td>
                        <td class="p-3 text-sm text-gray-500">${cus.Cus_Tel}</td>
                        <td class="p-3 text-sm text-gray-500 truncate max-w-xs">${cus.Cus_Address}</td>
                        <td class="p-3 text-center">
                            <button onclick="app.editCustomer(${cus.Cus_id})" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-full mr-1">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="app.deleteCustomer(${cus.Cus_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-full">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            },

            saveCustomer: function(e) {
                e.preventDefault();
                const cusId = document.getElementById('cus-id').value;
                const name = document.getElementById('cus-name').value;
                const tel = document.getElementById('cus-tel').value;
                const address = document.getElementById('cus-address').value;

                const postData = { cus_id: cusId, name, tel, address };
                const url = cusId ? 'actions/update_customer.php' : 'actions/add_customer.php';

                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(postData)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.resetCustomerForm();
                        this.loadCustomers();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving customer:', error);
                    this.showToast('ไม่สามารถบันทึกข้อมูลได้', 'error');
                });
            },

            editCustomer: function(cusId) {
                fetch(`actions/get_customer_by_id.php?id=${cusId}`)
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        const cus = result.customer;
                        document.getElementById('cus-id').value = cus.Cus_id;
                        document.getElementById('cus-name').value = cus.Cus_name;
                        document.getElementById('cus-tel').value = cus.Cus_Tel;
                        document.getElementById('cus-address').value = cus.Cus_Address;
                        document.getElementById('customer-form-title').innerHTML = '<i class="fa-solid fa-user-edit mr-2"></i>แก้ไขข้อมูลลูกค้า';
                        document.getElementById('customer-submit-btn').innerText = 'อัปเดตข้อมูล';
                        // เลื่อนขึ้นไปที่ฟอร์ม
                        document.getElementById('page-customers').scrollTo({ top: 0, behavior: 'smooth' });
                    }
                });
            },

            resetCustomerForm: function() {
                document.getElementById('customer-form').reset();
                document.getElementById('cus-id').value = '';
                document.getElementById('customer-form-title').innerHTML = '<i class="fa-solid fa-user-plus mr-2"></i>เพิ่มลูกค้าใหม่';
                document.getElementById('customer-submit-btn').innerText = 'บันทึกข้อมูล';
            },

            deleteCustomer: function(cusId) {
                if (!confirm('ยืนยันการลบข้อมูลลูกค้าท่านนี้?')) return;

                fetch('actions/delete_customer.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cus_id: cusId })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.loadCustomers();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting customer:', error);
                    this.showToast('ไม่สามารถลบข้อมูลได้', 'error');
                });
            },

            // ================= ส่วนจัดการข้อมูลพนักงาน (Employee Functions) =================
            loadEmployees: function() {
                fetch('actions/get_employees.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.renderEmployees(result.employees);
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading employees:', error);
                    this.showToast('ไม่สามารถโหลดข้อมูลพนักงานได้', 'error');
                });
            },

            renderEmployees: function(employees) {
                const tbody = document.getElementById('employee-table-body');
                if (!tbody) return;
                if (employees.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">ยังไม่มีข้อมูลพนักงานในระบบ</td></tr>';
                    return;
                }
                tbody.innerHTML = employees.map(emp => `
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="p-3 text-sm font-medium text-gray-800">${emp.Emp_name}</td>
                        <td class="p-3 text-sm text-gray-500">${emp.Emp_Tel}</td>
                        <td class="p-3 text-sm text-gray-500">${emp.Username}</td>
                        <td class="p-3 text-sm text-gray-500">
                            <span class="px-2 py-1 rounded text-xs ${emp.Role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700'}">
                                ${emp.Role === 'admin' ? 'ผู้ดูแลระบบ' : 'พนักงาน'}
                            </span>
                        </td>
                        <td class="p-3 text-center">
                            <button onclick="app.editEmployee(${emp.Emp_id})" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-full mr-1">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="app.deleteEmployee(${emp.Emp_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-full">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            },

            saveEmployee: function(e) {
                e.preventDefault();
                const empId = document.getElementById('emp-id').value;
                const name = document.getElementById('emp-name').value;
                const card = document.getElementById('emp-card').value;
                const address = document.getElementById('emp-address').value;
                const tel = document.getElementById('emp-tel').value;
                const gender = document.getElementById('emp-gender').value;
                const username = document.getElementById('emp-username').value;
                const password = document.getElementById('emp-password').value;
                const role = document.getElementById('emp-role').value;

                const postData = { emp_id: empId, name, card, address, tel, gender, username, password, role };
                const url = empId ? 'actions/update_employee.php' : 'actions/add_employee.php';

                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(postData)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.resetEmployeeForm();
                        this.loadEmployees();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving employee:', error);
                    this.showToast('ไม่สามารถบันทึกข้อมูลได้', 'error');
                });
            },

            editEmployee: function(empId) {
                fetch(`actions/get_employee_by_id.php?id=${empId}`)
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        const emp = result.employee;
                        document.getElementById('emp-id').value = emp.Emp_id;
                        document.getElementById('emp-name').value = emp.Emp_name;
                        document.getElementById('emp-card').value = emp.Emp_card;
                        document.getElementById('emp-address').value = emp.Emp_Address;
                        document.getElementById('emp-tel').value = emp.Emp_Tel;
                        document.getElementById('emp-gender').value = emp.Emp_gender;
                        document.getElementById('emp-username').value = emp.Username;
                        document.getElementById('emp-password').value = ''; // ล้างรหัสผ่านเพื่อความปลอดภัย (กรอกใหม่เฉพาะตอนจะเปลี่ยน)
                        document.getElementById('emp-role').value = emp.Role;
                        
                        document.getElementById('employee-form-title').innerHTML = '<i class="fa-solid fa-user-edit mr-2"></i>แก้ไขข้อมูลพนักงาน';
                        document.getElementById('employee-submit-btn').innerText = 'อัปเดตข้อมูล';
                        document.getElementById('page-employees').scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            resetEmployeeForm: function() {
                document.getElementById('employee-form').reset();
                document.getElementById('emp-id').value = '';
                document.getElementById('employee-form-title').innerHTML = '<i class="fa-solid fa-user-plus mr-2"></i>เพิ่มพนักงานใหม่';
                document.getElementById('employee-submit-btn').innerText = 'บันทึกพนักงาน';
            },

            deleteEmployee: function(empId) {
                if (!confirm('ยืนยันการลบพนักงานท่านนี้?')) return;

                fetch('actions/delete_employee.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ emp_id: empId })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.loadEmployees();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting employee:', error);
                    this.showToast('ไม่สามารถลบข้อมูลได้', 'error');
                });
            },

            // ================= ส่วนจัดการการเงิน (Finance Functions) =================
            loadFinance: function() {
                fetch('actions/get_finance.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        document.getElementById('fin-revenue').innerText = this.formatCurrency(result.total_revenue);
                        document.getElementById('fin-expense').innerText = this.formatCurrency(result.total_expense);
                        
                        const tbody = document.getElementById('finance-table-body');
                        if (tbody) {
                            tbody.innerHTML = result.history.map(h => `
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm text-gray-500">${new Date(h.Revenue_date).toLocaleDateString('th-TH')}</td>
                                    <td class="p-3 text-sm font-medium">
                                        <span class="mr-2 ${h.Revenue_id == 1 ? 'text-green-500' : 'text-red-500'}">
                                            <i class="fa-solid ${h.Revenue_id == 1 ? 'fa-circle-plus' : 'fa-circle-minus'}"></i>
                                        </span>
                                        ${h.Revenue_name}
                                    </td>
                                    <td class="p-3 text-sm text-right font-bold ${h.Revenue_id == 1 ? 'text-green-600' : 'text-red-600'}">
                                        ${h.Revenue_id == 1 ? '+' : '-'}₭${this.formatCurrency(h.Revenue_Price)}
                                    </td>
                                </tr>
                            `).join('');
                        }
                    }
                });
            },

            saveFinance: function(e) {
                e.preventDefault();
                const type_id = document.getElementById('fin-type').value;
                const name = document.getElementById('fin-name').value;
                const amount = document.getElementById('fin-amount').value;

                fetch('actions/save_finance.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type_id, name, amount })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        document.getElementById('fin-name').value = '';
                        document.getElementById('fin-amount').value = '';
                        this.loadFinance();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            // ================= ส่วนจัดการประเภทวัตถุดิบ (Material Types Functions) =================
            loadMaterialTypes: function() {
                fetch('actions/get_material_types.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        state.materialTypes = result.material_types; // เก็บข้อมูลไว้ใน state เพื่อใช้ค้นหา
                        this.renderMaterialTypes(state.materialTypes);
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading material types:', error);
                    this.showToast('ไม่สามารถโหลดข้อมูลประเภทวัตถุดิบได้', 'error');
                });
            },

            renderMaterialTypes: function(materialTypes) {
                const tbody = document.getElementById('mat-type-table-body');
                if (!tbody) return;
                if (materialTypes.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-gray-400">ยังไม่มีข้อมูลประเภทวัตถุดิบในระบบ</td></tr>';
                    return;
                }
                tbody.innerHTML = materialTypes.map(mt => `
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="p-3 text-sm text-gray-500">${mt.MaterialType_id}</td>
                        <td class="p-3 text-sm font-medium text-gray-800">${mt.MaterialType_name}</td>
                        <td class="p-3 text-center">
                            <button onclick="app.editMaterialType(${mt.MaterialType_id}, '${mt.MaterialType_name}')" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-full mr-1">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button onclick="app.deleteMaterialType(${mt.MaterialType_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-full">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            },

            searchMaterialTypes: function() {
                const query = document.getElementById('search-mat-type-input').value.toLowerCase();
                const filtered = state.materialTypes.filter(mt => 
                    mt.MaterialType_name.toLowerCase().includes(query) || 
                    mt.MaterialType_id.toString().includes(query)
                );
                this.renderMaterialTypes(filtered);
            },

            saveMaterialType: function() {
                const idInput = document.getElementById('edit-mat-type-id');
                const nameInput = document.getElementById('new-mat-type-name');
                const id = idInput.value;
                const name = nameInput.value.trim();

                if (!name) {
                    this.showToast('กรุณาระบุชื่อประเภทวัตถุดิบ', 'error');
                    return;
                }

                const url = id ? 'actions/update_material_type.php' : 'actions/add_material_type.php';
                const postData = id ? { id: id, name: name } : { name: name };

                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(postData)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.resetMaterialTypeForm();
                        this.loadMaterialTypes();
                        // อาจจะต้องรีโหลดหน้า materials.php ด้วยถ้ามีการใช้ dropdown ประเภทวัตถุดิบ
                        // หรือเรียก loadMaterials() อีกครั้ง
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            editMaterialType: function(id, name) {
                document.getElementById('edit-mat-type-id').value = id;
                document.getElementById('new-mat-type-name').value = name;
                document.getElementById('mat-type-submit-btn').innerText = 'บันทึกการแก้ไข';
                document.getElementById('mat-type-cancel-btn').classList.remove('hidden');
                document.getElementById('new-mat-type-name').focus();
            },

            resetMaterialTypeForm: function() {
                document.getElementById('edit-mat-type-id').value = '';
                document.getElementById('new-mat-type-name').value = '';
                document.getElementById('mat-type-submit-btn').innerText = 'เพิ่มประเภท';
                document.getElementById('mat-type-cancel-btn').classList.add('hidden');
            },

            deleteMaterialType: function(id) {
                if (!confirm('ยืนยันการลบประเภทวัตถุดิบนี้?')) return;

                fetch('actions/delete_material_type.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.loadMaterialTypes();
                        // อาจจะต้องรีโหลดหน้า materials.php ด้วยถ้ามีการใช้ dropdown ประเภทวัตถุดิบ
                        // หรือเรียก loadMaterials() อีกครั้ง
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting material type:', error);
                    this.showToast('ไม่สามารถลบข้อมูลได้', 'error');
                });
            },

            // ================= ส่วนจัดการรายงานรายเดือน (Monthly Report Functions) =================
            loadMonthlySalesReport: function() {
                const month = document.getElementById('report-month')?.value || new Date().getMonth() + 1;
                const year = document.getElementById('report-year')?.value || new Date().getFullYear();

                fetch(`actions/get_monthly_sales.php?year=${year}&month=${month}`)
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        // อัปเดตตัวเลขสรุป
                        document.getElementById('monthly-total-sales').innerText = this.formatCurrency(result.summary.total_monthly_sales);
                        document.getElementById('monthly-total-orders').innerText = result.summary.total_monthly_orders;
                        document.getElementById('monthly-best-seller').innerText = result.top_products[0]?.Product_name || '-';

                        // อัปเดตตารางยอดขายแยกตามประเภท
                        const salesByTypeTbody = document.getElementById('monthly-sales-by-type-table');
                        if (salesByTypeTbody) {
                            if (result.sales_by_type.length === 0) {
                                salesByTypeTbody.innerHTML = '<tr><td colspan="2" class="p-4 text-center text-gray-400">ไม่มีข้อมูล</td></tr>';
                            } else {
                                salesByTypeTbody.innerHTML = result.sales_by_type.map(item => `
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm font-medium">${item.ProductType_name}</td>
                                        <td class="p-3 text-sm text-right">₭${this.formatCurrency(item.total_sales_by_type)}</td>
                                    </tr>
                                `).join('');
                            }
                        }

                        // อัปเดตตาราง 10 อันดับเมนูขายดี
                        const topProductsTbody = document.getElementById('monthly-top-products-table');
                        if (topProductsTbody) {
                            if (result.top_products.length === 0) {
                                topProductsTbody.innerHTML = '<tr><td colspan="3" class="p-4 text-center text-gray-400">ไม่มีข้อมูล</td></tr>';
                            } else {
                                topProductsTbody.innerHTML = result.top_products.map(item => `
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 text-sm font-medium">${item.Product_name}</td>
                                        <td class="p-3 text-sm text-right">${item.total_qty_sold}</td>
                                        <td class="p-3 text-sm text-right">₭${this.formatCurrency(item.total_revenue)}</td>
                                    </tr>
                                `).join('');
                            }
                        }

                        // อัปเดตลิงก์ Export CSV
                        const exportBtn = document.getElementById('export-monthly-sales-btn');
                        if (exportBtn) {
                            exportBtn.href = `actions/export_monthly_sales_csv.php?year=${year}&month=${month}`;
                        }

                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading monthly sales report:', error);
                    this.showToast('ไม่สามารถโหลดรายงานรายเดือนได้', 'error');
                });
            },

            // ================= ส่วนจัดการวัตถุดิบ (Material Functions) =================
            loadMaterials: function() {
                fetch('actions/get_materials.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        state.materials = result.materials;
                        this.renderMaterials(result.materials);
                        this.updateRequisitionSelect(result.materials);
                    }
                })
                .catch(err => console.error('Error loading materials:', err));
            },

            renderMaterials: function(materials) {
                const tbody = document.getElementById('material-table-body');
                if (!tbody) return;
                tbody.innerHTML = materials.map(m => `
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 text-sm font-medium">${m.Material_name}</td>
                        <td class="p-3 text-sm text-gray-500">${m.MaterialType_name}</td>
                        <td class="p-3 text-sm text-right font-bold ${m.Material_total <= 5 ? 'text-red-500' : 'text-gray-800'}">${m.Material_total}</td>
                        <td class="p-3 text-sm text-gray-500">${m.Material_unit}</td>
                        <td class="p-3 text-sm text-right">₭${this.formatCurrency(m.Material_costprice)}</td>
                        <td class="p-3 text-center">
                            <button onclick="app.editMaterial(${m.Material_id})" class="text-blue-500 hover:text-blue-700 mr-1"><i class="fa-solid fa-edit"></i></button>
                            <button onclick="app.deleteMaterial(${m.Material_id})" class="text-red-500 hover:text-red-700"><i class="fa-solid fa-trash-can"></i></button>
                        </td>
                    </tr>
                `).join('');
            },

            updateRequisitionSelect: function(materials) {
                const reqSelect = document.getElementById('req-material-id');
                const admitSelect = document.getElementById('admit-material-id');
                const options = '<option value="">-- เลือกวัตถุดิบ --</option>' + 
                    materials.map(m => `<option value="${m.Material_id}">${m.Material_name} (คงเหลือ: ${m.Material_total} ${m.Material_unit})</option>`).join('');
                
                if (reqSelect) reqSelect.innerHTML = options;
                if (admitSelect) admitSelect.innerHTML = options;
            },

            saveRequisition: function(e) {
                e.preventDefault();
                const mat_id = document.getElementById('req-material-id').value;
                const qty = document.getElementById('req-qty').value;
                const note = document.getElementById('req-note').value;

                fetch('actions/save_requisition.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mat_id, qty, note })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        document.getElementById('requisition-form').reset();
                        this.loadMaterials();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            saveAdmit: function(e) {
                e.preventDefault();
                const mat_id = document.getElementById('admit-material-id').value;
                const qty = document.getElementById('admit-qty').value;
                const price = document.getElementById('admit-price').value;

                fetch('actions/save_admit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ mat_id, qty, price })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        document.getElementById('admit-form').reset();
                        this.loadMaterials();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            saveMaterial: function(e) {
                e.preventDefault();
                const id = document.getElementById('edit-mat-id').value;
                const name = document.getElementById('mat-name').value;
                const type_id = document.getElementById('mat-type').value;
                const cost = document.getElementById('mat-cost').value;
                const unit = document.getElementById('mat-unit').value;

                const url = id ? 'actions/update_material.php' : 'actions/add_material.php';
                
                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, name, type_id, cost, unit })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.resetMaterialForm();
                        this.loadMaterials();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            editMaterial: function(id) {
                const m = state.materials.find(mat => mat.Material_id == id);
                if (!m) return;

                document.getElementById('edit-mat-id').value = m.Material_id;
                document.getElementById('mat-name').value = m.Material_name;
                document.getElementById('mat-type').value = m.MaterialType_id;
                document.getElementById('mat-cost').value = m.Material_costprice;
                document.getElementById('mat-unit').value = m.Material_unit;

                document.getElementById('mat-form-title').innerHTML = '<i class="fa-solid fa-edit mr-2"></i>แก้ไขวัตถุดิบ';
                document.getElementById('mat-submit-btn').innerText = 'บันทึกการแก้ไข';
                document.getElementById('mat-cancel-btn').classList.remove('hidden');
                document.getElementById('mat-name').focus();
            },

            resetMaterialForm: function() {
                document.getElementById('add-material-form').reset();
                document.getElementById('edit-mat-id').value = '';
                document.getElementById('mat-form-title').innerHTML = '<i class="fa-solid fa-box-open mr-2"></i>เพิ่มวัตถุดิบใหม่';
                document.getElementById('mat-submit-btn').innerText = 'บันทึกข้อมูล';
                document.getElementById('mat-cancel-btn').classList.add('hidden');
            },

            deleteMaterial: function(id) {
                if (!confirm('ยืนยันการลบวัตถุดิบนี้? ข้อมูลสต็อกปัจจุบันจะหายไป')) return;

                fetch('actions/delete_material.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        this.loadMaterials();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            // ================= ส่วนจัดการหน้าเพจ (Page Navigation) =================
            switchPage: function(page) {
                state.currentPage = page;
                
                const allPages = [
                    'pos', 'dashboard', 'menu', 'product-types', 'material-types', 
                    'customers', 'employees', 'materials', 'material-order', 
                    'material-admit', 'material-requisition', 'finance', 'reports'
                ];

                allPages.forEach(p => {
                    const navBtn = document.getElementById(`nav-${p}`);
                    if(navBtn) navBtn.className = p === page 
                        ? 'w-full flex items-center p-3 rounded-lg bg-orange-900/50 text-white transition-colors shadow-inner' 
                        : 'w-full flex items-center p-3 rounded-lg hover:bg-orange-800 text-orange-200 transition-colors';
                });

                const titles = { 
                    'pos': 'ขายอาหาร (POS)', 'dashboard': 'สรุปยอดขาย', 'menu': 'จัดการเมนูอาหาร',
                    'product-types': 'จัดการประเภทสินค้า', 'customers': 'ข้อมูลลูกค้า', 'employees': 'ข้อมูลพนักงาน',
                    'materials': 'สต็อกวัตถุดิบ', 'material-order': 'สั่งซื้อวัตถุดิบ', 'material-admit': 'รับเข้าวัตถุดิบ', 
                    'material-requisition': 'เบิกใช้วัตถุดิบ', 'finance': 'รายรับ - รายจ่าย', 'reports': 'ออกรายงาน'
                };

                document.getElementById('page-title').innerText = titles[page] || 'ระบบบริหารจัดการ';
                
                allPages.forEach(p => {
                    const el = document.getElementById(`page-${p}`);
                    if(el) {
                        if(p === page) {
                            el.classList.remove('hidden');
                            setTimeout(() => el.classList.remove('opacity-0'), 50);
                        } else el.classList.add('hidden', 'opacity-0');
                    }
                });
                
                // โหลดข้อมูลตามหน้าที่เลือก
                if (page === 'customers' && typeof this.loadCustomers === 'function') this.loadCustomers();
                if (page === 'employees' && typeof this.loadEmployees === 'function') this.loadEmployees();
                if (page === 'product-types') {
                    if(document.getElementById('search-cat-input')) document.getElementById('search-cat-input').value = ''; 
                    if(typeof this.loadProductTypes === 'function') this.loadProductTypes();
                }
                if (['materials', 'material-requisition', 'material-admit'].includes(page) && typeof this.loadMaterials === 'function') this.loadMaterials();
                if (page === 'finance') this.loadFinance();
                if (page === 'reports') this.loadMonthlySalesReport(); // โหลดรายงานรายเดือนเมื่อเข้าหน้า Reports
                if (page === 'dashboard') {
                    this.updateDashboard();
                    if (typeof this.updateStaffDashboard === 'function') this.updateStaffDashboard();
                }
                if (page === 'menu') this.renderMenuMgmt();
                if (page === 'material-types') {
                    if(document.getElementById('search-mat-type-input')) document.getElementById('search-mat-type-input').value = '';
                    if(typeof this.loadMaterialTypes === 'function') this.loadMaterialTypes();
                }
            },

            toggleMobileCart: function() {
                if(window.innerWidth >= 1024) return;
                const panel = document.getElementById('mobile-cart-panel');
                const chevron = document.getElementById('cart-chevron');
                state.isMobileCartOpen = !state.isMobileCartOpen;
                if(state.isMobileCartOpen) { panel.classList.remove('translate-y-[calc(100%-60px)]'); chevron.classList.add('rotate-180'); } 
                else { panel.classList.add('translate-y-[calc(100%-60px)]'); chevron.classList.remove('rotate-180'); }
            },

            showToast: function(message, type = 'success') {
                const toast = document.getElementById('toastBox');
                document.getElementById('toastMessage').innerText = message;
                const icon = document.getElementById('toastIcon');
                if (type === 'error') { toast.classList.replace('border-green-500', 'border-red-500'); icon.className = 'fa-solid fa-circle-exclamation text-red-500 text-xl'; } 
                else { toast.classList.replace('border-red-500', 'border-green-500'); icon.className = 'fa-solid fa-circle-check text-green-500 text-xl'; }
                toast.classList.remove('opacity-0', 'translate-y-[-20px]');
                setTimeout(() => toast.classList.add('opacity-0', 'translate-y-[-20px]'), 3000);
            },

            updateTime: function() {
                const now = new Date();
                const dateStr = now.toLocaleDateString('th-TH', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
                const timeStr = now.toLocaleTimeString('th-TH');
                document.getElementById('current-time').innerHTML = `<span class="hidden md:inline">${dateStr} | </span><span class="font-bold text-primary">${timeStr}</span>`;
            }
        };

        // ใช้ Object.assign เพื่อรวมฟังก์ชันจากไฟล์อื่นๆ เข้ากับตัวแปรหลัก โดยไม่ลบฟังก์ชันเดิมทิ้ง
        window.app = Object.assign(window.app || {}, mainFunctions);
        window.addEventListener('DOMContentLoaded', () => window.app.init());
    </script>
</body>
</html>