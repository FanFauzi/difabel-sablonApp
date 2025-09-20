@extends('layouts.user')

@section('title', 'Edit Profil')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Edit Profil</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <!-- Profile Information -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Informasi Profil</h5>
            </div>
            <div class="card-body text-center">
                <div class="mb-3">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-2x"></i>
                    </div>
                </div>
                <h5 class="mb-1">{{ $user->name }}</h5>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>

                <hr>
                <div class="small text-muted">
                    <p class="mb-1"><strong>Bergabung:</strong> {{ $user->created_at->format('d M Y') }}</p>
                    <p class="mb-0"><strong>Terakhir Update:</strong> {{ $user->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Profil & Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Edit Nama -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-user me-2"></i>Edit Nama
                        </h6>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name', $user->name) }}" required>
                                <div class="form-text">Masukkan nama lengkap Anda</div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit Email -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-envelope me-2"></i>Edit Email
                        </h6>
                        <div class="row">
                            <div class="col-md-8">
                                <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="{{ old('email', $user->email) }}" required>
                                <div class="form-text">Email akan digunakan untuk login dan notifikasi</div>
                            </div>
                        </div>
                    </div>

                    <!-- Ubah Password -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-lock me-2"></i>Ubah Password
                        </h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Kosongkan field password jika tidak ingin mengubah password.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="current_password" class="form-label">Password Saat Ini</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                                <div class="form-text">Diperlukan untuk mengubah password</div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password Baru</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Minimal 8 karakter</div>
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                <div class="form-text">Ulangi password baru</div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Account Statistics -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Statistik Akun</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="text-primary mb-2">
                                <i class="fas fa-shopping-cart fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->orders()->count() }}</h4>
                            <small class="text-muted">Total Pesanan</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="text-success mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->orders()->where('status', 'selesai')->count() }}</h4>
                            <small class="text-muted">Pesanan Selesai</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="text-warning mb-2">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->orders()->where('status', 'pending')->count() }}</h4>
                            <small class="text-muted">Menunggu Konfirmasi</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="p-3 bg-light rounded">
                            <div class="text-info mb-2">
                                <i class="fas fa-cog fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->orders()->where('status', 'proses')->count() }}</h4>
                            <small class="text-muted">Sedang Diproses</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;

    if (password !== confirmPassword) {
        this.setCustomValidity('Password tidak cocok');
    } else {
        this.setCustomValidity('');
    }
});

// Show/hide password fields based on current password input
document.getElementById('current_password').addEventListener('input', function() {
    const passwordFields = document.querySelectorAll('#password, #password_confirmation');
    const hasCurrentPassword = this.value.length > 0;

    passwordFields.forEach(field => {
        field.required = hasCurrentPassword;
        if (!hasCurrentPassword) {
            field.value = '';
        }
    });
});
</script>
@endsection