<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'design_description',
        'design_file',
        'status',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status options
    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];
    }

    // Get status color for UI
    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'proses' => 'info',
            'selesai' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    // Get status badge class
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'bg-warning text-dark',
            'proses' => 'bg-info text-dark',
            'selesai' => 'bg-success text-white',
            'ditolak' => 'bg-danger text-white',
            default => 'bg-secondary text-white',
        };
    }
}
