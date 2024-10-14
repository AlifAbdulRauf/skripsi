<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $table = 'dokter';
    protected $primaryKey = 'dokter_id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'alamat',
        'nomor_hp',
        'lokasi_id',
        'user_id,'

    ];

    public function detailJadwal()
    {
        return $this->hasMany(DetailJadwal::class, 'dokter_id');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'dokter_id');
    }

    public function rekamMedik()
    {
        return $this->hasMany(RekamMedik::class, 'dokter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
