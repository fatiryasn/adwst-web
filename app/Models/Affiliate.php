<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Affiliate extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    //UUID config
    public $incrementing = false;
    protected $keyType = 'string';
    //table name
    protected $table = 'affiliates';

    //fillable
    protected $fillable = [
        'code',
        'full_name',
        'email',
        'phone_number',
        'total_points',
        'promotion_channels',
        'join_reason',
    ];

    //attribute casting
    protected function casts(): array
    {
        return [
            'total_points' => 'integer',
        ];
    }

    //RELATION: tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    //RELATION: affiliate points
    public function points()
    {
        return $this->hasMany(AffiliatePoint::class);
    }

    //HELPER
    public function addPoints(int $points): void
    {
        $this->increment('total_points', $points);
    }
    public function deductPoints(int $points): void
    {
        $this->decrement('total_points', $points);
    }
}
