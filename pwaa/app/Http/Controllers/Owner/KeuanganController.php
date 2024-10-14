<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class KeuanganController extends Controller
{
    public function index()
    {
        // Mendapatkan semua tahun yang ada dalam tabel reservasi_rekam_medik dan pengeluaran
        $years = DB::table('reservasi_rekam_medik')
                    ->selectRaw('YEAR(tanggal) as year')
                    ->distinct()
                    ->union(
                        DB::table('pengeluaran')
                        ->selectRaw('YEAR(tanggal) as year')
                        ->distinct()
                    )
                    ->orderBy('year', 'desc')
                    ->pluck('year');

        // Daftar bulan
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Array untuk menampung data pemasukan, pengeluaran, dan profit
        $keuangan = [];

        foreach ($years as $year) {
            foreach ($months as $monthNum => $monthName) {
                // Menghitung total pemasukan untuk bulan tertentu pada tahun tertentu
                $totalIncome = DB::table('Perawatan')
                    ->join('perawatan_reservasi', 'Perawatan.perawatan_id', '=', 'perawatan_reservasi.perawatan_id')
                    ->join('reservasi_rekam_medik', 'perawatan_reservasi.reservasi_id', '=', 'reservasi_rekam_medik.reservasi_id')
                    ->where('reservasi_rekam_medik.draft', 0)
                    ->whereYear('reservasi_rekam_medik.tanggal', $year)
                    ->whereMonth('reservasi_rekam_medik.tanggal', $monthNum)
                    ->sum('perawatan_reservasi.harga');

                // Menghitung total pengeluaran untuk bulan tertentu pada tahun tertentu
                $totalExpenses = DB::table('pengeluaran')
                    ->whereYear('tanggal', $year)
                    ->whereMonth('tanggal', $monthNum)
                    ->sum('jumlah_pengeluaran');

                // Menghitung profit
                $profit = $totalIncome - $totalExpenses;

                // Menyimpan data pemasukan, pengeluaran, dan profit ke dalam array
                if ($totalIncome > 0 || $totalExpenses > 0) { // Simpan hanya jika ada data
                    $keuangan[] = [
                        'year' => $year, // Tambahkan tahun untuk sorting
                        'monthNum' => $monthNum, // Tambahkan nomor bulan untuk sorting
                        'month' => $monthName . '-' . $year,
                        'totalIncome' => $totalIncome,
                        'totalExpenses' => $totalExpenses,
                        'profit' => $profit,
                    ];
                }
            }
        }

        // Urutkan keuangan berdasarkan tahun (desc), dan bulan dari Desember (12) ke Januari (1)
        usort($keuangan, function ($a, $b) {
            if ($a['year'] == $b['year']) {
                return $b['monthNum'] - $a['monthNum']; // Urutkan bulan secara descending
            }
            return $b['year'] - $a['year']; // Urutkan tahun secara descending
        });

        // Mengembalikan view dengan data yang telah dihitung
        return view('owner.keuangan', compact('keuangan'));
    }
}
