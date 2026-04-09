document.addEventListener('DOMContentLoaded', function () {

   // ── Preview foto baru ─────────────────────────────────────────
const inputFoto   = document.getElementById('inputFotoEdit');
const previewBaru = document.getElementById('previewBaru');
const previewLama = document.getElementById('previewLama');

// Tampilkan foto lama saat halaman dimuat
if (previewLama) {
    const fotoLamaSrc = previewLama.getAttribute('data-src') || previewLama.src;
    if (fotoLamaSrc) {
        previewLama.src           = fotoLamaSrc;
        previewLama.style.display = 'block';
        previewLama.style.opacity = '1';
    }
}

// Preview foto baru saat file dipilih
if (inputFoto && previewBaru) {
    inputFoto.onchange = () => {
        const [file] = inputFoto.files;
        if (file) {
            // Tampilkan preview baru
            previewBaru.src           = URL.createObjectURL(file);
            previewBaru.style.display = 'block';

            // Redup-kan foto lama sebagai perbandingan
            if (previewLama) {
                previewLama.style.opacity    = '0.4';
                previewLama.style.transition = 'opacity 0.3s ease';
            }
        } else {
            // Reset jika file dibatalkan
            previewBaru.src           = '';
            previewBaru.style.display = 'none';
            if (previewLama) previewLama.style.opacity = '1';
        }
    };
}

    // ── Validasi form edit ────────────────────────────────────────
    const form = document.getElementById('formEdit');
    if (form) {
        form.addEventListener('submit', function (e) {
            const nama   = document.querySelector('input[name="nama"]').value;
            const notelp = document.querySelector('input[name="notelp"]').value;

            if (nama.trim().length < 3) {
                e.preventDefault();
                Swal.fire('Oops!', 'Nama minimal 3 karakter!', 'error');
                return;
            }
            if (notelp !== '' && !/^\d+$/.test(notelp)) {
                e.preventDefault();
                Swal.fire('Error', 'Nomor telepon harus berupa angka!', 'error');
                return;
            }
        });
    }

    // ── Dropdown Provinsi → Kabkot (AJAX) ────────────────────────
    const selectProvinsi = document.getElementById('pilihProvinsi');
    const selectKabkot   = document.getElementById('pilihKabkot');

    function loadKabkot(idProvinsi, idKabkotSelected = null) {
        if (!idProvinsi) {
            selectKabkot.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';
            selectKabkot.disabled  = true;
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
                    if (idKabkotSelected && kabkot.id == idKabkotSelected) {
                        opt.selected = true;
                    }
                    selectKabkot.appendChild(opt);
                });
                selectKabkot.disabled = false;
            })
            .catch(err => {
                console.error('Terjadi kesalahan:', err);
                selectKabkot.innerHTML = '<option value="">Gagal memuat data</option>';
            });
    }

    if (selectProvinsi && selectKabkot) {
        const savedProvId   = selectProvinsi.value;
        const savedKabkotId = selectKabkot.getAttribute('data-selected');

        // Load kabkot saat halaman pertama dibuka (mode edit)
        if (savedProvId) {
            loadKabkot(savedProvId, savedKabkotId);
        }

        selectProvinsi.addEventListener('change', function () {
            loadKabkot(this.value, null);
        });
    }
});
