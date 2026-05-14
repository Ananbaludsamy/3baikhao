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
            materials: [],
            finHistory: [],
            finFilter: 'all',
            finPage: 1,
            finPerPage: 15,
            cusData: [],
            cusPage: 1,
            cusPerPage: 10,
            cusSearch: '',
            cusLevel: '',
            salesData: [],
            salesPage: 1,
            salesPerPage: 10,
            memberDiscount: 0, // เก็บ % ส่วนลดปัจจุบัน
            posCustomers: [], // เก็บรายชื่อลูกค้าทั้งหมดสำหรับ POS (รวมลูกค้าทั่วไป)
            occupiedTablesWithCustomers: [], // เก็บข้อมูลโต๊ะที่มีออเดอร์ค้างอยู่ พร้อมรหัสลูกค้า
            customerSearchQuery: '', // เก็บคำค้นหาลูกค้าปัจจุบัน
            filteredPosCustomers: [], // รายชื่อลูกค้าที่ถูกกรองแล้วสำหรับแสดงผล
            selectedPosCustomer: { Cus_id: 1, Cus_name: 'ລູກຄ້າທົ່ວໄປ (ໜ້າຮ້ານ)', Cus_Level: 'General', Cus_Points: 0 },
            settings: { shop_name: 'ຮ້ານເຝີເຮືອ 3ໃບເຂົາ', shop_phone: '020-XXXX-XXXX', shop_address: '', point_value: 1000, tables_count: 20 },
            pointsUsed: 0
        };

        const mainFunctions = {
            init: function() {
                this.loadSettings();
                this.loadDatabase();
                this.updateTime();
                this.loadMaterials();
                this.renderTableSelection();
                this.loadPosCustomers();
                this.switchPage(state.currentPage);
                this.loadCustomers();
                setInterval(() => { this.updateTime(); this.renderTableSelection(); }, 1000);
                setInterval(() => this.renderTableSelection(), 10000);
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
                    grid.innerHTML = `<div class="col-span-full py-10 text-center text-gray-400">ຍັງບໍ່ມີລາຍການອາຫານໃນໝວດນີ້</div>`;
                    return;
                }
                
                filteredProducts.forEach(product => {
                    const el = document.createElement('div');
                    el.className = 'bg-white rounded-xl shadow-sm hover:shadow-md transition-all overflow-hidden cursor-pointer border border-slate-100 flex flex-col group';
                    el.onclick = () => this.addToCart(product.id);
                    el.innerHTML = `
                        <div class="h-20 sm:h-24 md:h-28 bg-slate-100 overflow-hidden relative">
                            <img src="${product.img}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" onerror="this.src='https://images.unsplash.com/photo-1585032226651-759b368d7246?auto=format&fit=crop&w=300&q=80'">
                        </div>
                        <div class="p-2 sm:p-2.5 flex flex-col flex-1">
                            <h4 class="font-semibold text-[10px] sm:text-xs text-slate-700 leading-tight mb-1 flex-1">${product.name}</h4>
                            <div class="flex justify-between items-center mt-auto gap-1">
                                <span class="text-primary font-black text-xs sm:text-sm">₭${this.formatCurrency(product.price)}</span>
                                <button class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-orange-100 text-primary group-hover:bg-primary group-hover:text-white transition-colors flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-plus text-[8px] sm:text-[10px]"></i>
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
                    container.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-slate-300 py-10"><i class="fa-solid fa-basket-shopping text-4xl mb-3"></i><p class="text-sm">ຍັງບໍ່ມີລາຍການ</p></div>`;
                    checkoutBtn.disabled = true;
                } else {
                    container.innerHTML = '';
                    state.cart.forEach(item => {
                        totalQty += item.qty; totalPrice += (item.price * item.qty);
                        const el = document.createElement('div');
                        el.className = 'flex justify-between items-center mb-2 p-2.5 bg-white rounded-xl border border-slate-100 shadow-sm';
                        el.innerHTML = `
                            <button onclick="app.removeFromCart('${item.id}')" class="mr-2 text-slate-300 hover:text-red-400 transition-colors flex-shrink-0">
                                <i class="fa-solid fa-circle-xmark text-sm"></i>
                            </button>
                            <div class="flex-1 min-w-0 pr-2">
                                <h5 class="text-xs font-bold text-slate-700 truncate">${item.name}</h5>
                                <span class="text-[10px] text-slate-400">₭${this.formatCurrency(item.price)}</span>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="flex items-center bg-slate-100 rounded-lg overflow-hidden">
                                    <button onclick="app.decreaseQty('${item.id}')" class="w-6 h-6 flex items-center justify-center text-primary hover:bg-orange-100 transition-colors"><i class="fa-solid fa-minus text-[9px]"></i></button>
                                    <span class="w-6 text-center text-xs font-black text-slate-700">${item.qty}</span>
                                    <button onclick="app.addToCart('${item.id}')" class="w-6 h-6 flex items-center justify-center text-primary hover:bg-orange-100 transition-colors"><i class="fa-solid fa-plus text-[9px]"></i></button>
                                </div>
                                <span class="text-xs font-black w-14 text-right text-primary">₭${this.formatCurrency(item.price * item.qty)}</span>
                            </div>
                        `;
                        container.appendChild(el);
                    });
                }
                document.getElementById('cart-total-qty').innerText = totalQty;
                document.getElementById('cart-subtotal').innerText = this.formatCurrency(totalPrice);
                document.getElementById('cart-total').innerText = this.formatCurrency(totalPrice);
                cartBadge.innerText = totalQty;
                this.applyMemberDiscount(); // คำนวณส่วนลดใหม่ทุกครั้งที่ตะกร้าเปลี่ยน
            },

            loadPosCustomers: function() {
                fetch('actions/get_customers.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        state.posCustomers = result.customers;
                        // Ensure "ลูกค้าทั่วไป" is always in the list if not fetched
                        if (!state.posCustomers.some(c => c.Cus_id == 1)) {
                            state.posCustomers.unshift({ Cus_id: 1, Cus_name: 'ລູກຄ້າທົ່ວໄປ (ໜ້າຮ້ານ)', Cus_Address: '-', Cus_Tel: '-', Cus_Level: 'General', Cus_Points: 0 });
                        }
                        state.filteredPosCustomers = [...state.posCustomers];
                        this.selectCustomer(1);
                    }
                })
                .catch(err => console.error('Error loading POS customers:', err));
            },

            filterCustomers: function(query) {
                const q = query.toLowerCase().trim();
                state.customerSearchQuery = q;
                state.filteredPosCustomers = state.posCustomers.filter(cus => 
                    cus.Cus_name.toLowerCase().includes(q) || 
                    (cus.Cus_Tel && cus.Cus_Tel.includes(q))
                );
                this.renderCustomerSearch();
            },

            renderCustomerSearch: function() {
                const resultsDiv = document.getElementById('customer-search-results');
                if (!resultsDiv) return;
                resultsDiv.innerHTML = '';
                
                if (state.filteredPosCustomers.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-3 text-center text-gray-400 text-sm">ບໍ່ພົບຂໍ້ມູນລູກຄ້າ</div>';
                    return;
                }

                state.filteredPosCustomers.forEach(cus => {
                    const el = document.createElement('div');
                    el.className = 'p-3 hover:bg-orange-50 cursor-pointer border-b border-gray-50 last:border-0 transition-colors';
                    el.onclick = () => this.selectCustomer(cus.Cus_id);
                    el.innerHTML = `
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-sm font-bold text-gray-800">${cus.Cus_name} ${cus.Cus_id === 1 ? '' : `(${cus.Cus_Points} ຄະແນນ)`}</div>
                                <div class="text-[10px] text-gray-500">${cus.Cus_Tel || '-'}</div>
                            </div>
                            <span class="text-[10px] px-2 py-0.5 rounded-full font-bold ${
                                cus.Cus_Level === 'Gold' ? 'bg-yellow-100 text-yellow-700' : 
                                cus.Cus_Level === 'VIP' ? 'bg-purple-100 text-purple-700' : 
                                'bg-gray-100 text-gray-600'
                            }">${cus.Cus_Level || 'General'}</span>
                        </div>
                    `;
                    resultsDiv.appendChild(el);
                });
            },

            selectCustomer: function(cusId) {
                const customer = state.posCustomers.find(c => c.Cus_id == cusId);
                if (!customer) return;

                // ล้างค่าหมายเลขโต๊ะเมื่อเปลี่ยนสมาชิก เพื่อป้องกันการสั่งข้ามโต๊ะโดยไม่ตั้งใจ
                const tableInput = document.getElementById('table-number');
                if (tableInput) tableInput.value = '';

                state.selectedPosCustomer = { ...customer };
                state.selectedPosCustomer.Cus_Level = state.selectedPosCustomer.Cus_Level || 'General';
                state.selectedPosCustomer.Cus_Points = state.selectedPosCustomer.Cus_Points || 0;
                
                const input = document.getElementById('customer-search-input');
                const hiddenInput = document.getElementById('pos-customer-select-id');
                
                if (input) input.value = customer.Cus_name;
                if (hiddenInput) hiddenInput.value = customer.Cus_id;
                
                this.renderTableSelection(); // Re-render table selection based on new customer
                this.applyMemberDiscount();
                document.getElementById('customer-search-results')?.classList.add('hidden');
            },

            applyMemberDiscount: function() {
                const info = document.getElementById('member-discount-info');
                const rateEl = document.getElementById('member-discount-rate');
                const pointsSection = document.getElementById('points-redemption-section');
                if (!state.selectedPosCustomer || !state.selectedPosCustomer.Cus_Level) return;

                let discount = 0;
                if (state.selectedPosCustomer.Cus_Level === 'VIP') discount = state.settings.vip_discount || 5;
                else if (state.selectedPosCustomer.Cus_Level === 'Gold') discount = state.settings.gold_discount || 10;
                state.memberDiscount = discount;

                if (state.memberDiscount > 0) {
                    info.classList.remove('hidden');
                    rateEl.innerText = state.memberDiscount;
                } else {
                    info.classList.add('hidden');
                }

                // Points redemption section
                if (pointsSection) {
                    const pts = parseInt(state.selectedPosCustomer.Cus_Points) || 0;
                    if (state.selectedPosCustomer.Cus_Level !== 'General' && state.selectedPosCustomer.Cus_id != 1 && pts > 0) {
                        pointsSection.classList.remove('hidden');
                        const pv = state.settings.point_value || 1000;
                        document.getElementById('available-points').innerText = pts.toLocaleString('lo-LA');
                        document.getElementById('available-points-value').innerText = (pts * pv).toLocaleString('lo-LA');
                        document.getElementById('points-to-use').max = pts;
                        document.getElementById('points-to-use').value = 0;
                    } else {
                        pointsSection.classList.add('hidden');
                        if (document.getElementById('points-to-use')) document.getElementById('points-to-use').value = 0;
                    }
                }
                state.pointsUsed = 0;
                this.calculateChange();
            },

            renderTableSelection: async function() {
                const grid = document.getElementById('table-grid');
                if (!grid) return;

                try {
                    const response = await fetch('actions/get_pending_orders.php');
                    const result = await response.json();
                    // Assuming result.data now contains objects like { table_no: '1', cus_id: '123', ... }
                    state.occupiedTablesWithCustomers = result.success ? result.data : [];
                    const currentSelectedTableInput = document.getElementById('table-number');
                    const currentSelectedTableNo = currentSelectedTableInput.value.toString();
                    const selectedCusId = state.selectedPosCustomer.Cus_id.toString();

                    grid.innerHTML = '';
                    const tablesCount = parseInt(state.settings.tables_count) || 20;
                    for (let i = 1; i <= tablesCount; i++) {
                        const tableNo = i.toString();
                        const occupiedOrder = state.occupiedTablesWithCustomers.find(o => o.table_no === tableNo);
                        const isOccupied = !!occupiedOrder;
                        const isOccupiedBySelectedCustomer = isOccupied && occupiedOrder.cus_id === selectedCusId && selectedCusId !== '1';
                        const isCurrentlySelected = currentSelectedTableNo === tableNo; // This is the table currently in the input field

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        
                        let bgColor = 'bg-white border-gray-200 text-gray-600 hover:bg-orange-50';
                        let statusText = 'ຫວ່າງ';
                        let isDisabled = false;

                        if (isOccupied) {
                            if (isOccupiedBySelectedCustomer) {
                                bgColor = 'bg-primary border-primary text-white shadow-md scale-95';
                                statusText = 'ຂອງທ່ານ';
                            } else {
                                bgColor = 'bg-red-100 border-red-200 text-red-500 cursor-not-allowed';
                                statusText = 'ບໍ່ຫວ່າງ';
                                isDisabled = true;
                            }
                        } else if (isCurrentlySelected) { // If not occupied, but it's the selected table for the current order
                            bgColor = 'bg-primary border-primary text-white shadow-md scale-95';
                        }

                        btn.onclick = () => {
                            if (isDisabled) {
                                this.showToast(`ໂຕະ ${tableNo} ມີລູກຄ້າອື່ນນັ່ງຢູ່`, 'error');
                            } else {
                                this.selectTable(tableNo);
                            }
                        };
                        btn.disabled = isDisabled;
                        btn.className = `${bgColor} border p-2 rounded-lg text-sm font-bold transition-all flex flex-col items-center justify-center h-12`;
                        btn.innerHTML = `<span class="leading-none">${tableNo}</span><span class="text-[8px] uppercase mt-1">${statusText}</span>`;
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
                const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const discountAmount = (subtotal * state.memberDiscount) / 100;

                // Points discount
                const pointsInput = document.getElementById('points-to-use');
                const ptsUsed = pointsInput ? Math.min(Math.max(0, parseInt(pointsInput.value) || 0), parseInt(state.selectedPosCustomer?.Cus_Points) || 0) : 0;
                const pv = state.settings.point_value || 1000;
                const pointsDiscount = ptsUsed * pv;
                state.pointsUsed = ptsUsed;

                const pointsDisplay = document.getElementById('points-discount-display');
                const pointsAmountEl = document.getElementById('points-discount-amount');
                if (pointsDisplay && pointsAmountEl) {
                    if (ptsUsed > 0) {
                        pointsDisplay.classList.remove('hidden');
                        pointsAmountEl.innerText = this.formatCurrency(pointsDiscount);
                    } else {
                        pointsDisplay.classList.add('hidden');
                    }
                }

                const total = Math.max(0, subtotal - discountAmount - pointsDiscount);

                if (document.getElementById('cart-total')) {
                    document.getElementById('cart-total').innerText = this.formatCurrency(total);
                }

                const cashInput = document.getElementById('cash-received');
                if (!cashInput) return;

                const received = parseFloat(cashInput.value) || 0;
                const change = received - total;

                const changeEl = document.getElementById('cart-change');
                if (changeEl) {
                    changeEl.innerText = this.formatCurrency(change);
                    if (change < 0) {
                        changeEl.parentElement.classList.replace('text-orange-600', 'text-red-500');
                    } else {
                        changeEl.parentElement.classList.replace('text-red-500', 'text-orange-600');
                    }
                }

                const checkoutBtn = document.getElementById('checkout-btn');
                if (checkoutBtn) {
                    // ปิดปุ่มถ้า: ตะกร้าຫວ່າງ หรือ ยังไม่ได้ระบุเงินรับ หรือ เงินรับน้อยกว่ายอดรวม
                    checkoutBtn.disabled = (state.cart.length === 0 || received <= 0 || received < total);
                }
            },

            checkout: function() {
                if (state.cart.length === 0) return;

                const subtotal = state.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const memberDiscountAmt = (subtotal * state.memberDiscount) / 100;
                const pv = state.settings.point_value || 1000;
                const pointsDiscount = (state.pointsUsed || 0) * pv;
                const totalAmount = Math.max(0, subtotal - memberDiscountAmt - pointsDiscount);
                const received = parseFloat(document.getElementById('cash-received').value) || 0;
                const tableNo = document.getElementById('table-number').value.trim();

                // ตรวจสอบหมายเลขโต๊ะก่อนชำระเงิน
                if (!tableNo) {
                    window.app.showToast('ກະລຸນາລະບຸໝາຍເລກໂຕະກ່ອນຊໍາລະເງິນ', 'error');
                    document.getElementById('table-number').focus();
                    return;
                }

                if (received < totalAmount) {
                    window.app.showToast('ຈໍານວນເງິນຮັບບໍ່ພຽງພໍ', 'error');
                    return;
                }

                const checkoutBtn = document.getElementById('checkout-btn');
                const originalText = checkoutBtn.innerHTML;

                // ปิดปุ่มและแสดงสถานะกำลังประมวลผล เพื่อป้องกันการกดซ้ำหรือเครื่องค้าง
                checkoutBtn.disabled = true;
                checkoutBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ກໍາລັງບັນທຶກ...';

                const postData = {
                    cart: state.cart,
                    total: totalAmount,
                    cus_id: document.getElementById('pos-customer-select-id').value,
                    emp_id: window.serverData.user.id,
                    table_no: tableNo,
                    points_used: state.pointsUsed || 0
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
                            dateStr: now.toLocaleDateString('lo-LA'),
                            timeStr: now.toLocaleTimeString('lo-LA', { hour: '2-digit', minute: '2-digit' })
                        };

                        state.orders.unshift(orderData); 
                        window.app.saveOrdersToStorage(); // เก็บประวัติไว้ในเครื่องด้วยเพื่อความเร็ว

                        state.cart = [];
                        window.app.renderCart();
                        
                        document.getElementById('cash-received').value = '';
                        document.getElementById('table-number').value = '';
                        if (document.getElementById('points-to-use')) document.getElementById('points-to-use').value = 0;
                        state.pointsUsed = 0;
                        window.app.renderTableSelection();
                        
                        // เรียกใช้ผ่าน window.app เพื่อให้มั่นใจว่าเรียกฟังก์ชันที่ถูกรวมแล้ว
                        if (typeof window.app.updateStaffDashboard === 'function') {
                            window.app.updateStaffDashboard();
                        }
                        
                        window.app.updateDashboard(); // อัปเดตสถิติ (ถ้าเป็น Admin)
                        if(state.isMobileCartOpen) window.app.toggleMobileCart();
                        window.app.showToast(`ຊໍາລະເງິນສໍາເລັດ! ລະຫັດບິນ: ${result.order_id}`);
                        
                        // เปิดหน้าพิมพ์ใบเสร็จอัตโนมัติ
                        const change = received - totalAmount;
                        window.open(`print_receipt.php?id=${result.sale_id}&cash=${received}&change=${change}&table=${tableNo}`, '_blank', 'width=360,height=520');

                        // เปลี่ยนหน้าไปยังหน้า รายการอาหารรอเสิร์ฟ (Dashboard) ทันที
                        window.app.switchPage('dashboard');

                        setTimeout(() => {
                            checkoutBtn.disabled = false;
                            checkoutBtn.innerHTML = originalText;
                        }, 2000);
                    } else {
                        window.app.showToast(result.message || 'ບັນທຶກຂໍ້ມູນບໍ່ສໍາເລັດ', 'error');
                        checkoutBtn.disabled = false;
                        checkoutBtn.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.app.showToast('ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນໄດ້', 'error');
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = originalText;
                });
            },

            updateDashboard: function() {
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
                    state.salesData = result.history || [];
                    state.salesPage = 1;
                    this.renderDashboardSales();

                    // 3. อัปเดตรายการสินค้าขายดี 5 อันดับ
                    const topList = document.getElementById('top-products-list');
                    if (topList) {
                        topList.innerHTML = result.top5.map((p, index) => `
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 flex items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-500">${index + 1}</span>
                                    <span class="text-sm font-medium text-gray-700">${p.Product_name}</span>
                                </div>
                                <span class="text-sm font-bold text-primary">${p.qty} ຈານ</span>
                            </div>
                        `).join('');
                    }

                    // 4. วาดกราฟแนวโน้มยอดขาย (Line Chart)
                    const trendCanvas = document.getElementById('weeklySalesChart');
                    if (trendCanvas) {
                        const existing = Chart.getChart(trendCanvas);
                        if (existing) existing.destroy();
                        window.salesTrendChart = new Chart(trendCanvas.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: result.trend.map(t => new Date(t.Sale_date).toLocaleDateString('lo-LA', {day:'numeric', month:'short'})),
                                datasets: [{
                                    label: 'ຍອດຂາຍ (₭)',
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
                    const catCanvas = document.getElementById('categoryDistributionChart');
                    if (catCanvas) {
                        const existing = Chart.getChart(catCanvas);
                        if (existing) existing.destroy();
                        window.catDistChart = new Chart(catCanvas.getContext('2d'), {
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
                const name = document.getElementById('new-item-name').value.trim();
                const price = parseFloat(document.getElementById('new-item-price').value);
                const cat = document.getElementById('new-item-cat').value;
                const imgFile = document.getElementById('new-item-img').files[0];

                if (!name) { this.showToast('ກະລຸນາປ້ອນຊື່ເມນູ', 'error'); document.getElementById('new-item-name').focus(); return; }
                if (!price || price <= 0) { this.showToast('ລາຄາຕ້ອງຫຼາຍກວ່າ 0', 'error'); document.getElementById('new-item-price').focus(); return; }
                if (!cat) { this.showToast('ກະລຸນາເລືອກໝວດໝູ່', 'error'); return; }
                
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
                        this.showToast(id ? 'ອັບເດດເມນູສໍາເລັດ' : 'ເພີ່ມເມນູໃໝ່ສໍາເລັດ');
                    } else {
                        this.showToast(result.message || 'ດໍາເນີນການບໍ່ສໍາເລັດ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showToast('ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນໄດ້', 'error');
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
                if (!confirm('ຢືນຢັນການລຶບເມນູນີ້ອອກຈາກລະບົບ?')) return;

                fetch('actions/delete_product.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        state.products = state.products.filter(p => String(p.id) !== String(id));
                        this.renderProducts();
                        this.renderMenuMgmt();
                        this.showToast('ລຶບເມນູສໍາເລັດ');
                    } else {
                        this.showToast(result.message || 'ລຶບເມນູບໍ່ສໍາເລັດ', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.showToast('ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນໄດ້', 'error');
                });
            },

            renderMenuMgmt: function() {
                const tbody = document.getElementById('menu-mgmt-table');
                if (!tbody) return; // ป้องกัน Error หากไม่มีตารางในหน้านี้ (เช่น เมื่อ Staff ล็อกอิน)
                tbody.innerHTML = '';
                if (state.products.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">ບໍ່ມີເມນູໃນລະບົບ</td></tr>';
                    return;
                }
                
                // สร้าง Object สำหรับแมป ID หมวดหมู่กับชื่อ
                const catMap = {};
                if(window.serverData) window.serverData.categories.forEach(c => catMap[c.ProductType_id] = c.ProductType_name);

                state.products.forEach(p => {
                    const tr = document.createElement('tr');
                    tr.className = 'table-row';
                    tr.innerHTML = `
                        <td><img src="${p.img}" class="w-9 h-9 rounded-lg object-cover border border-slate-200"></td>
                        <td class="font-medium text-slate-700">${p.name}</td>
                        <td><span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-lg text-xs font-medium">${catMap[p.cat] || p.cat}</span></td>
                        <td class="font-bold text-primary text-right">₭${this.formatCurrency(p.price)}</td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="app.editProduct('${p.id}')" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition-colors"><i class="fa-solid fa-edit text-xs"></i></button>
                                <button onclick="app.deleteProduct('${p.id}')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-lg transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            },

            // ================= ส่วนจัดการข้อมูลลูกค้า (Customer Functions) =================
            renderPagination: function(wrapperId, page, total, perPage, goFn) {
                const el = document.getElementById(wrapperId);
                if (!el) return;
                const totalPages = Math.ceil(total / perPage);
                if (totalPages <= 1) { el.innerHTML = ''; return; }
                const from = (page - 1) * perPage + 1;
                const to   = Math.min(page * perPage, total);
                const nums = [...new Set([1, totalPages, page-1, page, page+1].filter(p => p >= 1 && p <= totalPages))].sort((a,b) => a-b);
                let pagesHtml = '', prev = 0;
                for (const p of nums) {
                    if (prev && p - prev > 1) pagesHtml += `<span class="px-1 text-slate-300 text-xs select-none">…</span>`;
                    pagesHtml += `<button onclick="app.${goFn}(${p})" class="w-7 h-7 rounded-lg text-xs font-bold transition-colors ${p === page ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'}">${p}</button>`;
                    prev = p;
                }
                el.innerHTML = `<div class="flex items-center justify-between px-4 py-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">${from}–${to} ຈາກ <span class="font-bold text-slate-600">${total}</span> ລາຍການ</span>
                    <div class="flex items-center gap-1">
                        <button onclick="app.${goFn}(${page-1})" ${page===1?'disabled':''} class="w-7 h-7 rounded-lg text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        ${pagesHtml}
                        <button onclick="app.${goFn}(${page+1})" ${page===totalPages?'disabled':''} class="w-7 h-7 rounded-lg text-xs bg-slate-100 text-slate-600 hover:bg-slate-200 disabled:opacity-30 disabled:cursor-not-allowed transition-colors"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>`;
            },

            loadCustomers: function() {
                fetch('actions/get_customers.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        state.cusData = result.customers;
                        state.cusPage = 1;
                        this.renderCustomers();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error loading customers:', error);
                    this.showToast('ไม่สามารถโหลดข้อมูลลูกค้าได้', 'error');
                });
            },

            renderCustomers: function() {
                const tbody = document.getElementById('customer-table-body');
                if (!tbody) return;

                let rows = state.cusData || [];
                const search = (state.cusSearch || '').toLowerCase().trim();
                if (search) rows = rows.filter(c =>
                    (c.Cus_name || '').toLowerCase().includes(search) ||
                    (c.Cus_Tel  || '').toLowerCase().includes(search)
                );
                if (state.cusLevel) rows = rows.filter(c => c.Cus_Level === state.cusLevel);

                const total  = rows.length;
                const perPage = state.cusPerPage;
                const page   = state.cusPage;
                const paged  = rows.slice((page - 1) * perPage, page * perPage);

                const countEl = document.getElementById('cus-count');
                if (countEl) countEl.innerText = total + ' ລາຍການ';

                if (paged.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-gray-400">${!search && !state.cusLevel ? 'ຍັງບໍ່ມີຂໍ້ມູນລູກຄ້າໃນລະບົບ' : 'ບໍ່ພົບລາຍການ'}</td></tr>`;
                    const pg = document.getElementById('cus-pagination'); if (pg) pg.innerHTML = '';
                    return;
                }

                const levelBadge = { 'General': 'badge-general', 'VIP': 'badge-vip', 'Gold': 'badge-gold' };
                const vipThr  = state.settings.vip_threshold  || 500000;
                const goldThr = state.settings.gold_threshold || 2000000;
                const fmt = n => Number(n).toLocaleString('lo-LA');

                tbody.innerHTML = paged.map(cus => {
                    const spend = parseFloat(cus.Cus_TotalSpend || 0);
                    const pts   = parseInt(cus.Cus_Points || 0);
                    const lvl   = cus.Cus_Level || 'General';
                    let progressHTML = '';
                    if (lvl === 'General' && spend < vipThr) {
                        const pct = Math.min(100, (spend / vipThr) * 100).toFixed(0);
                        progressHTML = `<div class="mt-1"><div class="text-[9px] text-slate-400 mb-0.5">→ VIP: ₭${fmt(spend)} / ₭${fmt(vipThr)}</div><div class="h-1 bg-slate-100 rounded-full overflow-hidden"><div class="h-1 bg-purple-400 rounded-full" style="width:${pct}%"></div></div></div>`;
                    } else if (lvl === 'VIP' && spend < goldThr) {
                        const pct = Math.min(100, (spend / goldThr) * 100).toFixed(0);
                        progressHTML = `<div class="mt-1"><div class="text-[9px] text-slate-400 mb-0.5">→ Gold: ₭${fmt(spend)} / ₭${fmt(goldThr)}</div><div class="h-1 bg-slate-100 rounded-full overflow-hidden"><div class="h-1 bg-yellow-400 rounded-full" style="width:${pct}%"></div></div></div>`;
                    } else if (lvl === 'Gold') {
                        progressHTML = `<div class="text-[9px] text-yellow-600 mt-0.5 font-bold">★ Gold Member</div>`;
                    }
                    return `<tr class="table-row">
                        <td>
                            <div class="font-medium text-slate-700">${cus.Cus_name}</div>
                            <div class="text-[10px] text-slate-400">${cus.Cus_Tel}</div>
                            ${progressHTML}
                        </td>
                        <td><span class="${levelBadge[lvl] || 'badge-general'}">${lvl}</span></td>
                        <td class="text-right"><div class="font-bold text-slate-700 text-sm">₭${fmt(spend)}</div></td>
                        <td class="text-right">
                            <button onclick="app.showPointHistory(${cus.Cus_id}, '${cus.Cus_name.replace(/'/g,"\\'")}', ${pts}, ${spend})"
                                class="font-bold text-purple-600 hover:text-purple-800 hover:underline transition-colors">
                                ${fmt(pts)} ຄະ
                            </button>
                        </td>
                        <td class="text-slate-500 text-sm">${cus.Cus_Address}</td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="app.editCustomer(${cus.Cus_id})" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-edit text-xs"></i>
                                </button>
                                <button onclick="app.deleteCustomer(${cus.Cus_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
                this.renderPagination('cus-pagination', page, total, perPage, 'setCusPage');
            },

            filterCustomers: function() {
                state.cusSearch = (document.getElementById('cus-search') || {}).value || '';
                state.cusLevel  = (document.getElementById('cus-level-filter') || {}).value || '';
                state.cusPage   = 1;
                this.renderCustomers();
            },

            setCusPage: function(page) {
                state.cusPage = Math.max(1, page);
                this.renderCustomers();
            },

            saveCustomer: function(e) {
                e.preventDefault();
                const cusId = document.getElementById('cus-id').value;
                const name = document.getElementById('cus-name').value.trim();
                const level = document.getElementById('cus-level').value;
                const tel = document.getElementById('cus-tel').value.trim();
                const address = document.getElementById('cus-address').value.trim();

                if (!name) { this.showToast('ກະລຸນາປ້ອນຊື່ລູກຄ້າ', 'error'); document.getElementById('cus-name').focus(); return; }
                if (!tel)  { this.showToast('ກະລຸນາປ້ອນເບີໂທລະສັບ', 'error'); document.getElementById('cus-tel').focus(); return; }
                if (!address) { this.showToast('ກະລຸນາປ້ອນທີ່ຢູ່', 'error'); document.getElementById('cus-address').focus(); return; }

                const postData = { cus_id: cusId, name, level, tel, address };
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
                        document.getElementById('cus-level').value = cus.Cus_Level;
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
                if (!confirm('ຢືນຢັນການລຶບຂໍ້ມູນລູກຄ້າທ່ານນີ້?')) return;

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
                    tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-400">ຍັງບໍ່ມີຂໍ້ມູນພະນັກງານໃນລະບົບ</td></tr>';
                    return;
                }
                tbody.innerHTML = employees.map(emp => `
                    <tr class="table-row">
                        <td class="font-medium text-slate-700">${emp.Emp_name}</td>
                        <td class="text-slate-500">${emp.Emp_Tel}</td>
                        <td class="text-slate-500">${emp.Username}</td>
                        <td>
                            <span class="${emp.Role === 'admin' ? 'badge-admin' : 'badge-staff'}">
                                ${emp.Role === 'admin' ? '⚙ ຜູ້ດູແລ' : '👤 ພະນັກງານ'}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="app.editEmployee(${emp.Emp_id})" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-edit text-xs"></i>
                                </button>
                                <button onclick="app.deleteEmployee(${emp.Emp_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            },

            saveEmployee: function(e) {
                e.preventDefault();
                const empId = document.getElementById('emp-id').value;
                const name = document.getElementById('emp-name').value.trim();
                const card = document.getElementById('emp-card').value.trim();
                const address = document.getElementById('emp-address').value.trim();
                const tel = document.getElementById('emp-tel').value.trim();
                const gender = document.getElementById('emp-gender').value;
                const username = document.getElementById('emp-username').value.trim();
                const password = document.getElementById('emp-password').value;
                const role = document.getElementById('emp-role').value;

                if (!name || !card || !address || !tel || !gender || !username || !role) {
                    this.showToast('ກະລຸນາປ້ອນຂໍ້ມູນໃຫ້ຄົບຖ້ວນ', 'error'); return;
                }
                if (!empId && !password) {
                    this.showToast('ກະລຸນາຕັ້ງລະຫັດຜ່ານສໍາລັບພະນັກງານໃໝ່', 'error');
                    document.getElementById('emp-password').focus(); return;
                }

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
                if (!confirm('ຢືນຢັນການລຶບພະນັກງານທ່ານນີ້?')) return;

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
            loadFinance: function(month) {
                const m = month || document.getElementById('fin-month-picker')?.value || new Date().toISOString().slice(0,7);
                const tbody = document.getElementById('finance-table-body');
                if (tbody) tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-10"><i class="fa-solid fa-spinner fa-spin mr-2"></i>ກໍາລັງໂຫຼດ...</td></tr>';

                fetch(`actions/get_finance.php?month=${m}`)
                .then(res => res.json())
                .then(result => {
                    if (!result.success) return;
                    const fmt = n => this.formatCurrency(n);

                    document.getElementById('fin-revenue').innerText = fmt(result.total_revenue);
                    document.getElementById('fin-sales').innerText   = '₭' + fmt(result.sales_total);
                    document.getElementById('fin-other').innerText   = '₭' + fmt(result.other_revenue);
                    document.getElementById('fin-expense').innerText = fmt(result.total_expense);

                    const profit = result.net_profit;
                    document.getElementById('fin-profit').innerText = fmt(Math.abs(profit));
                    const profitEl = document.getElementById('fin-profit-amount');
                    const iconBox  = document.getElementById('fin-profit-icon-box');
                    const icon     = document.getElementById('fin-profit-icon');
                    if (profitEl) profitEl.className = 'text-xl font-black mt-0.5 ' + (profit >= 0 ? 'text-blue-600' : 'text-red-500');
                    if (iconBox)  iconBox.className  = iconBox.className.replace(/bg-\w+-100/, profit >= 0 ? 'bg-blue-100' : 'bg-red-100');
                    if (icon)     icon.className     = 'fa-solid fa-scale-balanced ' + (profit >= 0 ? 'text-blue-600' : 'text-red-500');
                    const prefix = document.getElementById('fin-profit');
                    if (prefix) prefix.closest('p').innerHTML = (profit < 0 ? '-' : '') + '₭<span id="fin-profit">' + fmt(Math.abs(profit)) + '</span>';

                    state.finHistory = result.history || [];
                    state.finPage = 1;
                    this.renderFinanceTable();
                })
                .catch(() => {
                    if (tbody) tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-red-400 py-10">ໂຫຼດຂໍ້ມູນບໍ່ໄດ້</td></tr>';
                });
            },

            renderFinanceTable: function() {
                const filter = state.finFilter || 'all';
                let rows = state.finHistory || [];
                if (filter === '1') rows = rows.filter(h => h.Revenue_id == 1);
                if (filter === '2') rows = rows.filter(h => h.Revenue_id == 2);

                const total  = rows.length;
                const perPage = state.finPerPage;
                const page   = state.finPage;
                const paged  = rows.slice((page - 1) * perPage, page * perPage);

                const tbody = document.getElementById('finance-table-body');
                if (!tbody) return;
                if (paged.length === 0) {
                    tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-12"><i class="fa-solid fa-inbox text-3xl block mb-2 text-slate-200"></i>ບໍ່ມີລາຍການໃນເດືອນນີ້</td></tr>';
                    const pg = document.getElementById('fin-pagination'); if (pg) pg.innerHTML = '';
                    return;
                }
                tbody.innerHTML = paged.map(h => `
                    <tr class="table-row">
                        <td class="text-slate-400 text-sm whitespace-nowrap">${new Date(h.Revenue_date + 'T00:00:00').toLocaleDateString('lo-LA', {day:'2-digit',month:'short'})}</td>
                        <td class="font-medium text-slate-700 text-sm">${h.Revenue_name}</td>
                        <td class="text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold ${h.Revenue_id == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'}">
                                ${h.Revenue_id == 1 ? '+ ລາຍຮັບ' : '− ລາຍຈ່າຍ'}
                            </span>
                        </td>
                        <td class="text-right font-bold text-sm ${h.Revenue_id == 1 ? 'text-green-600' : 'text-red-500'}">
                            ${h.Revenue_id == 1 ? '+' : '-'}₭${this.formatCurrency(h.Revenue_Price)}
                        </td>
                        <td class="text-center">
                            <button onclick="app.deleteFinance(${h.Revenue_no})"
                                class="w-7 h-7 bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 rounded-lg transition-colors" title="ລຶບ">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
                this.renderPagination('fin-pagination', page, total, perPage, 'setFinPage');
            },

            filterFinance: function(type) {
                state.finFilter = type;
                state.finPage = 1;
                ['all','1','2'].forEach(t => {
                    const btn = document.getElementById('fin-tab-' + t);
                    if (!btn) return;
                    btn.className = t === type
                        ? 'px-3 py-1 rounded-lg text-xs font-bold bg-primary text-white transition-colors'
                        : 'px-3 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors';
                });
                this.renderFinanceTable();
            },

            setFinPage: function(page) {
                state.finPage = Math.max(1, page);
                this.renderFinanceTable();
            },

            saveFinance: function(e) {
                e.preventDefault();
                const type_id = document.getElementById('fin-type').value;
                const name    = document.getElementById('fin-name').value.trim();
                const amount  = parseFloat(document.getElementById('fin-amount').value);
                const date    = document.getElementById('fin-date').value;

                if (!name)             { this.showToast('ກະລຸນາລະບຸລາຍການ', 'error');           document.getElementById('fin-name').focus();   return; }
                if (!amount || amount <= 0) { this.showToast('ຈໍານວນເງິນຕ້ອງຫຼາຍກວ່າ 0', 'error'); document.getElementById('fin-amount').focus(); return; }

                const btn = document.getElementById('fin-submit-btn');
                if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>ກໍາລັງບັນທຶກ...'; }

                fetch('actions/save_finance.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ type_id, name, amount, date })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(type_id == 1 ? 'ບັນທຶກລາຍຮັບສໍາເລັດ' : 'ບັນທຶກລາຍຈ່າຍສໍາເລັດ');
                        document.getElementById('fin-name').value   = '';
                        document.getElementById('fin-amount').value = '';
                        this.loadFinance();
                    } else {
                        this.showToast(result.message || 'ບັນທຶກບໍ່ສໍາເລັດ', 'error');
                    }
                })
                .finally(() => {
                    if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-save mr-2"></i>ບັນທຶກຂໍ້ມູນ'; }
                });
            },

            deleteFinance: async function(id) {
                if (!confirm('ຢືນຢັນລຶບລາຍການນີ້?')) return;
                try {
                    const res = await fetch('actions/delete_finance.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id })
                    });
                    const result = await res.json();
                    if (result.success) {
                        this.showToast('ລຶບລາຍການສໍາເລັດ');
                        this.loadFinance();
                    } else {
                        this.showToast(result.message || 'ລຶບບໍ່ສໍາເລັດ', 'error');
                    }
                } catch {
                    this.showToast('ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນ', 'error');
                }
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
                    tbody.innerHTML = '<tr><td colspan="3" class="p-8 text-center text-gray-400">ຍັງບໍ່ມີຂໍ້ມູນປະເພດວັດຖຸດິບໃນລະບົບ</td></tr>';
                    return;
                }
                tbody.innerHTML = materialTypes.map(mt => `
                    <tr class="table-row">
                        <td class="text-slate-400">${mt.MaterialType_id}</td>
                        <td class="font-medium text-slate-700">${mt.MaterialType_name}</td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="app.editMaterialType(${mt.MaterialType_id}, '${mt.MaterialType_name}')" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-edit text-xs"></i>
                                </button>
                                <button onclick="app.deleteMaterialType(${mt.MaterialType_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
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
                    this.showToast('ກະລຸນາລະບຸຊື່ປະເພດວັດຖຸດິບ', 'error');
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
                if (!confirm('ຢືນຢັນການລຶບປະເພດວັດຖຸດິບນີ້?')) return;

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

            // ================= ส่วนจัดการประเภทสินค้า (Product Type Functions) =================
            loadProductTypes: function() {
                fetch('actions/get_product_types.php')
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        state.productTypes = result.categories;
                        this.renderProductTypes(state.productTypes);
                    } else {
                        this.showToast(result.message, 'error');
                    }
                })
                .catch(err => console.error('Error loading product types:', err));
            },

            renderProductTypes: function(productTypes) {
                const tbody = document.getElementById('cat-table-body');
                if (!tbody) return;
                if (productTypes.length === 0) {
                    tbody.innerHTML = '<tr class="table-row"><td colspan="3" class="text-center py-10 text-slate-400">ຍັງບໍ່ມີຂໍ້ມູນປະເພດສິນຄ້າ</td></tr>';
                    return;
                }
                tbody.innerHTML = productTypes.map(pt => `
                    <tr class="table-row">
                        <td class="text-slate-400">${pt.ProductType_id}</td>
                        <td class="font-medium text-slate-700">${pt.ProductType_name}</td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="app.editProductType(${pt.ProductType_id}, '${pt.ProductType_name}')" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-edit text-xs"></i>
                                </button>
                                <button onclick="app.deleteProductType(${pt.ProductType_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-lg transition-colors">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `).join('');
            },

            searchProductTypes: function() {
                const query = document.getElementById('search-cat-input').value.toLowerCase();
                const filtered = state.productTypes.filter(pt =>
                    pt.ProductType_name.toLowerCase().includes(query) ||
                    pt.ProductType_id.toString().includes(query)
                );
                this.renderProductTypes(filtered);
            },

            saveCategory: function() {
                const idInput = document.getElementById('edit-cat-id');
                const nameInput = document.getElementById('new-cat-name');
                const id = idInput.value;
                const name = nameInput.value.trim();

                if (!name) {
                    this.showToast('ກະລຸນາລະບຸຊື່ປະເພດສິນຄ້າ', 'error');
                    return;
                }

                const url = id ? 'actions/update_product_type.php' : 'actions/add_product_type.php';
                const postData = id ? { id, name } : { name };

                fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(postData)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message || 'บันทึกสำเร็จ');
                        this.resetCategoryForm();
                        this.loadProductTypes();
                    } else {
                        this.showToast(result.message, 'error');
                    }
                });
            },

            editProductType: function(id, name) {
                document.getElementById('edit-cat-id').value = id;
                document.getElementById('new-cat-name').value = name;
                const btn = document.getElementById('cat-submit-btn');
                if (btn) btn.innerHTML = '<i class="fa-solid fa-save"></i> บันทึกการแก้ไข';
                document.getElementById('cat-cancel-btn').classList.remove('hidden');
                document.getElementById('new-cat-name').focus();
            },

            resetCategoryForm: function() {
                document.getElementById('edit-cat-id').value = '';
                document.getElementById('new-cat-name').value = '';
                const btn = document.getElementById('cat-submit-btn');
                if (btn) btn.innerHTML = '<i class="fa-solid fa-plus"></i> <span id="cat-submit-label">เพิ่มประเภท</span>';
                const cancelBtn = document.getElementById('cat-cancel-btn');
                if (cancelBtn) cancelBtn.classList.add('hidden');
            },

            deleteProductType: function(id) {
                if (!confirm('ຢືນຢັນການລຶບປະເພດສິນຄ້ານີ້?')) return;

                fetch('actions/delete_product_type.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message || 'ลบสำเร็จ');
                        this.loadProductTypes();
                    } else {
                        this.showToast(result.message, 'error');
                    }
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
                    <tr class="table-row">
                        <td class="font-medium text-slate-700">${m.Material_name}</td>
                        <td><span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-lg text-xs font-medium">${m.MaterialType_name}</span></td>
                        <td class="text-right font-bold ${m.Material_total <= 5 ? 'text-red-500' : m.Material_total <= 10 ? 'text-amber-500' : 'text-slate-700'}">
                            ${m.Material_total <= 5 ? '<i class="fa-solid fa-triangle-exclamation text-xs mr-1"></i>' : ''}${m.Material_total}
                        </td>
                        <td class="text-slate-500">${m.Material_unit}</td>
                        <td class="text-right text-slate-600">₭${this.formatCurrency(m.Material_costprice)}</td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <button onclick="app.editMaterial(${m.Material_id})" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 w-8 h-8 rounded-lg transition-colors"><i class="fa-solid fa-edit text-xs"></i></button>
                                <button onclick="app.deleteMaterial(${m.Material_id})" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 w-8 h-8 rounded-lg transition-colors"><i class="fa-solid fa-trash-can text-xs"></i></button>
                            </div>
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
                if (!confirm('ຢືນຢັນການລຶບວັດຖຸດິບນີ້? ຂໍ້ມູນສະຕ໋ອກຈະຫາຍໄປ')) return;

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
                    'material-admit', 'material-requisition', 'finance', 'reports', 'settings'
                ];

                allPages.forEach(p => {
                    const navBtn = document.getElementById(`nav-${p}`);
                    if(navBtn) navBtn.className = p === page
                        ? 'w-full flex items-center gap-3 px-3 py-2.5 rounded-xl bg-white/20 text-white font-bold text-sm'
                        : 'w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-orange-100 hover:bg-white/10 hover:text-white text-sm transition-colors';
                });

                const titles = {
                    'pos': 'ຂາຍອາຫານ (POS)', 'dashboard': 'ສະຫຼຸບຍອດຂາຍ / ລາຍການລໍຖ້າເສີບ', 'menu': 'ຈັດການເມນູອາຫານ',
                    'product-types': 'ຈັດການປະເພດສິນຄ້າ', 'material-types': 'ຈັດການປະເພດວັດຖຸດິບ',
                    'customers': 'ຂໍ້ມູນລູກຄ້າ', 'employees': 'ຂໍ້ມູນພະນັກງານ',
                    'materials': 'ສະຕ໋ອກວັດຖຸດິບ', 'material-order': 'ສັ່ງຊື້ວັດຖຸດິບ', 'material-admit': 'ຮັບເຂົ້າວັດຖຸດິບ',
                    'material-requisition': 'ເບີກໃຊ້ວັດຖຸດິບ', 'finance': 'ລາຍຮັບ - ລາຍຈ່າຍ',
                    'reports': 'ອອກລາຍງານ', 'settings': 'ຕັ້ງຄ່າລະບົບ'
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
                if (page === 'material-order' && typeof this.loadMaterialOrders === 'function') this.loadMaterialOrders();
                if (page === 'reports') this.loadMonthlySalesReport();
                if (page === 'settings') this.loadSettings();
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
                const borderClasses = ['border-green-500', 'border-red-500', 'border-blue-500'];
                borderClasses.forEach(c => toast.classList.remove(c));
                if (type === 'error') {
                    toast.classList.add('border-red-500');
                    icon.className = 'fa-solid fa-circle-exclamation text-red-500 text-xl';
                } else if (type === 'info') {
                    toast.classList.add('border-blue-500');
                    icon.className = 'fa-solid fa-bell text-blue-500 text-xl';
                } else {
                    toast.classList.add('border-green-500');
                    icon.className = 'fa-solid fa-circle-check text-green-500 text-xl';
                }
                toast.classList.remove('opacity-0', 'translate-y-[-20px]');
                setTimeout(() => toast.classList.add('opacity-0', 'translate-y-[-20px]'), 3000);
            },

            updateTime: function() {
                const now = new Date();
                const dateStr = now.toLocaleDateString('lo-LA', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
                const timeStr = now.toLocaleTimeString('lo-LA');
                document.getElementById('current-time').innerHTML = `<span class="hidden md:inline">${dateStr} | </span><span class="font-bold text-primary">${timeStr}</span>`;
            },

            // ================= Settings =================
            loadSettings: function() {
                fetch('actions/get_settings.php')
                .then(r => r.json())
                .then(result => {
                    if (!result.success) return;
                    state.settings = { ...state.settings, ...result.settings };
                    const s = state.settings;
                    const fmt = n => Number(n).toLocaleString('lo-LA');

                    // Populate settings form
                    const sn = document.getElementById('setting-shop-name');
                    if (sn) {
                        sn.value = s.shop_name || '';
                        document.getElementById('setting-shop-phone').value  = s.shop_phone || '';
                        document.getElementById('setting-shop-address').value = s.shop_address || '';
                        document.getElementById('setting-point-value').value  = s.point_value || 1000;
                        document.getElementById('setting-earn-rate').value    = s.earn_rate || 10000;
                        document.getElementById('setting-tables-count').value = s.tables_count || 20;
                        document.getElementById('setting-vip-threshold').value  = s.vip_threshold || 500000;
                        document.getElementById('setting-gold-threshold').value = s.gold_threshold || 2000000;
                        document.getElementById('setting-vip-discount').value   = s.vip_discount || 5;
                        document.getElementById('setting-gold-discount').value  = s.gold_discount || 10;
                    }

                    // Populate customer page info card
                    const iv = document.getElementById('info-vip-threshold');
                    if (iv) {
                        iv.innerText = '≥ ₭' + fmt(s.vip_threshold);
                        document.getElementById('info-gold-threshold').innerText = '≥ ₭' + fmt(s.gold_threshold);
                        document.getElementById('info-vip-discount').innerText   = s.vip_discount + '%';
                        document.getElementById('info-gold-discount').innerText  = s.gold_discount + '%';
                        document.getElementById('info-earn-rate').innerText      = '₭' + fmt(s.earn_rate);
                    }
                })
                .catch(() => {});
            },

            saveSettings: function(e) {
                e.preventDefault();
                const data = {
                    shop_name: document.getElementById('setting-shop-name').value,
                    shop_phone: document.getElementById('setting-shop-phone').value,
                    shop_address: document.getElementById('setting-shop-address').value
                };
                fetch('actions/save_settings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(result => {
                    if (result.success) {
                        state.settings = { ...state.settings, ...data };
                        this.showToast('ບັນທຶກຂໍ້ມູນຮ້ານສໍາເລັດ');
                    } else {
                        this.showToast(result.message || 'ບັນທຶກບໍ່ສໍາເລັດ', 'error');
                    }
                })
                .catch(() => this.showToast('ເຊື່ອມຕໍ່ບໍ່ໄດ້', 'error'));
            },

            savePosSettings: function(e) {
                e.preventDefault();
                const data = {
                    point_value:    parseInt(document.getElementById('setting-point-value').value)    || 1000,
                    earn_rate:      parseInt(document.getElementById('setting-earn-rate').value)       || 10000,
                    tables_count:   parseInt(document.getElementById('setting-tables-count').value)   || 20,
                    vip_threshold:  parseInt(document.getElementById('setting-vip-threshold').value)  || 500000,
                    gold_threshold: parseInt(document.getElementById('setting-gold-threshold').value) || 2000000,
                    vip_discount:   parseInt(document.getElementById('setting-vip-discount').value)   || 5,
                    gold_discount:  parseInt(document.getElementById('setting-gold-discount').value)  || 10,
                };
                if (data.gold_threshold <= data.vip_threshold) {
                    this.showToast('ຍອດ Gold ຕ້ອງຫຼາຍກວ່າ VIP', 'error');
                    document.getElementById('setting-gold-threshold').focus(); return;
                }
                if (data.tables_count < 1 || data.tables_count > 100) {
                    this.showToast('ຈໍານວນໂຕະຕ້ອງຢູ່ລະຫວ່າງ 1–100', 'error');
                    document.getElementById('setting-tables-count').focus(); return;
                }
                if (data.earn_rate < 1) {
                    this.showToast('ອັດຕາສະສົມຄະແນນຕ້ອງ > 0', 'error'); return;
                }
                fetch('actions/save_settings.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(result => {
                    if (result.success) {
                        state.settings = { ...state.settings, ...data };
                        this.showToast('ບັນທຶກການຕັ້ງຄ່າ POS ສໍາເລັດ');
                        this.renderTableSelection();
                    } else {
                        this.showToast(result.message || 'ບັນທຶກບໍ່ສໍາເລັດ', 'error');
                    }
                })
                .catch(() => this.showToast('ເຊື່ອມຕໍ່ບໍ່ໄດ້', 'error'));
            },

            useAllPoints: function() {
                const pts = parseInt(state.selectedPosCustomer?.Cus_Points) || 0;
                const inp = document.getElementById('points-to-use');
                if (inp) { inp.value = pts; this.calculateChange(); }
            },

            showPointHistory: function(cusId, cusName, curPoints, totalSpend) {
                const modal = document.getElementById('point-history-modal');
                if (!modal) return;
                modal.classList.remove('hidden');
                document.getElementById('adjust-cus-id').value = cusId;
                document.getElementById('point-history-title').innerText = 'ປະຫວັດຄະແນນ: ' + cusName;

                const fmt = n => Number(n).toLocaleString('lo-LA');
                const pv  = state.settings.point_value || 1000;
                const vipThr  = state.settings.vip_threshold  || 500000;
                const goldThr = state.settings.gold_threshold || 2000000;

                // Summary strip
                document.getElementById('point-history-summary').innerHTML = `
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">ຄະແນນປັດຈຸບັນ</p>
                        <p class="text-xl font-black text-purple-700">${fmt(curPoints)}</p>
                        <p class="text-[10px] text-purple-400">≈ ₭${fmt(curPoints * pv)}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">ຍອດໃຊ້ຈ່າຍລວມ</p>
                        <p class="text-xl font-black text-slate-700">₭${fmt(totalSpend)}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 uppercase font-bold">ຫຼຸດ/ທ່ຽວ</p>
                        <p class="text-xl font-black text-green-700">₭${fmt(state.settings.earn_rate || 10000)}</p>
                        <p class="text-[10px] text-green-500">= 1 ຄະ</p>
                    </div>
                `;

                // Progress bar
                const progressEl = document.getElementById('point-history-progress');
                let progressHTML = '';
                const spend = totalSpend;
                if (spend < vipThr) {
                    const pct = Math.min(100, (spend / vipThr) * 100).toFixed(1);
                    progressHTML = `<p class="text-xs text-slate-500 mb-1.5">ຄວາມຄືບໜ້າ → <span class="badge-vip">VIP</span> ₭${fmt(spend)} / ₭${fmt(vipThr)} (${pct}%)</p>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden"><div class="h-2 bg-purple-400 rounded-full transition-all" style="width:${pct}%"></div></div>`;
                } else if (spend < goldThr) {
                    const pct = Math.min(100, (spend / goldThr) * 100).toFixed(1);
                    progressHTML = `<p class="text-xs text-slate-500 mb-1.5">ຄວາມຄືບໜ້າ → <span class="badge-gold">Gold</span> ₭${fmt(spend)} / ₭${fmt(goldThr)} (${pct}%)</p>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden"><div class="h-2 bg-yellow-400 rounded-full transition-all" style="width:${pct}%"></div></div>`;
                } else {
                    progressHTML = `<div class="flex items-center gap-2 text-yellow-600 font-bold text-sm"><i class="fa-solid fa-crown"></i> Gold Member — ລະດັບສູງສຸດ</div>`;
                }
                if (progressHTML) {
                    progressEl.innerHTML = progressHTML;
                    progressEl.classList.remove('hidden');
                }

                // Load history
                const tbody = document.getElementById('point-history-table');
                tbody.innerHTML = '<tr class="table-row"><td colspan="4" class="text-center text-slate-400 py-6">ກໍາລັງໂຫຼດ...</td></tr>';
                fetch(`actions/get_point_history.php?cus_id=${cusId}`)
                .then(r => r.json())
                .then(result => {
                    if (!result.success || result.logs.length === 0) {
                        tbody.innerHTML = '<tr class="table-row"><td colspan="4" class="text-center text-slate-400 py-6 italic">ຍັງບໍ່ມີປະຫວັດຄະແນນ</td></tr>';
                        return;
                    }
                    const typeLabel = { earn: { text: 'ສະສົມ', cls: 'bg-green-100 text-green-700' }, redeem: { text: 'ໃຊ້', cls: 'bg-red-100 text-red-600' }, adjust: { text: 'ປັບ', cls: 'bg-blue-100 text-blue-600' } };
                    tbody.innerHTML = result.logs.map(l => {
                        const t = typeLabel[l.Log_type] || { text: l.Log_type, cls: 'bg-slate-100 text-slate-600' };
                        const pts = parseInt(l.Points);
                        return `<tr class="table-row">
                            <td class="text-slate-400 text-xs">${new Date(l.Log_date).toLocaleDateString('lo-LA')}</td>
                            <td><span class="px-2 py-0.5 rounded-full text-xs font-bold ${t.cls}">${t.text}</span></td>
                            <td class="text-slate-500 text-xs">${l.Log_note || '-'}</td>
                            <td class="text-right font-bold ${pts > 0 ? 'text-green-600' : 'text-red-500'}">${pts > 0 ? '+' : ''}${fmt(pts)}</td>
                        </tr>`;
                    }).join('');
                })
                .catch(() => { tbody.innerHTML = '<tr class="table-row"><td colspan="4" class="text-center text-red-400 py-6">ໂຫຼດຂໍ້ມູນບໍ່ໄດ້</td></tr>'; });
            },

            adjustPoints: function() {
                const cusId = document.getElementById('adjust-cus-id').value;
                const pts   = parseInt(document.getElementById('adjust-points').value) || 0;
                const note  = document.getElementById('adjust-note').value.trim() || 'ປັບຄ່ານວນໂດຍ Admin';
                if (!cusId || pts === 0) { this.showToast('ກະລຸນາລະບຸຈໍານວນຄະແນນ', 'error'); return; }
                fetch('actions/adjust_points.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ cus_id: cusId, points: pts, note })
                })
                .then(r => r.json())
                .then(result => {
                    if (result.success) {
                        this.showToast(result.message);
                        document.getElementById('adjust-points').value = '';
                        document.getElementById('adjust-note').value = '';
                        this.loadCustomers();
                        document.getElementById('point-history-modal').classList.add('hidden');
                    } else { this.showToast(result.message, 'error'); }
                })
                .catch(() => this.showToast('ເຊື່ອມຕໍ່ບໍ່ໄດ້', 'error'));
            },

            // ================= Reports =================
            showDailySalesPanel: function() {
                const panel = document.getElementById('daily-sales-panel');
                if (!panel) return;
                panel.classList.remove('hidden');
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                const dateInput = document.getElementById('daily-sales-date');
                if (dateInput && !dateInput.value) {
                    dateInput.value = new Date().toISOString().split('T')[0];
                }
                this.loadDailySales(document.getElementById('daily-sales-date').value);
            },

            showStockReportPanel: function() {
                const panel = document.getElementById('stock-report-panel');
                if (!panel) return;
                panel.classList.remove('hidden');
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                this.loadStockReport();
            },

            renderDashboardSales: function() {
                const tbody = document.getElementById('order-history-table');
                if (!tbody) return;
                const rows = state.salesData || [];
                const total  = rows.length;
                const perPage = state.salesPerPage;
                const page   = state.salesPage;
                const paged  = rows.slice((page - 1) * perPage, page * perPage);
                if (paged.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="p-8 text-center text-gray-400">ຍັງບໍ່ມີຂໍ້ມູນການຂາຍໃນລະບົບ</td></tr>';
                    const pg = document.getElementById('sales-pagination'); if (pg) pg.innerHTML = '';
                    return;
                }
                tbody.innerHTML = paged.map(order => {
                    const _d = new Date(order.Sale_date);
                    const saleDate = _d.toLocaleDateString('lo-LA', {day:'2-digit',month:'short'}) + ' ' + _d.toLocaleTimeString('lo-LA', {hour:'2-digit',minute:'2-digit'});
                    const orderId = 'ORD-' + order.Sale_id.toString().padStart(5, '0');
                    return `<tr class="table-row">
                        <td class="font-mono font-bold text-slate-600">${orderId}</td>
                        <td class="text-slate-400">${saleDate}</td>
                        <td class="text-slate-500 truncate max-w-[150px] lg:max-w-xs" title="${order.items}">${order.items || '-'}</td>
                        <td class="font-bold text-green-600 text-right">₭${this.formatCurrency(order.Sale_sumprice)}</td>
                    </tr>`;
                }).join('');
                this.renderPagination('sales-pagination', page, total, perPage, 'setSalesPage');
            },

            setSalesPage: function(page) {
                state.salesPage = Math.max(1, page);
                this.renderDashboardSales();
            },

            loadDailySales: function(date) {
                const tbody = document.getElementById('daily-sales-table');
                if (!tbody) return;
                tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-6">ກໍາລັງໂຫຼດ...</td></tr>';
                const d = date || new Date().toISOString().split('T')[0];
                fetch(`actions/get_daily_sales.php?date=${d}`)
                .then(r => r.json())
                .then(result => {
                    const totalEl = document.getElementById('daily-sales-total');
                    const countEl = document.getElementById('daily-sales-count');
                    if (totalEl) totalEl.innerText = this.formatCurrency(result.total || 0);
                    if (countEl) countEl.innerText = result.count || 0;
                    if (!result.success || !result.sales || result.sales.length === 0) {
                        tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-8 italic">ບໍ່ມີຂໍ້ມູນການຂາຍໃນວັນທີນີ້</td></tr>';
                        return;
                    }
                    tbody.innerHTML = result.sales.map(s => `
                        <tr class="table-row">
                            <td class="font-mono font-bold text-slate-600">ORD-${String(s.Sale_id).padStart(5,'0')}</td>
                            <td class="text-slate-500">${s.Table_no ? 'ໂຕະ '+s.Table_no : '-'}</td>
                            <td class="text-slate-700">${s.Cus_name}</td>
                            <td class="text-slate-400 text-xs max-w-xs truncate">${s.items || '-'}</td>
                            <td class="text-right font-bold text-green-600">₭${this.formatCurrency(s.Sale_sumprice)}</td>
                        </tr>
                    `).join('');
                })
                .catch(() => { tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-8">ໂຫຼດຂໍ້ມູນບໍ່ໄດ້</td></tr>'; });
            },

            loadStockReport: function() {
                const tbody = document.getElementById('stock-report-table');
                if (!tbody) return;
                tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-6">ກໍາລັງໂຫຼດ...</td></tr>';
                fetch('actions/get_stock_report.php')
                .then(r => r.json())
                .then(result => {
                    const lowEl = document.getElementById('stock-low-count');
                    const medEl = document.getElementById('stock-medium-count');
                    if (lowEl) lowEl.innerText = result.low_count || 0;
                    if (medEl) medEl.innerText = result.medium_count || 0;
                    if (!result.success || !result.materials || result.materials.length === 0) {
                        tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-8 italic">ບໍ່ມີຂໍ້ມູນ</td></tr>';
                        return;
                    }
                    tbody.innerHTML = result.materials.map(m => {
                        const qty = parseFloat(m.Material_total);
                        let badge = '<span class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">ປົກກະຕິ</span>';
                        if (qty <= 5) badge = '<span class="px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-600">ສ່ຽງໝົດ</span>';
                        else if (qty <= 10) badge = '<span class="px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">ໃກ້ໝົດ</span>';
                        return `<tr class="table-row">
                            <td class="font-medium text-slate-700">${m.Material_name}</td>
                            <td class="text-slate-500">${m.MaterialType_name}</td>
                            <td class="text-right font-bold ${qty <= 5 ? 'text-red-600' : qty <= 10 ? 'text-yellow-600' : 'text-slate-700'}">${qty}</td>
                            <td class="text-slate-400">${m.Material_unit}</td>
                            <td>${badge}</td>
                        </tr>`;
                    }).join('');
                })
                .catch(() => { tbody.innerHTML = '<tr class="table-row"><td colspan="5" class="text-center text-slate-400 py-8">ໂຫຼດຂໍ້ມູນບໍ່ໄດ້</td></tr>'; });
            }
        };

        // ใช้ Object.assign เพื่อรวมฟังก์ชันจากไฟล์อื่นๆ เข้ากับตัวแปรหลัก โดยไม่ลบฟังก์ชันเดิมทิ้ง
        window.app = Object.assign(window.app || {}, mainFunctions);
        window.addEventListener('DOMContentLoaded', () => window.app.init());
    </script>
</body>
</html>