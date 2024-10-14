<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Perawatan extends Model
{
    use HasFactory;

    protected $table = 'perawatan';
    protected $primaryKey = 'perawatan_id';
    public $timestamps = false;

    protected $fillable = [
        'jenis_Perawatan',
        'harga',
        'estimasi_waktu_perawatan'
    ];

    public function reservasi()
    {
        return $this->belongsToMany(Reservasi::class, 'perawatan_reservasi', 'perawatan_id', 'reservasi_id');
    }

    public function allData()
    {
        return DB::table('perawatan')->get();
    }
    



    public function addData($data)
    {
        DB::table('perawatan')->insert($data);
    }
}
