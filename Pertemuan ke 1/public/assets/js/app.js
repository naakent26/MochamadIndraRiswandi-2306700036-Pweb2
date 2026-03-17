/* ==============================
   Helpers
============================== */
const $ = (id) => document.getElementById(id);

function formatPrice(price){ return 'Rp ' + Number(price || 0).toLocaleString('id-ID'); }

function showToast(message) {
  const toast = document.createElement('div');
  toast.className = 'fixed bottom-4 left-1/2 transform -translate-x-1/2 px-4 py-3 rounded-lg text-sm z-50 fade-in';
  toast.style.background = 'rgba(17,17,26,.92)';
  toast.style.border = '1px solid rgba(255,255,255,.12)';
  toast.style.color = 'var(--text)';
  toast.textContent = message;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 2500);
}

async function api(path, { method='GET', body=null } = {}) {
  const opt = { method, headers: {} };
  if (body) { opt.headers['Content-Type'] = 'application/json'; opt.body = JSON.stringify(body); }
  const res = await fetch(path, opt);
  const data = await res.json().catch(()=> ({}));
  if (!res.ok) throw new Error(data?.message || 'Request gagal');
  return data;
}

/* ==============================
   Data Produk (seed di backend juga)
============================== */
const products = [
  { id: 'ip1', brand: 'iphone', name: 'iPhone 15 Pro Max', price: 24999000, stock: 8,  desc: 'Chip A17 Pro, Titanium Design, 48MP Camera, USB-C 3.0, Dynamic Island, IP68 Rating, 120Hz Display', image: 'https://images.unsplash.com/photo-1592286927505-1fed6c6d03d5?w=400&h=400&fit=crop' },
  { id: 'ip2', brand: 'iphone', name: 'iPhone 15',         price: 16999000, stock: 12, desc: 'Chip A16 Bionic, Dynamic Island, 48MP Main Camera, USB-C, Night Mode Ultra', image: 'https://images.unsplash.com/photo-1511275539165-cc3641b266b6?w=400&h=400&fit=crop' },
  { id: 'ip3', brand: 'iphone', name: 'iPhone 14 Pro',     price: 18999000, stock: 5,  desc: 'Chip A16 Bionic, Always-On Display, ProMotion 120Hz, Pro Camera System', image: 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=400&fit=crop' },
  { id: 'ip4', brand: 'iphone', name: 'iPhone 14',         price: 14999000, stock: 15, desc: 'Chip A15 Bionic, Crash Detection, Action Mode, Ceramic Shield', image: 'https://images.unsplash.com/photo-1559056199-641a0ac8b3f4?w=400&h=400&fit=crop' },
  { id: 'ip5', brand: 'iphone', name: 'iPhone SE 2022',    price: 8999000,  stock: 20, desc: 'Chip A15 Bionic, Touch ID, 4.7" Retina Display, Wireless Charging', image: 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=400&fit=crop' },

  { id: 'ss1', brand: 'samsung', name: 'Samsung S24 Ultra', price: 23999000, stock: 6,  desc: 'Snapdragon 8 Gen 3, S Pen Integrated, 200MP Camera, AI Zoom 100x, IP68', image: 'https://images.unsplash.com/photo-1512941691920-25bda36fb6fd?w=400&h=400&fit=crop' },
  { id: 'ss2', brand: 'samsung', name: 'Samsung S24+',      price: 17999000, stock: 10, desc: 'Snapdragon 8 Gen 3, 50MP Camera, 120Hz AMOLED, AI Features', image: 'https://images.unsplash.com/photo-1511275539165-cc3641b266b6?w=400&h=400&fit=crop' },
  { id: 'ss3', brand: 'samsung', name: 'Samsung S24',       price: 14999000, stock: 14, desc: 'Snapdragon 8 Gen 3, Compact Design, Galaxy AI, Night Photography', image: 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=400&fit=crop' },
  { id: 'ss4', brand: 'samsung', name: 'Samsung Z Fold 5',  price: 28999000, stock: 3,  desc: 'Foldable 7.6" AMOLED, Flex Mode, Lightweight Titanium, IPX8', image: 'https://images.unsplash.com/photo-1559056199-641a0ac8b3f4?w=400&h=400&fit=crop' },
  { id: 'ss5', brand: 'samsung', name: 'Samsung A54 5G',    price: 6499000,  stock: 25, desc: 'Exynos 1380, 50MP OIS Camera, Super AMOLED 120Hz, IP67', image: 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=400&fit=crop' },

  { id: 'xm1', brand: 'xiaomi', name: 'Xiaomi 14 Ultra',    price: 18999000, stock: 7,  desc: 'Snapdragon 8 Gen 3, Leica Camera System, 90W HyperCharge, IP68', image: 'https://images.unsplash.com/photo-1592286927505-1fed6c6d03d5?w=400&h=400&fit=crop' },
  { id: 'xm2', brand: 'xiaomi', name: 'Xiaomi 14',          price: 12999000, stock: 11, desc: 'Snapdragon 8 Gen 3, Leica Lens, 120W Charging, Camera Master Mode', image: 'https://images.unsplash.com/photo-1511275539165-cc3641b266b6?w=400&h=400&fit=crop' },
  { id: 'xm3', brand: 'xiaomi', name: 'Poco F6 Pro',        price: 7999000,  stock: 18, desc: 'Snapdragon 8 Gen 2, 120W Charging, LiquidCool 4.0, Gorilla Glass', image: 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=400&h=400&fit=crop' },
  { id: 'xm4', brand: 'xiaomi', name: 'Redmi Note 13 Pro+', price: 5499000,  stock: 30, desc: '200MP Camera with OIS, 120W Charging, AMOLED 120Hz, IP54', image: 'https://images.unsplash.com/photo-1559056199-641a0ac8b3f4?w=400&h=400&fit=crop' },
  { id: 'xm5', brand: 'xiaomi', name: 'Redmi Note 13',      price: 3299000,  stock: 35, desc: 'Snapdragon 685, 108MP Camera, 33W Fast Charging, Budget Friendly', image: 'https://images.unsplash.com/photo-1556656793-08538906a9f8?w=400&h=400&fit=crop' }
];


/* ==============================
   State
============================== */
let currentUser = null;
let currentBrand = 'all';
let currentProductModal = null;
let currentQty = 1;

let shippingData = {};
let buyNowData = null;

let buyerOrderFilter = 'ALL';
let adminOrderFilter = 'ALL';

const shippingCosts = { reguler: 25000, express: 50000, overnight: 75000 };

/* ==============================
   Cart Selection State
============================== */
let selectedCartIds = new Set();
let lastCartSnapshotIds = '';

function snapshotIds(items){
  return (items || []).map(it => it.id).sort().join('|');
}
function ensureCartSelection(items){
  if (!items || items.length === 0){
    selectedCartIds.clear();
    lastCartSnapshotIds = '';
    return;
  }

  const snap = snapshotIds(items);
  // kalau cart berubah total (id berubah), minimal bersihin id yang invalid
  const valid = new Set(items.map(it => it.id));
  selectedCartIds = new Set([...selectedCartIds].filter(id => valid.has(id)));

  // kalau pertama kali buka cart atau selection kosong -> pilih semua
  if (selectedCartIds.size === 0) {
    items.forEach(it => selectedCartIds.add(it.id));
  }

  lastCartSnapshotIds = snap;
}
function toggleCartSelect(id, checked){
  if (checked) selectedCartIds.add(id);
  else selectedCartIds.delete(id);
  renderCartItems().catch(()=>{});
}
function toggleSelectAll(checked, items){
  selectedCartIds.clear();
  if (checked) (items||[]).forEach(it => selectedCartIds.add(it.id));
  renderCartItems().catch(()=>{});
}
function getSelectedCartItems(items){
  return (items||[]).filter(it => selectedCartIds.has(it.id));
}

/* ==============================
   Auth UI
============================== */
function showAuthTab(tab){
  const isLogin = tab === 'login';
  $('login-form').classList.toggle('hidden', !isLogin);
  $('register-form').classList.toggle('hidden', isLogin);

  $('login-tab').className = isLogin
    ? 'flex-1 py-3 rounded-lg font-semibold transition-all bg-white/10 border border-white/10 text-purple-200'
    : 'flex-1 py-3 rounded-lg font-semibold transition-all text-white/60';

  $('register-tab').className = !isLogin
    ? 'flex-1 py-3 rounded-lg font-semibold transition-all bg-white/10 border border-white/10 text-purple-200'
    : 'flex-1 py-3 rounded-lg font-semibold transition-all text-white/60';

  $('login-error').classList.add('hidden');
  $('register-error').classList.add('hidden');
  $('register-success').classList.add('hidden');
}

async function handleRegister(e){
  e.preventDefault();
  $('register-error').classList.add('hidden');
  $('register-success').classList.add('hidden');

  const name = $('register-name').value.trim();
  const email = $('register-email').value.trim().toLowerCase();
  const password = $('register-password').value;

  try{
    await api('/api/auth.php?action=register', { method:'POST', body:{ name, email, password }});
    $('register-success').textContent = '✅ Berhasil daftar. Silakan login.';
    $('register-success').classList.remove('hidden');
    showAuthTab('login');
  }catch(err){
    $('register-error').textContent = err.message;
    $('register-error').classList.remove('hidden');
  }
}

async function handleLogin(e){
  e.preventDefault();
  $('login-error').classList.add('hidden');

  const email = $('login-email').value.trim().toLowerCase();
  const password = $('login-password').value;

  try{
    const data = await api('/api/auth.php?action=login', { method:'POST', body:{ email, password }});
    currentUser = data.user;
    afterLogin();
  }catch(err){
    $('login-error').textContent = err.message;
    $('login-error').classList.remove('hidden');
  }
}

function handleLogout(){
  currentUser = null;
  selectedCartIds.clear();
  api('/api/auth.php?action=logout').catch(()=>{});
  $('main-page').classList.add('hidden');
  $('auth-page').classList.remove('hidden');
  $('admin-btn').classList.add('hidden');
  showToast('Logout');
}

/* ==============================
   Products UI
============================== */
function getBrandName(b){ return b==='iphone'?'iPhone': b==='samsung'?'Samsung':'Xiaomi'; }
function getBrandBadge(b){
  if (b==='iphone') return 'bg-white/10 border border-white/10 text-white/80';
  if (b==='samsung') return 'bg-blue-500/10 border border-blue-400/20 text-blue-200';
  return 'bg-orange-500/10 border border-orange-400/20 text-orange-200';
}

function renderProducts(){
  const grid = $('products-grid');
  const filtered = currentBrand === 'all' ? products : products.filter(p => p.brand === currentBrand);

  grid.innerHTML = filtered.map((p,i)=>`
    <div onclick="openProductModal('${p.id}')" class="product-card rounded-2xl shadow-md overflow-hidden fade-in" style="animation-delay:${i*0.05}s">
      <div class="h-40 flex items-center justify-center overflow-hidden" style="background: rgba(255,255,255,.03); border-bottom: 1px solid rgba(255,255,255,.08);">
        <img src="${p.image}" alt="${p.name}" class="w-full h-full object-cover" loading="lazy">
      </div>
      <div class="p-4">
        <span class="inline-block px-2 py-1 text-xs font-medium rounded-full ${getBrandBadge(p.brand)} mb-2">${getBrandName(p.brand)}</span>
        <h3 class="font-bold text-white mb-1 line-clamp-2">${p.name}</h3>
        <p class="text-sm text-white/60 mb-3 line-clamp-2">${p.desc.substring(0,60)}.</p>
        <div class="flex items-center justify-between">
          <span class="text-lg font-bold text-purple-200">${formatPrice(p.price)}</span>
          <span class="text-xs ${p.stock < 10 ? 'text-red-300 font-bold' : 'text-green-300'}">Stok: ${p.stock}</span>
        </div>
      </div>
    </div>
  `).join('');
}

function filterBrand(brand){
  currentBrand = brand;
  document.querySelectorAll('.brand-tab').forEach(tab=>{
    if (tab.dataset.brand === brand){
      tab.classList.add('active');
      tab.classList.remove('bg-white/5','text-white/70');
    } else {
      tab.classList.remove('active');
      tab.classList.add('bg-white/5','text-white/70');
    }
  });
  renderProducts();
}

/* ==============================
   Product Modal
============================== */
function openProductModal(productId){
  const product = products.find(p=>p.id===productId);
  if(!product) return;

  currentProductModal = product;
  currentQty = 1;

  $('modal-title').textContent = product.name;
  $('modal-image').src = product.image;
  $('modal-brand').textContent = getBrandName(product.brand);
  $('modal-name').textContent = product.name;
  $('modal-desc').textContent = product.desc;
  $('modal-price').textContent = formatPrice(product.price);
  $('modal-stock').textContent = product.stock < 10 ? `⚠️ Sisa ${product.stock} unit` : `✅ Stok: ${product.stock} unit`;
  $('qty-input').textContent = '1';

  $('product-modal').classList.remove('hidden');
}
function closeProductModal(){ $('product-modal').classList.add('hidden'); }

function increaseQty(){
  if(!currentProductModal) return;
  if(currentQty + 1 > currentProductModal.stock){ showToast('Stok tidak mencukupi!'); return; }
  currentQty++;
  $('qty-input').textContent = String(currentQty);
}
function decreaseQty(){
  if(!currentProductModal) return;
  currentQty = Math.max(1, currentQty - 1);
  $('qty-input').textContent = String(currentQty);
}

async function addToCartFromModal(){
  if(!currentUser){ showToast('Silakan login dulu'); return; }
  const p = currentProductModal;
  if(!p) return;

  try{
    await api('/api/cart.php?action=add', { method:'POST', body:{ product_id:p.id, qty: currentQty }});
    showToast('✅ Masuk keranjang');
    await updateCartCount();
    closeProductModal();
  }catch(err){ showToast(err.message); }
}

function buyNowFromModal(){
  if(!currentUser){ showToast('Silakan login dulu'); return; }
  buyNowData = { product_id: currentProductModal.id, quantity: currentQty };
  closeProductModal();
  $('shipping-modal').classList.remove('hidden');
}

/* ==============================
   Cart
============================== */
async function showCart(){
  if(!currentUser){ showToast('Silakan login dulu'); return; }
  await renderCartItems();
  $('cart-modal').classList.remove('hidden');
}
function closeCart(){ $('cart-modal').classList.add('hidden'); }

async function renderCartItems(){
  const wrap = $('cart-items');
  const data = await api('/api/cart.php?action=list');
  const items = data.items || [];

  ensureCartSelection(items);

  if(items.length===0){
    wrap.innerHTML = `<div class="text-white/60 text-center py-10">Keranjang kosong.</div>`;
    $('cart-total').textContent = 'Rp 0';
    $('checkout-btn').disabled = true;
    return;
  }

  // header pilih semua
  const allChecked = items.length > 0 && items.every(it => selectedCartIds.has(it.id));
  const header = `
    <label class="flex items-center gap-2 mb-3 text-sm text-white/70 select-none">
      <input type="checkbox" class="w-4 h-4 accent-purple-500"
        ${allChecked ? 'checked' : ''}
        onchange="toggleSelectAll(this.checked, window.__lastCartItems)">
      Pilih semua
    </label>
  `;

  // simpan items terakhir supaya toggleSelectAll bisa akses
  window.__lastCartItems = items;

  let total = 0;

  wrap.innerHTML = header + items.map(it=>{
    const p = products.find(x=>x.id===it.product_id);
    const price = p ? p.price : it.price;
    const name = p ? p.name : it.name;
    const line = price * it.qty;

    const checked = selectedCartIds.has(it.id);
    if (checked) total += line;

    return `
      <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
        <div class="flex items-start justify-between gap-3">
          <div class="flex items-start gap-3">
            <input type="checkbox" class="mt-1 w-4 h-4 accent-purple-500"
              ${checked ? 'checked' : ''}
              onchange="toggleCartSelect('${it.id}', this.checked)">

            <div>
              <div class="font-semibold text-white">${name}</div>
              <div class="text-sm text-purple-200 font-bold mt-1">${formatPrice(price)}</div>
              <div class="text-xs text-white/60 mt-1">${checked ? '✅ Dipilih' : '⛔ Tidak dipilih'}</div>
            </div>
          </div>

          <button onclick="removeCartItem('${it.id}')" class="text-white/60 hover:text-white">✕</button>
        </div>

        <div class="mt-3 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <button onclick="updateQuantity('${it.id}',-1)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center">−</button>
            <span class="w-10 text-center font-semibold text-white">${it.qty}</span>
            <button onclick="updateQuantity('${it.id}',1)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center">+</button>
          </div>
          <div class="text-white/80 text-sm">Total: <span class="font-bold text-white">${formatPrice(line)}</span></div>
        </div>
      </div>
    `;
  }).join('');

  $('cart-total').textContent = formatPrice(total);
  $('checkout-btn').disabled = (getSelectedCartItems(items).length === 0);
}

async function updateQuantity(itemId, delta){
  try{
    await api('/api/cart.php?action=update', { method:'POST', body:{ id:itemId, delta }});
    await updateCartCount();
    await renderCartItems();
  }catch(err){ showToast(err.message); }
}

async function removeCartItem(itemId){
  try{
    await api('/api/cart.php?action=remove', { method:'POST', body:{ id:itemId }});
    // hapus dari selection juga
    selectedCartIds.delete(itemId);

    await updateCartCount();
    await renderCartItems();
  }catch(err){ showToast(err.message); }
}

function checkout(){
  // wajib pilih minimal 1 item jika bukan buyNow
  if (!buyNowData){
    const items = window.__lastCartItems || [];
    const selected = getSelectedCartItems(items);
    if (selected.length === 0){
      showToast('Pilih minimal 1 item dulu!');
      return;
    }
  }

  closeCart();
  $('shipping-modal').classList.remove('hidden');
}

/* ==============================
   Shipping & Payment
============================== */
function closeShippingModal(){ $('shipping-modal').classList.add('hidden'); }

function submitShipping(){
  const form = $('shipping-form');
  if(!form.checkValidity()){ showToast('Mohon isi semua data pengiriman!'); return; }

  shippingData = {
    name: $('recipient-name').value,
    phone: $('recipient-phone').value,
    address: $('recipient-address').value,
    city: $('recipient-city').value,
    zip: $('recipient-zip').value,
    method: document.querySelector('input[name="shipping-method"]:checked').value,
    isBuyNow: !!buyNowData
  };

  closeShippingModal();
  openPaymentModal();
}
function handleShippingSubmit(e){ e.preventDefault(); submitShipping(); }

async function openPaymentModal(){
  let subtotal = 0;
  let itemsHtml = '';

  if (shippingData.isBuyNow && buyNowData){
    const p = products.find(x=>x.id===buyNowData.product_id);
    const itemTotal = p.price * buyNowData.quantity;
    subtotal = itemTotal;
    itemsHtml = `<div class="flex justify-between text-xs text-white/80"><span>${p.name} x${buyNowData.quantity}</span><span>${formatPrice(itemTotal)}</span></div>`;
  } else {
    const cart = await api('/api/cart.php?action=list');
    const items = cart.items || [];
    ensureCartSelection(items);

    const selected = getSelectedCartItems(items);
    if (selected.length === 0){
      showToast('Pilih minimal 1 item dulu!');
      $('payment-modal').classList.add('hidden');
      return;
    }

    itemsHtml = selected.map(it=>{
      const p = products.find(x=>x.id===it.product_id);
      const itemTotal = (p.price) * it.qty;
      subtotal += itemTotal;
      return `<div class="flex justify-between text-xs text-white/80"><span>${p.name} x${it.qty}</span><span>${formatPrice(itemTotal)}</span></div>`;
    }).join('');
  }

  $('payment-items').innerHTML = itemsHtml;

  const shippingCost = shippingCosts[shippingData.method] || 0;
  const total = subtotal + shippingCost;

  $('summary-subtotal').textContent = formatPrice(subtotal);
  $('summary-shipping-cost').textContent = formatPrice(shippingCost);
  $('payment-total').textContent = formatPrice(total);

  $('summary-name').textContent = shippingData.name;
  $('summary-phone').textContent = shippingData.phone;
  $('summary-address').textContent = `${shippingData.address}, ${shippingData.city} ${shippingData.zip}`;

  const shippingLabels = { reguler:'🚚 Reguler (3-5 hari)', express:'⚡ Express (1-2 hari)', overnight:'🌙 Overnight (Besok pagi)' };
  $('summary-shipping').textContent = shippingLabels[shippingData.method] || shippingData.method;

  $('payment-modal').classList.remove('hidden');
}
function closePaymentModal(){ $('payment-modal').classList.add('hidden'); }

function confirmPayment(){
  const method = document.querySelector('input[name="payment-method"]:checked')?.value || 'transfer';
  showPaymentInstructions(method);
}

function showPaymentInstructions(method){
  const totalText = $('payment-total').textContent;
  const map = {
    transfer: { title:'🏦 Transfer Bank', html:`<div class="p-4 rounded-xl bg-white/5 border border-white/10">
      <p class="font-bold text-white mb-2">Total: <span class="text-purple-200">${totalText}</span></p>
      <p class="text-white/70 text-sm mb-3">Transfer ke salah satu rekening:</p>
      <div class="space-y-2 text-sm">
        <div class="p-3 rounded border border-white/10 bg-white/5">🏦 BCA • <span class="font-mono text-white">1234567890</span> a/n PT NaaKent Store</div>
        <div class="p-3 rounded border border-white/10 bg-white/5">🏦 Mandiri • <span class="font-mono text-white">9876543210</span> a/n PT NaaKent Store</div>
        <div class="p-3 rounded border border-white/10 bg-white/5">🏦 BNI • <span class="font-mono text-white">1122334455</span> a/n PT NaaKent Store</div>
      </div></div>` },
    ewallet: { title:'📱 E-Wallet', html:`<div class="p-4 rounded-xl bg-white/5 border border-white/10">
      <p class="font-bold text-white mb-2">Total: <span class="text-purple-200">${totalText}</span></p>
      <p class="text-white/70 text-sm">Bayar via GoPay/OVO/Dana ke nomor: <span class="font-mono text-white">0812-3456-7890</span></p></div>` },
    qris: { title:'🔳 QRIS', html:`<div class="p-4 rounded-xl bg-white/5 border border-white/10">
      <p class="font-bold text-white mb-2">Total: <span class="text-purple-200">${totalText}</span></p>
      <p class="text-white/70 text-sm">Scan QRIS di kasir (simulasi). Klik “Saya Sudah Bayar” untuk lanjut.</p></div>` },
    cod: { title:'🚚 COD', html:`<div class="p-4 rounded-xl bg-white/5 border border-white/10">
      <p class="font-bold text-white mb-2">Total: <span class="text-purple-200">${totalText}</span></p>
      <p class="text-white/70 text-sm">Pembayaran dilakukan saat barang sampai.</p></div>` }
  };

  const cfg = map[method] || map.transfer;
  $('payment-instructions-title').textContent = cfg.title;
  $('payment-instructions-content').innerHTML = cfg.html;

  closePaymentModal();
  $('payment-instructions-modal').classList.remove('hidden');
}

function closePaymentInstructionsModal(){ $('payment-instructions-modal').classList.add('hidden'); }

/* ==============================
   Create Order
============================== */
async function finishOrder(){
  try{
    const method = document.querySelector('input[name="payment-method"]:checked')?.value || 'transfer';
    const shipCost = shippingCosts[shippingData.method] || 0;

    // kalau cart mode: kirim selected_cart_ids ke backend
    let selected_cart_ids = [];
    if (!shippingData.isBuyNow){
      const cart = await api('/api/cart.php?action=list');
      const items = cart.items || [];
      ensureCartSelection(items);
      selected_cart_ids = getSelectedCartItems(items).map(it => it.id);
      if (selected_cart_ids.length === 0){
        showToast('Pilih minimal 1 item dulu!');
        return;
      }
    }

    const payload = {
      payment_method: method,
      shipping: { ...shippingData, cost: shipCost },
      buy_now: buyNowData,
      selected_cart_ids
    };

    await api('/api/orders.php?action=create', { method:'POST', body: payload });

    // reset
    buyNowData = null;
    shippingData = {};
    selectedCartIds.clear();

    closePaymentInstructionsModal();
    $('success-modal').classList.remove('hidden');
    await updateCartCount();
  }catch(err){
    showToast(err.message);
  }
}

function closeSuccessModal(){
  $('success-modal').classList.add('hidden');
}

/* ==============================
   Orders (Buyer)
============================== */
function statusLabel(status){
  const st = normalizeOrderStatus(status);
  if (st==='PROCESSING') return { text:'Diproses', cls:'border-yellow-400/30 text-yellow-200 bg-yellow-500/10' };
  if (st==='SHIPPED') return { text:'Dikirim', cls:'border-blue-400/30 text-blue-200 bg-blue-500/10' };
  if (st==='DONE') return { text:'Selesai', cls:'border-green-400/30 text-green-200 bg-green-500/10' };
  return { text:String(status||'Unknown'), cls:'border-white/10 text-white/70 bg-white/5' };
}
function normalizeOrderStatus(s){
  const up = String(s||'').toUpperCase();
  if (up.includes('SHIP')) return 'SHIPPED';
  if (up.includes('DONE') || up.includes('FIN')) return 'DONE';
  return 'PROCESSING';
}

function setBuyerOrderFilter(filter){
  buyerOrderFilter = filter;
  document.querySelectorAll('[data-filter]').forEach(btn=>{
    btn.classList.toggle('active', btn.dataset.filter === filter);
  });
  renderOrdersForUser();
}

async function openOrdersModal(){
  if(!currentUser) return;
  await renderOrdersForUser();
  $('orders-modal').classList.remove('hidden');
}
function closeOrdersModal(){ $('orders-modal').classList.add('hidden'); }

async function renderOrdersForUser(){
  const list = $('orders-list');
  const data = await api('/api/orders.php?action=list_mine');
  let orders = data.orders || [];

  orders.sort((a,b)=> new Date(b.created_at) - new Date(a.created_at));
  if (buyerOrderFilter !== 'ALL') orders = orders.filter(o => normalizeOrderStatus(o.status) === buyerOrderFilter);

  if(!orders.length){
    list.innerHTML = `<div class="text-white/60 text-center py-10">Belum ada pesanan di kategori ini.</div>`;
    return;
  }

  list.innerHTML = orders.map(o=>{
    const st = statusLabel(o.status);
    return `
      <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
        <div class="flex items-start justify-between gap-3 flex-wrap">
          <div>
            <div class="font-semibold text-white">${o.order_no}</div>
            <div class="text-xs text-white/60 mt-1">${new Date(o.created_at).toLocaleString('id-ID')}</div>
          </div>
          <div class="text-xs px-2 py-1 rounded-full border ${st.cls}">${st.text}</div>
        </div>

        <div class="mt-3 space-y-1 text-sm">
          ${(o.items||[]).map(it=>`
            <div class="flex justify-between text-white/80"><span>${it.name} x${it.qty}</span><span>${formatPrice(it.line_total)}</span></div>
          `).join('')}
        </div>

        <div class="mt-3 pt-3 border-t border-white/10 flex justify-between text-white">
          <span>Total</span><span class="font-bold text-purple-200">${formatPrice(o.total)}</span>
        </div>

        <div class="text-xs text-white/60 mt-2">
          Kurir: ${o.shipping?.method || '-'} • Pembayaran: ${o.payment_method || '-'}
        </div>
      </div>
    `;
  }).join('');
}

/* ==============================
   Admin
============================== */
function setAdminOrderFilter(filter){
  adminOrderFilter = filter;
  document.querySelectorAll('[data-admin-filter]').forEach(btn=>{
    btn.classList.toggle('active', btn.dataset.adminFilter === filter);
    btn.classList.toggle('active', btn.dataset.adminFilter === filter);
  });
  renderOrdersForAdmin();
}

async function openAdminModal(){
  if(!currentUser || !currentUser.is_admin) return;
  await renderOrdersForAdmin();
  $('admin-modal').classList.remove('hidden');
}
function closeAdminModal(){ $('admin-modal').classList.add('hidden'); }

async function renderOrdersForAdmin(){
  const wrap = $('admin-orders-list');
  const data = await api('/api/orders.php?action=list_all');
  let orders = data.orders || [];
  orders.sort((a,b)=> new Date(b.created_at) - new Date(a.created_at));

  if (adminOrderFilter !== 'ALL') orders = orders.filter(o => normalizeOrderStatus(o.status) === adminOrderFilter);

  if(!orders.length){
    wrap.innerHTML = `<div class="text-white/60 text-center py-10">Tidak ada pesanan.</div>`;
    return;
  }

  wrap.innerHTML = orders.map(o=>{
    const st = statusLabel(o.status);
    return `
      <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
        <div class="flex items-start justify-between gap-3 flex-wrap">
          <div>
            <div class="font-semibold text-white">${o.order_no}</div>
            <div class="text-xs text-white/60 mt-1">${new Date(o.created_at).toLocaleString('id-ID')}</div>
            <div class="text-xs text-white/60 mt-1">Pembeli: <span class="text-white/80">${o.buyer_name} (${o.buyer_email})</span></div>
          </div>
          <div class="text-xs px-2 py-1 rounded-full border ${st.cls}">${st.text}</div>
        </div>

        <div class="mt-3 grid md:grid-cols-2 gap-4">
          <div class="text-xs text-white/60">
            <div>Telp: <span class="text-white/80">${o.shipping?.phone || '-'}</span></div>
            <div class="mt-2">Alamat:</div>
            <div class="text-white/80 text-xs">${o.shipping?.address || '-'}, ${o.shipping?.city || '-'} ${o.shipping?.zip || ''}</div>
            <div class="text-[11px] text-white/60 mt-2">
              Kurir: ${o.shipping?.method || '-'} • Ongkir: ${formatPrice(o.shipping?.cost || 0)} • Bayar: ${o.payment_method || '-'}
            </div>
          </div>

          <div>
            <div class="text-sm font-semibold text-white mb-2">Barang yang dibeli</div>
            <div class="space-y-1 text-sm">
              ${(o.items||[]).map(it=>`
                <div class="flex justify-between text-white/85"><span>${it.name} x${it.qty}</span><span>${formatPrice(it.line_total)}</span></div>
              `).join('')}
            </div>

            <div class="mt-3 pt-3 border-t border-white/10 flex justify-between text-white">
              <span>Total</span><span class="font-bold text-purple-200">${formatPrice(o.total)}</span>
            </div>

            <div class="mt-3">
              <label class="text-xs text-white/60 block mb-2">Ubah status pesanan</label>
              <select class="w-full px-3 py-3 rounded-xl input-dark" onchange="adminUpdateOrderStatus('${o.id}', this.value)">
                <option value="PROCESSING" ${normalizeOrderStatus(o.status)==='PROCESSING'?'selected':''}>Diproses</option>
                <option value="SHIPPED" ${normalizeOrderStatus(o.status)==='SHIPPED'?'selected':''}>Dikirim</option>
                <option value="DONE" ${normalizeOrderStatus(o.status)==='DONE'?'selected':''}>Selesai</option>
              </select>
              <p class="text-[11px] text-white/50 mt-2">Status ini otomatis tampil di “Pesanan Saya” milik pembeli.</p>
            </div>
          </div>
        </div>
      </div>
    `;
  }).join('');
}

async function adminUpdateOrderStatus(orderId, status){
  try{
    await api('/api/orders.php?action=admin_update_status', { method:'POST', body:{ id: orderId, status }});
    showToast('✅ Status diupdate');
    await renderOrdersForAdmin();
  }catch(err){ showToast(err.message); }
}

/* ==============================
   Cart badge
============================== */
async function updateCartCount(){
  if(!currentUser){ $('cart-count').classList.add('hidden'); return; }
  const data = await api('/api/cart.php?action=count');
  const n = data.count || 0;
  $('cart-count').textContent = String(n);
  $('cart-count').classList.toggle('hidden', n===0);
}

/* ==============================
   After login init
============================== */
async function afterLogin(){
  $('auth-page').classList.add('hidden');
  $('main-page').classList.remove('hidden');
  $('user-name').textContent = currentUser.name || currentUser.email;
  $('admin-btn').classList.toggle('hidden', !currentUser.is_admin);

  renderProducts();
  await updateCartCount();
  showToast('✅ Login berhasil');
}

/* ==============================
   Backdrop click close
============================== */
['product-modal','cart-modal','success-modal','orders-modal','admin-modal','payment-modal','payment-instructions-modal','shipping-modal']
  .forEach(id=>{
    const el = $(id);
    if(!el) return;
    el.addEventListener('click', (e)=>{ if(e.target.id === id) el.classList.add('hidden'); });
  });

/* ==============================
   Boot
============================== */
(async function init(){
  // try session
  try{
    const me = await api('/api/auth.php?action=me');
    if(me.user){
      currentUser = me.user;
      afterLogin();
      return;
    }
  }catch{}
  // default view
  showAuthTab('login');
})();

/* expose globals for inline onclick */
Object.assign(window, {
  showAuthTab, handleRegister, handleLogin, handleLogout,
  renderProducts, filterBrand,
  openProductModal, closeProductModal, increaseQty, decreaseQty,
  addToCartFromModal, buyNowFromModal,
  showCart, closeCart, updateQuantity, removeCartItem, checkout,
  // selection handlers
  toggleCartSelect, toggleSelectAll,
  closeShippingModal, submitShipping, handleShippingSubmit,
  openPaymentModal, closePaymentModal, confirmPayment,
  closePaymentInstructionsModal, finishOrder,
  openOrdersModal, closeOrdersModal, setBuyerOrderFilter,
  openAdminModal, closeAdminModal, setAdminOrderFilter, adminUpdateOrderStatus,
});
