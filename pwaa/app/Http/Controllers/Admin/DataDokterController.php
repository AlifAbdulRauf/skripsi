<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\Models\Lokasi;
use App\Models\DataDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;



class DataDokterController extends Controller
{
    protected $datadokter;

    public function __construct(DataDokter $datadokter)
    {
        $this->datadokter = $datadokter;
    }

    public function index()
    {
        $datadokter = [
            'datadokter' => $this->datadokter->allData(),
        ];
        $user = Auth::user();
        if ($user->role === 'owner') {
            return view('owner.datadokter', $datadokter);
        }

        else{ 

        return view('admin.datadokter', $datadokter);
        }
    }

    public function add()
    {
        $lokasi = Lokasi::all();
        return view('admin.datadokter_add', compact('lokasi'));
    }

    public function insert(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nomor_hp' => 'required',
            'lokasi_id'=> 'required',
        ],[
            'nama.required' => 'Nama harus diisi!',
            'alamat.required' => 'alamat harus diisi!',
            'nomor_hp.required' => 'nomor_hp harus diisi!',
            'lokasi_id.required' => 'lokasi praktek harus diisi'
        ]);

        

        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
            'lokasi_id' => $request->lokasi_id
        ];

        $this->datadokter->addData($data);
        Alert::success('Berhasil!', 'Data dokter berhasil ditambahkan!');
        return redirect('/datadokter');
    }

    public function destroy($dokter_id)
    {
        DB::table('dokter')->where('dokter_id', $dokter_id)->delete();
    
        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data dokter
        Alert::success('Berhasil!', 'Data dokter berhasil dihapus!');
        return redirect('/datadokter');
    }

    public function edit($id)
    {
        $datadokter = DB::table('dokter')->where('dokter_id', $id)->first();
        return view('admin.datadokter_edit', ['datadokter' => $datadokter]);
    }

    public function detail($id)
    {
        $datajadwal = DB::table('detail_jadwal')->where('dokter_id', $id)->get();
        return view('admin.datadokterjadwal', ['datajadwal' => $datajadwal]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'nomor_hp' => 'required',
        ]);

        $data = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'nomor_hp' => $request->nomor_hp,
        ];

        DB::table('dokter')->where('dokter_id', $id)->update($data);
        Alert::success('Berhasil!', 'Data dokter berhasil diupdate!');
        return redirect('/datadokter');
    }
}
