@extends('layouts.admin')

@section('title', 'Tambah User Baru')

@section('content')
<div class="container-fluid">

    <!-- User Creation Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-primary text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>Informasi User Baru
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('users.store') }}" method="POST" id="userForm">
                        @csrf

                        <!-- Personal Information Section -->
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
                                           id="name" name="name" value="{{ old('name') }}"
                                           placeholder="Masukkan nama lengkap" required>
                                    <div class="form-text">Nama akan ditampilkan di profil user</div>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope text-primary me-1"></i>Alamat Email
                                    </label>
                                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}"
                                           placeholder="contoh@email.com" required>
                                    <div class="form-text">Email akan digunakan untuk login</div>
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
                                        <i class="fas fa-lock text-primary me-1"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                               id="password" name="password" placeholder="Minimal 8 karakter" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Gunakan kombinasi huruf, angka, dan simbol</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">
                                        <i class="fas fa-lock text-success me-1"></i>Konfirmasi Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control form-control-lg"
                                               id="password_confirmation" name="password_confirmation"
                                               placeholder="Ulangi password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="form-text">Pastikan password sama dengan di atas</div>
                                </div>
                            </div>

                            <!-- Password Strength Indicator -->
                            <div class="mb-3" id="passwordStrength" style="display: none;">
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" id="strengthBar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small id="strengthText" class="text-muted"></small>
                            </div>
                        </div>

                        <!-- Role & Permissions Section -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user-tag me-2"></i>Role & Akses
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label fw-bold">
                                        <i class="fas fa-crown text-primary me-1"></i>Level Akses
                                    </label>
                                    <select class="form-select form-select-lg @error('role') is-invalid @enderror"
                                            id="role" name="role" required>
                                        <option value="">Pilih Level Akses</option>
                                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>
                                            <i class="fas fa-user me-2"></i>User - Akses Terbatas
                                        </option>
                                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                                            <i class="fas fa-user-shield me-2"></i>Admin - Akses Penuh
                                        </option>
                                    </select>
                                    <div class="form-text">Tentukan tingkat akses user di sistem</div>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="card bg-light border-0" id="roleInfo" style="display: none;">
                                        <div class="card-body p-3">
                                            <h6 class="card-title text-primary mb-2" id="roleTitle"></h6>
                                            <p class="card-text small mb-0" id="roleDescription"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <div>
                                <button type="button" class="btn btn-outline-primary me-2" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Buat User Baru
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Information Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-gradient-info text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Panduan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-shield-alt me-2"></i>Keamanan
                        </h6>
                        <ul class="small text-muted mb-0">
                            <li>Password minimal 8 karakter</li>
                            <li>Gunakan kombinasi huruf dan angka</li>
                            <li>Email harus unik di sistem</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">
                            <i class="fas fa-user-tag me-2"></i>Role User
                        </h6>
                        <div class="small">
                            <strong class="text-success">User:</strong> Akses terbatas ke fitur dasar<br>
                            <strong class="text-danger">Admin:</strong> Akses penuh ke semua fitur
                        </div>
                    </div>

                    <div class="alert alert-info small">
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Tip:</strong> Pastikan informasi yang dimasukkan akurat karena akan mempengaruhi akses user ke sistem.
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card shadow-lg border-0 mt-3" style="border-radius: 15px;">
                <div class="card-header bg-gradient-success text-white" style="border-radius: 15px 15px 0 0 !important; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik User
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-6">
                            <div class="h4 mb-1 text-primary">{{ \App\Models\User::count() }}</div>
                            <small class="text-muted">Total User</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 mb-1 text-success">{{ \App\Models\User::where('role', 'admin')->count() }}</div>
                            <small class="text-muted">Admin</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password toggle functionality
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
    const confirmInput = document.getElementById('password_confirmation');
    const icon = this.querySelector('i');

    if (confirmInput.type === 'password') {
        confirmInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        confirmInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

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

    if (password.length >= 8) strength += 25;
    else feedback.push('Minimal 8 karakter');

    if (/[a-z]/.test(password)) strength += 25;
    else feedback.push('Huruf kecil');

    if (/[A-Z]/.test(password)) strength += 25;
    else feedback.push('Huruf besar');

    if (/[0-9]/.test(password)) strength += 25;
    else feedback.push('Angka');

    strengthBar.style.width = strength + '%';

    if (strength < 25) {
        strengthBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Lemah: ' + feedback.join(', ');
    } else if (strength < 50) {
        strengthBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Sedang: ' + feedback.join(', ');
    } else if (strength < 75) {
        strengthBar.className = 'progress-bar bg-info';
        strengthText.textContent = 'Kuat: Tingkatkan lagi';
    } else {
        strengthBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Sangat Kuat!';
    }
});

// Role information display
document.getElementById('role').addEventListener('change', function() {
    const roleInfo = document.getElementById('roleInfo');
    const roleTitle = document.getElementById('roleTitle');
    const roleDescription = document.getElementById('roleDescription');

    if (this.value === 'admin') {
        roleInfo.style.display = 'block';
        roleTitle.textContent = 'Administrator';
        roleDescription.textContent = 'Akses penuh ke semua fitur sistem, termasuk manajemen user, produk, pesanan, dan laporan.';
    } else if (this.value === 'user') {
        roleInfo.style.display = 'block';
        roleTitle.textContent = 'Regular User';
        roleDescription.textContent = 'Akses terbatas ke fitur dasar sistem seperti melihat produk dan membuat pesanan.';
    } else {
        roleInfo.style.display = 'none';
    }
});

// Form reset function
function resetForm() {
    document.getElementById('userForm').reset();
    document.getElementById('passwordStrength').style.display = 'none';
    document.getElementById('roleInfo').style.display = 'none';
}

// Form validation
document.getElementById('userForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;

    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password dan konfirmasi password tidak cocok!');
        return false;
    }

    if (password.length < 8) {
        e.preventDefault();
        alert('Password minimal 8 karakter!');
        return false;
    }
});
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