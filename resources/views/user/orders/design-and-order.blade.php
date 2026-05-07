@extends('layouts.user')

@section('title', 'Desain & Pesan - ' . $product->name)

@section('content')
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.products') }}">Produk</a></li>
                    <li class="breadcrumb-item active">Desain & Pesan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-drafting-compass me-2"></i>Alat Desain & Form Pemesanannnnn</h5>
                </div>
                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form id="order-form" action="{{ route('user.orders.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="design_data_url_depan" name="design_data_url_depan">
                        <input type="hidden" id="design_data_url_belakang" name="design_data_url_belakang">
                        <input type="hidden" id="design_data_url_samping" name="design_data_url_samping">

                        <input type="hidden" id="raw_design_data_url_depan" name="raw_design_data_url_depan">
                        <input type="hidden" id="raw_design_data_url_belakang" name="raw_design_data_url_belakang">
                        <input type="hidden" id="raw_design_data_url_samping" name="raw_design_data_url_samping">

                        <input type="hidden" id="total_price_input" name="total_price_input">
                        <input type="hidden" id="selected_color_input" name="selected_color" value="putih">

                        <div class="mb-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-tshirt me-2"></i>1. Detail Produk & Jumlah</h6>
                            <div class="alert alert-info">
                                Produk terpilih: <strong>{{ $product->name }}</strong>
                                <br>Harga per unit: <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label">Jumlah Pesanan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="decrementQuantity()">-</button>
                                        <input type="number" class="form-control text-center" id="quantity"
                                            name="quantity" value="1" min="1" required>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="incrementQuantity()">+</button>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="size" class="form-label">Ukuran <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="size" name="size" required>
                                        <option value="S">S</option>
                                        <option value="M" selected>M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-paint-brush me-2"></i>2. Desain Kustom Anda</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Warna Produk</label>
                                    <div id="color-options" class="d-flex justify-content-center flex-wrap gap-2">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Upload Gambar Desain</label>
                                    <input type="file" id="design-file" class="form-control" accept="image/*" multiple>
                                </div>

                                <div id="active-design-list" class="mb-4">
                                    <label class="form-label fw-bold text-primary"><i class="fas fa-list me-2"></i>Daftar
                                        Ukuran Desain</label>
                                    <div id="design-items-container">
                                        <div class="text-muted small p-2 border rounded bg-light text-center"
                                            id="empty-design-msg">
                                            Belum ada desain yang ditambahkan
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="design_notes" class="form-label fw-bold">Catatan Desain</label>
                                    <textarea class="form-control" id="design_notes" name="design_notes" rows="3"
                                        placeholder="Contoh: Tolong sablon agak ke atas">{{ old('design_notes') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div id="active-design-list" class="mb-4">
                            <label class="form-label fw-bold text-primary"><i class="fas fa-list me-2"></i>Daftar Ukuran
                                Desain (Semua Sisi)</label>
                            <div id="design-items-container" class="p-2 border rounded bg-light">
                                <div class="text-muted small text-center py-2" id="empty-design-msg">
                                    Belum ada desain yang ditambahkan
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-file-invoice-dollar me-2"></i>3. Ringkasan &
                                Konfirmasi</h6>
                            <div class="card bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title">Total Harga:</h6>
                                    <h3 class="text-success" id="total-price">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</h3>
                                    <p class="mb-0 text-muted small">Harga akan diperbarui berdasarkan jumlah &
                                        kompleksitas desain.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya menyetujui <a href="#" data-bs-toggle="modal"
                                        data-bs-target="#termsModal">syarat dan ketentuan</a> pemesanan
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg" id="submit-button">
                                <i class="fas fa-check-circle me-2"></i>Konfirmasi & Pesan
                            </button>
                            <a href="{{ route('user.products') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar Produk
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0 text-center"><i class="fas fa-eye me-2"></i>Pratinjau Desain</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    <div class="position-relative w-100" style="max-width: 500px;">
                        <canvas id="tshirt-canvas"></canvas>
                    </div>
                    <div class="btn-group mt-3" role="group" aria-label="Tampilan Kaos">
                        <button type="button" class="btn btn-outline-primary active" id="view-depan"
                            data-view="depan">Depan</button>
                        <button type="button" class="btn btn-outline-primary" id="view-belakang"
                            data-view="belakang">Belakang</button>
                        <button type="button" class="btn btn-outline-primary" id="view-samping"
                            data-view="samping">Samping</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Syarat dan Ketentuan Pemesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Proses Pemesanan</h6>
                    <p>Pesanan akan diproses dalam 1-2 hari kerja setelah konfirmasi pembayaran.</p>

                    <h6>2. Pembayaran</h6>
                    <p>Pembayaran dilakukan setelah pesanan dikonfirmasi oleh admin via COD (Cash on Delivery).</p>

                    <h6>3. Desain Kustom</h6>
                    <p>Desain yang diupload akan diproses sesuai dengan spesifikasi yang diberikan.</p>

                    <h6>4. Pengiriman</h6>
                    <p>Pengiriman dilakukan setelah pesanan selesai diproduksi.</p>

                    <h6>5. Kebijakan Pembatalan</h6>
                    <p>Pembatalan dapat dilakukan sebelum pesanan diproses.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ==========================================
            // 1. KONFIGURASI HARGA & STATE
            // ==========================================
            const productPrice = {{ $product->price ?? 0 }};
            const maxStock = {{ $product->stock ?? 0 }};
            const sizePrices = {
                'S': 0,
                'M': 0,
                'L': 0,
                'XL': 10000,
                'XXL': 15000
            };
            const designCosts = {
                'small': {{ $product->small_design_cost ?? 0 }}, // A5
                'medium': {{ $product->medium_design_cost ?? 0 }}, // A4
                'large': {{ $product->large_design_cost ?? 0 }} // A3
            };

            let canvas, baseTshirtImage;
            let currentView = 'depan';
            let currentColor = 'putih';

            // Array pusat untuk menyimpan semua desain (Depan, Belakang, Samping)
            let uploadedDesigns = [];

            // ==========================================
            // 2. FUNGSI KALKULASI HARGA AKUMULATIF
            // ==========================================
            window.calculatePrice = function() {
                const quantity = parseInt(document.getElementById('quantity').value) || 1;
                const size = document.getElementById('size').value;

                let totalDesignCost = 0;
                uploadedDesigns.forEach(item => {
                    totalDesignCost += designCosts[item.size] || 0;
                });

                const basePrice = productPrice + (sizePrices[size] || 0);
                const finalTotal = (basePrice + totalDesignCost) * quantity;

                document.getElementById('total-price').innerText = `Rp ${finalTotal.toLocaleString('id-ID')}`;
                document.getElementById('total_price_input').value = finalTotal;
            };

            // ==========================================
            // 3. FUNGSI MANAJEMEN UI DAFTAR DESAIN
            // ==========================================
            function updateDesignUI() {
                const container = document.getElementById('design-items-container');
                const emptyMsg = document.getElementById('empty-design-msg');

                container.querySelectorAll('.design-row-item').forEach(el => el.remove());

                if (uploadedDesigns.length === 0) {
                    emptyMsg.style.display = 'block';
                } else {
                    emptyMsg.style.display = 'none';

                    uploadedDesigns.forEach((item, index) => {
                        const row = document.createElement('div');
                        row.className = 'design-row-item border rounded p-2 mb-2 bg-white shadow-sm';
                        row.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small fw-bold text-truncate" style="max-width: 120px;">
                                <i class="fas fa-image me-1"></i> Gbr ${index + 1} (${item.view})
                            </div>
                            <select class="form-select form-select-sm w-50" onchange="updateItemSize('${item.id}', this.value)">
                                <option value="small" ${item.size === 'small' ? 'selected' : ''}>A5 (+Rp ${designCosts.small.toLocaleString()})</option>
                                <option value="medium" ${item.size === 'medium' ? 'selected' : ''}>A4 (+Rp ${designCosts.medium.toLocaleString()})</option>
                                <option value="large" ${item.size === 'large' ? 'selected' : ''}>A3 (+Rp ${designCosts.large.toLocaleString()})</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeSpecificDesign('${item.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                        container.appendChild(row);
                    });
                }
                calculatePrice();
            }

            window.updateItemSize = function(id, newSize) {
                const item = uploadedDesigns.find(d => d.id === id);
                if (item) {
                    item.size = newSize;
                    calculatePrice();
                }
            };

            window.removeSpecificDesign = function(id) {
                const index = uploadedDesigns.findIndex(d => d.id === id);
                if (index !== -1) {
                    // Cek apakah item sedang nampil di layar sekarang, kalau iya hapus visualnya
                    if (uploadedDesigns[index].view === currentView) {
                        canvas.remove(uploadedDesigns[index].fabricObj);
                    }
                    uploadedDesigns.splice(index, 1);
                    updateDesignUI();
                }
            };

            // ==========================================
            // 4. NAVIGASI VIEW & WARNA
            // ==========================================
            window.switchTshirtView = function(view) {
                currentView = view;
                updateCanvasForView(currentColor, view);

                document.querySelectorAll('.btn-group .btn').forEach(b => b.classList.remove('active'));
                const activeBtn = document.getElementById(`view-${view}`);
                if (activeBtn) activeBtn.classList.add('active');
            };

            function updateCanvasForView(color, view) {
                canvas.clear();
                const imageUrl = `{{ asset('kaos') }}/kaos-${color}-${view}.png`;

                fabric.Image.fromURL(imageUrl, function(img) {
                    img.set({
                        id: 'bg-kaos',
                        left: 225,
                        top: 325,
                        originX: 'center',
                        originY: 'center',
                        selectable: false,
                        evented: false
                    });
                    img.scaleToHeight(630);
                    canvas.add(img);
                    canvas.sendToBack(img);

                    // Tampilkan desain milik sisi yang lagi dilihat aja
                    uploadedDesigns.forEach(item => {
                        if (item.view === view) {
                            canvas.add(item.fabricObj);
                        }
                    });

                    canvas.renderAll();
                }, {
                    crossOrigin: 'anonymous'
                });
            }

            // ==========================================
            // 5. UPLOAD & INITIALIZE
            // ==========================================
            function initCanvas() {
                canvas = new fabric.Canvas('tshirt-canvas', {
                    width: 450,
                    height: 650,
                    backgroundColor: '#f8f9fa'
                });
                updateCanvasForView(currentColor, currentView);

                // BUG SEBELUMNYA TELAH DIHAPUS DARI SINI: (canvas.on('object:removed'))
                // Sekarang menggunakan event keyboard delete manual yang jauh lebih aman!
            }

            // [FITUR BARU] Hapus pakai tombol Delete/Backspace di Keyboard
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Delete' || e.key === 'Backspace') {
                    const activeObj = canvas.getActiveObject();
                    if (activeObj && activeObj.id !== 'bg-kaos') {
                        // Cari desain tersebut di daftar memori
                        const item = uploadedDesigns.find(d => d.fabricObj === activeObj);
                        if (item) window.removeSpecificDesign(item.id);
                    }
                }
            });

            document.getElementById('design-file').addEventListener('change', function(e) {
                Array.from(e.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(f) {
                        fabric.Image.fromURL(f.target.result, function(img) {
                            const uniqueId = 'dsgn_' + Date.now() + Math.random();
                            img.scaleToWidth(150);
                            img.set({
                                left: 225,
                                top: 200,
                                originX: 'center',
                                originY: 'center',
                                hasControls: true,
                                selectable: true
                            });

                            uploadedDesigns.push({
                                id: uniqueId,
                                view: currentView,
                                fabricObj: img,
                                size: 'small'
                            });

                            canvas.add(img);
                            canvas.setActiveObject(img);
                            updateDesignUI();
                        });
                    };
                    reader.readAsDataURL(file);
                });
                this.value = '';
            });

            // ==========================================
            // 6. RENDER SUBMIT HD
            // ==========================================
            document.getElementById('order-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = document.getElementById('submit-button');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mempersiapkan File HD...';

                // HARGA DIKUNCI DI SINI SEBELUM PROSES RENDER (ANTI-RESET)
                calculatePrice();

                const views = ['depan', 'belakang', 'samping'];
                for (const v of views) {
                    await new Promise((resolve) => {
                        canvas.clear();
                        const shirtUrl = `{{ asset('kaos') }}/kaos-${currentColor}-${v}.png`;
                        fabric.Image.fromURL(shirtUrl, function(img) {
                            img.set({
                                left: 225,
                                top: 325,
                                originX: 'center',
                                originY: 'center',
                                selectable: false
                            });
                            img.scaleToHeight(630);
                            canvas.add(img);

                            uploadedDesigns.forEach(item => {
                                if (item.view === v) canvas.add(item.fabricObj);
                            });

                            const doRender = () => {
                                setTimeout(() => {
                                    document.getElementById(
                                            `design_data_url_${v}`).value =
                                        canvas.toDataURL({
                                            multiplier: 2
                                        });
                                    img.visible = false;
                                    canvas.backgroundColor =
                                    'rgba(0,0,0,0)';
                                    canvas.renderAll();
                                    document.getElementById(
                                            `raw_design_data_url_${v}`)
                                        .value = canvas.toDataURL({
                                            multiplier: 5
                                        });
                                    resolve();
                                }, 300);
                            };
                            doRender();
                        }, {
                            crossOrigin: 'anonymous'
                        });
                    });
                }
                this.submit();
            });

            initCanvas();

            // Panggil hitung harga pertama kali (Saat halaman dimuat)
            calculatePrice();

            // Event Listener Navigasi View 
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const viewName = this.dataset.view || this.id.replace('view-', '');
                    switchTshirtView(viewName);
                });
            });

            // Event Listener Warna Kaos
            document.querySelectorAll('#color-options .btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('#color-options .btn').forEach(b => b.classList
                        .remove('active', 'border-primary'));
                    this.classList.add('active', 'border-primary');
                    currentColor = this.dataset.color;
                    document.getElementById('selected_color_input').value = currentColor;
                    updateCanvasForView(currentColor, currentView);
                });
            });

            // Event Listener Jumlah & Size Kaos
            window.incrementQuantity = () => {
                let q = document.getElementById('quantity');
                q.value++;
                calculatePrice();
            };
            window.decrementQuantity = () => {
                let q = document.getElementById('quantity');
                if (q.value > 1) q.value--;
                calculatePrice();
            };
            document.getElementById('size').addEventListener('change', calculatePrice);
        });
    </script>
@endsection
