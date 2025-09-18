@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Log Aktivitas</h2>
            <p class="text-muted mb-0">Pantau aktivitas sistem berdasarkan kategori</p>
        </div>
        <form action="{{ route('admin.activity-logs.clear') }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus semua log aktivitas?')">
                <i class="fas fa-trash me-2"></i>Hapus Semua
            </button>
        </form>
    </div>

    <!-- Activity Categories -->
    <div class="row">
        <!-- Catatan User Login -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-sign-in-alt me-2"></i>Catatan User Login
                    </h6>
                </div>
                <div class="card-body">
                    @if($loginActivities->count() > 0)
                        <div class="timeline">
                            @foreach($loginActivities->take(5) as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $activity->user->name ?? 'Unknown User' }}</h6>
                                                <p class="text-muted small mb-0">{{ $activity->description }}</p>
                                            </div>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($loginActivities->count() > 5)
                            <div class="text-center mt-3">
                                <small class="text-muted">Dan {{ $loginActivities->count() - 5 }} aktivitas lainnya...</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-sign-in-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada catatan login</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Catatan User Membuat Pesanan -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-gradient-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-shopping-cart me-2"></i>Catatan User Membuat Pesanan
                    </h6>
                </div>
                <div class="card-body">
                    @if($orderActivities->count() > 0)
                        <div class="timeline">
                            @foreach($orderActivities->take(5) as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-info"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $activity->user->name ?? 'Unknown User' }}</h6>
                                                <p class="text-muted small mb-0">{{ $activity->description }}</p>
                                            </div>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($orderActivities->count() > 5)
                            <div class="text-center mt-3">
                                <small class="text-muted">Dan {{ $orderActivities->count() - 5 }} aktivitas lainnya...</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada catatan pesanan</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Catatan Admin Mengubah Data -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-gradient-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-user-edit me-2"></i>Catatan Admin Mengubah Data
                    </h6>
                </div>
                <div class="card-body">
                    @if($adminActivities->count() > 0)
                        <div class="timeline">
                            @foreach($adminActivities->take(5) as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $activity->user->name ?? 'Unknown Admin' }}</h6>
                                                <p class="text-muted small mb-0">{{ $activity->description }}</p>
                                                @if($activity->model_type)
                                                    <small class="badge bg-secondary">{{ basename($activity->model_type) }}</small>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($adminActivities->count() > 5)
                            <div class="text-center mt-3">
                                <small class="text-muted">Dan {{ $adminActivities->count() - 5 }} aktivitas lainnya...</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-edit fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Belum ada catatan perubahan data</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Activity Log Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Log Aktivitas Lengkap
                    </h6>
                    <div class="d-flex">
                        <input type="text" class="form-control form-control-sm me-2" id="searchInput" placeholder="Cari aktivitas...">
                        <select class="form-select form-select-sm" id="categoryFilter">
                            <option value="">Semua Kategori</option>
                            <option value="login">Login</option>
                            <option value="order">Pesanan</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="activityTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th>User</th>
                                    <th>Aksi</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activityLogs as $log)
                                    <tr>
                                        <td>
                                            @if($log->action === 'login')
                                                <span class="badge bg-success">
                                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                                </span>
                                            @elseif($log->action === 'created' && $log->model_type === 'App\Models\Order')
                                                <span class="badge bg-info">
                                                    <i class="fas fa-shopping-cart me-1"></i>Pesanan
                                                </span>
                                            @elseif($log->user && $log->user->role === 'admin')
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-user-edit me-1"></i>Admin
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-info-circle me-1"></i>Lainnya
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-{{ $log->user->role === 'admin' ? 'danger' : 'primary' }} text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                                        <i class="fas fa-{{ $log->user->role === 'admin' ? 'user-shield' : 'user' }}"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $log->user->name }}</div>
                                                        <small class="text-muted">{{ ucfirst($log->user->role) }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">System</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ ucfirst($log->action) }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $log->description }}</div>
                                            @if($log->model_type)
                                                <small class="text-muted">{{ basename($log->model_type) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div>{{ $log->created_at->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $log->created_at->format('H:i:s') }}</small>
                                        </td>
                                        <td>
                                            <form action="{{ route('activity-logs.destroy', $log->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus log ini?')" title="Hapus Log">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">Tidak ada log aktivitas ditemukan.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const tableRows = document.querySelectorAll('#activityTable tbody tr');

    tableRows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Category filter functionality
document.getElementById('categoryFilter').addEventListener('change', function() {
    const selectedCategory = this.value;
    const tableRows = document.querySelectorAll('#activityTable tbody tr');

    tableRows.forEach(row => {
        if (!selectedCategory) {
            row.style.display = '';
            return;
        }

        const categoryBadge = row.querySelector('.badge');
        const categoryText = categoryBadge ? categoryBadge.textContent.toLowerCase() : '';
        const shouldShow = (selectedCategory === 'login' && categoryText.includes('login')) ||
                          (selectedCategory === 'order' && categoryText.includes('pesanan')) ||
                          (selectedCategory === 'admin' && categoryText.includes('admin'));

        row.style.display = shouldShow ? '' : 'none';
    });
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.bg-gradient-success {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}
.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin-left: 10px;
}

.font-weight-bold {
    font-weight: 700 !important;
}
</style>
@endsection