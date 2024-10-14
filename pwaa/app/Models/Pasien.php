<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $primaryKey = 'pasien_id';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'pekerjaan',
        'alamat',
        'no_Telp',
        'user_id'
    ];

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }

    public function allData()
    {
        return DB::table('pasien')->get();
    }

    public function addData($data)
    {
        DB::table('pasien')->insert($data);
    }

    public function pasien()
    {
        return $this->hasMany(Reservasi::class, 'id', 'id');
    }
}
