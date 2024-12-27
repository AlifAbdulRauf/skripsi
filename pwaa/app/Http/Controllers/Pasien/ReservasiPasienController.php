<?php

namespace App\Http\Controllers\Pasien;

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
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use DateTime;
use DateInterval;

use function Laravel\Prompts\table;

class ReservasiPasienController extends Controller
{
    protected $user;


    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {

        $userid = Auth::user();
        $reservasi_rekam_medik = DB::table('reservasi_rekam_medik')
        ->join('pasien', 'reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
        ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
        ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
        ->where('pasien.user_id', $userid->id)
        ->select('reservasi_rekam_medik.*', 
            'lokasi.*',
            "pasien.*",
            'dokter.nama as nama_dokter')
        ->orderBy('reservasi_rekam_medik.reservasi_id', 'DESC') // Urutkan berdasarkan data terbaru
        ->get();
    

        $reservasi_admin = DB::table("reservasi_rekam_medik")
        ->join("pasien", "reservasi_rekam_medik.pasien_id", "=", "pasien.pasien_id")->get();

        return view('pasien.reservasipasien', compact('reservasi_rekam_medik'));
    }

    
    public function pasien()
    {

        $reservasi_rekam_medik = DB::table('reservasi_rekam_medik')
        ->join('pasien', 'reservasi_rekam_medik.pasien_id', '=', 'pasien.pasien_id')
        ->join('lokasi', 'reservasi_rekam_medik.lokasi_id', '=', 'lokasi.lokasi_id')
        ->join("dokter", "reservasi_rekam_medik.dokter_id", "=", "dokter.dokter_id")
        ->where('draft', 1)
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
    
        return view('pasien.reservasipasien_add', compact('lokasi', 'dokter', 'perawatan', 'tomorrow', 'timeslots'));
    }

    public function add_pasien_lama()
    {
        $lokasi = Lokasi::all();
        $perawatan = Perawatan::all();
        $dokter = Dokter::all();
        $user = Auth::user();
        // $pasien = DB::table('pasien')
        // ->join('reservasi_rekam_medik', 'pasien.pasien_id', '=', 'reservasi_rekam_medik.pasien_id')
        // ->where('user_id', $user->id)
        // ->where('reservasi_rekam_medik.draft', 0)
        // ->groupBy('reservasi_rekam_medik.pasien_id')
        // ->select('pasien.pasien_id', DB::raw('MAX(pasien.nama) as nama')) // Contoh agregasi
        // ->get();

        $pasien = DB::table('pasien')
        ->select(
            DB::raw('COALESCE(pasien.pasien_id, reservasi_rekam_medik.pasien_id) as pasien_id'),
            DB::raw('MAX(pasien.nama) as nama'),
            DB::raw('MAX(pasien.no_Telp) as no_Telp')
        )
        ->leftJoin('reservasi_rekam_medik', 'pasien.pasien_id', '=', 'reservasi_rekam_medik.pasien_id')
        ->rightJoin('reservasi_rekam_medik as r2', 'pasien.pasien_id', '=', 'r2.pasien_id') // Simulasi FULL JOIN
        ->where('reservasi_rekam_medik.draft', 0)
        ->where('pasien.user_id', $user->id)
        ->groupBy(DB::raw('COALESCE(pasien.pasien_id, reservasi_rekam_medik.pasien_id)'))
        ->orderBy(DB::raw('COALESCE(pasien.pasien_id, reservasi_rekam_medik.pasien_id)'))
        ->get();

    
    
        
    
    
    
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
            return view('pasien.reservasipasien_add_pasienlama', compact('lokasi', 'dokter', 'perawatan', 'tomorrow', 'timeslots', 'pasien'));
        } elseif($user->role === 'Admin' ||  $user->role === 'admin') {
            return view('admin.reservasiadmin_add_pasienlama', compact('lokasi', 'dokter', 'perawatan', 'tomorrow', 'timeslots', 'pasien'));
        }
        
    }

