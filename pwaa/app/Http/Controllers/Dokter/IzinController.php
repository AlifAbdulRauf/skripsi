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
use App\Services\WhatsAppService;


class IzinController extends Controller
{
    protected $izin;
    protected $whatsAppService;


    public function __construct(izin $izin, WhatsAppService $whatsAppService)
    {
        $this->izin = $izin;
        $this->whatsAppService = $whatsAppService;

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

    // public function terima($id)
    // {
    //     $reservasi_rekam_medik = Reservasi::where('reservasi_id', $id)->firstOrFail();
    
    //     // Prepare data for reservasi_rekam_medik
    //     $reservasiData = [
    //         'draft' => 0,
    //     ];
    //     // Update the reservasi_rekam_medik
    //     $reservasi_rekam_medik->update($reservasiData);
    //     $reservasi = Reservasi::with(['pasien'])
    //     ->where('reservasi_id', $id)
    //     ->firstOrFail();

    //     $this->whatsAppService->sendWhatsAppMessage($reservasi->pasien->no_Telp, 'Reservasi Anda telah diterima, silahkan cek status reservasi anda pada akun website Xenon Dental House anda');
        

    //     // Redirect or return a response
    //     Alert::success('Berhasil!', 'Data reservasi_rekam_medik pasien diterima !');
    //     return redirect()->route('reservasi.pasien');

    // }



    // public function tolak(Request $request, $id)
    // {
    //     // Validasi input alasan
    //     $request->validate([
    //         'alasan' => 'required|string|max:255',
    //     ]);
    
    //     // Ambil data reservasi yang ditolak
    //     $reservasi_rekam_medik = Reservasi::where('reservasi_id', $id)->firstOrFail();
    
    //     // Update data reservasi, set ke draft atau status ditolak
    //     $reservasiData = [
    //         'draft' => 1,
    //     ];
    //     $reservasi_rekam_medik->update($reservasiData);
    
    //     // Dapatkan reservasi dengan data pasien
    //     $reservasi = Reservasi::with(['pasien'])->where('reservasi_id', $id)->firstOrFail();
    
    //     // Dapatkan alasan dari request
    //     $alasan = $request->input('alasan');
    
    //     // Kirim pesan WhatsApp
    //     $this->whatsAppService->sendWhatsAppMessage($reservasi->pasien->no_Telp, "Reservasi Anda telah ditolak. Alasan: $alasan. Silakan cek status reservasi Anda di akun website Xenon Dental House Anda.");
    
    //     // Kembalikan response JSON agar AJAX bisa memprosesnya dengan benar
    //     return response()->json(['success' => true, 'message' => 'Reservasi berhasil ditolak']);
    // }
    


    public function tolak(Request $request, $id)
    {
        $izin = Izin::where('izin_id', $id)->firstOrFail();
        $dokter = DB::table('izin_dokter')
        ->join('dokter','izin_dokter.dokter_id', '=', 'dokter.dokter_id' )
        ->where('izin_dokter.izin_id', $id)
        ->select('izin_dokter.*', 'dokter.*')
        ->first();
    
        // Prepare data for reservasi
        $perizinan = [
            'status' => 0,
        ];
        // Update the reservasi
        $izin->update($perizinan);

                // Validasi input alasan
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);

       // Dapatkan alasan dari request
        $alasan = $request->input('alasan');
    
        // Kirim pesan WhatsApp
        $this->whatsAppService->sendWhatsAppMessage($dokter->nomor_hp, "Permintaan izin Anda telah ditolak. Alasan: $alasan. Silakan cek statusnya pada akun website Xenon Dental House Anda.");
    

        // Alert::success('Berhasil!', 'Data perizinan dokter ditolak !');
        // return redirect()->route('owner.izin.index');
        return response()->json(['success' => true, 'message' => 'Izin berhasil ditolak']);

    }
    public function terima($id)
    {
        $izin = Izin::where('izin_id', $id)->firstOrFail();
        $dokter = DB::table('izin_dokter')
        ->join('dokter','izin_dokter.dokter_id', '=', 'dokter.dokter_id' )
        ->where('izin_dokter.izin_id', $id)
        ->select('izin_dokter.*', 'dokter.*')
        ->first();
        
    
        // Prepare data for reservasi
        $perizinan = [
            'status' => 1,
        ];
        // Update the reservasi
        $izin->update($perizinan);
        $this->whatsAppService->sendWhatsAppMessage($dokter->nomor_hp, 'Permintaan izin anda sudah diterima, silahkan cek statusnya pada akun website Xenon Dental House anda');

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
