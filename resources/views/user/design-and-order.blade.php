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
                    <h5 class="mb-0"><i class="fas fa-drafting-compass me-2"></i>Alat Desain & Form Pemesanan</h5>
                </div>
                <div class="card-body">
                    <form id="order-form" action="{{ route('user.orders.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="design_data_url_depan" name="design_data_url_depan">
                        <input type="hidden" id="design_data_url_belakang" name="design_data_url_belakang">
                        <input type="hidden" id="design_data_url_samping" name="design_data_url_samping">
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
                                            name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                            required>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="incrementQuantity()">+</button>
                                    </div>
                                    <div class="form-text">Stok tersedia: {{ $product->stock }}</div>
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
                                        {{-- Tombol akan dibuat oleh JavaScript --}}
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
            const productPrice = {{ $product->price }};
            const maxStock = {{ $product->stock }};
            let canvas;
            let baseTshirtImage;
            let currentColor = 'putih';
            let currentView = 'depan';

            const loadedTshirtImages = {
                'depan': {},
                'belakang': {},
                'samping': {}
            };

            // Objek untuk menyimpan state kanvas dalam format JSON
            const designStates = {
                'depan': null,
                'belakang': null,
                'samping': null
            };

            const colorOptions = {
                'putih': '#FFFFFF',
                'hitam': '#000000',
                'merah': '#E53E3E',
                'biru': '#3182CE',
                'hijau': '#38A169',
            };
            const sizePrices = {
                'S': 0,
                'M': 5000,
                'L': 10000,
                'XL': 15000,
                'XXL': 20000
            };

            const designCosts = {
                'small': 20000,
                'medium': 35000,
                'large': 50000
            };

            // Fungsi untuk memperbarui gambar kaos dasar dan desain yang sesuai
            function updateCanvasForView(color, view) {
                // Hapus semua objek dari kanvas sebelum memuat yang baru
                canvas.clear();

                // Muat kembali gambar kaos
                if (loadedTshirtImages[view][color]) {
                    baseTshirtImage = loadedTshirtImages[view][color];
                    canvas.add(baseTshirtImage);
                    canvas.sendToBack(baseTshirtImage);
                    // Muat desain yang disimpan
                    if (designStates[view]) {
                        canvas.loadFromJSON(designStates[view], () => {
                            canvas.renderAll();
                        });
                    }
                    canvas.renderAll();
                } else {
                    const imageUrl = `/kaos/kaos-${color}-${view}.png`;
                    fabric.Image.fromURL(imageUrl, function(img) {
                        img.set({
                            left: canvas.width / 2,
                            top: canvas.height / 2,
                            originX: 'center',
                            originY: 'center',
                            selectable: false,
                            evented: false,
                        });
                        img.scaleToWidth(canvas.width * 0.9);
                        baseTshirtImage = img;
                        loadedTshirtImages[view][color] = img;
                        canvas.add(baseTshirtImage);
                        canvas.sendToBack(baseTshirtImage);

                        // Muat desain yang disimpan setelah gambar kaos dimuat
                        if (designStates[view]) {
                            canvas.loadFromJSON(designStates[view], () => {
                                canvas.renderAll();
                            });
                        }
                        canvas.renderAll();
                    }, {
                        crossOrigin: 'anonymous'
                    });
                }
            }

            // Fungsi untuk mengelola designImage saat beralih tampilan
            function switchTshirtView(newView) {
                if (currentView === newView) return;

                // Simpan state kanvas saat ini (tanpa gambar kaos)
                designStates[currentView] = canvas.toJSON(['selectable', 'evented', 'hasControls', 'hasBorders']);

                currentView = newView;
                updateCanvasForView(currentColor, currentView);

                // Perbarui tombol aktif
                document.querySelectorAll('.btn-group .btn').forEach(btn => btn.classList.remove('active'));
                const newViewButton = document.querySelector(`#view-${newView}`);
                if (newViewButton) {
                    newViewButton.classList.add('active');
                }
            }

            // function calculatePrice() {
            //     let total = productPrice;
            //     const quantity = parseInt(document.getElementById('quantity').value);
            //     const size = document.getElementById('size').value;
            //     total += sizePrices[size];

            //     // Hitung biaya desain dari semua objek di semua sisi
            //     let totalDesignCost = 0;
            //     const allDesigns = [];

            //     // Kumpulkan desain dari state yang disimpan
            //     for (const view in designStates) {
            //         if (designStates[view]) {
            //             allDesigns.push(...designStates[view].objects.filter(obj => obj.type !==
            //             'image')); // Exclude base t-shirt image
            //         }
            //     }
            //     // Kumpulkan desain dari kanvas saat ini (belum disimpan)
            //     const currentDesigns = canvas.getObjects().filter(obj => obj !== baseTshirtImage);
            //     allDesigns.push(...currentDesigns.map(obj => obj.toObject()));

            //     allDesigns.forEach(obj => {
            //         if (obj.type === 'image') {
            //             const imgArea = obj.width * obj.height * (obj.scaleX || 1) * (obj.scaleY || 1);
            //             if (imgArea < 100000) totalDesignCost += designCosts.small;
            //             else if (imgArea < 300000) totalDesignCost += designCosts.medium;
            //             else totalDesignCost += designCosts.large;
            //         }
            //     });

            //     total += totalDesignCost;
            //     total *= quantity;

            //     document.getElementById('total-price').innerText = `Rp ${total.toLocaleString('id-ID')}`;
            //     document.getElementById('total_price_input').value = total;
            // }

            function calculatePrice() {
                // 1. Hitung harga dasar per unit (termasuk biaya ukuran)
                const quantity = parseInt(document.getElementById('quantity').value);
                const size = document.getElementById('size').value;
                const pricePerUnit = productPrice + sizePrices[size];

                // 2. Hitung total harga produk (tanpa biaya desain)
                const totalProductPrice = pricePerUnit * quantity;

                // 3. Hitung biaya desain total (dikenakan sekali untuk seluruh pesanan)
                let totalDesignCost = 0;
                const allDesigns = [];

                // Kumpulkan desain dari state yang disimpan
                for (const view in designStates) {
                    if (designStates[view] && designStates[view].objects) {
                        allDesigns.push(...designStates[view].objects.filter(obj => obj.type === 'image'));
                    }
                }
                // Kumpulkan desain dari kanvas saat ini
                canvas.getObjects().forEach(obj => {
                    if (obj.type === 'image' && obj !== baseTshirtImage) {
                        allDesigns.push(obj.toObject());
                    }
                });

                if (allDesigns.length > 0) {
                    // Tentukan biaya desain berdasarkan desain yang paling "mahal" (area terbesar)
                    let maxDesignCost = 0;
                    allDesigns.forEach(obj => {
                        const imgArea = (obj.width || 0) * (obj.height || 0) * (obj.scaleX || 1) * (obj
                            .scaleY || 1);
                        if (imgArea < 100000) maxDesignCost = Math.max(maxDesignCost, designCosts.small);
                        else if (imgArea < 300000) maxDesignCost = Math.max(maxDesignCost, designCosts
                            .medium);
                        else maxDesignCost = Math.max(maxDesignCost, designCosts.large);
                    });
                    totalDesignCost = maxDesignCost;
                }

                // 4. Hitung total harga akhir
                const total = totalProductPrice + totalDesignCost;

                document.getElementById('total-price').innerText = `Rp ${total.toLocaleString('id-ID')}`;
                document.getElementById('total_price_input').value = total;
            }

            function initCanvas() {
                canvas = new fabric.Canvas('tshirt-canvas', {
                    width: 400,
                    height: 400,
                    backgroundColor: '#f8f9fa'
                });

                updateCanvasForView(currentColor, currentView);

                // Tambahkan event listener untuk perubahan pada objek di kanvas
                canvas.on('object:modified', calculatePrice);
                canvas.on('object:added', calculatePrice);
                canvas.on('object:removed', calculatePrice);
            }

            document.getElementById('design-file').addEventListener('change', function(e) {
                const files = e.target.files;
                if (files.length > 0) {
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            fabric.Image.fromURL(event.target.result, function(img) {
                                img.scaleToWidth(canvas.width * 0.4);
                                img.set({
                                    left: canvas.width / 2,
                                    top: canvas.height / 2,
                                    originX: 'center',
                                    originY: 'center',
                                    selectable: true,
                                    hasControls: true,
                                    hasBorders: true
                                });
                                canvas.add(img);
                                canvas.setActiveObject(img);
                                canvas.bringToFront(img);
                                calculatePrice();
                            }, {
                                crossOrigin: 'anonymous'
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });

            document.getElementById('quantity').addEventListener('change', calculatePrice);
            document.getElementById('size').addEventListener('change', calculatePrice);

            document.getElementById('order-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                document.getElementById('submit-button').disabled = true;

                // Simpan state kanvas terakhir sebelum submit
                designStates[currentView] = canvas.toJSON(['selectable', 'evented', 'hasControls',
                    'hasBorders'
                ]);

                // Render dan simpan gambar untuk setiap sisi
                const views = ['depan', 'belakang', 'samping'];
                for (const view of views) {
                    await new Promise(resolve => {
                        canvas.clear();
                        const shirtUrl = `/kaos/kaos-${currentColor}-${view}.png`;
                        fabric.Image.fromURL(shirtUrl, function(img) {
                            img.set({
                                left: canvas.width / 2,
                                top: canvas.height / 2,
                                originX: 'center',
                                originY: 'center',
                                selectable: false,
                                evented: false,
                            });
                            img.scaleToWidth(canvas.width * 0.9);
                            canvas.add(img);
                            canvas.sendToBack(img);

                            if (designStates[view]) {
                                canvas.loadFromJSON(designStates[view], () => {
                                    setTimeout(() => {
                                        document.getElementById(
                                                `design_data_url_${view}`
                                            ).value = canvas
                                            .toDataURL({
                                                format: 'png',
                                                quality: 1.0
                                            });
                                        resolve();
                                    }, 200);
                                });
                            } else {
                                setTimeout(() => {
                                    document.getElementById(
                                            `design_data_url_${view}`).value =
                                        canvas.toDataURL({
                                            format: 'png',
                                            quality: 1.0
                                        });
                                    resolve();
                                }, 200);
                            }
                        }, {
                            crossOrigin: 'anonymous'
                        });
                    });
                }
                // Setelah semua gambar disimpan, submit form
                this.submit();
            });

            window.incrementQuantity = function() {
                const input = document.getElementById('quantity');
                let value = parseInt(input.value);
                if (value < maxStock) {
                    input.value = value + 1;
                    calculatePrice();
                }
            };

            window.decrementQuantity = function() {
                const input = document.getElementById('quantity');
                let value = parseInt(input.value);
                if (value > 1) {
                    input.value = value - 1;
                    calculatePrice();
                }
            };

            initCanvas();

            const colorOptionsDiv = document.getElementById('color-options');
            Object.keys(colorOptions).forEach(colorKey => {
                const button = document.createElement('button');
                button.type = 'button';
                button.classList.add('btn', 'rounded-circle', 'p-4', 'border');
                button.style.backgroundColor = colorOptions[colorKey];
                button.dataset.color = colorKey;
                if (colorKey === 'putih') {
                    button.classList.add('active', 'border-primary');
                }
                button.addEventListener('click', function() {
                    document.querySelectorAll('#color-options .btn').forEach(btn => btn.classList
                        .remove('active', 'border-primary'));
                    this.classList.add('active', 'border-primary');
                    currentColor = this.dataset.color;
                    document.getElementById('selected_color_input').value = currentColor;
                    updateCanvasForView(currentColor, currentView);
                });
                colorOptionsDiv.appendChild(button);
            });

            document.querySelectorAll('.btn-group .btn').forEach(button => {
                button.addEventListener('click', function() {
                    switchTshirtView(this.dataset.view);
                });
            });
        });
    </script>
@endsection
