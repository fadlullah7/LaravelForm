<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta extends Model
{
    protected $table = 'peserta';

    protected $fillable = [
        'nama',
        'tempatLahir',
        'tanggalLahir',
        'agama',
        'alamat',
        'telepon',
        'jk',
        'foto',
        'provinsi_id',
        'kabkot_id',
    ];

    protected $casts = [
        'tanggalLahir' => 'date',
    ];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kabkot()
    {
        return $this->belongsTo(Kabkot::class, 'kabkot_id');
    }

    public function getJenisKelaminLabelAttribute(): string
    {
        return match($this->jk) {
            0 => 'Pria',
            1 => 'Wanita',
            default => '-',
        };
    }


}
