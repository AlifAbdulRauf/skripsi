<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PerawatanReservasi extends Model
{
    use HasFactory;

    protected $table = 'perawatan_reservasi';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'perawatan_id',
        'reservasi_id',
        'harga',
        'estimasi_waktu_perawatan'
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'reservasi_id', 'reservasi_id');
    }

    public function perawatan()
    {
        return $this->belongsTo(Perawatan::class, 'perawatan_id', 'perawatan_id');
    }

    public function allData()
    {
        return DB::table('perawatan_reservasi')->get();
    }
}
