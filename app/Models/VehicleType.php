<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    protected $fillable = [
        'jenis',
        'perjam_pertama',
        'perjam_berikutnya',
        'max_perhari',
    ];

    /**
     * Get the transactions for this vehicle type.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_jenis');
    }
}
