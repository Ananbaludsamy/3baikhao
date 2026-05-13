<!-- ================= หน้า: ประเภทสินค้า ================= -->
<div id="page-product-types" class="absolute inset-0 p-4 lg:p-8 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-4 border-b border-gray-200 bg-primary text-white">
                <h3 class="font-bold"><i class="fa-solid fa-tags mr-2"></i>จัดการประเภทสินค้า</h3>
            </div>
            <div class="p-6">
                <div class="flex gap-4 mb-6">
                    <input type="hidden" id="edit-cat-id">
                    <input type="text" id="new-cat-name" class="flex-1 p-2 border rounded-lg outline-none focus:ring-2 focus:ring-primary" placeholder="ระบุชื่อประเภทสินค้า">
                    <div class="flex gap-2">
                        <button id="cat-submit-btn" onclick="app.saveCategory()" class="bg-primary text-white px-6 py-2 rounded-lg font-bold hover:bg-secondary transition-colors">เพิ่มประเภท</button>
                        <button id="cat-cancel-btn" onclick="app.resetCategoryForm()" class="hidden bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300">ยกเลิก</button>
                    </div>
                </div>
                <!-- ส่วนค้นหา -->
                <div class="mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                    </div>
                    <input type="text" id="search-cat-input" oninput="app.searchProductTypes()" 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm" 
                        placeholder="ค้นหาชื่อประเภทสินค้า หรือรหัส...">
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead><tr class="bg-gray-100 text-sm">
                            <th class="p-3 border-b">รหัส</th>
                            <th class="p-3 border-b">ชื่อประเภทสินค้า</th>
                            <th class="p-3 border-b text-center w-24">จัดการ</th>
                        </tr></thead>
                        <tbody id="cat-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>