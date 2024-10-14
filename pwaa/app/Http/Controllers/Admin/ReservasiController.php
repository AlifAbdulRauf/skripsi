<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\Models\Pasien;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Perawatan;
use App\Models\Reservasi;
use App\Models\RekamMedik;
use App\Models\Dokter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\Controller;
use App\Models\DetailJadwal;
use Illuminate\Support\Carbon;
use App\Services\WhatsAppService;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use DateTime;
use DateInterval;

class ReservasiController extends Controller
{
    protected $user;
    protected $whatsAppService;

    public function __construct(User $user, WhatsAppService $whatsAppService)
    {
        $this->user = $user;
        $this->whatsAppService = $whatsAppService;
    }

    public function index()
    {

        $reservasi_rekam_medik = DB::table('reservasi_rekam_medik')
        ->join('pasien', 'reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
        ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
        ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
        ->where('draft', 0)
        ->select('reservasi_rekam_medik.*', 
        'lokasi.*',
        "pasien.*",
        'dokter.nama as nama_dokter')
        ->orderBy('tanggal', 'desc')
        ->get();

        $reservasi_admin = DB::table("reservasi_rekam_medik")
        ->join("pasien", "reservasi_rekam_medik.pasien_id", "=", "pasien.pasien_id")->get();

        return view('admin.reservasiadmin', compact('reservasi_rekam_medik'));
    }

    
    public function pasien()
    {

        $reservasi_rekam_medik = DB::table('reservasi_rekam_medik')
        ->join('pasien', 'reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
        ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
        ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
        ->where('draft', NULL)
        ->select('reservasi_rekam_medik.*', 
        'lokasi.*',
        "pasien.*",
        'dokter.nama as nama_dokter')->get();

        return view('admin.reservasipasien', compact('reservasi_rekam_medik'));
    }

    public function add()
    {
        $lokasi = Lokasi::all();
        $perawatan = Perawatan::all();
        $dokter = Dokter::all();
        $pasien = Pasien::all();
    
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
    
        // Generate available timeslots
        $timeslots = [];
        for ($hour = 10; $hour < 21; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $start = Carbon::createFromTime($hour, $minute);
                $end = $start->copy()->addMinutes(30);
                $timeslots[] = [
                    'start' => $start->format('H:i'),
                    'end' => $end->format('H:i') 
                ];
            }
        }


        $user = Auth::user();
        if ($user->role === 'Pasien') {
            return view('pasien.reservasipasien_add', compact('lokasi', 'dokter', 'perawatan', 'tomorrow', 'timeslots', 'pasien'));
        } elseif($user->role === 'Admin' ||  $user->role === 'admin') {
            return view('admin.reservasiadmin_add', compact('lokasi', 'dokter', 'perawatan', 'tomorrow', 'timeslots', 'pasien'));
        }
        
    }

    public function add_pasien_lama()
    {
        $lokasi = Lokasi::all();
        $perawatan = Perawatan::all();
        $dokter = Dokter::all();
        $pasien = Pasien::all();
    
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');
    
        // Generate available timeslots
        $timeslots = [];
        for ($hour = 10; $hour < 21; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $start = Carbon::createFromTime($hour, $minute);
                $end = $start->copy()->addMinutes(30);
                $timeslots[] = [
                    'start' => $start->format('H:i'),
                    'end' => $end->format('H:i') 
                ];
            }
        }


        $user = Auth::user();
        if ($user->role === 'Pasien') {
            return view('pasien.reservasipasien_add', compact('lokasi', 'dokter', 'perawatan', 'tomorrow', 'timeslots', 'pasien'));
        } elseif($user->role === 'Admin' ||  $user->role === 'admin') {
            return view('admin.reservasiadmin_add_pasienlama', compact('lokasi', 'dokter', 'perawatan', 'tomorrow', 'timeslots', 'pasien'));
        }
        
    }
    
    
    public function insert(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'no_Telp' => 'required|string|max:15',
                'lokasi_id' => 'required|integer|exists:lokasi,lokasi_id',
                'dokter_id' => 'required|integer',
                'tanggal' => 'required|date',
                'jam_mulai' => 'required',
                'perawatan_id' => 'required|array|max:2',
                'perawatan_id.*' => 'integer|exists:perawatan,perawatan_id',
            ]);
    
            $tanggal = Carbon::parse($validatedData['tanggal'])->format('Y-m-d');
    
            // Calculate total estimated time
            $totalEstimasi = 0;
            foreach ($validatedData['perawatan_id'] as $perawatanId) {
                $perawatan = Perawatan::find($perawatanId);
                $totalEstimasi += $perawatan->estimasi_waktu_perawatan;
            }
    
            $startTime = new DateTime($validatedData['jam_mulai']);
            $endTime = (clone $startTime)->modify("+{$totalEstimasi} minutes")->format('H:i');
    
            // Check for time conflicts
            $conflictingReservation = Reservasi::where('tanggal', $tanggal)
                ->where(function ($query) use ($validatedData, $endTime) {
                    $query->whereBetween('jam_mulai', [$validatedData['jam_mulai'], $endTime])
                          ->orWhereBetween('jam_selesai', [$validatedData['jam_mulai'], $endTime])
                          ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$validatedData['jam_mulai']])
                          ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$endTime]);
                })
                ->exists();
    
    
            // Create new reservation

            $pasien = Pasien::create([
                'nama' => $validatedData['nama'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'pekerjaan' => $validatedData['pekerjaan'],
                'alamat' => $validatedData['alamat'],
                'no_Telp' => $validatedData['no_Telp'],
            ]);
            
            $reservasi_rekam_medik = Reservasi::create([
                'pasien_id' => $pasien['pasien_id'],
                'lokasi_id' => $validatedData['lokasi_id'],
                'dokter_id' => $validatedData['dokter_id'],
                'tanggal' => $tanggal,
                'jam_mulai' => $validatedData['jam_mulai'],
                'jam_selesai' => $endTime,
                'status_penginput' => 1,
                'draft' => 0

            ]);

            $syncData = [];
            foreach ($validatedData['perawatan_id'] as $perawatanId) {
                $perawatan = Perawatan::find($perawatanId);
                $totalEstimasi += $perawatan->estimasi_waktu_perawatan;
                $syncData[$perawatanId] = [
                    'harga' => $perawatan->harga,
                    'estimasi_waktu_perawatan' => $perawatan->estimasi_waktu_perawatan,
                ];
            }
            
    
            $reservasi_rekam_medik->perawatan()->attach($syncData);

            Alert::success('Success', 'Reservasi berhasil ditambahkan!');
            return redirect()->route('reservasi.index');
        } catch (Exception $e) {
            dd('simpan data gagal : ', $e);
        }
    }

    public function insert_pasien_lama(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'pasien_id' => 'required|integer',
                'lokasi_id' => 'required|integer|exists:lokasi,lokasi_id',
                'dokter_id' => 'required|integer',
                'tanggal' => 'required|date',
                'jam_mulai' => 'required',
                'perawatan_id' => 'required|array|max:2',
                'perawatan_id.*' => 'integer|exists:perawatan,perawatan_id',
            ]);
    
            $tanggal = Carbon::parse($validatedData['tanggal'])->format('Y-m-d');
    
            // Calculate total estimated time
            $totalEstimasi = 0;
            foreach ($validatedData['perawatan_id'] as $perawatanId) {
                $perawatan = Perawatan::find($perawatanId);
                $totalEstimasi += $perawatan->estimasi_waktu_perawatan;
            }
    
            $startTime = new DateTime($validatedData['jam_mulai']);
            $endTime = (clone $startTime)->modify("+{$totalEstimasi} minutes")->format('H:i');
    
            // Check for time conflicts
            $conflictingReservation = Reservasi::where('tanggal', $tanggal)
                ->where(function ($query) use ($validatedData, $endTime) {
                    $query->whereBetween('jam_mulai', [$validatedData['jam_mulai'], $endTime])
                          ->orWhereBetween('jam_selesai', [$validatedData['jam_mulai'], $endTime])
                          ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$validatedData['jam_mulai']])
                          ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$endTime]);
                })
                ->exists();
    
    
            // Create new reservation
            $reservasi_rekam_medik = Reservasi::create([
                'pasien_id' => $validatedData['pasien_id'],
                'lokasi_id' => $validatedData['lokasi_id'],
                'dokter_id' => $validatedData['dokter_id'],
                'tanggal' => $tanggal,
                'jam_mulai' => $validatedData['jam_mulai'],
                'jam_selesai' => $endTime,
                'status_penginput' => 1,
                'draft' => 0

            ]);

            $syncData = [];
            foreach ($validatedData['perawatan_id'] as $perawatanId) {
                $perawatan = Perawatan::find($perawatanId);
                $totalEstimasi += $perawatan->estimasi_waktu_perawatan;
                $syncData[$perawatanId] = [
                    'harga' => $perawatan->harga,
                    'estimasi_waktu_perawatan' => $perawatan->estimasi_waktu_perawatan,
                ];
            }
            
    
            $reservasi_rekam_medik->perawatan()->attach($syncData);

            Alert::success('Success', 'Reservasi berhasil ditambahkan!');
            return redirect()->route('reservasi.index');
        } catch (Exception $e) {
            dd('simpan data gagal : ', $e);
        }
    }

    public function getBookedTimes(Request $request)
    {
        $dokter_id = $request->query('dokter_id');
        $tanggal = $request->query('tanggal');
        $tanggalDate = Carbon::parse($tanggal);
        

        $bookedTimes = Reservasi::where('dokter_id', $dokter_id)
                                ->where('tanggal',  $tanggalDate)
                                ->get(['jam_mulai', 'jam_selesai']);

        return response()->json($bookedTimes);
    }
    
    public function getDokterByLokasi(Request $request)
    {
        $lokasi_id = $request->query('lokasi_id');
        $dokter = Dokter::where('lokasi_id', $lokasi_id)->get();
        return response()->json($dokter);
    }
    
    public function getAvailableDays(Request $request)
    {
        $dokter_id = $request->query('dokter_id');
        $jadwals = DetailJadwal::where('dokter_id', $dokter_id)->pluck('hari');
        return response()->json($jadwals);
    }
    
    public function getSesiByDokterAndHari(Request $request)
    {
        $dokter_id = $request->query('dokter_id');
        $hari = $request->query('hari');
        $jadwals = DetailJadwal::where('dokter_id', $dokter_id)
                          ->where('hari', $hari)
                          ->pluck('sesi');
        return response()->json($jadwals);
    }
    

    public function destroy($id)
    {
        // Hapus entri terkait di tabel detail_jadwal terlebih dahulu
        DB::table('reservasi_rekam_medik')->where('reservasi_id', $id)->delete();

        // Menampilkan alert sukses dan mengarahkan kembali ke halaman data user
        Alert::success('Berhasil!', 'Data reservasi_rekam_medik berhasil dihapus!');
        return redirect('/reservasi/admin');
    }

    public function terima($id)
    {
        $reservasi_rekam_medik = Reservasi::where('reservasi_id', $id)->firstOrFail();
    
        // Prepare data for reservasi_rekam_medik
        $reservasiData = [
            'draft' => 0,
        ];
        // Update the reservasi_rekam_medik
        $reservasi_rekam_medik->update($reservasiData);
        $reservasi = Reservasi::with(['pasien'])
        ->where('reservasi_id', $id)
        ->firstOrFail();

        $this->whatsAppService->sendWhatsAppMessage($reservasi->pasien->no_Telp, 'Reservasi Anda telah diterima, silahkan cek status reservasi anda pada akun website Xenon Dental House anda');
        

        // Redirect or return a response
        Alert::success('Berhasil!', 'Data reservasi_rekam_medik pasien diterima !');
        return redirect()->route('reservasi.pasien');

    }

    // public function tolak($id)
    // {
    //     $reservasi_rekam_medik = Reservasi::where('reservasi_id', $id)->firstOrFail();
    
    //     // Prepare data for reservasi_rekam_medik
    //     $reservasiData = [
    //         'draft' => 1,
    //     ];
    //     // Update the reservasi_rekam_medik
    //     $reservasi_rekam_medik->update($reservasiData);

    //     $reservasi = Reservasi::with(['pasien'])
    //     ->where('reservasi_id', $id)
    //     ->firstOrFail();

    //     $this->whatsAppService->sendWhatsAppMessage($reservasi->pasien->no_Telp, 'Reservasi Anda telah ditolak, silahkan cek status reservasi anda pada akun website Xenon Dental House anda');

    //     // Redirect or return a response
    //     Alert::success('Berhasil!', 'Data reservasi_rekam_medik pasien ditolak !');
    //     return redirect()->route('reservasi.pasien');

    // }

    public function tolak(Request $request, $id)
    {
        // Validasi input alasan
        $request->validate([
            'alasan' => 'required|string|max:255',
        ]);
    
        // Ambil data reservasi yang ditolak
        $reservasi_rekam_medik = Reservasi::where('reservasi_id', $id)->firstOrFail();
    
        // Update data reservasi, set ke draft atau status ditolak
        $reservasiData = [
            'draft' => 1,
        ];
        $reservasi_rekam_medik->update($reservasiData);
    
        // Dapatkan reservasi dengan data pasien
        $reservasi = Reservasi::with(['pasien'])->where('reservasi_id', $id)->firstOrFail();
    
        // Dapatkan alasan dari request
        $alasan = $request->input('alasan');
    
        // Kirim pesan WhatsApp
        $this->whatsAppService->sendWhatsAppMessage($reservasi->pasien->no_Telp, "Reservasi Anda telah ditolak. Alasan: $alasan. Silakan cek status reservasi Anda di akun website Xenon Dental House Anda.");
    
        // Kembalikan response JSON agar AJAX bisa memprosesnya dengan benar
        return response()->json(['success' => true, 'message' => 'Reservasi berhasil ditolak']);
    }
    



    
    public function edit($id)
    {
        $lokasi = Lokasi::all();
        $perawatan = Perawatan::all();
        $dokter = Dokter::all();
        $reservasi = Reservasi::with(['pasien', 'dokter', 'lokasi'])
                      ->where('reservasi_id', $id)
                      ->first();


        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Generate available timeslots
        $timeslots = [];
        for ($hour = 10; $hour < 21; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $start = Carbon::createFromTime($hour, $minute);
                $end = $start->copy()->addMinutes(30);
                $timeslots[] = [
                    'start' => $start->format('H:i'),
                    'end' => $end->format('H:i')
                ];
            }
        }


        return view('admin.reservasiadmin_edit', compact('lokasi', 'dokter', 'perawatan','reservasi', 'tomorrow'));
    }

    public function update(Request $request, $reservasi_id)
    {
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'no_Telp' => 'required|string|max:15',
                'lokasi_id' => 'required|integer|exists:lokasi,lokasi_id',
                'dokter_id' => 'required|integer',
                'tanggal' => 'required|date',
                'jam_mulai' => 'required',
                'perawatan_id' => 'required|array|max:2',
                'perawatan_id.*' => 'integer|exists:perawatan,perawatan_id',
            ]);
    
            $tanggal = Carbon::parse($validatedData['tanggal'])->format('Y-m-d');
    
            // Calculate total estimated time
            $totalEstimasi = 0;
            foreach ($validatedData['perawatan_id'] as $perawatanId) {
                $perawatan = Perawatan::find($perawatanId);
                $totalEstimasi += $perawatan->estimasi_waktu_perawatan;
            }
    
            $startTime = new DateTime($validatedData['jam_mulai']);
            $endTime = (clone $startTime)->modify("+{$totalEstimasi} minutes")->format('H:i');
    
            // Check for time conflicts
            $conflictingReservation = Reservasi::where('tanggal', $tanggal)
                ->where('reservasi_id', '!=', $reservasi_id)
                ->where(function ($query) use ($validatedData, $endTime) {
                    $query->whereBetween('jam_mulai', [$validatedData['jam_mulai'], $endTime])
                          ->orWhereBetween('jam_selesai', [$validatedData['jam_mulai'], $endTime])
                          ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$validatedData['jam_mulai']])
                          ->orWhereRaw('? BETWEEN jam_mulai AND jam_selesai', [$endTime]);
                })
                ->exists();
    
            // if ($conflictingReservation) {
            //     Alert::error('Error', 'Waktu yang dipilih bentrok dengan reservasi lain!');
            //     return redirect()->back()->withInput();
            // }
    
            // Update existing reservation
            $reservasi = Reservasi::findOrFail($reservasi_id);
            $pasien = Pasien::findOrFail($reservasi->pasien_id);
    
            $pasien->update([
                'nama' => $validatedData['nama'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'pekerjaan' => $validatedData['pekerjaan'],
                'alamat' => $validatedData['alamat'],
                'no_Telp' => $validatedData['no_Telp'],
            ]);
    
            $reservasi->update([
                'lokasi_id' => $validatedData['lokasi_id'],
                'dokter_id' => $validatedData['dokter_id'],
                'tanggal' => $tanggal,
                'jam_mulai' => $validatedData['jam_mulai'],
                'jam_selesai' => $endTime,
                'status_penginput' => 1,
                'draft' => 0
            ]);
    
            $syncData = [];
            foreach ($validatedData['perawatan_id'] as $perawatanId) {
                $perawatan = Perawatan::find($perawatanId);
                $syncData[$perawatanId] = [
                    'harga' => $perawatan->harga,
                    'estimasi_waktu_perawatan' => $perawatan->estimasi_waktu_perawatan,
                ];
            }
    
            $reservasi->perawatan()->sync($syncData);
    
            Alert::success('Success', 'Reservasi berhasil diperbarui!');
            return redirect()->route('reservasi.index');
        } catch (Exception $e) {
            dd('update data gagal : ', $e);
        }
    }
    
}
