document.addEventListener('DOMContentLoaded', function () {

    // ── Preview foto ──────────────────────────────────────────────
    const inputFoto = document.getElementById('inputFoto');
    const preview   = document.getElementById('preview');
    if (inputFoto && preview) {
        inputFoto.addEventListener('change', () => {
            const [file] = inputFoto.files;
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });
    }

    // ── Dropdown Provinsi → Kabkot (AJAX) ────────────────────────
    const selectProvinsi = document.getElementById('pilihProvinsi');
    const selectKabkot   = document.getElementById('pilihKabkot');

    if (selectProvinsi && selectKabkot) {
        selectProvinsi.addEventListener('change', function () {
            const idProvinsi = this.value;

            if (!idProvinsi) {
                selectKabkot.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';
                selectKabkot.disabled = true;
                return;
            }

            selectKabkot.innerHTML = '<option value="">Memuat data...</option>';
            selectKabkot.disabled  = true;

            // GET_KABKOT_URL didefinisikan di Blade view
            fetch(`${GET_KABKOT_URL}?id_prov=${idProvinsi}`)
                .then(res => res.json())
                .then(data => {
                    selectKabkot.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';
                    data.forEach(kabkot => {
                        const opt       = document.createElement('option');
                        opt.value       = kabkot.id;
                        opt.textContent = kabkot.nama_kabkot;
                        selectKabkot.appendChild(opt);
                    });
                    selectKabkot.disabled = false;
                })
                .catch(err => {
                    console.error('Terjadi kesalahan:', err);
                    selectKabkot.innerHTML = '<option value="">Gagal memuat data</option>';
                });
        });
    }

    // ── Validasi form sebelum submit ──────────────────────────────
    const form = document.getElementById('formPendaftaran');
    if (form) {
        form.addEventListener('submit', function (e) {
            const nama   = document.querySelector('input[name="nama"]').value;
            const notelp = document.querySelector('input[name="notelp"]').value;
            const jk     = document.querySelector('input[name="jk"]:checked');

            if (nama.trim().length < 3) {
                e.preventDefault();
                Swal.fire('Oops!', 'Nama minimal 3 karakter', 'error');
                return;
            }
            if (!jk) {
                e.preventDefault();
                Swal.fire('Peringatan', 'Silakan pilih Jenis Kelamin', 'warning');
                return;
            }
            if (notelp !== '' && !/^\d+$/.test(notelp)) {
                e.preventDefault();
                Swal.fire('Error', 'Nomor telepon harus berupa angka!', 'error');
                return;
            }
        });
    }

    // ── Search / filter tabel ─────────────────────────────────────
    const searchInput = document.getElementById('cariSiswa');
    if (searchInput) {
        searchInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') e.preventDefault();
        });
        searchInput.addEventListener('input', function () {
            const filter = this.value.toLowerCase();
            document.querySelectorAll('table tbody tr').forEach(row => {
                if (row.cells.length > 0) {
                    const nama = row.cells[0].textContent.toLowerCase();
                    row.style.display = nama.includes(filter) ? '' : 'none';
                }
            });
        });
    }
});

// ── Konfirmasi hapus dengan SweetAlert2 ───────────────────────────
function hapusData(id) {
    Swal.fire({
        title: 'Apakah anda yakin?',
        text: 'Data yang dihapus tidak bisa dikembalikan!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (result.isConfirmed) {
            document.getElementById('form-hapus-' + id).submit();
        }
    });
}
