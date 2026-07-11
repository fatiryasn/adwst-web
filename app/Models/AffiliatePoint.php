<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AffiliatePoint extends Model
{
    use HasFactory, HasUuids;

    //UUID config
    public $incrementing = false;
    protected $keyType = 'string';
    //table name
    protected $table = 'affiliate_points';
    //disable updated_at
    const UPDATED_AT = null;

    //fillable
    protected $fillable = [
        'affiliate_id',
        'ticket_id',
        'points',
        'description',
    ];

    //attribute casting
    protected function casts(): array
    {
        return [
            'points' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    //RELATION: affiliate
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }
    //RELATION: ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
