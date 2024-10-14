<?php

namespace App\Http\Controllers\Owner;

use Session;
use Illuminate\Http\Request;
use App\Models\Reservasi;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use App\Models\Perawatan;
use App\Models\PerawatanReservasi;
use Illuminate\Support\Facades\Auth;

class PemasukanController extends Controller
{
    protected $reservasi;

    public function __construct(reservasi $pemasukan)
    {
        $this->reservasi = $pemasukan;
    }

    public function index()
    {
        $pemasukan = Reservasi::with(['pasien', 'dokter', 'perawatan_reservasi.perawatan'])
        ->where('draft', 0)
        ->get();

        $perawatan_reservasi = PerawatanReservasi::with(['reservasi'])
        ->get();

        $perawatan = Perawatan::get();

        return view('owner.datapemasukan', ["pemasukan"=> $pemasukan, "perawatan_reservasi"=> $perawatan_reservasi, "perawatan"=> $perawatan]);
    }

}
