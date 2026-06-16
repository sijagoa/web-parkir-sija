<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'location_name',
        'max_motorcycle',
        'max_car',
        'max_other',
    ];

    /**
     * Get the transactions for this location.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_lokasi');
    }
}
