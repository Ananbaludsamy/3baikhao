<?php
$stmt_all_mat = $conn->query("SELECT * FROM material_db ORDER BY Material_name ASC");
$all_materials = $stmt_all_mat->fetchAll();
?>

<!-- ================= ໜ້າ: ສັ່ງຊື້ວັດຖຸດິບ ================= -->
<div id="page-material-order" class="absolute inset-0 p-4 lg:p-6 overflow-y-auto hidden opacity-0 transition-opacity duration-300">
    <div class="max-w-5xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-5">
            <div>
                <h2 class="text-base font-black text-slate-800">ລາຍການສັ່ງຊື້ວັດຖຸດິບ</h2>
                <p class="text-xs text-slate-400 mt-0.5">ສ້າງໃບສັ່ງຊື້ — ລາຍຈ່າຍຈະຖືກບັນທຶກຕອນ <span class="text-blue-500 font-bold">ຮັບເຂົ້າວັດຖຸດິບ</span> ເທົ່ານັ້ນ</p>
            </div>
            <button onclick="app.showOrderForm()" id="btn-new-order" class="btn-primary">
                <i class="fa-solid fa-plus-circle"></i> ສ້າງໃບສັ່ງຊື້ໃໝ່
            </button>
        </div>

        <!-- ຟອມສ້າງໃບສັ່ງຊື້ -->
        <div id="order-form-container" class="page-card mb-5 hidden">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-file-invoice text-blue-600 text-sm"></i>
                    </div>
                    <h3 class="font-bold text-slate-700 text-sm" id="order-form-title">ລາຍລະອຽດໃບສັ່ງຊື້ໃໝ່</h3>
                </div>
                <button type="button" onclick="app.hideOrderForm()" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="fa-solid fa-times text-lg"></i>
                </button>
            </div>
            <form id="material-order-form" onsubmit="app.saveMaterialOrder(event)" class="p-4 lg:p-5">
                <input type="hidden" id="order-id" value="">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
                    <div>
                        <label class="form-label">ຜູ້ສັ່ງຊື້</label>
                        <input type="text" readonly class="form-input" style="background:#f8fafc;color:#64748b;"
                            value="<?php echo htmlspecialchars($_SESSION['Emp_name'] ?? ''); ?>">
                        <input type="hidden" id="order-emp-id" value="<?php echo $_SESSION['Emp_id'] ?? ''; ?>">
                    </div>
                    <div>
                        <label class="form-label">ວັນທີສັ່ງຊື້</label>
                        <input type="date" id="order-date" required class="form-input" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div>
                        <label class="form-label">ໝາຍເຫດ</label>
                        <input type="text" id="order-note" class="form-input" placeholder="ເຊັ່ນ: ດ່ວນ, ລະບຸຍີ່ຫໍ້...">
                    </div>
                </div>

                <!-- ຕາລາງລາຍການ -->
                <div class="mb-5">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">ລາຍການວັດຖຸດິບ</p>
                        <button type="button" onclick="app.addOrderRow()" class="btn-secondary" style="padding:6px 14px;font-size:12px;">
                            <i class="fa-solid fa-plus"></i> ເພີ່ມລາຍການ
                        </button>
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-slate-100">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="table-header bg-slate-50">
                                    <th>ວັດຖຸດິບ</th>
                                    <th class="w-36 text-right">ຈໍານວນ</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="order-items-body"></tbody>
                        </table>
                    </div>
                    <p id="order-empty-msg" class="text-center text-slate-400 text-sm py-6">
                        <i class="fa-solid fa-arrow-up text-xs mr-1"></i> ກົດ "ເພີ່ມລາຍການ" ເພື່ອເລີ່ມ
                    </p>
                </div>

                <!-- Summary + Submit -->
                <div class="flex flex-col md:flex-row justify-end gap-5 border-t border-slate-100 pt-5">
                    <div class="w-full md:w-64">
                        <div class="bg-slate-50 rounded-xl p-4 mb-4">
                            <div class="flex justify-between text-sm text-slate-500">
                                <span>ລາຍການທັງໝົດ:</span>
                                <span id="order-items-count" class="font-bold text-slate-700 text-base">0 ລາຍການ</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" onclick="app.hideOrderForm()" class="btn-secondary flex-1 justify-center">
                                ຍົກເລີກ
                            </button>
                            <button type="submit" id="order-submit-btn" class="btn-primary flex-1 justify-center" style="background:#16a34a;">
                                <i class="fa-solid fa-save"></i> ບັນທຶກ
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- ປະຫວັດໃບສັ່ງຊື້ -->
        <div class="page-card">
            <div class="page-card-header">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-slate-400 text-sm"></i>
                    <h3 class="font-bold text-slate-700 text-sm">ປະຫວັດໃບສັ່ງຊື້ທັງໝົດ</h3>
                    <span class="text-xs text-slate-400 font-normal" id="order-count"></span>
                </div>
                <button onclick="app.loadMaterialOrders()" class="btn-secondary" style="padding:6px 12px;font-size:12px;">
                    <i class="fa-solid fa-rotate"></i> ໂຫຼດໃໝ່
                </button>
            </div>
            <div class="px-4 py-3 border-b border-slate-100">
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-300 text-xs pointer-events-none"></i>
                    <input type="text" id="order-search" oninput="app.filterOrders()" placeholder="ຄົ້ນຫາ PO, ຜູ້ສັ່ງຊື້, ໝາຍເຫດ..." class="form-input pl-8" style="padding-top:7px;padding-bottom:7px;font-size:13px;">
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="table-header">
                            <th class="w-32">ເລກທີ PO</th>
                            <th class="w-32">ວັນທີ</th>
                            <th>ຜູ້ສັ່ງຊື້</th>
                            <th class="w-32 text-center">ຈໍານວນລາຍການ</th>
                            <th class="w-40">ໝາຍເຫດ</th>
                            <th class="w-32 text-center">ຈັດການ</th>
                        </tr>
                    </thead>
                    <tbody id="order-list-table">
                        <tr class="table-row">
                            <td colspan="6" class="text-center text-slate-400 py-10">ກໍາລັງໂຫຼດ...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======= Modal ລາຍລະອຽດໃບສັ່ງຊື້ ======= -->
