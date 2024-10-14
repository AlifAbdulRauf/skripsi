<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\Models\Dokter;
use App\Models\DetailJadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class JadwalController extends Controller
{
    protected $datadokter;

    public function __construct(DetailJadwal $datadokter)
    {
        $this->datadokter = $datadokter;
    }

    public function index($id)
    {
        $dokter = Dokter::where("dokter_id", $id)->first();
        $dokterjadwal = DetailJadwal::with(['dokter'])->where("dokter_id", $id)->first();
        $datajadwal = DB::table('detail_jadwal')->where('dokter_id', $id)->get();

        $user = Auth::user();
        if ($user->role === 'owner') {
            return view('owner.datadokterjadwal', ['datajadwal' => $datajadwal, 'datadokter' => $dokter]);
        }
        return view('admin.datadokterjadwal', ['datajadwal' => $datajadwal, 'datadokter' => $dokter]);
        // $datadokter = [
        //     'datadokter' => $this->datadokter->allData(),
        // ];

        // return view('admin.datadokter', $datadokter);
    }

    public function add($id)
    {
        return view('admin.datadokterjadwal_add', ['id' => $id]);
    }

    public function insert(Request $request, $id)
    {
        // Validasi input form
        $request->validate([
            'hari' => 'required',
            'sesi' => 'required',
        ], [
            'hari.required' => 'Hari harus diisi!',
            'sesi.required' => 'Sesi harus diisi!',
        ]);

        // Menyiapkan data yang akan disimpan
        $data = [
            'hari' => $request->hari,
            'sesi' => $request->sesi,
            'dokter_id' => $id,
        ];

        // Menyimpan data menggunakan model (asumsi ada method addData di model yang digunakan)
        $this->datadokter->addData($data);

        // Menampilkan pesan sukses menggunakan SweetAlert (asumsi Alert diimport dengan benar)
        Alert::success('Berhasil!', 'Data jadwal berhasil ditambahkan!');

        // Redirect ke halaman yang diinginkan dengan sintaks kurung keriting ganda
        return redirect("/datadokter/jadwal/{$id}");
    }

    public function destroy($jadwal_id)
    {
        // Hapus entri terkait di tabel detail_jadwal terlebih dahulu
        DB::table('detail_jadwal')->where('jadwal_id', $jadwal_id)->delete();

    
        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data dokter
        Alert::success('Berhasil!', 'Data jadwal dokter berhasil dihapus!'); 
        $id_dokter = DB::table('detail_jadwal')->where('jadwal_id', $jadwal_id)->value('dokter_id');
        if($id_dokter==NULL){
            return redirect("/datadokter");
        }
        else{
            return redirect("/datadokter/jadwal/{$id_dokter}");
        }
        
    }

    public function edit($id)
    {
        $datadokter = DB::table('detail_jadwal')->where('jadwal_id', $id)->first();
        return view('admin.datadokterjadwal_edit', ['datadokter' => $datadokter]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hari' => 'required',
            'sesi' => 'required',
        ], [
            'hari.required' => 'Hari harus diisi!',
            'sesi.required' => 'Sesi harus diisi!',
        ]);

        $data = [
            'hari' => $request->hari,
            'sesi' => $request->sesi,
        ];

        DB::table('detail_jadwal')->where('jadwal_id', $id)->update($data);
        $id_dokter = DB::table('detail_jadwal')->where('jadwal_id', $id)->value('dokter_id');
        Alert::success('Berhasil!', 'Data Jadwal berhasil diupdate!');
        return redirect("/datadokter/jadwal/{$id_dokter}");
    }
}
