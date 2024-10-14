@extends('layout.v_template')

@section('main-content')
<!-- Page Heading -->
<h3 class="font-weight-bold mb-2">Dashboard Admin </h3>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@foreach($absences as $absence)
    <div class="alert alert-warning">
        Dokter {{ $absence->dokter->nama }} tidak bisa datang ke klinik dari tanggal {{ $absence->tanggal_awal }} hingga {{ $absence->tanggal_akhir }}.
    </div>
@endforeach
<div class="row">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Jumlah Kunjungan Berdasarkan Bulan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('home.filter') }}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-8">
                            <label for="month">Pilih Bulan</label>
                            <input type="month" id="month" name="month" class="form-control" value="{{$month ?? $currentMonth}}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary ml-2">Filter</button>
                        </div>
                    </div>
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"></div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $visits }} Kunjungan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-header py-3">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Dokter Tetap</div>
            </div>
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="card-body">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $doctor }} Dokter</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-header py-3">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah Cabang Klinik</div>
            </div>
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="card-body">
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $location }} Kota</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4 mx-auto">
        <!-- Illustrations -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-success">Xenon Dental House</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-1 mb-4" style="width: 100%;" src="{{ asset('img/fotoklinik.jpg') }}" alt="">
                    </div>
                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                        <div>
                            <h2 class="font-weight-bold text-success text-center">Motto Klinik</h3>
                            <h2 class="font-weight-bold text-dark text-center">Treat with heart like family</h2>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <a target="_blank" rel="nofollow" href="https://www.instagram.com/xenondentalhouse/">Instagram Official Xenon Dental House →</a>
                </div>
            </div>
        </div>
    </div>
</div>


    @endsection
    