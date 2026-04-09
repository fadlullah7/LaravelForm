@extends('layouts.app')

@section('title', 'Edit Data Siswa')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/editstyle.css') }}">
@endpush

@section('content')

<div class="container">
    <h2>Edit Data Siswa</h2>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formEdit" method="POST"
          action="{{ route('peserta.update', $peserta->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>Nama Calon Siswa</label>
        <input type="text" name="nama"
               value="{{ old('nama', $peserta->nama) }}" required>

        <label>Tempat Lahir</label>
        <input type="text" name="tempat"
               value="{{ old('tempat', $peserta->tempatLahir) }}" required>

        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal"
               value="{{ old('tanggal', \Carbon\Carbon::parse($peserta->tanggalLahir)->format('Y-m-d')) }}" required>

        <label>Agama</label>
        <select name="agama">
            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                <option value="{{ $agama }}"
                    {{ old('agama', $peserta->agama) == $agama ? 'selected' : '' }}>
                    {{ $agama }}
                </option>
            @endforeach
        </select>

        <label>Provinsi</label>
        <select name="provinsi_id" id="pilihProvinsi" required>
            <option value="">-- Pilih Provinsi --</option>
            @foreach($dataProvinsi as $prov)
                <option value="{{ $prov->id }}"
                    {{ old('provinsi_id', $peserta->provinsi_id) == $prov->id ? 'selected' : '' }}>
                    {{ $prov->nama_provinsi }}
                </option>
            @endforeach
        </select>

        <label>Kabupaten/Kota</label>
        <select name="kabkot_id" id="pilihKabkot"
                data-selected="{{ old('kabkot_id', $peserta->kabkot_id) }}" required>
            <option value="">-- Pilih Kab/Kota --</option>
        </select>

        <label>Alamat</label>
        <textarea name="alamat">{{ old('alamat', $peserta->alamat) }}</textarea>

        <label>No Telp/HP</label>
        <input type="text" name="notelp"
               value="{{ old('notelp', $peserta->telepon) }}">

        <label>Jenis Kelamin</label>
        <div class="inline">
            <input type="radio" name="jk" value="Pria"
                {{ old('jk', $peserta->jk) === 0 || old('jk', $peserta->jk) === '0' ? 'checked' : '' }}> Pria
            <input type="radio" name="jk" value="Wanita"
                {{ old('jk', $peserta->jk) == 1 ? 'checked' : '' }}> Wanita
        </div>



        <label>Pas Foto</label>
        @if($peserta->foto)
            <img src="{{ asset('storage/' . $peserta->foto) }}"
                 width="120" id="previewLama"
                 style="display:block; margin-bottom:10px; border-radius:8px;" class="img-preview">
        @endif

        <input type="file" name="foto" id="inputFotoEdit" accept="image/*">
        <img id="previewBaru" src="#"
             style="display:none; width:120px; margin-top:10px; border-radius:8px; border: 2px solid #333;" class="img-preview">

        <div class="btn-group" style="margin-top:20px;">
            <button type="submit" name="update">UPDATE DATA</button>
            <a href="{{ route('peserta.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    const GET_KABKOT_URL = "{{ route('get.kabkot') }}";
</script>
<script src="{{ asset('assets/js/edit.js') }}"></script>
@endpush
