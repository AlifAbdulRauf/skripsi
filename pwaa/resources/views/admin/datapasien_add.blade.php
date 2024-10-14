@extends('layout.v_template')

@section('main-content')
<h3 class="font-weight-bold mx-4">Tambah data Pasien </h3>
<!-- Page Heading -->
<div class="mx-4">
    <form action="/adatapasien/insert" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama Pasien</label>
            <input name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
            <div class="invalid-feedback">
                @error('nama')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Tempat Lahir</label>
            <input name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" value="{{ old('tempat_lahir') }}">
            <div class="invalid-feedback">
                @error('tempat_lahir')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Tanggal Lahir </label>
            <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" value="{{ old('tanggal_lahir') }}">
            <div class="invalid-feedback">
                @error('tanggal_lahir')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Pekerjaan</label>
            <input name="pekerjaan" class="form-control @error('pekerjaan') is-invalid @enderror" value="{{ old('pekerjaan') }}">
            <div class="invalid-feedback">
                @error('pekerjaan')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <input name="alamat" class="form-control @error('alamat') is-invalid @enderror" value="{{ old('alamat') }}">
            <div class="invalid-feedback">
                @error('alamat')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>No HP</label>
            <input name="no_Telp" class="form-control @error('no_Telp') is-invalid @enderror" value="{{ old('no_Telp') }}">
            <div class="invalid-feedback">
                @error('no_Telp')
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
