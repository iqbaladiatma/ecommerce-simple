<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'shipping_address',
        'billing_address',
        'shipping_method',
        'shipping_cost',
        'notes',
        'shipping_city',
        'shipping_postal_code',
        'shipping_phone',
        'estimated_delivery',
        'is_paid',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'estimated_delivery' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }

    public function markAsPaid(): void
    {
        $this->update([
            'status' => 'paid',
            'is_paid' => true,
        ]);
    }

    public function markAsShipped(): void
    {
        $this->update([
            'status' => 'shipped',
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
        ]);
    }

    public function sendInvoice(): void
    {
        // TODO: Implement invoice sending logic
        // This could be implemented using Laravel's notification system
        // to send an email with the invoice PDF
    }
}
