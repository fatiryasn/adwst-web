<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    //UUID config
    public $incrementing = false;
    protected $keyType = 'string';
    //table name
    protected $table = 'tickets';

    //fillable
    protected $fillable = [
        'code',
        'destination_id',
        'affiliate_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'visit_date',
        'departure_date',
        'referral_source',
        'ticket_price',
        'payment_status',
        'ticket_status',
        'notes',

        'payment_verified_at',
        'payment_verified_by',
        'checked_in_at'
    ];

    //attribute casting
    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'departure_date' => 'date',
            'payment_verified_at' => 'datetime',
            'checked_in_at' => 'datetime',
            'ticket_price' => 'decimal:2',
        ];
    }

    //RELATIONS: destination
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }
    //RELATIONS: affiliate
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    //RELATIONS: payment verified by
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    //RELATIONS: affiliate points
    public function affiliatePoints()
    {
        return $this->hasMany(AffiliatePoint::class);
    }

    //CONSTANTS
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';
    public const PAYMENT_FAILED = 'failed';
    public const PAYMENT_REFUNDED = 'refunded';

    public const STATUS_ACTIVE = 'active';
    public const STATUS_CHECKED_IN = 'checked_in';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_CANCELLED = 'cancelled';
}
