@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Siswa')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@endpush

@section('content')

<div class="container">
    <h2>Formulir Pendaftaran Siswa</h2>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="formPendaftaran" method="POST" action="{{ route('peserta.store') }}" enctype="multipart/form-data">
        @csrf

        <label>Nama Calon Siswa</label>
        <input type="text" name="nama" value="{{ old('nama') }}" required>

        <label>Tempat Lahir</label>
        <input type="text" name="tempat" value="{{ old('tempat') }}" required>

        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal" value="{{ old('tanggal') }}" required>

        <label>Agama</label>
        <select name="agama">
            @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                <option value="{{ $agama }}" {{ old('agama') == $agama ? 'selected' : '' }}>
                    {{ $agama }}
                </option>
            @endforeach
        </select>

        <label>Provinsi</label>
        <select name="provinsi_id" id="pilihProvinsi" required>
            <option value="">-- Pilih Provinsi --</option>
            @foreach($dataProvinsi as $prov)
                <option value="{{ $prov->id }}" {{ old('provinsi_id') == $prov->id ? 'selected' : '' }}>
                    {{ $prov->nama_provinsi }}
                </option>
            @endforeach
        </select>

        <label>Kabupaten/Kota</label>
        <select name="kabkot_id" id="pilihKabkot" disabled required>
            <option value="">-- Pilih Kab/Kota --</option>
        </select>

        <label>Alamat Lengkap</label>
        <textarea name="alamat">{{ old('alamat') }}</textarea>

        <label>No Telp/HP</label>
        <input type="text" name="notelp" value="{{ old('notelp') }}">

        <label>Jenis Kelamin</label>
        <div class="inline">
            <input type="radio" name="jk" value="Pria" {{ old('jk') == 'Pria' ? 'checked' : '' }}> Pria
            <input type="radio" name="jk" value="Wanita" {{ old('jk') == 'Wanita' ? 'checked' : '' }}> Wanita
        </div>


        <label>Pas Foto</label>
        <input type="file" name="foto" id="inputFoto" accept="image/*">
        <img id="preview" src="#" alt="Preview Foto"
             style="display:none; width:100px; margin-top:10px; border-radius:5px; border:1px solid #ddd;">

        <button type="submit" name="submit">SUBMIT</button>
    </form>
</div>

<div class="container">
    <h2>Data Pendaftaran Siswa</h2>

    <input type="text" id="cariSiswa" class="search-box" placeholder="Cari nama siswa...">

    <table class="tabel-data">
        <thead>
            <tr>
                <th rowspan="2">Nama</th>
                <th colspan="2">Lahir</th>
                <th rowspan="2">No Telp</th>
                <th rowspan="2">Agama</th>
                <th rowspan="2">Aksi</th>
            </tr>
            <tr>
                <th>Tempat</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody id="tabelBody">
            @forelse($dataPeserta as $data)
                <tr>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->tempatLahir }}</td>
                    <td>{{ \Carbon\Carbon::parse($data->tanggalLahir)->translatedFormat('d F Y') }}</td>
                    <td>{{ $data->telepon ?? '-' }}</td>
                    <td>{{ $data->agama }}</td>
                    <td align="center">
                        <a href="{{ route('peserta.edit', $data->id) }}" class="btn-edit">Edit</a>

                        <form id="form-hapus-{{ $data->id }}"
                              action="{{ route('peserta.destroy', $data->id) }}"
                              method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn-delete"
                                onclick="hapusData({{ $data->id }})">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:20px; color:#999;">
                        Belum ada data pendaftaran.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
    const GET_KABKOT_URL = "{{ route('get.kabkot') }}";
</script>
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush
