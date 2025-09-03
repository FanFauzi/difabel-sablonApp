@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Profil Admin</h2>
            <p class="text-muted mb-0">Kelola informasi akun dan keamanan</p>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-sign-out-alt me-2"></i>Logout
            </button>
        </form>
    </div>

    <div class="row">
        <!-- Edit Profile Section -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>Edit Profil
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-id-card me-2"></i>Informasi Pribadi
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold">
                                        <i class="fas fa-user text-primary me-1"></i>Nama Lengkap
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                           placeholder="Masukkan nama lengkap" required>
                                    <div class="form-text">Nama yang akan ditampilkan di sistem</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope text-primary me-1"></i>Alamat Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                           placeholder="nama@email.com" required>
                                    <div class="form-text">Email untuk login dan notifikasi</div>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-shield-alt me-2"></i>Keamanan Akun
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-lock text-primary me-1"></i>Password Baru
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                               id="password" name="password" placeholder="Minimal 8 karakter">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="passwordIcon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">
                                        <i class="fas fa-lock text-primary me-1"></i>Konfirmasi Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-lg"
                                               id="password_confirmation" name="password_confirmation"
                                               placeholder="Ulangi password baru">
                                        <button class="btn btn-outline-secondary" type="button" onclick="toggleConfirmPassword()">
                                            <i class="fas fa-eye" id="confirmPasswordIcon"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Harus sama dengan password baru</div>
                                </div>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div id="passwordStrength" class="mb-3" style="display: none;">
                                <div class="progress" style="height: 8px;">
                                    <div id="strengthBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="strengthText" class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Perubahan akan tersimpan secara permanen
                            </div>
                            <div>
                                <button type="button" class="btn btn-outline-secondary me-2" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Account Information & Quick Actions -->
        <div class="col-lg-4">
            <!-- Account Info Card -->
            <div class="card shadow-lg border-0 mb-4" style="border-radius: 15px;">
                <div class="card-header bg-gradient-info text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h6 class="mb-0">
                        <i class="fas fa-user-circle me-2"></i>Informasi Akun
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                        <p class="text-muted mb-2">{{ auth()->user()->email }}</p>
                        <span class="badge bg-danger fs-6 px-3 py-2">
                            <i class="fas fa-crown me-1"></i>{{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>

                    <hr>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">
                                <i class="fas fa-calendar-plus me-1"></i>Tanggal Bergabung
                            </span>
                        </div>
                        <div class="fw-bold">{{ auth()->user()->created_at->format('d F Y') }}</div>
                        <small class="text-muted">{{ auth()->user()->created_at->format('H:i') }}</small>
                    </div>

                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small">
                                <i class="fas fa-clock me-1"></i>Terakhir Aktif
                            </span>
                        </div>
                        <div class="fw-bold">{{ auth()->user()->updated_at->format('d F Y') }}</div>
                        <small class="text-muted">{{ auth()->user()->updated_at->format('H:i') }}</small>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-success text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <h6 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>Aksi Cepat
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>

                        <button class="btn btn-outline-info" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh Halaman
                        </button>

                        <hr class="my-3">

                        <div class="alert alert-warning small mb-0">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <strong>Peringatan:</strong> Logout akan mengakhiri sesi Anda
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password visibility toggle
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('passwordIcon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        passwordInput.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

function toggleConfirmPassword() {
    const confirmInput = document.getElementById('password_confirmation');
    const icon = document.getElementById('confirmPasswordIcon');

    if (confirmInput.type === 'password') {
        confirmInput.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        confirmInput.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    const strengthDiv = document.getElementById('passwordStrength');

    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        return;
    }

    strengthDiv.style.display = 'block';

    let strength = 0;
    let feedback = [];

    if (password.length >= 8) strength++;
    else feedback.push('Minimal 8 karakter');

    if (/[a-z]/.test(password)) strength++;
    else feedback.push('Huruf kecil');

    if (/[A-Z]/.test(password)) strength++;
    else feedback.push('Huruf besar');

    if (/[0-9]/.test(password)) strength++;
    else feedback.push('Angka');

    if (/[^A-Za-z0-9]/.test(password)) strength++;
    else feedback.push('Karakter khusus');

    const percentage = (strength / 5) * 100;
    strengthBar.style.width = percentage + '%';

    if (strength <= 2) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Lemah: ' + feedback.join(', ');
    } else if (strength <= 3) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Sedang: ' + (feedback.length > 0 ? feedback.join(', ') : 'Cukup baik');
    } else if (strength <= 4) {
        strengthBar.className = 'progress-bar bg-info';
        strengthText.textContent = 'Kuat: ' + (feedback.length > 0 ? 'Tambahkan ' + feedback.join(', ') : 'Sangat baik');
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Sangat Kuat: Password ideal';
    }
});

// Form validation
document.getElementById('profileForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;

    if (password && password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        document.getElementById('password_confirmation').focus();
        return false;
    }

    if (password && password.length < 8) {
        e.preventDefault();
        alert('Password minimal 8 karakter!');
        document.getElementById('password').focus();
        return false;
    }
});

// Reset form function
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset semua perubahan?')) {
        document.getElementById('profileForm').reset();
        document.getElementById('passwordStrength').style.display = 'none';
    }
}
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-1px);
}

.input-group .btn {
    border-color: #ced4da;
}

.input-group .btn:hover {
    background-color: #f8f9fa;
}
</style>
@endsection