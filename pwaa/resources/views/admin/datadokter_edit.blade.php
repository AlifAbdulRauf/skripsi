@extends('layout.v_template')

@section('main-content')
<h3 class="font-weight-bold mx-4">Edit data Dokter </h3>
<!-- Page Heading -->
<div class="mx-4">
    <form action="/datadokter/update/{{ $datadokter->dokter_id }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama Dokter</label>
            <input name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ $datadokter->nama }}">
            <div class="invalid-feedback">
                @error('nama')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <input name="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ $datadokter->alamat }}">
            <div class="invalid-feedback">
                @error('alamat')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Nomor HP</label>
            <input name="nomor_hp" class="form-control @error('nomor_hp') is-invalid @enderror" value="{{ $datadokter->nomor_hp }}">
            <div class="invalid-feedback">
                @error('nomor_hp')
                    {{ $message }}
                @enderror
            </div>
        </div>



        <div class="form-group">
            <button class="btn btn-primary btn-sm">Simpan</button>
        </div>
    </form>
</div>
@endsection
