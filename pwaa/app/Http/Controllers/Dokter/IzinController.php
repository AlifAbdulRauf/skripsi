<?php

namespace App\Http\Controllers\Dokter;

use Session;
use Exception;
use App\Models\Izin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;



class IzinController extends Controller
{
    protected $izin;

    public function __construct(izin $izin)
    {
        $this->izin = $izin;
    }

    public function index()
    {
        $izin = [
            'izin' => $this->izin->allData(),
        ];

        $userid = Auth::user()->id;
        
        // $izin_user = Izin::with(['dokter'])
        // ->where('user_id', $userid )
        // ->get();

        $izin_user = DB::table('izin_dokter')
        ->join('dokter', 'izin_dokter.dokter_id', '=', 'dokter.dokter_id')
        ->where('dokter.user_id', $userid)
        ->get();

        $izin_user_owner = Izin::with(['dokter'])
        ->whereNull('status')
        ->get();
    
        
        $user = Auth::user();
        if($user->role === 'Dokter'){
            return view('dokter.dataabsen', compact('izin','izin_user'));
        }
        else{
            return view('owner.dataizin', compact('izin','izin_user_owner'));
        }
    }
        

    public function add()
    {
        return view('dokter.dataabsen_add');
    }

    public function insert(Request $request)
    {

        try{
            $userid = Auth::user()->id;

            $dokter_id = DB::table('dokter')
            ->where('dokter.user_id', $userid)
            ->value('dokter_id');

            

        
            $request->validate([
                'tanggal_awal' => 'required',
                'tanggal_akhir' => 'required',
                'alasan' => 'required',
            ],[
                'tanggal_awal' => 'Tanggal awal harus diisi',
                'tanggal_akhir' => 'tanggal akhir harus diisi',
                'alasan' => 'alasan harus diisi',
            ]);

            $user = Auth::user();
            $data = [
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'alasan' => $request->alasan,
                'dokter_id' => $dokter_id,

                
            ];

            $this->izin->addData($data);
            Alert::success('Berhasil!', 'Data Izin berhasil diajukan!');
            return redirect('/dataabsen');
        } catch (Exception $e) {
            dd('simpan data gagal : ', $e);
        }
    }

    public function destroy($dokter_id)
    {
        // // Hapus entri terkait di tabel detail_jadwal terlebih dahulu
        // DB::table('detail_jadwal')->where('dokter_id', $dokter_id)->delete();
    
        // // Hapus entri terkait di tabel izin
        // DB::table('izin')->where('dokter_id', $dokter_id)->delete();
    
        // // Cari semua reservasi terkait dengan dokter_id melalui rekam_medik
        // $reservasiIds = DB::table('rekam_medik')->where('dokter_id', $dokter_id)->pluck('reservasi_id');
    
        // // Hapus entri terkait di tabel rekam_medik
        // DB::table('rekam_medik')->where('dokter_id', $dokter_id)->delete();
    
        // // Hapus entri terkait di tabel reservasi
        // DB::table('reservasi')->whereIn('reservasi_id', $reservasiIds)->delete();
    
        // Setelah itu, hapus entri dari tabel dokter
        DB::table('izin_dokter')->where('izin_id', $dokter_id)->delete();
    
        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data dokter
        Alert::success('Berhasil!', 'Data izin berhasil dihapus!');
        return redirect('/dataabsen');
    }

    public function edit($id)
    {
        $izin = DB::table('izin_dokter')->where('izin_id', $id)->first();
        return view('dokter.dataabsen_edit', ['izin' => $izin]);
    }

    public function detail($id)
    {
        $datajadwal = DB::table('detail_jadwal')->where('dokter_id', $id)->get();
        return view('admin.izinjadwal', ['datajadwal' => $datajadwal]);
    }

    public function tolak($id)
    {
        $izin = Izin::where('izin_id', $id)->firstOrFail();
    
        // Prepare data for reservasi
        $perizinan = [
            'status' => 0,
        ];
        // Update the reservasi
        $izin->update($perizinan);

        // Redirect or return a response
        Alert::success('Berhasil!', 'Data perizinan dokter ditolak !');
        return redirect()->route('owner.izin.index');

    }
    public function terima($id)
    {
        $izin = Izin::where('izin_id', $id)->firstOrFail();
    
        // Prepare data for reservasi
        $perizinan = [
            'status' => 1,
        ];
        // Update the reservasi
        $izin->update($perizinan);

        // Redirect or return a response
        Alert::success('Berhasil!', 'Data perizinan dokter diterima !');
        return redirect()->route('owner.izin.index');

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'alasan' => 'required',
        ]);

        $data = [
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'alasan' => $request->alasan,
        ];

        DB::table('izin_dokter')->where('izin_id', $id)->update($data);
        Alert::success('Berhasil!', 'Data pengajuan izin berhasil diupdate!');
        return redirect('/dataabsen');
    }
}
