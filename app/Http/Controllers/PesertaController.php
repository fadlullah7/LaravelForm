<?php

namespace App\Http\Controllers;

use App\Models\Kabkot;
use App\Models\Peserta;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PesertaController extends Controller
{
    /**
     * Tampilkan form pendaftaran + tabel data peserta.
     */
    public function index()
    {
        $dataProvinsi = Provinsi::orderBy('nama_provinsi')->get();
        $dataPeserta  = Peserta::orderByDesc('id')->get();

        return view('peserta.index', compact('dataProvinsi', 'dataPeserta'));
    }

    /**
     * Simpan data peserta baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|min:3',
            'tempat'      => 'required',
            'tanggal'     => 'required|date',
            'agama'       => 'required',
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabkot_id'   => 'required|exists:kabkot,id',
            'notelp'      => 'nullable|numeric',
            'foto'        => 'nullable|image|max:2048',
        ]);

        $jkValue = match($request->jk) {
            'Pria'   => 0,
            'Wanita' => 1,
            default  => null,
        };

        $hobi = !empty($request->hobi) ? implode(', ', $request->hobi) : null;

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads', 'public');
        }

        Peserta::create([
            'nama'        => $request->nama,
            'tempatLahir' => $request->tempat,
            'tanggalLahir'=> $request->tanggal,
            'agama'       => $request->agama,
            'alamat'      => $request->alamat,
            'telepon'     => $request->notelp,
            'jk'          => $jkValue,
            'hobi'        => $hobi,
            'foto'        => $fotoPath,
            'provinsi_id' => $request->provinsi_id,
            'kabkot_id'   => $request->kabkot_id,
        ]);

        return redirect()->route('peserta.index')
            ->with('success', 'Data peserta berhasil disimpan!');
    }

    /**
     * Tampilkan form edit peserta.
     */
    public function edit(Peserta $peserta)
    {
        $dataProvinsi = Provinsi::orderBy('nama_provinsi')->get();
        return view('peserta.edit', compact('peserta', 'dataProvinsi'));
    }

    /**
     * Update data peserta.
     */
    public function update(Request $request, Peserta $peserta)
    {
        $request->validate([
            'nama'        => 'required|min:3',
            'tempat'      => 'required',
            'tanggal'     => 'required|date',
            'agama'       => 'required',
            'provinsi_id' => 'required|exists:provinsi,id',
            'kabkot_id'   => 'required|exists:kabkot,id',
            'notelp'      => 'nullable|numeric',
            'foto'        => 'nullable|image|max:2048',
        ]);

        $jkValue = match($request->jk) {
            'Pria'   => 0,
            'Wanita' => 1,
            default  => null,
        };

        $hobi = !empty($request->hobi) ? implode(', ', $request->hobi) : null;

        $fotoPath = $peserta->foto;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('uploads', 'public');
        }

        $peserta->update([
            'nama'        => $request->nama,
            'tempatLahir' => $request->tempat,
            'tanggalLahir'=> $request->tanggal,
            'agama'       => $request->agama,
            'alamat'      => $request->alamat,
            'telepon'     => $request->notelp,
            'jk'          => $jkValue,
            'hobi'        => $hobi,
            'foto'        => $fotoPath,
            'provinsi_id' => $request->provinsi_id,
            'kabkot_id'   => $request->kabkot_id,
        ]);

        return redirect()->route('peserta.index')
            ->with('success', 'Data peserta berhasil diperbarui!');
    }

    /**
     * Hapus data peserta.
     */
    public function destroy(Peserta $peserta)
    {
        if ($peserta->foto && Storage::disk('public')->exists($peserta->foto)) {
            Storage::disk('public')->delete($peserta->foto);
        }
        $peserta->delete();

        return redirect()->route('peserta.index')
            ->with('success', 'Data peserta berhasil dihapus!');
    }

    /**
     * AJAX: ambil daftar kabupaten/kota berdasarkan provinsi.
     */
    public function getKabkot(Request $request)
    {
        $kabkotList = Kabkot::where('provinsi_id', $request->id_prov)
            ->orderBy('nama_kabkot')
            ->get(['id', 'nama_kabkot']);

        return response()->json($kabkotList);
    }
}
