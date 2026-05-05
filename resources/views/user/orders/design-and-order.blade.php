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
                                    {{-- <div class="form-text">Stok tersedia: {{ $product->stock }}</div> --}}
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
                                <div class="col-12">
                                    <label class="form-label">Catatan Desain (Opsional)</label>
                                    <textarea class="form-control" name="design_notes" rows="2"
                                        placeholder="Posisi, ukuran, atau instruksi khusus..."></textarea>
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
            // 1. DATA DARI DATABASE & CONFIG
            // ==========================================
            const productPrice = {{ $product->price ?? 0 }};
            const maxStock = {{ $product->stock ?? 0 }};

            // Biaya Desain sesuai input Admin (A5, A4, A3)
            const designCosts = {
                'small': {{ $product->small_design_cost ?? 0 }}, // A5
                'medium': {{ $product->medium_design_cost ?? 0 }}, // A4
                'large': {{ $product->large_design_cost ?? 0 }} // A3
            };

            const sizePrices = {
                'S': 0,
                'M': 0,
                'L': 0,
                'XL': 10000,
                'XXL': 15000
            };

            let canvas;
            let baseTshirtImage;
            let currentColor = 'putih';
            let currentView = 'depan';
            let isSwitchingView = false; // FLAG PENTING: Mencegah Bug Lompatan Harga

            // Kita simpan array objek desain saja, bukan seluruh kanvas
            const designStates = {
                'depan': [],
                'belakang': [],
                'samping': []
            };
            const loadedTshirtImages = {
                'depan': {},
                'belakang': {},
                'samping': {}
            };
            const colorOptions = {
                'putih': '#FFFFFF',
                'hitam': '#000000',
                'merah': '#E53E3E',
                'biru': '#3182CE',
                'hijau': '#38A169'
            };

            // ==========================================
            // 2. LOGIKA KALKULASI HARGA (KUMULATIF & STABIL)
            // ==========================================
            function calculatePrice() {
                if (isSwitchingView) return; // Jangan hitung saat sedang transisi view

                const quantity = parseInt(document.getElementById('quantity').value) || 1;
                const size = document.getElementById('size').value;
                const pricePerUnit = productPrice + (sizePrices[size] || 0);
                const totalProductPrice = pricePerUnit * quantity;

                let totalDesignCost = 0;
                let allDesigns = [];

                // Gabungkan desain dari VIEW LAIN (yang tersimpan di memori)
                Object.keys(designStates).forEach(view => {
                    if (view !== currentView && designStates[view]) {
                        allDesigns.push(...designStates[view]);
                    }
                });

                // Gabungkan desain dari KANVAS SEKARANG (Live)
                canvas.getObjects().forEach(obj => {
                    if (obj.type === 'image' && obj !== baseTshirtImage) {
                        allDesigns.push(obj.toObject(['selectable', 'evented', 'hasControls',
                            'hasBorders']));
                    }
                });

                // Hitung Biaya Tiap Desain berdasarkan Luas Area (Threshold A5, A4, A3)
                allDesigns.forEach(obj => {
                    const imgArea = (obj.width * obj.scaleX) * (obj.height * obj.scaleY);

                    // Threshold area pada canvas 400x400
                    if (imgArea < 60000) totalDesignCost += designCosts.small; // A5
                    else if (imgArea < 110000) totalDesignCost += designCosts.medium; // A4
                    else totalDesignCost += designCosts.large; // A3
                });

                const total = totalProductPrice + totalDesignCost;
                document.getElementById('total-price').innerText = `Rp ${total.toLocaleString('id-ID')}`;
                document.getElementById('total_price_input').value = total;
            }

            // ==========================================
            // 3. NAVIGASI VIEW & STATE MANAGEMENT
            // ==========================================
            function switchTshirtView(newView) {
                if (currentView === newView) return;

                isSwitchingView = true; // Kunci perhitungan harga sementara

                // Simpan HANYA objek desain dari kanvas saat ini ke memori
                designStates[currentView] = canvas.getObjects()
                    .filter(obj => obj !== baseTshirtImage)
                    .map(obj => obj.toObject(['selectable', 'evented', 'hasControls', 'hasBorders']));

                currentView = newView;
                updateCanvasForView(currentColor, currentView);

                document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
                document.querySelector(`#view-${newView}`).classList.add('active');
            }

            function updateCanvasForView(color, view) {
                canvas.clear();
                const imageUrl = `{{ asset('kaos') }}/kaos-${color}-${view}.png`;

                fabric.Image.fromURL(imageUrl, function(img) {
                    img.set({
                        left: 200,
                        top: 200,
                        originX: 'center',
                        originY: 'center',
                        selectable: false,
                        evented: false
                    });
                    img.scaleToWidth(360);
                    baseTshirtImage = img;
                    canvas.add(baseTshirtImage);
                    canvas.sendToBack(baseTshirtImage);

                    // Muat desain yang disimpan di memori untuk view ini
                    if (designStates[view] && designStates[view].length > 0) {
                        fabric.util.enlivenObjects(designStates[view], function(objects) {
                            objects.forEach(o => canvas.add(o));
                            canvas.renderAll();
                            isSwitchingView = false; // Buka kunci
                            calculatePrice();
                        });
                    } else {
                        isSwitchingView = false; // Buka kunci
                        calculatePrice();
                    }
                }, {
                    crossOrigin: 'anonymous'
                });
            }

            // ==========================================
            // 4. INIT & EVENT LISTENERS
            // ==========================================
            function initCanvas() {
                canvas = new fabric.Canvas('tshirt-canvas', {
                    width: 400,
                    height: 400,
                    backgroundColor: '#f8f9fa'
                });
                updateCanvasForView(currentColor, currentView);
                canvas.on('object:modified', calculatePrice);
                canvas.on('object:added', calculatePrice);
                canvas.on('object:removed', calculatePrice);
            }

            // Tambah Gambar
            document.getElementById('design-file').addEventListener('change', function(e) {
                Array.from(e.target.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(f) {
                        fabric.Image.fromURL(f.target.result, function(img) {
                            img.scaleToWidth(150);
                            img.set({
                                left: 200,
                                top: 200,
                                originX: 'center',
                                originY: 'center'
                            });
                            canvas.add(img);
                            canvas.setActiveObject(img);
                            calculatePrice();
                        });
                    };
                    reader.readAsDataURL(file);
                });
            });

            // ==========================================
            // 5. PROSES KONFIRMASI (FINAL RENDERING)
            // ==========================================
            document.getElementById('order-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                const btn = document.getElementById('submit-button');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                // Simpan state terakhir
                designStates[currentView] = canvas.getObjects()
                    .filter(obj => obj !== baseTshirtImage)
                    .map(obj => obj.toObject());

                const views = ['depan', 'belakang', 'samping'];
                for (const view of views) {
                    await new Promise((resolve) => {
                        canvas.clear();
                        const shirtUrl =
                        `{{ asset('kaos') }}/kaos-${currentColor}-${view}.png`;
                        fabric.Image.fromURL(shirtUrl, function(img) {
                            img.set({
                                left: 200,
                                top: 200,
                                originX: 'center',
                                originY: 'center',
                                selectable: false
                            });
                            img.scaleToWidth(360);
                            canvas.add(img);

                            const doRender = () => {
                                setTimeout(() => {
                                    // 1. Simpan MOCKUP (Ada Kaos)
                                    document.getElementById(
                                            `design_data_url_${view}`)
                                        .value = canvas.toDataURL({
                                            multiplier: 2
                                        });
                                    // 2. Simpan BAHAN (Hanya Desain, HD Transparan)
                                    img.visible = false;
                                    canvas.backgroundColor =
                                    'rgba(0,0,0,0)';
                                    canvas.renderAll();
                                    document.getElementById(
                                            `raw_design_data_url_${view}`)
                                        .value = canvas.toDataURL({
                                            multiplier: 6
                                        });
                                    resolve();
                                }, 300);
                            };

                            if (designStates[view].length > 0) {
                                fabric.util.enlivenObjects(designStates[view], (
                                objs) => {
                                    objs.forEach(o => canvas.add(o));
                                    doRender();
                                });
                            } else {
                                doRender();
                            }
                        }, {
                            crossOrigin: 'anonymous'
                        });
                    });
                }
                this.submit();
            });

            initCanvas();

            // Setup tombol quantity & warna (sama seperti sebelumnya)
            window.incrementQuantity = () => {
                let q = document.getElementById('quantity');
                if (parseInt(q.value) < maxStock) {
                    q.value++;
                    calculatePrice();
                }
            };
            window.decrementQuantity = () => {
                let q = document.getElementById('quantity');
                if (parseInt(q.value) > 1) {
                    q.value--;
                    calculatePrice();
                }
            };

            document.querySelectorAll('.btn-group .btn').forEach(b => b.addEventListener('click', function() {
                switchTshirtView(this.dataset.view);
            }));
        });
    </script>
@endsection
