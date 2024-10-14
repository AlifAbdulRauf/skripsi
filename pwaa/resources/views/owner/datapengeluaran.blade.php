@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Pengeluaran </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Pengeluaran</th>
            <th>Deskripsi Pengeluaran</th>
            <th>Jenis Pengeluaran</th>
            <th>Jumlah</th>
            <th>Tanggal</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($pengeluaran as $pen)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td> 
            <td>{{ $pen->nama_pengeluaran}}</td> 
            <td>{{ $pen->deskripsi_pengeluaran}}</td>
            <td>{{ $pen->kategori_pengeluaran}}</td>
            <td>{{ $pen->jumlah_pengeluaran}}</td>
            <td>{{ $pen->tanggal}}</td>
            <td>
                <div class="d-flex">
                    <a href="{{ route("pengeluaran.edit",[$pen->pengeluaran_id]) }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('pengeluaran.destroy', [$pen->pengeluaran_id]) }}" method="POST" display="inline" onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data pasien ini juga akan ikut terhapus');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
    @endforeach

    </tbody>
</table>

<a type="submit" class="btn btn-success mx-auto" href="/pengeluaran/add" style="font-size: 16px; font-weight: bold;">Tambahkan Data Pengeluaran</a>
@endsection