<div id="order-detail-modal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-5 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-file-invoice text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-black text-slate-800 text-sm" id="modal-po-title">ລາຍລະອຽດໃບສັ່ງຊື້</h3>
                    <p class="text-xs text-slate-400" id="modal-po-meta"></p>
                </div>
            </div>
            <button onclick="document.getElementById('order-detail-modal').classList.add('hidden')"
                class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-lg flex items-center justify-center transition-colors">
                <i class="fa-solid fa-times text-slate-500 text-sm"></i>
            </button>
        </div>
        <!-- Modal Body -->
        <div class="overflow-y-auto flex-1 p-5">
            <div id="modal-items-content">
                <div class="text-center py-8 text-slate-400">ກໍາລັງໂຫຼດ...</div>
            </div>
        </div>
        <!-- Modal Footer -->
        <div class="p-4 border-t border-slate-100 flex justify-between items-center">
            <div class="text-sm text-slate-500">
                ລາຍການທັງໝົດ:
                <span id="modal-grand-total" class="text-xl font-black text-slate-700 ml-2">0 ລາຍການ</span>
            </div>
            <button onclick="document.getElementById('order-detail-modal').classList.add('hidden')"
                class="btn-secondary" style="padding:8px 20px;">
                ປິດ
            </button>
        </div>
    </div>
</div>

<script>
window.app = window.app || {};
window.orderAllData = [];
window.orderSearch  = '';

window.materialOptionsHTML = `
    <option value="">-- ເລືອກວັດຖຸດິບ --</option>
    <?php foreach($all_materials as $mat): ?>
        <option value="<?php echo $mat['Material_id']; ?>"
            data-unit="<?php echo htmlspecialchars($mat['Material_unit']); ?>">
            <?php echo htmlspecialchars($mat['Material_name']); ?> (<?php echo htmlspecialchars($mat['Material_unit']); ?>)
        </option>
    <?php endforeach; ?>
`;

app.showOrderForm = function() {
    document.getElementById('order-id').value = '';
    document.getElementById('order-form-title').innerText = 'ລາຍລະອຽດໃບສັ່ງຊື້ໃໝ່';
    document.getElementById('order-submit-btn').innerHTML = '<i class="fa-solid fa-save"></i> ບັນທຶກ';
    document.getElementById('order-submit-btn').style.background = '#16a34a';
    const container = document.getElementById('order-form-container');
    container.classList.remove('hidden');
    document.getElementById('order-items-body').innerHTML = '';
    document.getElementById('order-empty-msg').classList.remove('hidden');
    document.getElementById('order-date').value = new Date().toISOString().split('T')[0];
    document.getElementById('order-note').value = '';
    this.updateOrderTotal();
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });
};

