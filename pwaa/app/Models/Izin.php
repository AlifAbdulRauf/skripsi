<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izin_dokter';
    protected $primaryKey = 'izin_id';
    public $timestamps = false;

    protected $fillable = [
        'dokter_id',
        'tanggal_awal',
        'tanggal_akhir',
        'alasan',
        'status',
    ];

    public function allData()
    {
        return DB::table('izin_dokter')->get();
    }   

    public function addData($data)
    {
        DB::table('izin_dokter')->insert($data);
    }

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

}
