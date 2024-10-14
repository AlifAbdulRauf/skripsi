@extends('layout.v_template_dokter')

@section('main-content')
<h3 class="font-weight-bold mx-4">Edit Rekam Medik </h3>
<!-- Page Heading -->
<div class="mx-4" >
    <form  method="POST" action="{{ route("dokter.rekampasien.update",[$reservasi->reservasi_id]) }}" method="POST" enctype="multipart/form-data" style="width: 95%;">
        @csrf
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="content mr-5">

                    <div class="form-group">
                        <label>Golongan Darah</label>
                        <input name="golongan_darah" class="form-control @error('golongan_darah') is-invalid @enderror" value="{{ $reservasi->golongan_darah }}">
                        <div class="invalid-feedback">
                            @error('golongan_darah')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tekanan Darah</label>
                        <input name="tekanan_darah" class="form-control @error('tekanan_darah') is-invalid @enderror" value="{{ $reservasi->tekanan_darah }}">
                        <div class="invalid-feedback">
                            @error('tekanan_darah')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Penyakit Jantung</label>
                        <input name="penyakit_jantung" class="form-control @error('penyakit_jantung') is-invalid @enderror" value="{{ $reservasi->penyakit_jantung }}">
                        <div class="invalid-feedback">
                            @error('penyakit_jantung')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Diabetes</label>
                        <input name="diabetes" class="form-control @error('diabetes') is-invalid @enderror" value="{{ $reservasi->diabetes }}">
                        <div class="invalid-feedback">
                            @error('diabetes')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Hepatitis</label>
                        <input name="hepatitis" class="form-control @error('hepatitis') is-invalid @enderror" value="{{ $reservasi->hepatitis }}">
                        <div class="invalid-feedback">
                            @error('hepatitis')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Penyakit Lainnya</label>
                        <input name="penyakit_lainnya" class="form-control @error('penyakit_lainnya') is-invalid @enderror" value="{{ $reservasi->penyakit_lainnya }}">
                        <div class="invalid-feedback">
                            @error('penyakit_lainnya')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alergi makanan</label>
                        <input name="alergi_makanan" class="form-control @error('alergi_makanan') is-invalid @enderror" value="{{ $reservasi->alergi_makanan }}">
                        <div class="invalid-feedback">
                            @error('alergi_makanan')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alergi Obat</label>
                        <input name="alergi_obat" class="form-control @error('alergi_obat') is-invalid @enderror" value="{{ $reservasi->alergi_obat }}">
                        <div class="invalid-feedback">
                            @error('alergi_obat')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keluhan</label>
                        <input name="keluhan" class="form-control @error('keluhan') is-invalid @enderror" value="{{ $reservasi->keluhan }}">
                        <div class="invalid-feedback">
                            @error('keluhan')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Perawatan</label>
                        <select name="perawatan_id[]" id="perawatan_id" class="form-control select2-multiple @error('perawatan_id') is-invalid @enderror" multiple>
                            @foreach($perawatan as $item)
                            <option value="{{ $item->perawatan_id }}"  @if(in_array($item->perawatan_id, (array) old('perawatan_id', $idperawatan_reservasi)))
                                selected
                            @endif>
                            {{ $item->jenis_Perawatan }} ({{ $item->estimasi_waktu_perawatan }} menit)</option>
                        @endforeach 
                        </select>
                        <div class="invalid-feedback">
                            @error('perawatan_id')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                    @foreach($perawatan_reservasi as $index => $item)
                    <div class="form-group">
                        <label>Rencana Tindak Lanjut {{ $item->jenis_Perawatan }}</label>
                        <input name="tindak[]" id="tindak" class="form-control @error('tindak') is-invalid @enderror" value="{{ $item->rencana_tindak_lanjut }}">
                        <div class="invalid-feedback">
                            @error('tindak')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="form-group">
                        <label>Gigi</label>
                        <input name="gigi" class="form-control @error('gigi') is-invalid @enderror" value="{{ $reservasi->gigi }}">
                        <div class="invalid-feedback">
                            @error('gigi')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Submit</button>
    </form>
</div>


@endsection
