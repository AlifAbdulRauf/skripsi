@extends('layout.v_template_owner')

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
            <td><a href="{{ route("owner.rekampasien", [$pas->pasien_id]) }}">Rekam Medik</a></td>
        </tr>
    @endforeach

    </tbody>
</table>
@endsection