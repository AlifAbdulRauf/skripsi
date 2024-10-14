<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RekamMedik extends Model
{
    use HasFactory;

    protected $table = 'rekam_medik';
    protected $primaryKey = 'rekam_id';
    public $timestamps = false;

    protected $fillable = [
        'reservasi_id',
        'dokter_id',
        'perawatan_id',
        'golongan_darah',
        'tekanan_darah',
        'penyakit_jantung',
        'diabetes',
        'hepatitis',
        'penyakit_lainnya',
        'alergi_makanan',
        'alergi_obat',
        'keluhan',
        'gigi',
        'biaya'
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id', 'reservasi_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function allData()
    {
        return DB::table('rekam_medik')->get();
    }

    public function addData($data)
    {
        DB::table('rekam_medik')->insert($data);
    }
}
