<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cottage extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    //UUID config
    public $incrementing = false;
    protected $keyType = 'string';
    //table name
    protected $table = 'cottages';

    protected $fillable = ['destination_id', 'name', 'description', 'price'];

    //RELATION: destination
    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    //RELATION: ticket
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