    public function insert_pasien_lama(Request $request)
    {
        // try {
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
                'status_penginput' => 0,


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
            return redirect()->route('pasien.history');
        // } catch (Exception $e) {
        //     dd('simpan data gagal : ', $e);
        // }
    }
    
    
    public function insert(Request $request)
    {
        // try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'tempat_lahir' => 'required|string|max:255',
                'tanggal_lahir' => 'required|date',
                'pekerjaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'no_Telp' => 'required|string|max:15',
                'lokasi_id' => 'required|integer|exists:lokasi,lokasi_id',
                'dokter_id' => 'required|integer',
                'tanggal' => 'required|date|after:today',
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
            $user_id = Auth::id();

            $pasien = Pasien::create([
                'nama' => $validatedData['nama'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'pekerjaan' => $validatedData['pekerjaan'],
                'alamat' => $validatedData['alamat'],
                'no_Telp' => $validatedData['no_Telp'],
                'user_id' => $user_id,
            ]);
            
            $reservasi_rekam_medik = Reservasi::create([
                'pasien_id' => $pasien['pasien_id'],
                'lokasi_id' => $validatedData['lokasi_id'],
                'dokter_id' => $validatedData['dokter_id'],
                'tanggal' => $tanggal,
                'jam_mulai' => $validatedData['jam_mulai'],
                'jam_selesai' => $endTime,
                'status_penginput' => 0
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
            return redirect()->route('pasien.history');
        // } catch (Exception $e) {
        //     printf('simpan data gagal : ', $e);
        // }
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

        // Redirect or return a response
        Alert::success('Berhasil!', 'Data reservasi_rekam_medik pasien diterima !');
        return redirect()->route('reservasi.index');

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

    public function update(Request $request, $id)
    {
        // try {
            $validatedData = $request->validate([
                'tanggal' => 'required|date|after:today',
                'jam_mulai' => 'required',
                'lokasi_id' => 'required|integer|exists:lokasi,lokasi_id',
                'dokter_id' => 'required|integer',
                'perawatan_id' => 'required|array|max:2',
                'perawatan_id.*' => 'integer|exists:perawatan,perawatan_id',
            ]);

            // Find existing reservation
            $reservasi_rekam_medik = Reservasi::findOrFail($id);

            // Check for time conflicts
            $conflictingReservation = Reservasi::where('tanggal', $validatedData['tanggal'])
                ->where('jam', $validatedData['jam_mulai'])
                ->where('reservasi_id', '!=', $id)
                ->first();

            if ($conflictingReservation) {
                return redirect()->back()->withErrors(['jam_mulai' => 'The selected time is already booked.'])->withInput();
            }

            // Update reservation data
            $reservasi_rekam_medik->update([
                'lokasi_id' => $validatedData['lokasi_id'],
                'dokter_id' => $validatedData['dokter_id'],
                'status_penginput' => "1" ,
                'tanggal' => $validatedData['tanggal'],
                'jam' => $validatedData['jam_mulai'],
            ]);

            $reservasi_rekam_medik->perawatan()->sync($validatedData['perawatan_id']);
            $perawatanData = Perawatan::whereIn('perawatan_id', $validatedData['perawatan_id'])->get();
            $syncData = [];
            foreach ($perawatanData as $perawatan) {
                $syncData[$perawatan->id] = [
                    'harga' => $perawatan->harga,
                    'estimasi_waktu_perawatan' => $perawatan->estimasi_waktu_perawatan,
                ];
            }
            

            $reservasi_rekam_medik->perawatan()->update($syncData);

            Alert::success('Berhasil!', 'Data reservasi_rekam_medik pasien berhasil diperbarui!');
            return redirect()->route('reservasi.index');
        // } catch (Exception $e) {
        //     dd('simpan data gagal : ', $e);
        // }
    }
}
