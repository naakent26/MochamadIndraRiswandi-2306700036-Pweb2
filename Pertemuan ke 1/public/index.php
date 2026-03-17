<!doctype html>
<html lang="id" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NaaKent Store</title>

  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Canva SDK (optional) -->
  <script src="/_sdk/element_sdk.js"></script>
  <script src="/_sdk/data_sdk.js"></script>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/app.css">

</head>

<body class="h-full">

<div id="app" class="h-full overflow-auto">

  <!-- ================= AUTH PAGE ================= -->
  <div id="auth-page" class="min-h-full flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <div class="text-center mb-8 fade-in">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl mb-4 shadow-lg app-logo">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
        </div>
        <h1 id="store-name-display" class="text-3xl font-bold text-white">NaaKent Store</h1>
        <p id="welcome-text-display" class="text-white/70 mt-2">Smartphone Terbaik dengan Harga Terjangkau</p>
      </div>

      <div class="modal-surface rounded-2xl shadow-xl p-6 fade-in" style="animation-delay:.1s">
        <div class="flex mb-6 rounded-xl p-1 auth-tabs">
          <button id="login-tab" onclick="showAuthTab('login')"
                  class="flex-1 py-3 rounded-lg font-semibold transition-all bg-white/10 border border-white/10 text-purple-200">
            Masuk
          </button>
          <button id="register-tab" onclick="showAuthTab('register')"
                  class="flex-1 py-3 rounded-lg font-semibold transition-all text-white/60">
            Daftar
          </button>
        </div>

        <!-- Login -->
        <form id="login-form" onsubmit="handleLogin(event)" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Email</label>
            <input type="email" id="login-email" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="email@contoh.com">
          </div>
          <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Kata Sandi</label>
            <input type="password" id="login-password" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="••••••••">
          </div>
          <button type="submit" class="w-full btn-primary text-white py-3 rounded-xl font-semibold">Masuk</button>
          <p id="login-error" class="text-red-400 text-sm text-center hidden"></p>

          <div class="text-xs text-white/50 pt-2 border-t border-white/10">
            <div class="flex items-center justify-between">
              <span>Admin login:</span><span class="font-mono text-white">admin@naakent.com</span>
            </div>
            <div class="flex items-center justify-between mt-1">
              <span>Password:</span><span class="font-mono text-white">admin123</span>
            </div>
          </div>
        </form>

        <!-- Register -->
        <form id="register-form" onsubmit="handleRegister(event)" class="space-y-4 hidden">
          <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Nama Lengkap</label>
            <input type="text" id="register-name" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="John Doe">
          </div>
          <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Email</label>
            <input type="email" id="register-email" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="email@contoh.com">
          </div>
          <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Kata Sandi</label>
            <input type="password" id="register-password" required minlength="6" class="w-full px-4 py-3 rounded-xl input-dark" placeholder="Minimal 6 karakter">
          </div>
          <button type="submit" class="w-full btn-primary text-white py-3 rounded-xl font-semibold">Daftar Sekarang</button>
          <p id="register-error" class="text-red-400 text-sm text-center hidden"></p>
          <p id="register-success" class="text-green-400 text-sm text-center hidden"></p>
        </form>
      </div>
    </div>
  </div>

  <!-- ================= MAIN PAGE ================= -->
  <div id="main-page" class="hidden min-h-full flex flex-col">

    <!-- Header -->
    <header class="sticky top-0 z-40 border-b border-white/10 backdrop-blur app-header">
      <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl flex items-center justify-center app-logo-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
          <span id="header-store-name" class="font-bold text-xl text-white">NaaKent Store</span>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
          <button id="orders-btn" onclick="openOrdersModal()" class="px-3 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-sm">
                <span class="sm:hidden">🧾</span>
                <span class="hidden sm:inline">🧾 Pesanan Saya</span>
          </button>


          <button id="admin-btn" onclick="openAdminModal()" class="px-3 py-2 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-sm hidden">
            🧑‍💼 Dashboard Penjual
          </button>

          <button onclick="showCart()" class="relative p-2 hover:bg-white/10 rounded-full transition border border-transparent hover:border-white/10">
            <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span id="cart-count" class="absolute -top-1 -right-1 w-5 h-5 bg-purple-600 text-white text-xs rounded-full flex items-center justify-center cart-badge hidden">0</span>
          </button>

          <div class="flex items-center gap-2">
            <span id="user-name" class="text-sm font-medium text-white/80 hidden sm:block"></span>
            <button onclick="handleLogout()" class="text-sm text-purple-200 hover:text-purple-100 font-medium">Keluar</button>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero -->
    <div class="py-8 md:py-12 border-b border-white/10 app-hero">
      <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-2 gap-6 items-center">
          <div class="fade-in">
            <h2 class="text-3xl md:text-4xl font-bold mb-3 text-white">Promo Spesial Hari Ini! 🎉</h2>
            <p class="text-white/70 mb-4">Diskon hingga 30% untuk semua kategori HP pilihan. Gratis ongkir untuk pembelian di atas Rp 10 juta!</p>
            <div class="flex gap-3 flex-wrap">
              <span class="bg-white/5 border border-white/10 px-4 py-2 rounded-full text-sm font-semibold text-white/80">⚡ Cicilan 0%</span>
              <span class="bg-white/5 border border-white/10 px-4 py-2 rounded-full text-sm font-semibold text-white/80">🚚 Gratis Ongkir</span>
              <span class="bg-white/5 border border-white/10 px-4 py-2 rounded-full text-sm font-semibold text-white/80">💯 Original 100%</span>
            </div>
          </div>
          <div class="text-center text-6xl hidden md:block opacity-90">📱✨🎁</div>
        </div>
      </div>
    </div>

    <!-- Content -->
    <main class="flex-1 max-w-6xl mx-auto px-4 py-6 w-full">
      <div class="flex flex-wrap gap-2 mb-6">
        <button onclick="filterBrand('all')" class="brand-tab active px-6 py-2 rounded-full font-medium" data-brand="all">Semua</button>
        <button onclick="filterBrand('iphone')" class="brand-tab px-6 py-2 rounded-full font-medium bg-white/5 text-white/70" data-brand="iphone">🍎 iPhone</button>
        <button onclick="filterBrand('samsung')" class="brand-tab px-6 py-2 rounded-full font-medium bg-white/5 text-white/70" data-brand="samsung">📱 Samsung</button>
        <button onclick="filterBrand('xiaomi')" class="brand-tab px-6 py-2 rounded-full font-medium bg-white/5 text-white/70" data-brand="xiaomi">🔥 Xiaomi</button>
      </div>

      <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 pb-12"></div>
    </main>

    <footer class="mt-12 border-t border-white/10" style="background: rgba(255,255,255,.02);">
      <div class="max-w-6xl mx-auto px-4 py-12">
        <div class="text-center text-sm text-white/40 border-t border-white/10 pt-4">
          <p>© 2024 NaaKent Store. Semua hak dilindungi.</p>
        </div>
      </div>
    </footer>
  </div>

  <!-- ================= PRODUCT MODAL ================= -->
  <div id="product-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-2xl max-h-[90%] overflow-hidden flex flex-col slide-in">
      <div class="p-4 border-b border-white/10 flex items-center justify-between">
        <h2 id="modal-title" class="text-xl font-bold text-white"></h2>
        <button onclick="closeProductModal()" class="p-2 hover:bg-white/10 rounded-full transition">✕</button>
      </div>
      <div class="flex-1 overflow-auto">
        <div class="grid md:grid-cols-2 gap-6 p-6">
          <div class="flex items-center justify-center">
            <div class="w-full h-80 rounded-2xl overflow-hidden flex items-center justify-center" style="background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.10);">
              <img id="modal-image" src="" alt="Produk" class="w-full h-full object-contain" loading="lazy">
            </div>
          </div>
          <div class="flex flex-col gap-4">
            <div>
              <span id="modal-brand" class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-purple-500/15 border border-purple-400/20 text-purple-200 mb-2"></span>
              <h1 id="modal-name" class="text-2xl font-bold text-white mb-2"></h1>
              <p id="modal-desc" class="text-white/70 text-sm leading-relaxed mb-4"></p>
            </div>
            <div class="space-y-2">
              <p id="modal-price" class="text-3xl font-bold text-purple-200"></p>
              <div class="flex items-center gap-2"><span id="modal-stock" class="text-sm font-medium text-white/70"></span></div>
            </div>
            <div class="border-t border-white/10 pt-4 space-y-3">
              <div class="flex items-center gap-3">
                <span class="text-sm text-white/70">Jumlah:</span>
                <div class="flex items-center gap-2">
                  <button type="button" onclick="decreaseQty()" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center">−</button>
                  <span id="qty-input" class="w-10 text-center font-semibold text-white">1</span>
                  <button type="button" onclick="increaseQty()" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 flex items-center justify-center">+</button>
                </div>
              </div>
              <div class="flex gap-3 pt-4">
                <button onclick="addToCartFromModal()" class="flex-1 btn-primary text-white py-3 rounded-xl font-semibold">🛒 Keranjang</button>
                <button onclick="buyNowFromModal()" class="flex-1 btn-secondary py-3 rounded-xl font-semibold">✓ Beli Langsung</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- CART -->
  <div id="cart-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-lg max-h-[90%] overflow-hidden flex flex-col slide-in">
      <div class="p-4 border-b border-white/10 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">🛒 Keranjang Belanja</h2>
        <button onclick="closeCart()" class="p-2 hover:bg-white/10 rounded-full transition">✕</button>
      </div>
      <div id="cart-items" class="flex-1 overflow-auto p-4 space-y-3"></div>
      <div class="p-4 border-t border-white/10">
        <div class="flex items-center justify-between mb-4">
          <span class="text-white/60">Total:</span>
          <span id="cart-total" class="text-xl font-bold text-purple-200">Rp 0</span>
        </div>
        <button onclick="checkout()" id="checkout-btn" class="w-full btn-primary text-white py-3 rounded-xl font-semibold disabled:opacity-50 disabled:cursor-not-allowed" disabled>
          Lanjut ke Pembayaran
        </button>
      </div>
    </div>
  </div>

  <!-- SHIPPING -->
  <div id="shipping-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-lg max-h-[90%] overflow-hidden flex flex-col slide-in">
      <div class="p-4 border-b border-white/10 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">📦 Data Pengiriman</h2>
        <button onclick="closeShippingModal()" class="p-2 hover:bg-white/10 rounded-full transition">✕</button>
      </div>

      <form id="shipping-form" onsubmit="handleShippingSubmit(event)" class="flex-1 overflow-auto p-6 space-y-4">
        <div>
          <label class="block text-sm font-medium text-white/80 mb-2">Nama Penerima</label>
          <input type="text" id="recipient-name" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="Nama lengkap penerima">
        </div>
        <div>
          <label class="block text-sm font-medium text-white/80 mb-2">Nomor Telepon</label>
          <input type="tel" id="recipient-phone" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="08xx-xxxx-xxxx">
        </div>
        <div>
          <label class="block text-sm font-medium text-white/80 mb-2">Alamat Lengkap</label>
          <textarea id="recipient-address" required rows="3" class="w-full px-4 py-3 rounded-xl input-dark resize-none" placeholder="Jalan, No rumah, Kelurahan, RT/RW..."></textarea>
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Kota</label>
            <input type="text" id="recipient-city" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="Jakarta Selatan">
          </div>
          <div>
            <label class="block text-sm font-medium text-white/80 mb-2">Kode Pos</label>
            <input type="text" id="recipient-zip" required class="w-full px-4 py-3 rounded-xl input-dark" placeholder="12345">
          </div>
        </div>

        <div class="border-t border-white/10 pt-4">
          <p class="text-sm font-medium text-white/80 mb-3">Pilih Kurir Pengiriman</p>
          <div class="space-y-2">
            <label class="flex items-center p-3 border rounded-xl cursor-pointer" style="border-color: rgba(167,139,250,.35); background: rgba(124,58,237,.10);">
              <input type="radio" name="shipping-method" value="reguler" checked class="w-4 h-4 accent-purple-500">
              <div class="ml-3 flex-1">
                <p class="font-medium text-white">🚚 Reguler - 3-5 hari</p>
                <p class="text-xs text-white/60">Rp 25.000</p>
              </div>
            </label>

            <label class="flex items-center p-3 border border-white/10 rounded-xl cursor-pointer hover:border-purple-300/40">
              <input type="radio" name="shipping-method" value="express" class="w-4 h-4 accent-purple-500">
              <div class="ml-3 flex-1">
                <p class="font-medium text-white">⚡ Express - 1-2 hari</p>
                <p class="text-xs text-white/60">Rp 50.000</p>
              </div>
            </label>

            <label class="flex items-center p-3 border border-white/10 rounded-xl cursor-pointer hover:border-purple-300/40">
              <input type="radio" name="shipping-method" value="overnight" class="w-4 h-4 accent-purple-500">
              <div class="ml-3 flex-1">
                <p class="font-medium text-white">🌙 Overnight - Besok pagi</p>
                <p class="text-xs text-white/60">Rp 75.000</p>
              </div>
            </label>
          </div>
        </div>
      </form>

      <div class="p-4 border-t border-white/10 flex gap-3">
        <button onclick="closeShippingModal()" class="flex-1 px-4 py-3 border border-white/10 rounded-xl font-semibold text-white/80 hover:bg-white/5 transition">Batal</button>
        <button onclick="submitShipping()" class="flex-1 btn-primary text-white px-4 py-3 rounded-xl font-semibold">Lanjut Bayar</button>
      </div>
    </div>
  </div>

  <!-- PAYMENT -->
  <div id="payment-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-2xl max-h-[90%] overflow-hidden flex flex-col slide-in">
      <div class="p-4 border-b border-white/10 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">💳 Konfirmasi Pesanan</h2>
        <button onclick="closePaymentModal()" class="p-2 hover:bg-white/10 rounded-full transition">✕</button>
      </div>

      <div class="flex-1 overflow-auto">
        <div class="grid md:grid-cols-2 gap-6 p-6">
          <div class="space-y-4">
            <h3 class="font-bold text-white border-b border-white/10 pb-2">📦 Informasi Pengiriman</h3>
            <div class="space-y-2 text-sm">
              <div><p class="text-white/60">Nama Penerima:</p><p id="summary-name" class="font-medium text-white"></p></div>
              <div><p class="text-white/60">Nomor Telepon:</p><p id="summary-phone" class="font-medium text-white"></p></div>
              <div><p class="text-white/60">Alamat:</p><p id="summary-address" class="font-medium text-white text-xs"></p></div>
              <div><p class="text-white/60">Kurir Pengiriman:</p><p id="summary-shipping" class="font-medium text-purple-200"></p></div>
            </div>
          </div>

          <div class="space-y-4">
            <h3 class="font-bold text-white border-b border-white/10 pb-2">💳 Metode Pembayaran</h3>
            <div class="space-y-2">
              <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer" style="border-color: rgba(167,139,250,.35); background: rgba(124,58,237,.10);">
                <input type="radio" name="payment-method" value="transfer" checked class="w-4 h-4 accent-purple-500">
                <div class="ml-3 flex-1">
                  <p class="font-semibold text-white text-sm">Transfer Bank</p>
                  <p class="text-xs text-white/60">BCA, Mandiri, BNI</p>
                </div>
              </label>

              <label class="flex items-center p-3 border-2 border-white/10 rounded-xl cursor-pointer hover:border-purple-300/40">
                <input type="radio" name="payment-method" value="ewallet" class="w-4 h-4 accent-purple-500">
                <div class="ml-3 flex-1">
                  <p class="font-semibold text-white text-sm">E-Wallet</p>
                  <p class="text-xs text-white/60">GoPay, OVO, Dana</p>
                </div>
              </label>

              <label class="flex items-center p-3 border-2 border-white/10 rounded-xl cursor-pointer hover:border-purple-300/40">
                <input type="radio" name="payment-method" value="qris" class="w-4 h-4 accent-purple-500">
                <div class="ml-3 flex-1">
                  <p class="font-semibold text-white text-sm">QRIS</p>
                  <p class="text-xs text-white/60">Scan dari smartphone</p>
                </div>
              </label>

              <label class="flex items-center p-3 border-2 border-white/10 rounded-xl cursor-pointer hover:border-purple-300/40">
                <input type="radio" name="payment-method" value="cod" class="w-4 h-4 accent-purple-500">
                <div class="ml-3 flex-1">
                  <p class="font-semibold text-white text-sm">COD</p>
                  <p class="text-xs text-white/60">Bayar di tempat</p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <div class="p-6 border-t border-white/10" style="background: rgba(255,255,255,.02);">
          <h3 class="font-bold text-white mb-3">Ringkasan Pesanan</h3>
          <div id="payment-items" class="space-y-2 mb-4 text-sm"></div>
          <div class="space-y-2 border-t border-white/10 pt-3">
            <div class="flex justify-between text-sm"><span class="text-white/60">Subtotal:</span><span id="summary-subtotal" class="font-medium text-white">Rp 0</span></div>
            <div class="flex justify-between text-sm"><span class="text-white/60">Ongkir:</span><span id="summary-shipping-cost" class="font-medium text-white">Rp 0</span></div>
            <div class="flex justify-between text-lg font-bold border-t border-white/10 pt-3"><span>Total Bayar:</span><span id="payment-total" class="text-purple-200">Rp 0</span></div>
          </div>
        </div>
      </div>

      <div class="p-4 border-t border-white/10 flex gap-3">
        <button onclick="closePaymentModal()" class="flex-1 px-4 py-3 border border-white/10 rounded-xl font-semibold text-white/80 hover:bg-white/5 transition">Batal</button>
        <button onclick="confirmPayment()" class="flex-1 btn-primary text-white px-4 py-3 rounded-xl font-semibold">✓ Bayar Sekarang</button>
      </div>
    </div>
  </div>

  <!-- PAYMENT INSTRUCTIONS -->
  <div id="payment-instructions-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-2xl max-h-[90%] overflow-hidden flex flex-col slide-in">
      <div class="p-4 border-b border-white/10 flex items-center justify-between">
        <h2 id="payment-instructions-title" class="text-xl font-bold text-white">Instruksi Pembayaran</h2>
        <button onclick="closePaymentInstructionsModal()" class="p-2 hover:bg-white/10 rounded-full transition">✕</button>
      </div>

      <div class="flex-1 overflow-auto p-6">
        <div id="payment-instructions-content"></div>
      </div>

      <div class="p-4 border-t border-white/10 flex gap-3">
        <button onclick="closePaymentInstructionsModal()" class="flex-1 px-4 py-3 border border-white/10 rounded-xl font-semibold text-white/80 hover:bg-white/5 transition">Kembali</button>
        <button onclick="finishOrder()" class="flex-1 btn-primary text-white px-4 py-3 rounded-xl font-semibold">✓ Saya Sudah Bayar</button>
      </div>
    </div>
  </div>

  <!-- SUCCESS -->
  <div id="success-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-md overflow-hidden flex flex-col slide-in">
      <div class="p-6 text-center">
        <div class="text-5xl mb-3">✅</div>
        <h2 class="text-xl font-bold text-white mb-2">Pesanan Berhasil Dibuat!</h2>
        <p class="text-white/70 text-sm">Cek statusnya di menu “Pesanan Saya”.</p>
        <button onclick="closeSuccessModal()" class="mt-5 w-full btn-primary text-white py-3 rounded-xl font-semibold">OK</button>
      </div>
    </div>
  </div>

  <!-- ORDERS (BUYER) -->
  <div id="orders-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-3xl max-h-[90%] overflow-hidden flex flex-col slide-in">
      <div class="p-4 border-b border-white/10 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">🧾 Pesanan Saya</h2>
        <button onclick="closeOrdersModal()" class="p-2 hover:bg-white/10 rounded-full transition">✕</button>
      </div>

      <div class="p-4 border-b border-white/10 flex gap-2 flex-wrap">
        <button class="pill active" data-filter="ALL" onclick="setBuyerOrderFilter('ALL')">Semua</button>
        <button class="pill" data-filter="PROCESSING" onclick="setBuyerOrderFilter('PROCESSING')">Diproses</button>
        <button class="pill" data-filter="SHIPPED" onclick="setBuyerOrderFilter('SHIPPED')">Dikirim</button>
        <button class="pill" data-filter="DONE" onclick="setBuyerOrderFilter('DONE')">Selesai</button>
      </div>

      <div id="orders-list" class="flex-1 overflow-auto p-4 space-y-3"></div>
    </div>
  </div>

  <!-- ADMIN -->
  <div id="admin-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center p-4">
    <div class="modal-surface rounded-2xl w-full max-w-4xl max-h-[90%] overflow-hidden flex flex-col slide-in">
      <div class="p-4 border-b border-white/10 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">🧑‍💼 Dashboard Penjual</h2>
        <button onclick="closeAdminModal()" class="p-2 hover:bg-white/10 rounded-full transition">✕</button>
      </div>

      <div class="p-4 border-b border-white/10 flex gap-2 flex-wrap">
        <button class="pill active" data-admin-filter="ALL" onclick="setAdminOrderFilter('ALL')">Semua</button>
        <button class="pill" data-admin-filter="PROCESSING" onclick="setAdminOrderFilter('PROCESSING')">Diproses</button>
        <button class="pill" data-admin-filter="SHIPPED" onclick="setAdminOrderFilter('SHIPPED')">Dikirim</button>
        <button class="pill" data-admin-filter="DONE" onclick="setAdminOrderFilter('DONE')">Selesai</button>
      </div>

      <div id="admin-orders-list" class="flex-1 overflow-auto p-4 space-y-3"></div>
    </div>
  </div>

</div>

<script src="assets/js/app.js?v=2"></script>

</body>
</html>
