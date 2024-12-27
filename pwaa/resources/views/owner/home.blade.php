@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->
<h3 class="font-weight-bold">Dashboard Owner </h3>

<div class="row">
    <div class="col-xl-9 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">Jumlah Kunjungan Berdasarkan Bulan</h6>
                <form action="{{ route('owner.filter') }}" method="POST" class="form-inline">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="visit_month" class="sr-only">Pilih Bulan</label>
                        <input type="month" id="visit_month" name="month" class="form-control" value="{{ isset($month) ? $month : '' }}">
                    </div>
                    <button type="submit" class="btn btn-success ml-2 mb-2">Filter</button>
                </form>
            </div>
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col-xl-9 mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1"></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Kunjungan</div>
                        <div class="row mt-2">
                            <div class="h5 col-xl-3">
                                Minggu pertama : {{ $visitStats['week1'] }}
                            </div>
                            <div class="h5 col-xl-3">
                                Minggu kedua : {{ $visitStats['week2'] }}
                            </div>
                            <div class="h5 col-xl-3">
                                Minggu ketiga : {{ $visitStats['week3'] }}
                            </div>
                            <div class="h5 col-xl-3">
                                Minggu keempat : {{ $visitStats['week4'] }}
                            </div>
                            <div class="h5 col-xl-3">
                                Bulan ini : {{ $visitStats['this_month'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger">Profit Berdasarkan Bulan</h6>
            </div>
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">RP {{ $profit ?? '0' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Existing content -->

    <div class="col-lg-6 mb-4">
        <!-- Peforma dokter berdasarkan jumlah pasien dalam 1 bulan -->
        <div class="card border-left-info shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-info">Peforma dokter berdasarkan jumlah pasien dalam 1 bulan</h6>
            </div>
            <div class="card-body">
                <canvas id="doctorChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <!-- Statistik Pasien -->
        <div class="card border-left-primary shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Statistik Pasien</h6>
            </div>
            <div class="card-body">
                <canvas id="patientChart"></canvas>
            </div>
        </div>
    </div>

</div>

<div class="row justify-content-center">
    <div class="col-lg-6 mb-4">
        <!-- Jumlah Perkategori Perawatan dalam 1 bulan -->
        <div class="card border-left-warning shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-warning">Jumlah Perkategori Perawatan dalam 1 bulan</h6>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div style="width: 75%; height: 75%;">
                    <canvas id="treatmentChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Treatment Chart
const treatmentCtx = document.getElementById('treatmentChart').getContext('2d');
const treatmentChart = new Chart(treatmentCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($treatments->pluck('jenis_Perawatan')->toArray()) !!},  // Label jenis perawatan
        datasets: [{
            label: 'Jumlah Perawatan',  // Label pada grafik
            data: {!! json_encode($treatments->pluck('total')->toArray()) !!},  // Data jumlah perawatan
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],  // Warna untuk setiap batang
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                beginAtZero: true  // Memastikan sumbu x dimulai dari nol
            },
            y: {
                beginAtZero: true,  // Memastikan sumbu y dimulai dari nol
                ticks: {
                    callback: function(value) {
                        return Number.isInteger(value) ? value : ''; // Hanya tampilkan bilangan bulat
                    }
                }
            }
        },
        plugins: {
            legend: {
                display: true,  // Menampilkan legenda
                position: 'top',  // Posisi legenda di atas grafik
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.dataset.label + ': ' + tooltipItem.raw;  // Menampilkan jumlah perawatan tanpa persentase
                    }
                }
            }
        }
    }
});

// Patient Chart
const patientCtx = document.getElementById('patientChart').getContext('2d');
const patientChart = new Chart(patientCtx, {
    type: 'bar',
    data: {
        labels: ['Bayi', 'Anak', 'Remaja', 'Dewasa', 'Lansia'],
        datasets: [{
            label: 'Jumlah Pasien',
            data: [{{ $patientStats->bayi }}, {{ $patientStats->anak }}, {{ $patientStats->remaja }}, {{ $patientStats->dewasa }}, {{ $patientStats->lansia }}],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                beginAtZero: true
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return Number.isInteger(value) ? value : ''; // Hanya tampilkan bilangan bulat
                    }
                }
            }
        }
    }
});

// Doctor Performance Chart
const doctorCtx = document.getElementById('doctorChart').getContext('2d');
const doctorChart = new Chart(doctorCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($doctorPerformance->pluck('nama')->toArray()) !!},
        datasets: [{
            label: 'Jumlah Pasien',
            data: {!! json_encode($doctorPerformance->pluck('total')->toArray()) !!},
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: {
                beginAtZero: true
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return Number.isInteger(value) ? value : ''; // Hanya tampilkan bilangan bulat
                    }
                }
            }
        }
    }
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const visitMonthInput = document.getElementById('visit_month');
        visitMonthInput.value = "{{ isset($month) ? $month : '' }}";
    });
</script>
@endsection
