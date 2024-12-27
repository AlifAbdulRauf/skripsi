@extends('layout.v_template_owner')

@section('main-content')
<h3 class="font-weight-bold mx-4">Edit data Pengeluaran </h3>
<!-- Page Heading -->
<div class="mx-4" >
    <form action="{{ route("pengeluaran.update", [$pengeluaran->pengeluaran_id]) }}" method="post" enctype="multipart/form-data"> 
        @csrf
        <div class="form-group">
            <label>Nama Pengeluaran</label>
            <input name="nama_pengeluaran" class="form-control @error('nama_pengeluaran') is-invalid @enderror" value="{{ $pengeluaran->nama_pengeluaran }}">
            <div class="invalid-feedback">
                @error('nama_pengeluaran')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Pengeluaran</label>
            <input name="deskripsi_pengeluaran" class="form-control @error('deskripsi_pengeluaran') is-invalid @enderror" value="{{ $pengeluaran->deskripsi_pengeluaran }}">
            <div class="invalid-feedback">
                @error('deskripsi_pengeluaran')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Jenis Pengeluaran</label>
            <input name="kategori_pengeluaran" class="form-control @error('kategori_pengeluaran') is-invalid @enderror" value="{{ $pengeluaran->kategori_pengeluaran }}">
            <div class="invalid-feedback">
                @error('kategori_pengeluaran')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Jumlah Pengeluaran</label>
            <input name="jumlah_pengeluaran" type="number" class="form-control @error('jumlah_pengeluaran') is-invalid @enderror" value="{{ $pengeluaran->jumlah_pengeluaran }}">
            <div class="invalid-feedback">
                @error('jumlah_pengeluaran')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ $pengeluaran->tanggal }}">
            <div class="invalid-feedback">
                @error('tanggal')
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
