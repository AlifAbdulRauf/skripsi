<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluaran';
    protected $primaryKey = 'pengeluaran_id';
    public $timestamps = false;

    protected $fillable = [
        'admin_id',
        'deskripsi_pengeluaran',
        'nama_pengeluaran',
        'kategori_pengeluaran',
        'jumlah_pengeluaran',
        'tanggal'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function allData()
    {
        return DB::table('pengeluaran')->get();
    }

    public function addData($data)
    {
        DB::table('pengeluaran')->insert($data);
    }
}
