@extends('layout.v_template')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data Reservasi dari Admin </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>No </th>
            <th>Nama Pasien</th>
            <th>Lokasi Perawatan</th>
            <th>Nama Dokter</th>
            <th>Tanggal</th>
            <th>Nomor HP</th>
            <th>Jam</th>
            <th>Rekam Medik</th>
            <th>Aksi</th>
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
            <td>
                @php
                    $nomor_hp = $res->no_Telp;
                    if (substr($nomor_hp, 0, 2) == '08') {
                        $nomor_hp = '628' . substr($nomor_hp, 2);
                    }
                @endphp
                <span style="display: flex; align-items: center;">
                    {{ $nomor_hp }}
                    <form action="{{ route('whatsapp.send') }}" method="POST" style="margin-left: 10px;">
                        @csrf
                        <input type="hidden" name="phone" value="{{ $nomor_hp }}">
                        <input type="hidden" name="message" value="Halo Ini dari Admin Klinik Xenon Dental House, jangan lupa hari ini datang perawatan pada jam {{ $res->jam_mulai }} yaa">
                        <button type="submit" class="btn">
                            <i class="fab fa-whatsapp" style="font-size:6mm; color: green"></i>
                        </button>
                    </form>
                </span>
            </td>
            <td>{{ $res->jam_mulai}}</td>
            <td><a href="{{ route("admin.rekampasien", [$res->pasien_id]) }}">Rekam Medik</a></td>
            <td>
                <div class="d-flex">
                    <a href="{{ route("reservasi.edit", [$res->reservasi_id ])}}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('reservasi.destroy', [$res->reservasi_id]) }}" method="POST" display="inline"
                        onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data dokter ini seperti rekam medik, jadwal dan lainnya juga akan ikut terhapus');">
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

<a type="submit" class="btn btn-success mx-auto" href="{{ route("reservasi.add") }}"
    style="font-size: 16px; font-weight: bold;">Tambah Reservasi Pasien Baru</a>
    <a type="submit" class="btn btn-success mx-auto" href="{{ route("reservasi_pasien_lama.add") }}"
    style="font-size: 16px; font-weight: bold;">Tambah Reservasi Pasien Lama</a>
@endsection