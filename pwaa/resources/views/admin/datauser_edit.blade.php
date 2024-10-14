@extends('layout.v_template')

@section('main-content')
<h3 class="font-weight-bold mx-4">Edit data User </h3>
<!-- Page Heading -->
<div class="mx-4" >
    <form  action="/datauser/update/{{ $user->id }}" method="POST" enctype="multipart/form-data" style="width: 95%;">
        @csrf
        <div class="form-group">
            <label>Nama </label>
            <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name }}">
            <div class="invalid-feedback">
                @error('name')
                    {{ $message }}
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $user->email }}">
            <div class="invalid-feedback">
                @error('email')
                    {{ $message }}
                @enderror
            </div>
        </div>
        
        <div class="form-group">
            <label>Role </label>
            <input name="role" class="form-control @error('role') is-invalid @enderror" value="{{ $user->role }}">
            <div class="invalid-feedback">
                @error('role')
                    {{ $message }}
                @enderror
            </div>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>


@endsection
