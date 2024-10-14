<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\Models\Dokter;
use App\Models\Perawatan;
use App\Models\Reservasi;
use App\Models\RekamMedik;
use Illuminate\Http\Request;
use App\Models\PerawatanReservasi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class RekamController extends Controller
{
    protected $rekam_medik;

    public function __construct(RekamMedik $rekam_medik)
    {
        $this->rekam_medik = $rekam_medik;
    }

    public function index($id)
    {
        // Cari semua pasien terkait dengan dokter_id melalui rekam_medik
        $rekammedik_id = DB::table('reservasi_rekam_medik')
        ->join("pasien", "reservasi_rekam_medik.pasien_id", "=", "pasien.pasien_id")
        ->where('reservasi_rekam_medik.pasien_id', $id)
        ->orderByDesc('reservasi_rekam_medik.tanggal')
        ->get();

        $reservasiIds = DB::table('reservasi_rekam_medik')->where('reservasi_id', $id)->pluck('pasien_id');

        $dokter = Dokter::all();
        $reservasi_pasien = DB::table("reservasi_rekam_medik")
        ->join("pasien", "reservasi_rekam_medik.pasien_id", "=", "pasien.pasien_id")
        ->where("reservasi_id", $id)
        ->first();

        $perawatan_reservasi = PerawatanReservasi::with(['reservasi','perawatan'])
        ->where('reservasi_id', $id )
        ->get();

        $pasien = DB::table('pasien')
        ->join('reservasi_rekam_medik', 'pasien.pasien_id', '=', 'reservasi_rekam_medik.pasien_id') // Join pasien dengan reservasi
        ->join('perawatan_reservasi', 'reservasi_rekam_medik.reservasi_id', '=', 'perawatan_reservasi.reservasi_id') // Join reservasi dengan perawatan_reservasi
        ->join('perawatan', 'perawatan_reservasi.perawatan_id', '=', 'perawatan.perawatan_id') // Join reservasi dengan perawatan_reservasi
        ->where('pasien.pasien_id', $id) // Filter berdasarkan id reservasi
        ->select('pasien.*', 'reservasi_rekam_medik.*', 'perawatan_reservasi.*', 'perawatan.*') 
        ->get();
        

        // $rekam_medik = DB::table('rekam_medik')
        // ->whereIn('rekam_medik.reservasi_id', $reservasiIds)
        // ->first();

        // $rekam_medik_perawatan = DB::table('rekam_medik')
        // ->join('perawatan', 'rekam_medik.perawatan_id', '=', 'perawatan.perawatan_id')
        // ->whereIn('rekam_medik.reservasi_id', $reservasiIds)
        // ->first();

        $reservasi_rekam_medik = Reservasi::with('perawatan')->where('reservasi_id', $rekammedik_id);
        $user = Auth::user();
        if ($user->role === 'owner') {
            return view('owner.rekammedik', compact('dokter','reservasi_pasien' ,'reservasi_rekam_medik','perawatan_reservasi', 'rekammedik_id', 'pasien'));
        } 
        elseif($user->role === 'dokter' || $user->role === 'Dokter') {
            return view('dokter.rekammedik', compact('dokter','reservasi_pasien' ,'reservasi_rekam_medik','perawatan_reservasi', 'rekammedik_id', 'pasien'));
        }
        else{
            return view('admin.rekammedik', compact('dokter','reservasi_pasien' ,'reservasi_rekam_medik','perawatan_reservasi', 'rekammedik_id', 'pasien'));
        }
        // $rekam_medik = [
        //     'rekam_medik' => $this->rekam_medik->allData(),
        // ];

        // return view('admin.rekam_medik', $rekam_medik);
    }

    public function add($id)
    {
        $user = Auth::user();
        if($user->role === 'dokter' || $user->role === 'Dokter') {
            return view('dokter.rekam_medik_add', ['id' => $id]);
        }
        else
        {
        return view('admin.rekam_medik_add', ['id' => $id]);
        }
        
    }

    public function edit($id)
    {

        $perawatan = DB::table('perawatan')->get();

        $reservasi = DB::table('reservasi_rekam_medik')->where('reservasi_id', $id)->first();
        $perawatan_reservasi = DB::table('perawatan_reservasi')
        ->join('perawatan', 'perawatan_reservasi.perawatan_id', '=', 'perawatan.perawatan_id')
        ->where('reservasi_id', $id)
        ->get();

        $idperawatan_reservasi = $perawatan_reservasi->pluck('perawatan_id')->toArray();
        $user = Auth::user();

        
        if($user->role === 'dokter' || $user->role === 'Dokter') {
            return view('dokter.rekammedik_edit', ['reservasi' => $reservasi], compact('perawatan','perawatan_reservasi','idperawatan_reservasi'));
        }
        else
        {
            return view('admin.rekammedik_edit', ['reservasi' => $reservasi], compact('perawatan','perawatan_reservasi','idperawatan_reservasi'));
        }
        
    }

    public function update(Request $request, $id)
    {
        // Validasi data yang dikirimkan dari formulir
        $validatedData = $request->validate([
            'golongan_darah' => 'required',
            'tekanan_darah' => 'required',
            'penyakit_jantung' => 'required',
            'diabetes' => 'required',
            'hepatitis' => 'required',
            'penyakit_lainnya' => 'required',
            'alergi_makanan' => 'required',
            'alergi_obat' => 'required',
            'keluhan' => 'required',
            'perawatan_id' => 'required',
            'gigi' => 'required',
            'perawatan_id' => 'required|array|max:2',
            'perawatan_id.*' => 'integer|exists:perawatan,perawatan_id',
            'tindak' => 'required|array|max:2',
        ]);

        // Perbarui data rekam medik dengan data yang dikirimkan dari formulir

        // Mengambil data harga dan estimasi waktu dari tabel perawatan berdasarkan ID
        
        $syncData = [];
        $i = 0;
        foreach ($validatedData['perawatan_id'] as $perawatanId) {
            $perawatan = Perawatan::find($perawatanId);
            $syncData[$perawatanId] = [
                'harga' => $perawatan->harga,
                'estimasi_waktu_perawatan' => $perawatan->estimasi_waktu_perawatan,
                'rencana_tindak_lanjut' => isset($validatedData['tindak'][$i]) ? $validatedData['tindak'][$i] : null, // Ambil nilai 'tindak' 
            ];
                $i = 1;
        }

        $pasien_id = DB::table('reservasi_rekam_medik')
        ->join('pasien','reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
        ->where('reservasi_rekam_medik.reservasi_id', $id)
        ->select('pasien.pasien_id')
        ->first();

        $reservasi = Reservasi::findOrFail($id);
        $reservasi->update($validatedData);
        $reservasi->perawatan()->sync($syncData);

        Alert::success('Berhasil!', 'Data dokter berhasil diupdate!');
        $user = Auth::user();
        if($user->role === 'dokter' || $user->role === 'Dokter') {
            return redirect()->route("dokter.rekampasien",[$pasien_id->pasien_id]);
        }
        else
        {
            return redirect()->route("admin.rekampasien",[$pasien_id->pasien_id]);
        }
        
    }
}
