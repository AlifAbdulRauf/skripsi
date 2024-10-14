@extends('layout.v_template_pasien')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">History Reservasi </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>No </th>
            <th>Nama Pasien</th>
            <th>Lokasi Perawatan</th>
            <th>Nama Dokter</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($reservasi_rekam_medik as $res)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $res->nama}}</td>
            <td>{{ $res->nama_Lokasi}}</td>
            <td>{{ $res->nama_dokter}}</td>
            <td>{{ $res->tanggal}}</td>
            <td>{{ $res->jam_mulai}}</td>
            <td>
                @if (is_null($res->draft))
                    Belum diproses
                @elseif ($res->draft==1)
                    Ditolak
                @elseif ($res->draft==0)
                    Diterima
                @endif
            </td>
        </tr>
        @endforeach

    </tbody>
</table>

@endsection