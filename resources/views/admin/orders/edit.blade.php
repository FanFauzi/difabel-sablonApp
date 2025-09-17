@extends('layouts.admin')

@section('title', 'Edit Pesanan #' . $order->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Perbarui Status Pesanan #{{ $order->id }}</h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <div class="mb-4">
                    <h6 class="text-primary">Ringkasan Pesanan</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Pelanggan:</strong> {{ $order->user->name }}</li>
                        <li class="list-group-item"><strong>Produk:</strong> {{ $order->product->name }}</li>
                        <li class="list-group-item"><strong>Jumlah:</strong> {{ $order->quantity }} pcs</li>
                        <li class="list-group-item"><strong>Total Harga:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</li>
                        @if ($order->design_file)
                            <li class="list-group-item"><strong>Ukuran Desain:</strong> {{ ucfirst($order->design_size) }}</li>
                            <li class="list-group-item"><strong>Biaya Desain:</strong> Rp {{ number_format($order->design_cost, 0, ',', '.') }}</li>
                            <li class="list-group-item"><strong>Deskripsi Desain:</strong> {{ $order->design_description }}</li>
                            <li class="list-group-item"><strong>File Desain:</strong> <a href="{{ asset('storage/' . $order->design_file) }}" target="_blank">Lihat File</a></li>
                        @endif
                    </ul>
                </div>

                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="status" class="form-label">Pilih Status Baru</label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="proses" {{ $order->status == 'proses' ? 'selected' : '' }}>Sedang Diproses</option>
                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="ditolak" {{ $order->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-success">Perbarui Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection