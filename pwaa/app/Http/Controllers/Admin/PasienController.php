<?php

namespace App\Http\Controllers\Admin;

use Session;
use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PasienController extends Controller
{
    protected $pasien;

    public function __construct(Pasien $pasien)
    {
        $this->pasien = $pasien;
    }

    public function index()
    {
        $reservasi = DB::table('pasien')
        ->join('reservasi_rekam_medik', 'pasien.pasien_id', '=', 'reservasi_rekam_medik.pasien_id')
        ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
        ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
        ->where('draft', 0)
        ->select(
            'pasien.pasien_id', 
            'pasien.nama as nama_pasien',
            'pasien.alamat as alamat_pasien',
            'pasien.pekerjaan as pekerjaan_pasien',
            'pasien.no_Telp as notelp_pasien',
            DB::raw('MAX(lokasi.nama_lokasi) as nama_lokasi'),
            DB::raw('MAX(dokter.nama) as nama_dokter'),
            DB::raw('MAX(reservasi_rekam_medik.tanggal) as tanggal_reservasi')
        )
        ->groupBy('pasien.pasien_id', 'nama_pasien', 'alamat_pasien', 'pekerjaan_pasien', 'notelp_pasien')
        ->get();


        $user = Auth::user();

        if(($user->role === 'dokter' ||  $user->role === 'Dokter')){
            $user_dokter = DB::table('dokter')
            ->where('user_id', '=', $user->id)->first();
            $dokter_id = $user_dokter->dokter_id;


            $reservasi_rekam_medik = DB::table('reservasi_rekam_medik')
            ->join('pasien', 'reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
            ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
            ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
            ->where('draft', 0)
            ->where('dokter.dokter_id', $dokter_id)
            ->select('reservasi_rekam_medik.*', 
            'lokasi.*',
            "pasien.*")
            ->orderBy('tanggal', 'desc')
            ->get();
        
        }

        
        if(($user->role === 'admin' ||  $user->role === 'admin'|| $user->role === 'owner'|| $user->role === 'Owner' )){

            $reservasi_rekam_medik = DB::table('reservasi_rekam_medik')
            ->join('pasien', 'reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
            ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
            ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
            ->where('draft', 0)
            ->select('reservasi_rekam_medik.*', 
            'lokasi.*',
            "pasien.*")
            ->orderBy('tanggal', 'desc')
            ->get();
        
        }



    
    

        if ($user->role === 'owner') {
            return view('owner.datapasien', compact('reservasi', 'reservasi_rekam_medik'));
        } elseif($user->role === 'dokter' ||  $user->role === 'Dokter') {
            return view('dokter.datapasien', compact('reservasi','reservasi_rekam_medik'));
        }
        else{
            return view('admin.datapasien', compact('reservasi','reservasi_rekam_medik'));
        }
        

    }

    public function add()
    {
        $user = Auth::user();
        if($user->role === 'dokter' ||  $user->role === 'Dokter') {
            return view('dokter.datapasien_add');
        }
        else{
            return view('admin.datapasien_add');
        }

    }

    public function insert(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'no_Telp' => 'required',
    
        ],[
            'nama.required' => 'Nama harus diisi!',
            'tempat_lahir.required' => 'tempat_lahir harus diisi!',
            'tanggal_lahir.required' => 'tanggal_lahir harus diisi!',
            'pekerjaan.required' => 'pekerjaan harus diisi!',
            'alamat.required' => 'alamat harus diisi!',
            'no_Telp.required' => 'no_Telp harus diisi!',

        ]);

        $data = [
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tempat_lahir,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'no_Telp' => $request->no_Telp,
            

        ];

        $this->pasien->addData($data);
        Alert::success('Berhasil!', 'Data pasien berhasil ditambahkan!');
        $user = Auth::user();
        if($user->role === 'dokter' ||  $user->role === 'Dokter') {
            return redirect('/ddatapasien');
        }
        else{
            return redirect('/adatapasien');
        }
    }

    public function destroy($pasien_id)
    {
        $reservasi = DB::table('reservasi_rekam_medik')
        ->join('pasien', 'reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
        ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
        ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
        ->where('draft', 0)
        ->select('reservasi_rekam_medik.*', 
        'lokasi.*',
        "pasien.*",
        'dokter.nama as nama_dokter')->get();

        // Setelah itu, hapus entri dari tabel pasien
        DB::table('pasien')->where('pasien_id', $pasien_id)->delete();
        
        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data pasien
        Alert::success('Berhasil!', 'Data pasien berhasil dihapus!');
        $user = Auth::user();
        if($user->role === 'dokter' ||  $user->role === 'Dokter') {
            return redirect('/ddatapasien');
        }
        else{
            return redirect('/adatapasien');
        }
    }

    public function edit($id)
    {
        $pasien = DB::table('pasien')->where('pasien_id', $id)->first();
        
        $user = Auth::user();
        if($user->role === 'dokter' ||  $user->role === 'Dokter') {
            return view('dokter.datapasien_edit', ['pasien' => $pasien]);
        }
        else{
            return view('admin.datapasien_edit', ['pasien' => $pasien]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'pekerjaan' => 'required',
            'alamat' => 'required',
            'no_Telp' => 'required',
        ]);

        $data = [
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'pekerjaan' => $request->pekerjaan,
            'alamat' => $request->alamat,
            'no_Telp' => $request->no_Telp,
        ];

        DB::table('pasien')->where('pasien_id', $id)->update($data);
        Alert::success('Berhasil!', 'Data pasien berhasil diupdate!');
        $user = Auth::user();
        if($user->role === 'dokter' ||  $user->role === 'Dokter') {
            return redirect("/ddatapasien");
        }
        else{
            return redirect('/adatapasien');
        }
    }
}
