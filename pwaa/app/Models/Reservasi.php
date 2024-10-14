<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reservasi extends Model
{
    use HasFactory;

    protected $table = 'reservasi_rekam_medik';
    protected $primaryKey = 'reservasi_id';
    public $timestamps = false;

    protected $fillable = [
        'pasien_id',
        'lokasi_id',
        'dokter_id',
        'admin_id',
        'rekam_medik_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
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
        'draft',
        'status_penginput',
    ];

    public function allData()
    {
        return DB::table('reservasi_rekam_medik')->get();
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function perawatan()
    {
        return $this->belongsToMany(Perawatan::class, 'perawatan_reservasi', 'reservasi_id', 'perawatan_id')
                    ->withPivot('harga', 'estimasi_waktu_perawatan');
    }

    // Metode untuk menghitung total harga perawatan
    public function getTotalHargaPerawatanAttribute()
    {
        return $this->perawatan->sum('pivot.harga');
    }

    public function user()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    public function rekamMedik()
    {
        return $this->hasOne(RekamMedik::class, 'reservasi_id', 'reservasi_id');
    }

    public function perawatan_reservasi()
    {
        return $this->hasMany(PerawatanReservasi::class, 'reservasi_id', 'reservasi_id');
    }


}

