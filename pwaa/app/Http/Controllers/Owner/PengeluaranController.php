<?php

namespace App\Http\Controllers\Owner;

use Session;
use Illuminate\Http\Request;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    protected $pengeluaran;

    public function __construct(pengeluaran $pengeluaran)
    {
        $this->pengeluaran = $pengeluaran;
    }

    public function index()
    {
        $pengeluaran = [
            'pengeluaran' => $this->pengeluaran->allData(),
        ];
            return view('owner.datapengeluaran', $pengeluaran);

    }

    public function add()
    {
        return view('owner.datapengeluaran_add');
    }

    public function insert(Request $request)
    {
        $request->validate([
            'nama_pengeluaran' => 'required',
            'deskripsi_pengeluaran' => 'required',
            'kategori_pengeluaran' => 'required',
            'jumlah_pengeluaran' => 'required',
            'tanggal' => 'required',
        ],[
            'nama_pengeluaran.required' => 'nama pengeluaran harus diisi!',
            'deskripsi_pengeluaran.required' => 'estimasi waktu pengeluaran harus diisi!',
            'kategori_pengeluaran' => 'jenis pengeluaran harus diisi!',
            'jumlah_pengeluaran' => 'jumlah pengeluaran harus diisi!',
            'tanggal' => 'tanggal harus diisi!',
            
        ]);

        $user = Auth::user();

        $data = [
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'deskripsi_pengeluaran' => $request->deskripsi_pengeluaran,
            'kategori_pengeluaran' => $request->kategori_pengeluaran,
            'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
            'tanggal' => $request->tanggal,
            'user_id' => $user->id,
        ];

        $this->pengeluaran->addData($data);
        Alert::success('Berhasil!', 'Data pengeluaran berhasil ditambahkan!');
        return redirect('/datapengeluaran');
    }

    public function destroy($pengeluaran_id)
    {
        // Setelah itu, hapus entri dari tabel pengeluaran
        DB::table('pengeluaran')->where('pengeluaran_id', $pengeluaran_id)->delete();
    
        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data pengeluaran
        Alert::success('Berhasil!', 'Data pengeluaran berhasil dihapus!');
        return redirect('/datapengeluaran');
    }

    public function edit($id)
    {
        $pengeluaran = DB::table('pengeluaran')->where('pengeluaran_id', $id)->first();
        return view('owner.datapengeluaran_edit', ['pengeluaran' => $pengeluaran]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pengeluaran' => 'required',
            'deskripsi_pengeluaran' => 'required',
            'kategori_pengeluaran' => 'required',
            'jumlah_pengeluaran' => 'required',
            'tanggal' => 'required',
        ]);

        $data = [
            'nama_pengeluaran' => $request->nama_pengeluaran,
            'deskripsi_pengeluaran' => $request->deskripsi_pengeluaran,
            'kategori_pengeluaran' => $request->kategori_pengeluaran,
            'jumlah_pengeluaran' => $request->jumlah_pengeluaran,
            'tanggal' => $request->tanggal,
        ];

        DB::table('pengeluaran')->where('pengeluaran_id', $id)->update($data);
        Alert::success('Berhasil!', 'Data pengeluaran berhasil diupdate!');
        return redirect('/datapengeluaran');
    }
}
