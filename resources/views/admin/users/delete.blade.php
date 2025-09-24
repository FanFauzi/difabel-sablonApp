@extends('layouts.admin')

@section('title', 'Hapus User')

@section('content')
<div class="mb-4">
    <h2>Hapus User</h2>
</div>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">
            <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus User
        </h5>
    </div>
    <div class="card-body">
        <div class="alert alert-warning">
            <strong>Perhatian!</strong> Tindakan ini tidak dapat dibatalkan. User yang dihapus akan kehilangan akses ke sistem.
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5>Detail User:</h5>
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Nama:</strong></td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role:</strong></td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'info' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Tanggal Dibuat:</strong></td>
                        <td>{{ $user->created_at->format('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Ya, Hapus User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection