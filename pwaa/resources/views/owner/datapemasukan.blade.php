@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Pemasukan </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Perawatan</th>
            <th>Total Pemasukan</th>
            <th>Nama Pasien</th>
            <th>Nama Dokter</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>

    @foreach ($pemasukan as $pem)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td> 
            <td>
                @foreach ($pem->perawatan_reservasi as $perare)
                    {{ $perare->perawatan->jenis_Perawatan }}
                @endforeach
            </td>
            <td>RP. {{ $pem->total_harga_perawatan }}</td> 
            <td>{{ $pem->pasien->nama}}</td>
            <td>{{ $pem->dokter->nama}}</td>
            <td>{{ $pem->tanggal}}</td>
        </tr>
    @endforeach

    </tbody>
</table>

@endsection