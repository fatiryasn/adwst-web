<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destination extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    //UUID config
    public $incrementing = false;
    protected $keyType = 'string';
    //table name
    protected $table = 'destinations';

    //fillable
    protected $fillable = [
        'name',
        'slug',
        'description',
        'ticket_price',
        'address',
        'latitude',
        'longitude',
        'thumbnail',
        'status',
    ];

    //attribute casting
    protected function casts(): array
    {
        return [
            'ticket_price' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    //RELATION: tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    //CONSTANTS
    public const STATUS_ACTIVE = 'active';
    public const STATUS_INACTIVE = 'inactive';
}
