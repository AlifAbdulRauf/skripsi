<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DataDokter extends Model
{
    protected $table = 'dokter';

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'id');
    }

    public function allData()
    {
        return DB::table('dokter')->get();
    }

    public function addData($data)
    {
        DB::table('dokter')->insert($data);
    }
}
