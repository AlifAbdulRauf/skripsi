@extends('layout.v_template_dokter')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Jadwal Saya </h3>
<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr><th>No</th>
            <th>Hari</th>
            <th>Sesi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jadwalid as $jadwal_id)
            <tr>
                <td scope="row">{{ $loop->iteration }}</td>
                <td>{{ $jadwal_id->hari }}</td>
                <td>{{ $jadwal_id->sesi }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection