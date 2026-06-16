<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'id_lokasi',
        'no_tiket',
        'no_polisi',
        'id_jenis',
        'masuk',
        'keluar',
        'perjam_pertama',
        'perjam_berikutnya',
        'max_perhari',
        'total_jam',
        'total_hari',
        'total_bayar',
    ];

    protected $casts = [
        'masuk' => 'datetime',
        'keluar' => 'datetime',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class, 'id_lokasi');
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class, 'id_jenis');
    }
}
