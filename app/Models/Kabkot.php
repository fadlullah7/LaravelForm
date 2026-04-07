<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kabkot extends Model
{
    protected $table = 'kabkot';
    protected $fillable = ['provinsi_id', 'nama_kabkot'];

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }
}