app.hideOrderForm = function() {
    document.getElementById('order-form-container').classList.add('hidden');
};

app.editMaterialOrder = function(orderId) {
    const container = document.getElementById('order-form-container');
    const tbody     = document.getElementById('order-items-body');
    const poNo      = 'PO-' + String(orderId).padStart(6, '0');

    document.getElementById('order-id').value = orderId;
    document.getElementById('order-form-title').innerText = 'ແກ້ໄຂ ' + poNo;
    document.getElementById('order-submit-btn').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> ບັນທຶກການແກ້ໄຂ';
    document.getElementById('order-submit-btn').style.background = '#d97706';
    container.classList.remove('hidden');
    document.getElementById('order-empty-msg').classList.add('hidden');
    tbody.innerHTML = '<tr><td colspan="3" class="text-center py-6 text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>ກໍາລັງໂຫຼດ...</td></tr>';
    container.scrollIntoView({ behavior: 'smooth', block: 'start' });

    fetch(`actions/get_order_detail.php?id=${orderId}`)
    .then(r => r.json())
    .then(result => {
        if (!result.success) { app.showToast('ໂຫຼດຂໍ້ມູນບໍ່ໄດ້', 'error'); return; }
        const h = result.header;
        document.getElementById('order-date').value = (h.Order_date || '').substring(0, 10);
        document.getElementById('order-note').value = h.Order_note || '';
        tbody.innerHTML = '';
        result.items.forEach(item => {
            const tr = document.createElement('tr');
            tr.className = 'table-row';
            tr.innerHTML = `
                <td>
                    <select class="order-mat-select form-input text-xs" style="padding:7px 10px;min-width:200px;" onchange="app.updateOrderTotal()">
                        ${window.materialOptionsHTML || ''}
                    </select>
                </td>
                <td>
                    <input type="number" class="order-qty-input form-input text-xs text-right" style="padding:7px 10px;width:90px;"
                        min="1" value="${item.Order_total}" oninput="app.updateOrderTotal()">
                </td>
                <td class="text-center">
                    <button type="button" onclick="this.closest('tr').remove(); app.updateOrderTotal();"
                        class="w-7 h-7 bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 rounded-lg transition-colors">
                        <i class="fa-solid fa-times text-xs"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            tr.querySelector('.order-mat-select').value = item.Material_id;
        });
        app.updateOrderTotal();
    })
    .catch(() => { app.showToast('ໂຫຼດຂໍ້ມູນບໍ່ໄດ້', 'error'); });
};

app.addOrderRow = function() {
    const tbody = document.getElementById('order-items-body');
    document.getElementById('order-empty-msg').classList.add('hidden');
    const tr = document.createElement('tr');
    tr.className = 'table-row';
    tr.innerHTML = `
        <td>
            <select class="order-mat-select form-input text-xs" style="padding:7px 10px;min-width:200px;" onchange="app.updateOrderTotal()">
                ${window.materialOptionsHTML || ''}
            </select>
        </td>
        <td>
            <input type="number" class="order-qty-input form-input text-xs text-right" style="padding:7px 10px;width:90px;"
                min="1" value="1" oninput="app.updateOrderTotal()">
        </td>
        <td class="text-center">
            <button type="button" onclick="this.closest('tr').remove(); app.updateOrderTotal();"
                class="w-7 h-7 bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 rounded-lg transition-colors">
                <i class="fa-solid fa-times text-xs"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
    tr.querySelector('.order-mat-select').focus();
};

app.updateOrderTotal = function() {
    const count = document.querySelectorAll('#order-items-body tr').length;
    const c = document.getElementById('order-items-count');
    if (c) c.innerText = count + ' ລາຍການ';
};

app.saveMaterialOrder = async function(e) {
    e.preventDefault();
    const rows = document.querySelectorAll('#order-items-body tr');
    if (rows.length === 0) {
        app.showToast('ກະລຸນາເພີ່ມລາຍການວັດຖຸດິບຢ່າງໜ້ອຍ 1 ລາຍການ', 'error'); return;
    }

    const items = [];
    let valid = true;
    const usedMats = new Set();
    rows.forEach(tr => {
        const matId = tr.querySelector('.order-mat-select')?.value;
        const qty   = parseFloat(tr.querySelector('.order-qty-input')?.value);
        if (!matId || !qty || qty <= 0) { valid = false; return; }
        if (usedMats.has(matId)) {
            app.showToast('ວັດຖຸດິບຊ້ຳກັນ — ກະລຸນາລວບລາຍການ', 'error'); valid = false; return;
        }
        usedMats.add(matId);
        items.push({ mat_id: matId, qty });
    });
    if (!valid) { app.showToast('ກະລຸນາລະບຸວັດຖຸດິບແລະຈໍານວນໃຫ້ຄົບ', 'error'); return; }

    const editId = document.getElementById('order-id').value;
    const isEdit = !!editId;
    const url    = isEdit ? 'actions/update_material_order.php' : 'actions/save_material_order.php';

    const btn = document.getElementById('order-submit-btn');
    if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i>ກໍາລັງບັນທຶກ...'; }

    try {
        const payload = {
            order_date: document.getElementById('order-date').value,
            emp_id:     document.getElementById('order-emp-id').value,
            note:       document.getElementById('order-note').value,
            items
        };
        if (isEdit) payload.order_id = parseInt(editId);

        const res    = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        const result = await res.json();
        if (result.success) {
            app.showToast((isEdit ? 'ແກ້ໄຂ ' : 'ບັນທຶກ ') + (result.order_no || '') + ' ສໍາເລັດ!');
            app.hideOrderForm();
            app.loadMaterialOrders();
        } else {
            app.showToast(result.message || 'ບໍ່ສໍາເລັດ', 'error');
        }
    } catch {
        app.showToast('ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນ', 'error');
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = isEdit ? '<i class="fa-solid fa-pen-to-square"></i> ບັນທຶກການແກ້ໄຂ' : '<i class="fa-solid fa-save"></i> ບັນທຶກ';
        }
    }
};

app.loadMaterialOrders = function() {
    const tbody = document.getElementById('order-list-table');
    if (!tbody) return;
    tbody.innerHTML = '<tr class="table-row"><td colspan="6" class="text-center text-slate-400 py-10"><i class="fa-solid fa-spinner fa-spin mr-2"></i>ກໍາລັງໂຫຼດ...</td></tr>';
    fetch('actions/get_material_orders.php')
    .then(r => r.json())
    .then(result => {
        window.orderAllData = result.success ? (result.orders || []) : [];
        window.orderSearch  = '';
        const el = document.getElementById('order-search');
        if (el) el.value = '';
        app.renderOrderList();
    })
    .catch(() => {
        tbody.innerHTML = '<tr class="table-row"><td colspan="6" class="text-center text-red-400 py-10">ໂຫຼດຂໍ້ມູນບໍ່ໄດ້</td></tr>';
    });
};

app.filterOrders = function() {
    window.orderSearch = (document.getElementById('order-search')?.value || '').toLowerCase();
    app.renderOrderList();
};

app.renderOrderList = function() {
    const tbody = document.getElementById('order-list-table');
    if (!tbody) return;

    let rows = window.orderAllData || [];
    const q  = window.orderSearch;
    if (q) rows = rows.filter(o =>
        ('PO-' + String(o.Order_id).padStart(6,'0')).toLowerCase().includes(q) ||
        (o.Emp_name   || '').toLowerCase().includes(q) ||
        (o.Order_note || '').toLowerCase().includes(q)
    );

    const countEl = document.getElementById('order-count');
    if (countEl) countEl.innerText = rows.length + ' ລາຍການ';

    if (rows.length === 0) {
        tbody.innerHTML = `<tr class="table-row"><td colspan="6" class="text-center text-slate-400 py-12 italic">
            <i class="fa-solid fa-box-open text-3xl block mb-3 text-slate-200"></i>
            ${q ? 'ບໍ່ພົບລາຍການ' : 'ຍັງບໍ່ມີປະຫວັດການສັ່ງຊື້'}
        </td></tr>`;
        return;
    }

    tbody.innerHTML = rows.map(o => {
        const poNo = 'PO-' + String(o.Order_id).padStart(6, '0');
        const dt   = new Date(o.Order_date).toLocaleDateString('lo-LA', { year:'numeric', month:'short', day:'numeric' });
        return `<tr class="table-row">
            <td class="font-mono font-bold text-slate-700 text-sm">${poNo}</td>
            <td class="text-slate-500 text-sm">${dt}</td>
            <td class="text-slate-700 font-medium text-sm">${o.Emp_name}</td>
            <td class="text-center text-slate-600 text-sm font-medium">${o.Order_sumtotal} ລາຍການ</td>
            <td class="text-slate-400 text-xs max-w-[160px] truncate" title="${o.Order_note || ''}">${o.Order_note || '-'}</td>
            <td class="text-center">
                <div class="flex justify-center gap-1">
                    <button onclick="app.showOrderDetail(${o.Order_id})"
                        class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-500 hover:text-blue-700 rounded-lg transition-colors" title="ລາຍລະອຽດ">
                        <i class="fa-solid fa-eye text-xs"></i>
                    </button>
                    <button onclick="app.editMaterialOrder(${o.Order_id})"
                        class="w-8 h-8 bg-amber-50 hover:bg-amber-100 text-amber-500 hover:text-amber-700 rounded-lg transition-colors" title="ແກ້ໄຂ">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <button onclick="window.open('print_order.php?id=${o.Order_id}','_blank','width=820,height=700')"
                        class="w-8 h-8 bg-green-50 hover:bg-green-100 text-green-600 hover:text-green-800 rounded-lg transition-colors" title="ພິມໃບສັ່ງຊື້">
                        <i class="fa-solid fa-print text-xs"></i>
                    </button>
                    <button onclick="app.deleteMaterialOrder(${o.Order_id}, '${poNo}')"
                        class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 rounded-lg transition-colors" title="ລຶບ">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
};

app.showOrderDetail = function(orderId) {
    const modal   = document.getElementById('order-detail-modal');
    const content = document.getElementById('modal-items-content');
    const totalEl = document.getElementById('modal-grand-total');
    modal.classList.remove('hidden');
    content.innerHTML = '<div class="text-center py-8 text-slate-400"><i class="fa-solid fa-spinner fa-spin mr-2"></i>ກໍາລັງໂຫຼດ...</div>';
    totalEl.innerText = '0 ລາຍການ';

    fetch(`actions/get_order_detail.php?id=${orderId}`)
    .then(r => r.json())
    .then(result => {
        if (!result.success) { content.innerHTML = '<p class="text-red-400 text-center py-6">' + result.message + '</p>'; return; }
        const h    = result.header;
        const poNo = 'PO-' + String(h.Order_id).padStart(6, '0');
        const dt   = new Date(h.Order_date).toLocaleDateString('lo-LA', { year:'numeric', month:'long', day:'numeric' });
        document.getElementById('modal-po-title').innerText = poNo;
        document.getElementById('modal-po-meta').innerText  = `${dt}  •  ຜູ້ສັ່ງ: ${h.Emp_name}${h.Order_note ? '  •  ' + h.Order_note : ''}`;

        const fmt  = n => Number(n).toLocaleString('lo-LA');
        const rows = result.items.map((item, i) =>
            `<tr class="${i % 2 === 0 ? 'bg-white' : 'bg-slate-50/60'}">
                <td class="px-4 py-3 font-medium text-slate-700 text-sm">${item.Material_name}</td>
                <td class="px-4 py-3 text-right text-slate-600 text-sm font-bold">${fmt(item.Order_total)}</td>
                <td class="px-4 py-3 text-slate-400 text-sm">${item.Material_unit}</td>
            </tr>`
        ).join('');
        content.innerHTML = `
            <table class="w-full text-left rounded-xl overflow-hidden border border-slate-100">
                <thead>
                    <tr class="bg-slate-100">
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">ວັດຖຸດິບ</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide text-right">ຈໍານວນ</th>
                        <th class="px-4 py-3 text-xs font-bold text-slate-500 uppercase tracking-wide">ຫົວໜ່ວຍ</th>
                    </tr>
                </thead>
                <tbody>${rows}</tbody>
            </table>`;
        totalEl.innerText = result.items.length + ' ລາຍການ';
    })
    .catch(() => { content.innerHTML = '<p class="text-red-400 text-center py-6">ໂຫຼດຂໍ້ມູນບໍ່ໄດ້</p>'; });
};

app.deleteMaterialOrder = async function(orderId, poNo) {
    if (!confirm(`ຢືນຢັນລຶບໃບສັ່ງຊື້ ${poNo}?\nການດໍາເນີນການນີ້ບໍ່ສາມາດຍ້ອນໄດ້`)) return;
    try {
        const res    = await fetch('actions/delete_material_order.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ order_id: orderId }) });
        const result = await res.json();
        if (result.success) {
            app.showToast('ລຶບ ' + poNo + ' ສໍາເລັດ');
            app.loadMaterialOrders();
        } else {
            app.showToast(result.message || 'ລຶບບໍ່ສໍາເລັດ', 'error');
        }
    } catch {
        app.showToast('ບໍ່ສາມາດເຊື່ອມຕໍ່ຖານຂໍ້ມູນ', 'error');
    }
};
</script>
