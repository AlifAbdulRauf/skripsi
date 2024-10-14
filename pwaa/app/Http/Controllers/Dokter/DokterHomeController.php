<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\DetailJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Izin;

class DokterHomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userid = $user->id; 

        // $jadwal = DetailJadwal::with('dokter')
        // ->where('user_id', $userid)
        // ->get();

        $jadwalid = DB::table('detail_jadwal')
        ->join('dokter', 'detail_jadwal.dokter_id', '=', 'dokter.dokter_id')
        ->where('dokter.user_id', $userid)->get();

        // Determine the user role and return the appropriate view
     
        return view('dokter.home', compact("jadwalid"));
        

    }

}
