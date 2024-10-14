@extends('layout.v_template')

@section('main-content')
<!-- Page Heading -->

<h3 class="font-weight-bold">Data User </h3>

<table id="example" class="table table-striped table-bordered mt-2" style="width:100%">
    <thead>
        <tr>
            <th>no</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($user as $us)
        <tr>
            <td scope="row">{{ $loop->iteration }}</td>
            <td>{{ $us->name}}</td> 
            <td>{{ $us->email}}</td>
            <td>{{ $us->role}}</td>
            <td>
                <div class="d-flex">
                    <a href="/datauser/edit/{{ $us->id }}" class="btn btn-sm btn-primary mr-2">Edit</a>
                    <form action="{{ route('datauser.destroy', [$us->id]) }}" method="POST" display="inline" onsubmit="return confirm('Yakin ingin menghapus? Data yang berhubungan dengan data ini juga akan ikut terhapus');">
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

<div class="d-flex">
    <a type="submit" class="btn btn-success " href="/datauser/add" style="font-size: 16px; font-weight: bold;">Tambahkan Data User</a>
    <a type="submit" class="btn btn-success ml-5" href="/datauserdokter/add" style="font-size: 16px; font-weight: bold;">Tambah User Dokter</a>
</div>
@endsection