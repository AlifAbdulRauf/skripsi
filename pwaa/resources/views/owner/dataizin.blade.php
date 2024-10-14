@extends('layout.v_template_owner')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Perizinan </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr><th>No</th>
            <th>Tanggal Awal</th>
            <th>Tanggal Akhir</th>
            <th>Alasan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($izin_user_owner as $izin)
            <tr>
                <td scope="row">{{ $loop->iteration }}</td>
                <td>{{ $izin->tanggal_awal }}</td>
                <td>{{ $izin->tanggal_akhir }}</td>
                <td>{{ $izin->alasan }}</td>    
                <td>
                    <div class="d-flex">
                            <form action="{{  route("owner.izin.terima", [$izin->izin_id ]) }}" method="POST" display="inline">
                                @csrf
                                <button type="submit" class="btn btn-primary mr-2">Terima</button>
                            </form>
                            <form action="{{  route("owner.izin.tolak", [$izin->izin_id ]) }}" method="POST" display="inline">
                                @csrf
                                <button type="submit" class="btn btn-danger mr-2">Tolak</button>
                            </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
