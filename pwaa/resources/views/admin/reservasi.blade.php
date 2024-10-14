@extends('layout.v_template')

@section('main-content')
<h3 class="font-weight-bold mx-4">Menu Reservasi </h3>
<!-- Page Heading -->
<div class="row" style="height: 45vh;">
    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-6 col-md-6 d-flex align-items-center justify-content-center" style="min-height: 100%;">
        <a type="submit" class="btn btn-success mx-auto px-5" href="/reservasi/admin" style="font-size: 25px; font-weight: bold;">Tambahkan Reservasi</a>
    </div>
    
    <div class="col-xl-6 col-md-6 d-flex align-items-center justify-content-center" style="min-height: 100%;">
        <a type="submit" class="btn btn-success mx-auto px-5" href="/reservasi/pasien" style="font-size: 25px; font-weight: bold;">Reservasi Dari Pasien</a>
    </div>
</div>

@endsection