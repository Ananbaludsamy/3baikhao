<!-- ================= ໜ້າ: POS (ການຂາຍ) ================= -->
<div id="page-pos" class="absolute inset-0 flex flex-col lg:flex-row transition-opacity duration-300 w-full h-full lg:pr-96 overflow-hidden">

    <!-- Product Area -->
    <div class="flex-1 px-3 sm:px-4 py-3 overflow-y-auto no-scrollbar pb-24 lg:pb-4 flex flex-col w-full">
        <!-- Category Filter -->
        <div class="flex gap-2 mb-4 overflow-x-auto pb-1 no-scrollbar flex-shrink-0">
            <button onclick="app.setFilter('all')" id="filter-all"
                class="filter-btn px-4 py-2 bg-primary text-white rounded-full whitespace-nowrap shadow text-xs font-bold">ທັງໝົດ</button>
            <?php foreach ($categories as $cat): ?>
                <button onclick="app.setFilter('<?php echo $cat['ProductType_id']; ?>')"
                    id="filter-<?php echo $cat['ProductType_id']; ?>"
                    class="filter-btn px-4 py-2 bg-white text-slate-600 hover:bg-orange-50 rounded-full whitespace-nowrap shadow-sm border border-slate-200 text-xs transition-colors">
                    <?php echo htmlspecialchars($cat['ProductType_name']); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <!-- Product Grid -->
        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-3 pb-10 w-full"></div>
    </div>

    <!-- Cart Panel -->
    <div class="w-full lg:w-96 bg-white shadow-2xl lg:shadow-lg border-l border-slate-200 flex flex-col h-[62vh] lg:h-full z-20 absolute lg:fixed bottom-0 lg:bottom-auto lg:right-0 lg:top-0 rounded-t-2xl lg:rounded-none transition-transform duration-300 transform translate-y-[calc(100%-60px)] lg:translate-y-0"
        id="mobile-cart-panel">

        <!-- Cart Header -->
        <div class="px-4 py-3 border-b border-slate-100 flex justify-between items-center bg-white rounded-t-2xl lg:rounded-none cursor-pointer lg:cursor-auto"
            onclick="app.toggleMobileCart()">
            <h3 class="font-black text-primary flex items-center gap-2 text-sm">
                <i class="fa-solid fa-clipboard-list"></i> ບິນປັດຈຸບັນ
            </h3>
            <div class="flex items-center gap-2">
                <span id="cart-count-badge" class="bg-primary text-white px-2.5 py-0.5 rounded-full text-xs font-bold lg:hidden">0</span>
                <i class="fa-solid fa-chevron-up lg:hidden transition-transform text-slate-400 text-sm" id="cart-chevron"></i>
            </div>
        </div>

        <!-- Cart Items -->
        <div id="cart-items-container" class="flex-1 overflow-y-auto p-3 bg-slate-50/50"></div>

        <!-- Cart Summary -->
        <div id="cart-summary" class="p-4 bg-white border-t border-slate-100">

            <!-- Subtotal -->
            <div class="flex justify-between mb-3 text-slate-600 text-sm">
                <span>ຍອດລວມ (<span id="cart-total-qty">0</span> ລາຍການ)</span>
                <span class="font-bold text-slate-700">₭<span id="cart-subtotal">0</span></span>
            </div>

            <!-- Member Search -->
            <div class="mb-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">ສະມາຊິກ</label>
                <div class="relative">
                    <input type="text" id="customer-search-input" oninput="app.filterCustomers(this.value)"
                        class="w-full px-3 py-2 border-1.5 border-slate-200 rounded-xl text-sm outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100 bg-white transition-all"
                        placeholder="ຄົ້ນຫາຊື່ ຫຼື ເບີໂທ"
                        onfocus="app.renderCustomerSearch(); document.getElementById('customer-search-results').classList.remove('hidden')"
                        onblur="setTimeout(() => document.getElementById('customer-search-results').classList.add('hidden'), 200)">
                    <div id="customer-search-results"
                        class="absolute z-10 w-full bg-white border border-slate-200 rounded-xl shadow-lg mt-1 max-h-48 overflow-y-auto hidden">
                    </div>
                </div>
                <input type="hidden" id="pos-customer-select-id" value="1">
                <div id="member-discount-info" class="text-[10px] text-green-600 font-bold mt-1 hidden">
                    <i class="fa-solid fa-star"></i> ສ່ວນຫຼຸດສະມາຊິກ <span id="member-discount-rate">0</span>%
                </div>
            </div>

            <!-- Points Redemption -->
            <div id="points-redemption-section" class="mb-3 hidden">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">ແລກຄະແນນ</label>
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-2.5">
                    <div class="flex justify-between items-center mb-2 text-xs">
                        <span class="text-slate-500">ຄະແນນທີ່ມີ:</span>
                        <span class="font-black text-purple-700">
                            <span id="available-points">0</span> ຄະ
                            <span class="text-purple-400 font-normal">(≈₭<span id="available-points-value">0</span>)</span>
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <input type="number" id="points-to-use" min="0" value="0"
                            oninput="app.calculateChange()"
                            class="flex-1 px-2 py-1.5 border border-purple-300 rounded-lg text-sm text-right font-bold outline-none focus:border-purple-500 bg-white">
                        <button type="button" onclick="app.useAllPoints()"
                            class="px-2.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-lg transition-colors whitespace-nowrap">
                            ໃຊ້ທັງໝົດ
                        </button>
                    </div>
                    <div id="points-discount-display" class="hidden mt-1.5 flex justify-between text-xs font-bold text-purple-700">
                        <span>ສ່ວນຫຼຸດຄະແນນ:</span>
                        <span>-₭<span id="points-discount-amount">0</span></span>
                    </div>
                </div>
            </div>

            <!-- Table Selection -->
            <div class="mb-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">ເລືອກໂຕະ</label>
                <div id="table-grid" class="grid grid-cols-5 gap-1.5 max-h-32 overflow-y-auto no-scrollbar p-2 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                </div>
                <input type="hidden" id="table-number" value="">
            </div>

            <!-- Cash Input -->
            <div class="mb-3">
                <div class="flex justify-between items-center mb-1.5">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">ເງິນຮັບ</label>
                    <input type="number" id="cash-received" oninput="app.calculateChange()"
                        class="text-right border-b-2 border-primary focus:outline-none font-black text-lg w-32 bg-transparent"
                        placeholder="0">
                </div>
                <div class="flex flex-wrap gap-1 justify-end">
                    <?php foreach ([5000, 10000, 20000, 50000, 100000] as $amt): ?>
                    <button onclick="app.setQuickCash(<?php echo $amt; ?>)"
                        class="px-2 py-1 bg-slate-100 hover:bg-orange-100 hover:text-primary rounded-lg text-xs border border-slate-200 transition-colors font-medium">
                        <?php echo number_format($amt); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Change & Total -->
            <div class="flex justify-between mb-1 text-orange-500 font-bold text-sm">
                <span>ເງິນທອນ</span>
                <span>₭<span id="cart-change">0</span></span>
            </div>
            <div class="flex justify-between mb-3 font-black text-xl text-primary border-t border-slate-100 pt-2 mt-1">
                <span>ຍອດສຸດທິ</span>
                <span>₭<span id="cart-total">0</span></span>
            </div>

            <!-- Checkout Button -->
            <button onclick="app.checkout()" id="checkout-btn"
                class="w-full bg-green-600 hover:bg-green-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-black py-3.5 rounded-xl shadow transition-all flex justify-center items-center gap-2 text-base active:scale-95">
                <i class="fa-solid fa-check-circle"></i> ຢືນຢັນຊໍາລະເງິນ
            </button>
        </div>
    </div>
</div>
