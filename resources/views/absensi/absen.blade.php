<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPG Jambuluwuk</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-50">
    <header class="fixed top-4 left-1/2 -translate-x-1/2 w-[90%] max-w-7xl z-50 
               transition-all duration-300 rounded-2xl bg-white/70 backdrop-blur-md 
               shadow-md shadow-sky-300/50 border border-sky-200">
        <nav class="flex items-center justify-between px-6 h-16">

            <!-- Logo -->
            <div class="flex items-center gap-2">
                <img src="/assets/img/logo-bgn.png" alt="Logo Bgn" class="w-12 h-12 object-contain">
                <span class="text-lg font-semibold text-gray-800">SPPG Jambuluwuk</span>
            </div>

            <!-- Menu Desktop -->
            <ul class="hidden md:flex gap-8 font-medium text-gray-700">
                <li>
                    <a href="{{ route('page') }}" class="relative group">
                        Home
                        <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-400 
                                     transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('absensi.index') }}" class="relative group">
                        Absensi
                        <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-400 
                                     transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('page') }}" class="relative group">
                        baju
                        <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-400 
                                     transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('page') }}" class="relative group">
                        Peraturan
                        <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-400 
                                     transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="#petugas" class="relative group">
                        Jadwal Petugas
                        <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-400 
                                     transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('page') }}" class="relative group">
                        Contact
                        <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-400 
                                     transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>
            </ul>

            <!-- Tombol Hubungi -->
            <a href="{{ route('login') }}" class="hidden md:inline-block px-5 py-2 rounded-lg font-semibold 
                       shadow-lg transition transform hover:scale-105 
                       bg-sky-400 text-white hover:bg-sky-500">
                Login Admin
            </a>

            <!-- Tombol Mobile -->
            <button id="menu-toggle" class="md:hidden focus:outline-none text-gray-700 text-2xl">
                ☰
            </button>
        </nav>

        <!-- Menu Mobile -->
        <div id="mobile-menu" class="hidden md:hidden mt-3 bg-white px-6 py-4 shadow-md rounded-2xl">
            <ul class="flex flex-col gap-3 text-gray-700 font-medium">
                <li><a href="{{ route('page') }}" class="hover:text-sky-500 transition">Home</a></li>
                <li><a href="{{ route('absensi.index') }}" class="hover:text-sky-500 transition">Absensi</a></li>
                <li><a href="{{ route('page') }}" class="hover:text-sky-500 transition">Baju</a></li>
                <li><a href="{{ route('page') }}" class="hover:text-sky-500 transition">Peraturan</a></li>
                <li><a href="#petugas" class="hover:text-sky-500 transition">Jadwal Petugas</a></li>
                <li><a href="{{ route('page') }}" class="hover:text-sky-500 transition">Contact</a></li>
                <li>
                    <a href="{{ route('login') }}" class=" block text-center px-4 py-2 bg-sky-400 text-white rounded-lg
                        shadow hover:bg-sky-500 transition">
                        Login Admin
                    </a>
                </li>
            </ul>
        </div>
    </header>
    <main class="pt-28 pb-12 px-6">
        <div class="max-w-2xl mx-auto">

            <!-- Tanggal & Jam -->
            <p class="text-gray-600 mb-8 text-center text-lg">
                Hari & Jam:
                <span id="current-day" class="font-semibold text-gray-900"></span>,
                <span id="current-time" class="font-semibold text-gray-900"></span>
            </p>

            <!-- Notifikasi -->
            <div id="notif"
                class="hidden fixed bottom-5 right-5 bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
                ✅ Absen hari ini telah selesai, silakan absen kembali besok.
            </div>

            @if(session('absensi_selesai'))
            <div
                class="fixed bottom-5 right-5 bg-green-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-2">
                ✅ {{ session('absensi_selesai') }}
            </div>
            @endif

            <!-- FORM REGISTRASI -->
            <div id="form-div" class="{{ $registrasiData ? 'hidden' : '' }}">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">📝 Form Registrasi Absensi</h2>

                <div class="bg-white p-6 rounded-2xl shadow-lg border border-gray-200">
                    <form id="absensi-form" action="{{ route('store.absensi') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Nama Karyawan -->
                        <div>
                            <label for="karyawan_id" class="block text-gray-700 mb-2 font-medium">Nama Karyawan</label>
                            <select id="karyawan_id" name="karyawan_id"
                                class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                                required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($karyawans as $karyawan)
                                <option value="{{ $karyawan->id }}">{{ $karyawan->nama }} ({{ $karyawan->tugas }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanda Tangan -->
                        <div>
                            <label class="block text-gray-700 mb-2 font-medium">Tanda Tangan</label>
                            <canvas id="signature-pad"
                                class="w-full h-40 border border-gray-300 rounded-xl bg-white shadow-inner"></canvas>
                            <input type="hidden" name="tanda_tangan" id="tanda_tangan">
                            <button type="button" id="clear-signature"
                                class="mt-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 shadow">
                                Hapus
                            </button>
                        </div>

                        <!-- Submit -->
                        <div>
                            <button id="submit-btn" type="submit"
                                class="w-full bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 shadow-lg transition">
                                Simpan Registrasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- FINGERPRINT -->
            <div id="fingerprint-div" class="{{ !$registrasiData ? 'hidden' : '' }} flex flex-col items-center mt-12">
                <h2 class="text-2xl font-bold mb-6 text-center">🔒 Tap Fingerprint untuk Absensi</h2>

                <!-- Info Karyawan (update via JS nanti) -->
                <div id="karyawan-info" class="w-full max-w-sm flex flex-col items-center mb-6">
                    @if($registrasiData)
                    @php
                    $karyawan = $karyawans->where('id', $registrasiData['karyawan_id'])->first();
                    @endphp
                    @if($karyawan)
                    <div
                        class="flex items-center gap-4 mb-8 bg-white text-gray-800 px-6 py-4 rounded-2xl shadow-lg shadow-sky-300 border border-sky-100 w-full">
                        <div
                            class="w-14 h-14 bg-blue-500 text-white flex items-center justify-center rounded-full shadow-md shadow-sky-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A8.966 8.966 0 0112 15c2.21 0 4.21.8 5.879 2.121M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-lg font-bold">{{ $karyawan->nama }}</p>
                            <p class="text-sm text-gray-500">{{ $karyawan->tugas }}</p>
                        </div>
                    </div>
                    @endif
                    @endif
                </div>

                <!-- Fingerprint Scanner -->
                <div id="fingerprint-scanner"
                    class="w-28 h-28 bg-gray-200 rounded-full flex items-center justify-center cursor-pointer shadow-lg hover:scale-105 hover:shadow-xl transition-all">
                    <img src="/assets/img/sidikjari1.png" class="w-16 h-16">
                </div>

                <div id="izin-div" class="hidden mt-4">
                    <label class="block text-gray-700 mb-2 font-medium">Absen dengan izin</label>
                    <select id="izin-select" class="w-full border border-gray-300 rounded-xl px-4 py-2">
                        <option value="">-- Pilih tipe izin --</option>
                        <option value="sakit">Sakit</option>
                        <option value="alpha">Alpha</option>
                        <option value="izin">Izin</option>
                    </select>
                    <button id="submit-izin"
                        class="mt-2 w-full bg-yellow-500 text-white px-4 py-2 rounded-xl hover:bg-yellow-600">Kirim
                        Izin</button>
                </div>

                <!-- Status -->
                <div id="statusMsg" class="mt-4 font-semibold text-blue-600"></div>

                <button id="reset-btn"
                    class="mt-8 bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 shadow transition">
                    Reset Registrasi
                </button>
            </div>


        </div>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        // ================= Jam real-time =================
        const dayEl = document.getElementById('current-day');
        const timeEl = document.getElementById('current-time');

        function updateTime() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            dayEl.textContent = days[now.getDay()];
            timeEl.textContent = now.toLocaleTimeString('id-ID', {
                hour12: false
            });
        }
        setInterval(updateTime, 1000);
        updateTime();

        // ================= Elements =================
        const formDiv = document.getElementById('form-div');
        const fingerprintDiv = document.getElementById('fingerprint-div');
        const izinDiv = document.getElementById('izin-div');
        const izinSelect = document.getElementById('izin-select');
        const submitIzinBtn = document.getElementById('submit-izin');
        const scanner = document.getElementById('fingerprint-scanner');
        const statusMsg = document.getElementById("statusMsg");
        const resetBtn = document.getElementById('reset-btn');
        const tandaTanganInput = document.getElementById('tanda_tangan');

        const toggleBtn = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        toggleBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Scroll halus untuk navbar
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    const headerOffset = 80;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                    if (!mobileMenu.classList.contains('hidden')) mobileMenu.classList.add(
                        'hidden');
                }
            });
        });

        // ================= Signature Pad =================
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255,255,255,1)',
            penColor: 'black'
        });

        const savedTtd = sessionStorage.getItem('tanda_tangan');
        if (savedTtd) {
            tandaTanganInput.value = savedTtd;
            const img = new Image();
            img.src = savedTtd;
            img.onload = () => {
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            }
        }

        signaturePad.onEnd = () => {
            const data = signaturePad.toDataURL();
            tandaTanganInput.value = data;
            sessionStorage.setItem('tanda_tangan', data);
        };

        document.getElementById('clear-signature').addEventListener('click', () => {
            signaturePad.clear();
            tandaTanganInput.value = '';
            sessionStorage.removeItem('tanda_tangan');
        });

        // ================= Notifikasi =================
        function showNotif(msg, type = 'success') {
            let notif = document.createElement('div');
            notif.className = `fixed bottom-5 right-5 px-4 py-3 rounded-xl shadow-lg flex items-center gap-2 
                           ${type==='success'?'bg-green-500 text-white':'bg-red-500 text-white'}`;
            notif.textContent = msg;
            document.body.appendChild(notif);
            setTimeout(() => notif.remove(), 4000);
        }

        // ================= Registrasi =================
        let selectedKaryawanId = @json($registrasiData['karyawan_id'] ?? null);
        if (selectedKaryawanId) {
            formDiv.classList.add('hidden');
            fingerprintDiv.classList.remove('hidden');
            statusMsg.textContent = "Klik fingerprint untuk absen masuk / keluar";
        }

        // ================= Submit Registrasi =================
        document.getElementById('absensi-form').addEventListener('submit', e => {
            e.preventDefault();
            if (signaturePad.isEmpty() && !tandaTanganInput.value) {
                alert("Tanda tangan wajib diisi!");
                return;
            }
            tandaTanganInput.value = tandaTanganInput.value || signaturePad.toDataURL();
            sessionStorage.setItem('tanda_tangan', tandaTanganInput.value);

            const formData = new FormData(e.target);
            fetch(e.target.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    selectedKaryawanId = formData.get('karyawan_id');
                    formDiv.classList.add('hidden');
                    fingerprintDiv.classList.remove('hidden');
                    statusMsg.textContent =
                    "Registrasi berhasil! Klik fingerprint untuk absen.";
                    localStorage.setItem('last_absensi',
                    'keluar'); // default setelah registrasi
                } else alert("Gagal registrasi: " + (data.message || "Coba lagi."));
            }).catch(err => console.error(err));
        });

        // ================= Submit Izin =================
        submitIzinBtn.addEventListener('click', () => {
            const tipeIzin = izinSelect.value;
            if (!tipeIzin) return alert("Silakan pilih tipe izin!");
            if (!selectedKaryawanId) return alert("Karyawan belum terpilih!");

            fetch("{{ route('absensi.izin') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        karyawan_id: selectedKaryawanId,
                        tipe_izin: tipeIzin,
                        tanda_tangan: tandaTanganInput.value || signaturePad.toDataURL()
                    })
                }).then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showNotif(
                            `✅ Izin "${tipeIzin}" telah dicatat. Anda masih bisa absen masuk/keluar.`,
                            'success');
                        statusMsg.textContent =
                            `✅ Izin "${tipeIzin}" dicatat. Anda masih bisa absen masuk/keluar.`;
                        izinDiv.classList.add('hidden');
                    } else {
                        statusMsg.textContent =
                        `❌ Gagal kirim izin: ${data.message || 'Coba lagi'}`;
                        showNotif(data.message || "Gagal kirim izin.", 'error');
                    }
                }).catch(err => {
                    console.error(err);
                    statusMsg.textContent = "❌ Terjadi kesalahan saat mengirim izin.";
                    showNotif("Terjadi kesalahan saat mengirim izin.", 'error');
                });
        });

        // ================= Cek status absensi terakhir =================
        let lastAbsensi = localStorage.getItem('last_absensi');
        if (lastAbsensi === 'masuk') {
            scanner.style.backgroundColor = '#ef4444';
            statusMsg.textContent = "⚠️ Anda belum absen keluar!";
        }

        // ================= Fingerprint =================
        scanner.addEventListener('click', () => {
            if (!selectedKaryawanId) return statusMsg.textContent = "Silakan pilih karyawan dulu!";
            const tandaTanganData = tandaTanganInput.value || signaturePad.toDataURL();
            if (!tandaTanganData) return alert("Tanda tangan kosong!");
            tandaTanganInput.value = tandaTanganData;
            sessionStorage.setItem('tanda_tangan', tandaTanganData);

            if (!navigator.geolocation) return alert("Geolocation tidak didukung browser ini.");
            statusMsg.textContent = "⏳ Mengambil lokasi...";

            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;

                const kantorLat = -6.691640391234676;
                const kantorLng = 106.88689131829916;
                const radiusM = 20;

                function getDistance(lat1, lon1, lat2, lon2) {
                    const R = 6371000;
                    const dLat = (lat2 - lat1) * Math.PI / 180;
                    const dLon = (lon2 - lon1) * Math.PI / 180;
                    const a = Math.sin(dLat / 2) ** 2 + Math.cos(lat1 * Math.PI / 180) *
                        Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
                    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                    return R * c;
                }

                const distance = getDistance(lat, lng, kantorLat, kantorLng);

                if (distance > radiusM) {
                    statusMsg.textContent =
                        `⚠️ Di luar lokasi kantor (${Math.round(distance)} m), pilih tipe izin`;
                    showNotif(`Di luar lokasi kantor (${Math.round(distance)} m)`, 'error');
                    izinDiv.classList.remove('hidden');
                    return;
                }

                statusMsg.textContent =
                    `⏳ Mengirim absensi... (jarak ${Math.round(distance)} m)`;

                fetch("{{ route('absensi.fingerprint') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            karyawan_id: selectedKaryawanId,
                            tanda_tangan: tandaTanganData,
                            lat,
                            lng
                        })
                    }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const tipe = data.data.waktu_keluar ? 'keluar' : 'masuk';
                            if (tipe === 'masuk') {
                                scanner.style.backgroundColor = '#ef4444';
                                localStorage.setItem('last_absensi', 'masuk');
                                showNotif("✅ Absen masuk berhasil, belum absen keluar!",
                                    'success');
                            } else {
                                scanner.style.backgroundColor = '#22c55e';
                                localStorage.setItem('last_absensi', 'keluar');
                                showNotif("✅ Absen keluar berhasil!", 'success');
                            }
                            statusMsg.textContent =
                                `✅ ${data.message} (jarak ${Math.round(distance)} m)`;
                        } else {
                            statusMsg.textContent =
                                `❌ ${data.message || "Gagal mencatat absensi."}`;
                            scanner.style.backgroundColor = '#ef4444';
                            showNotif(data.message || "Gagal mencatat absensi.", 'error');
                        }
                    }).catch(err => {
                        console.error(err);
                        statusMsg.textContent = "❌ Gagal mengirim data absensi.";
                        scanner.style.backgroundColor = '#ef4444';
                        showNotif("Gagal mengirim data absensi.", 'error');
                    });

            }, err => {
                statusMsg.textContent =
                    "❌ Tidak dapat mendeteksi lokasi. Pastikan GPS / Location aktif.";
                scanner.style.backgroundColor = '#ef4444';
                showNotif("Tidak dapat mendeteksi lokasi.", 'error');
                console.error(err);
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        });

        // ================= Reset =================
        resetBtn.addEventListener('click', () => {
            selectedKaryawanId = null;
            formDiv.classList.remove('hidden');
            fingerprintDiv.classList.add('hidden');
            document.getElementById('karyawan_id').value = '';
            signaturePad.clear();
            tandaTanganInput.value = '';
            sessionStorage.removeItem('tanda_tangan');
            localStorage.removeItem('last_absensi');
            statusMsg.textContent = '';
            scanner.style.backgroundColor = '#e5e7eb';

            fetch("{{ route('absensi.reset') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        });
    });
    </script>





</body>

</html>