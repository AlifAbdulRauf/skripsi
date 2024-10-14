@extends('layout.v_template')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Pasien </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>no</th>
            <th>Nama</th>
            <th>Pekerjaan</th>
            <th>Alamat</th>
            <th>No HP</th>
            <th>Rekam Medik</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($reservasi as $pas)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $pas->nama_pasien}}</td> 
            <td>{{ $pas->pekerjaan_pasien}}</td>
            <td>{{ $pas->alamat_pasien}}</td>
            <td>
                {{ $pas->notelp_pasien}}
            </td>
            <td><a href="{{ route("admin.rekampasien", [$pas->pasien_id]) }}">Rekam Medik</a></td>
            <td>
                <div class="d-flex">
                    <a href="/adatapasien/edit/{{ $pas->pasien_id }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('datapasien.destroy', [$pas->pasien_id]) }}" method="POST" display="inline" onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data pasien ini juga akan ikut terhapus');">
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

{{-- <a type="submit" class="btn btn-success mx-auto" href="/adatapasien/add" style="font-size: 16px; font-weight: bold;">Tambahkan Data pasien</a> --}}
@endsection