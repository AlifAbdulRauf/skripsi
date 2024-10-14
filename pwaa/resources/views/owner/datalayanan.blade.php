@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Layanan </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Layanan</th>
            <th>Harga</th>
            <th>Lama Perawatan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($perawatan as $per)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $per->jenis_Perawatan}}</td> 
            <td>{{ $per->harga}}</td>
            <td>{{ $per->estimasi_waktu_perawatan}}</td>
            <td>
                <div class="d-flex">
                    <a href="{{ route("layanan.edit",[$per->perawatan_id]) }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('layanan.destroy', [$per->perawatan_id]) }}" method="POST" display="inline" onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data pasien ini juga akan ikut terhapus');">
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

<a type="submit" class="btn btn-success mx-auto" href="/datalayanan/add" style="font-size: 16px; font-weight: bold;">Tambahkan Data Perawatan</a>
@endsection