<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Izin;

class HomeController extends Controller
{
    public function index()
    {

        $today = Carbon::today()->toDateString();
        $absences = Izin::where('tanggal_awal', '<=', $today)
                        ->where('tanggal_akhir', '>=', $today)
                        ->get();

        $currentMonth = Carbon::now()->format('Y-m');
        $visitsToday = DB::table('reservasi_rekam_medik')
                        ->whereDate('tanggal', Carbon::today())
                        ->count();
        $visitsThisMonth = $this->getMonthlyVisits($currentMonth);
    
        // $visitStats = [
        //     'today' => $visitsToday,
        //     'week1' => $visitsThisMonth['week1'],
        //     'week2' => $visitsThisMonth['week2'],
        //     'week3' => $visitsThisMonth['week3'],
        //     'week4' => $visitsThisMonth['week4'],
        //     'this_month' => $visitsThisMonth['this_month'],
        // ];

        $visitStats = [
            'today' => $visitsToday,
            'week1' => $visitsThisMonth['week1'],
            'week2' => $visitsThisMonth['week2'],
            'week3' => $visitsThisMonth['week3'],
            'week4' => $visitsThisMonth['week4'],
            'this_month' => $visitsThisMonth['week1'] + $visitsThisMonth['week2'] + $visitsThisMonth['week3'] + $visitsThisMonth['week4'],
        ];
        $visits = DB::table('reservasi_rekam_medik')
                    ->whereYear('tanggal', Carbon::now()->year)
                    ->whereMonth('tanggal', Carbon::now()->month)
                    ->count();
        $doctor = DB::table("Dokter")
                    ->count();
        
        $location = DB::table("lokasi")
                    ->count();
                    
        // Calculate patient stats
        $patientStats = DB::table('Pasien')
        ->join('reservasi_rekam_medik', 'Pasien.pasien_id', '=', 'reservasi_rekam_medik.pasien_id')
        ->whereYear('reservasi_rekam_medik.tanggal',Carbon::now()->year)
        ->whereMonth('reservasi_rekam_medik.tanggal', Carbon::now()->month)
        ->select(
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 < 2 THEN 1 ELSE 0 END) as bayi"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 BETWEEN 2 AND 12 THEN 1 ELSE 0 END) as anak"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 BETWEEN 13 AND 19 THEN 1 ELSE 0 END) as remaja"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 BETWEEN 20 AND 59 THEN 1 ELSE 0 END) as dewasa"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 >= 60 THEN 1 ELSE 0 END) as lansia")
        )->first();

        // Fetch treatments for the current month
        $treatments = DB::table('Perawatan')
        ->leftJoin('perawatan_reservasi', 'Perawatan.perawatan_id', '=', 'perawatan_reservasi.perawatan_id')
        ->leftJoin('reservasi_rekam_medik', function($join) {
            $join->on('perawatan_reservasi.reservasi_id', '=', 'reservasi_rekam_medik.reservasi_id')
                 ->whereYear('reservasi_rekam_medik.tanggal', Carbon::now()->year)
                 ->whereMonth('reservasi_rekam_medik.tanggal', Carbon::now()->month);
        })
        ->select('Perawatan.jenis_Perawatan', DB::raw('count(reservasi_rekam_medik.reservasi_id) as total'))
        ->groupBy('Perawatan.jenis_Perawatan')
        ->get();
    

        // Fetch doctor performance for the current month
        $doctorPerformance = DB::table('Dokter')
        ->leftJoin('reservasi_rekam_medik', function($join) {
            $join->on('Dokter.dokter_id', '=', 'reservasi_rekam_medik.dokter_id')
                 ->whereYear('reservasi_rekam_medik.tanggal', Carbon::now()->year)
                 ->whereMonth('reservasi_rekam_medik.tanggal', Carbon::now()->month);
        })
        ->select('Dokter.nama', DB::raw('count(reservasi_rekam_medik.dokter_id) as total'))
        ->groupBy('Dokter.nama')
        ->get();
    
    

        $totalIncome = DB::table('Perawatan')
        ->join('perawatan_reservasi', 'Perawatan.perawatan_id', '=', 'perawatan_reservasi.perawatan_id')
        ->join('reservasi_rekam_medik', 'perawatan_reservasi.reservasi_id', '=', 'reservasi_rekam_medik.reservasi_id')
        ->where('reservasi_rekam_medik.draft', 0)
        ->whereYear('reservasi_rekam_medik.tanggal', Carbon::now()->year)
        ->whereMonth('reservasi_rekam_medik.tanggal', Carbon::now()->month)
        ->sum('perawatan_reservasi.harga');


        $totalExpenses = DB::table('pengeluaran')
            ->whereYear('tanggal', Carbon::now()->year)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->sum('jumlah_pengeluaran');

        // Calculate profit
        $profit = $totalIncome - $totalExpenses;


        // Determine the user role and return the appropriate view
        $user = Auth::user();
        if ($user->role === 'owner') {
            return view('owner.home', compact('visitStats', 'currentMonth', 'visits','doctor', 'location', 'treatments', 'patientStats', 'doctorPerformance', 'profit'));
        }

        else{        
            return view('admin.home', compact('absences','visitStats', 'currentMonth', 'visits','doctor', 'location', 'treatments', 'patientStats', 'doctorPerformance','profit'));
        }

    }

    public function filter(Request $request)
    {
        $currentMonth = Carbon::now()->format('Y-m');
        $month = $request->input('month', $currentMonth);
        
        $today = Carbon::today()->toDateString();
        $absences = Izin::where('tanggal_awal', '<=', $today)
        ->where('tanggal_akhir', '>=', $today)
        ->get();
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);
    
        $visits = DB::table('reservasi_rekam_medik')
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $monthNumber)
                    ->count();
        $doctor = DB::table("Dokter")->count();
        $location = DB::table("lokasi")->count();
    
        $visitsToday = DB::table('reservasi_rekam_medik')
                        ->whereDate('tanggal', Carbon::today())
                        ->count();
    
        $visitsInMonth = DB::table('reservasi_rekam_medik')
                            ->whereYear('tanggal', $year)
                            ->whereMonth('tanggal', $monthNumber)
                            ->get();
    
        $week1 = $visitsInMonth->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 1;
        })->count();
    
        $week2 = $visitsInMonth->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 2;
        })->count();
    
        $week3 = $visitsInMonth->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 3;
        })->count();
    
        $week4 = $visitsInMonth->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 4;
        })->count();
    
        $visitStats = [
            'today' => $visitsToday,
            'week1' => $week1,
            'week2' => $week2,
            'week3' => $week3,
            'week4' => $week4,
            'this_month' => $week1+$week2+$week3+$week4,
        ];

        $totalIncome = DB::table('Perawatan')
        ->join('perawatan_reservasi', 'Perawatan.perawatan_id', '=', 'perawatan_reservasi.perawatan_id')
        ->join('reservasi_rekam_medik', 'perawatan_reservasi.reservasi_id', '=', 'reservasi_rekam_medik.reservasi_id')
        ->whereYear('reservasi_rekam_medik.tanggal', $year)
        ->whereMonth('reservasi_rekam_medik.tanggal', $monthNumber)
        ->sum('perawatan_reservasi.harga');

        // Calculate total expenses for the selected month
        $totalExpenses = DB::table('pengeluaran')
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $monthNumber)
            ->sum('jumlah_pengeluaran');

        // Calculate profit
        $profit = $totalIncome - $totalExpenses;
    
        // Fetch treatments for the selected month
        $treatments = DB::table('Perawatan')
        ->leftJoin('perawatan_reservasi', 'Perawatan.perawatan_id', '=', 'perawatan_reservasi.perawatan_id')
        ->leftJoin('reservasi_rekam_medik', function ($join) use ($month) {
            $join->on('perawatan_reservasi.reservasi_id', '=', 'reservasi_rekam_medik.reservasi_id')
                 ->whereRaw("DATE_FORMAT(reservasi_rekam_medik.tanggal, '%Y-%m') = ?", [$month]);
        })
        ->select('Perawatan.jenis_Perawatan', DB::raw('count(reservasi_rekam_medik.reservasi_id) as total'))
        ->groupBy('Perawatan.jenis_Perawatan')
        ->get();
    
        // Calculate patient stats for the selected month
        $patientStats = DB::table('Pasien')
        ->join('reservasi_rekam_medik', 'Pasien.pasien_id', '=', 'reservasi_rekam_medik.pasien_id')
        ->whereRaw("DATE_FORMAT(reservasi_rekam_medik.tanggal, '%Y-%m') = ?", [$month])
        ->select(
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 < 2 THEN 1 ELSE 0 END) as bayi"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 BETWEEN 2 AND 12 THEN 1 ELSE 0 END) as anak"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 BETWEEN 13 AND 19 THEN 1 ELSE 0 END) as remaja"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 BETWEEN 20 AND 59 THEN 1 ELSE 0 END) as dewasa"),
            DB::raw("SUM(CASE WHEN DATEDIFF(CURDATE(), tanggal_lahir) / 365.25 >= 60 THEN 1 ELSE 0 END) as lansia")
        )->first();
    
        // Fetch doctor performance for the selected month
        $doctorPerformance = DB::table('Dokter')
        ->leftJoin('reservasi_rekam_medik', function ($join) use ($month) {
            $join->on('Dokter.dokter_id', '=', 'reservasi_rekam_medik.dokter_id')
                 ->whereRaw("DATE_FORMAT(reservasi_rekam_medik.tanggal, '%Y-%m') = ?", [$month]);
        })
        ->select('Dokter.nama', DB::raw('count(reservasi_rekam_medik.dokter_id) as total'))
        ->groupBy('Dokter.nama')
        ->get();
    
        // Determine the user role and return the appropriate view
        $user = Auth::user();
        if ($user->role === 'owner') {
            return view('owner.home', compact('absences','visitStats', 'currentMonth', 'month', 'visits', 'doctor', 'location', 'treatments', 'patientStats', 'doctorPerformance', 'profit'));
        } else {
            return view('admin.home', compact('absences','visitStats', 'currentMonth', 'month', 'visits', 'doctor', 'location', 'treatments', 'patientStats', 'doctorPerformance', 'profit'));
        }
    }
    

    private function getMonthlyVisits($month)
    {
        $year = substr($month, 0, 4);
        $monthNumber = substr($month, 5, 2);

        $visits = DB::table('reservasi_rekam_medik')
        ->whereYear('tanggal', $year)
        ->whereMonth('tanggal', $monthNumber)
        ->get();

        $week1 = $visits->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 1;
        })->count();

        $week2 = $visits->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 2;
        })->count();

        $week3 = $visits->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 3;
        })->count();

        $week4 = $visits->filter(function ($visit) {
            return Carbon::parse($visit->tanggal)->weekOfMonth === 4;
        })->count();

        $thisMonth = $visits->count();

        return [
            'week1' => $week1,
            'week2' => $week2,
            'week3' => $week3,
            'week4' => $week4,
            'this_month' => $thisMonth,
        ];
    }
}
