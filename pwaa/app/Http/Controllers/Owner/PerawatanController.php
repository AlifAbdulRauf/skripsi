<?php

namespace App\Http\Controllers\Owner;

use Session;
use Illuminate\Http\Request;
use App\Models\Perawatan;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PerawatanController extends Controller
{
    protected $perawatan;

    public function __construct(Perawatan $perawatan)
    {
        $this->perawatan = $perawatan;
    }

    public function index()
    {
        $perawatan = [
            'perawatan' => $this->perawatan->allData(),
        ];
            return view('owner.datalayanan', $perawatan);

    }

    public function add()
    {
        return view('owner.datalayanan_add');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'jenis_Perawatan' => 'required',
            'estimasi_waktu_perawatan' => 'required',
            'harga' => 'required',
        ],[
            'jenis_Perawatan.required' => 'jenis perawatan harus diisi!',
            'estimasi_waktu_perawatan.required' => 'estimasi waktu perawatan harus diisi!',
            'harga.required' => 'harga harus diisi!',
        ]);

        $data = [
            'jenis_Perawatan' => $request->jenis_Perawatan,
            'estimasi_waktu_perawatan' => $request->estimasi_waktu_perawatan,
            'harga' => $request->harga,
            
        ];

        $this->perawatan->addData($data);
        Alert::success('Berhasil!', 'Data perawatan berhasil ditambahkan!');
        return redirect('/datalayanan');
    }

    public function destroy($layanan_id)
    {
        // Setelah itu, hapus entri dari tabel perawatan
        DB::table('perawatan')->where('perawatan_id', $layanan_id)->delete();
    
        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data perawatan
        Alert::success('Berhasil!', 'Data perawatan berhasil dihapus!');
        return redirect('/datalayanan');
    }

    public function edit($id)
    {
        $perawatan = DB::table('perawatan')->where('perawatan_id', $id)->first();
        return view('owner.datalayanan_edit', ['perawatan' => $perawatan]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_Perawatan' => 'required',
            'estimasi_waktu_perawatan' => 'required',
            'harga' => 'required',
        ]);

        $data = [
            'jenis_Perawatan' => $request->jenis_Perawatan,
            'estimasi_waktu_perawatan' => $request->estimasi_waktu_perawatan,
            'harga' => $request->harga,
        ];

        DB::table('perawatan')->where('perawatan_id', $id)->update($data);
        Alert::success('Berhasil!', 'Data perawatan berhasil diupdate!');
        return redirect('/datalayanan');
    }
}
