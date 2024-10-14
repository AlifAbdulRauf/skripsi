@extends('layout.v_template')

@section('main-content')
<h3 class="font-weight-bold mx-4">Tambah data Dokter </h3>
<!-- Page Heading -->
<div class="mx-4">
    <form action="/datauserdokter/insert" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama Dokter</label>
            <input name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}">
            <div class="invalid-feedback">
                @error('nama')
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
            <label>Nomor Hp </label>
            <input name="nomor_hp" class="form-control @error('nomor_hp') is-invalid @enderror" value="{{ old('nomor_hp') }}">
            <div class="invalid-feedback">
                @error('nomor_hp')
                    {{ $message }}
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label>Lokasi Praktek</label>
            <select name="lokasi_id" class="form-control @error('lokasi_id') is-invalid @enderror">
                @foreach($lokasi as $item)
                    <option value="{{ $item->lokasi_id }}" @if(old('lokasi_id') == $item->lokasi_id) selected @endif>{{ $item->nama_Lokasi }}</option>
                @endforeach
            </select>
            <div class="invalid-feedback">
                @error('lokasi_id')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
            <div class="invalid-feedback">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Password </label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" value="{{ old('password') }}">
            <div class="invalid-feedback">
                @error('password')
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
