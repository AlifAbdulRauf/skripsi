<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DetailJadwal extends Model
{
    use HasFactory;

    protected $table = 'detail_jadwal';
    protected $primaryKey = 'jadwal_id';
    public $timestamps = false;

    protected $fillable = [
        'dokter_id',
        'hari',
        'sesi'
    ];

    public function dokter()
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function allData()
    {
        return DB::table('detail_jadwal')->get();
    }

    public function addData($data)
    {
        DB::table('detail_jadwal')->insert($data);
    }
}
