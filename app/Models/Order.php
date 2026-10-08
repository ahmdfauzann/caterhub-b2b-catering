<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'customer_id',
        'merchant_id',
        'delivery_date',
        'delivery_time',
        'delivery_address',
        'total_amount',
        'tax_amount',
        'delivery_fee',
        'grand_total',
        'status',
        'payment_status',
        'payment_method',
        'payment_receipt',
        'notes',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function merchant()
    {
        return $this->belongsTo(MerchantProfile::class, 'merchant_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'order_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
            'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
            'preparing' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'delivering' => 'bg-purple-100 text-purple-800 border-purple-200',
            'delivered' => 'bg-teal-100 text-teal-800 border-teal-200',
            'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => 'Menunggu Konfirmasi',
            'confirmed' => 'Pesanan Dikonfirmasi',
            'preparing' => 'Sedang Dimasak / Disiapkan',
            'delivering' => 'Dalam Pengiriman',
            'delivered' => 'Sampai di Lokasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }
}
