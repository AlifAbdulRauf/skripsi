@extends('layout.v_template_dokter')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Perizinan </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr><th>No</th>
            <th>Tanggal Awal</th>
            <th>Tanggal Akhir</th>
            <th>Alasan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($izin_user as $izin)
            <tr>
                <td scope="row">{{ $loop->iteration }}</td>
                <td>{{ $izin->tanggal_awal }}</td>
                <td>{{ $izin->tanggal_akhir }}</td>
                <td>{{ $izin->alasan }}</td>    
                <td>
                    @if (is_null($izin->status))
                        Belum diproses
                    @elseif ($izin->status)
                        Diterima
                    @else
                        Ditolak
                    @endif
                </td>
                <td>
                    <div class="d-flex">
                        @if (is_null($izin->status))
                            <a href="{{ route("dataabsen.edit", [$izin->izin_id]) }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                            <form action="{{ route("dataabsen.destroy", [$izin->izin_id]) }}" method="POST" display="inline" onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data pasien ini juga akan ikut terhapus');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        @else
                            <button class="btn btn-sm btn-primary mr-2" disabled>Edit</button>
                            <button class="btn btn-danger" disabled>Hapus</button>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<a type="submit" class="btn btn-success mx-auto" href="/dataabsen/add" style="font-size: 16px; font-weight: bold;">Tambahkan Data Absen</a>
@endsection
