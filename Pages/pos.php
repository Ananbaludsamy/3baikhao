<!-- ================= หน้า 1: POS (การขาย) ================= -->
<div id="page-pos" class="absolute inset-0 flex flex-col lg:flex-row transition-opacity duration-300">
    <div class="flex-1 p-4 overflow-y-auto no-scrollbar pb-24 lg:pb-4 flex flex-col">
        <div class="flex gap-2 mb-4 overflow-x-auto pb-2 no-scrollbar flex-shrink-0">
            <button onclick="app.setFilter('all')" id="filter-all"
                class="filter-btn px-4 py-2 bg-primary text-white rounded-full whitespace-nowrap shadow">ทั้งหมด</button>
            <?php foreach ($categories as $cat): ?>
                <button onclick="app.setFilter('<?php echo $cat['ProductType_id']; ?>')"
                    id="filter-<?php echo $cat['ProductType_id']; ?>"
                    class="filter-btn px-4 py-2 bg-white text-gray-600 hover:bg-gray-200 rounded-full whitespace-nowrap shadow-sm border border-gray-200">
                    <?php echo htmlspecialchars($cat['ProductType_name']); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 pb-10"></div>
    </div>

    <div class="w-full lg:w-96 bg-white shadow-2xl lg:shadow-lg border-l border-gray-200 flex flex-col h-[60vh] lg:h-full z-20 absolute lg:relative bottom-0 lg:bottom-auto rounded-t-3xl lg:rounded-none transition-transform duration-300 transform translate-y-[calc(100%-60px)] lg:translate-y-0"
        id="mobile-cart-panel">
        <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-gray-50 lg:bg-white rounded-t-3xl lg:rounded-none cursor-pointer lg:cursor-auto shadow-sm lg:shadow-none"
            onclick="app.toggleMobileCart()">
            <h3 class="font-bold text-lg text-primary flex items-center">
                <i class="fa-solid fa-clipboard-list mr-2"></i> บิลปัจจุบัน
            </h3>
            <div class="flex items-center gap-2">
                <span id="cart-count-badge"
                    class="bg-primary text-white px-3 py-1 rounded-full text-xs font-bold lg:hidden shadow">0</span>
                <i class="fa-solid fa-chevron-up lg:hidden transition-transform text-gray-500" id="cart-chevron"></i>
            </div>
        </div>
        <div id="cart-items-container" class="flex-1 overflow-y-auto p-4 bg-gray-50/50"></div>
        <div id="cart-summary" class="p-4 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
            <div class="flex justify-between mb-2 text-gray-600 text-sm md:text-base">
                <span>ยอดรวม (<span id="cart-total-qty">0</span> รายการ)</span>
                <span><span id="cart-subtotal">0</span> ₭</span>
            </div>
            <div class="mb-3">
                <div class="flex justify-between mb-1 text-gray-700 font-medium">
                    <span>เงินรับ (Cash)</span>
                    <div class="relative w-32">
                        <input type="number" id="cash-received" oninput="app.calculateChange()" 
                            class="w-full text-right border-b-2 border-primary focus:outline-none font-bold text-lg" 
                            placeholder="0">
                    </div>
                </div>
                <!-- ปุ่มลัดเงินกีบ -->
                <div class="flex flex-wrap gap-1 justify-end mt-2">
                    <button onclick="app.setQuickCash(5000)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs border border-gray-300 transition-colors">5,000</button>
                    <button onclick="app.setQuickCash(10000)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs border border-gray-300 transition-colors">10,000</button>
                    <button onclick="app.setQuickCash(20000)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs border border-gray-300 transition-colors">20,000</button>
                    <button onclick="app.setQuickCash(50000)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs border border-gray-300 transition-colors">50,000</button>
                    <button onclick="app.setQuickCash(100000)" class="px-2 py-1 bg-gray-100 hover:bg-gray-200 rounded text-xs border border-gray-300 transition-colors">100,000</button>
                </div>
            </div>
            <div class="flex justify-between mb-2 text-orange-600 font-bold">
                <span>เงินทอน (Change)</span>
                <span><span id="cart-change">0</span> ₭</span>
            </div>
            <div
                class="flex justify-between mb-4 font-bold text-xl md:text-2xl text-primary border-b border-gray-200 pb-2">
                <span>ยอดสุทธิ</span>
                <span><span id="cart-total">0</span> ₭</span>
            </div>
            <button onclick="app.checkout()" id="checkout-btn"
                class="w-full bg-green-600 hover:bg-green-700 disabled:bg-gray-300 disabled:cursor-not-allowed text-white font-bold py-3 md:py-4 rounded-xl shadow-md transition-all flex justify-center items-center gap-2 text-lg active:scale-95">
                <i class="fa-solid fa-check-circle"></i> ยืนยันชำระเงิน
            </button>
        </div>
    </div>
</div>