<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'product_id',
        'custom_product_id',
        'quantity',
        'size',
        'color',
        'design_description',
        'status',
        'total_price',
        'notes',
        'design_file_depan',
        'design_file_belakang',
        'design_file_samping',
        'design_file',
        'design_size',
        'design_cost',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'design_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(CustomProduct::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];
    }

    public function getStatusColor()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'proses' => 'info',
            'selesai' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            'pending' => 'bg-warning text-dark',
            'proses' => 'bg-info text-dark',
            'selesai' => 'bg-success text-white',
            'ditolak' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }
}
