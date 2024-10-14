@extends('layout.v_template_owner')

@section('main-content')
<h3 class="font-weight-bold mx-4">Tambah data Layanan </h3>
<!-- Page Heading -->
<div class="mx-4">
    <form action="/datalayanan/insert" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Layanan</label>
            <input name="jenis_Perawatan" class="form-control @error('jenis_Perawatan') is-invalid @enderror" value="{{ old('jenis_Perawatan') }}">
            <div class="invalid-feedback">
                @error('jenis_Perawatan')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}">
            <div class="invalid-feedback">
                @error('harga')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Estimasi Waktu</label>
            <input name="estimasi_waktu_perawatan" class="form-control @error('estimasi_waktu_perawatan') is-invalid @enderror" value="{{ old('estimasi_waktu_perawatan') }}">
            <div class="invalid-feedback">
                @error('estimasi_waktu_perawatan')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-success btn-sm">Simpan</button>
        </div>
    </form>
</div>
@endsection
