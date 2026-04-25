<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code', 'customer_name', 'customer_phone',
        'customer_email', 'table_number',
        'payment_method', 'payment_status', 'payment_proof',
        'status', 'total_price',
    ];
    protected $casts = ['total_price' => 'decimal:2'];

    public const STATUS_FLOW = [
        'unpaid'      => 'paid',
        'paid'        => 'in_progress',
        'in_progress' => 'all_done',
    ];

    public function canTransitionTo(string $newStatus): bool
    {
        return isset(self::STATUS_FLOW[$this->status])
            && self::STATUS_FLOW[$this->status] === $newStatus;
    }

    public function items(): HasMany { return $this->hasMany(OrderItem::class); }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'unpaid'      => 'Unpaid',
            'paid'        => 'Paid',
            'in_progress' => 'In Progress',
            'all_done'    => 'Completed',
            default       => ucfirst($this->status),
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cashier' => 'Cashier',
            'qris' => 'QRIS',
            default => strtoupper((string) $this->payment_method),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Pending',
            'waiting_verification' => 'Waiting Verification',
            default => str((string) $this->payment_status)->headline()->value(),
        };
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }

        return asset('storage/' . ltrim($this->payment_proof, '/'));
    }
}
