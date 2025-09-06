<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPG Jambuluwuk</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-50">

    <!-- Navbar -->
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
                    <a href="#home" class="relative group">
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
                    <a href="#baju" class="relative group">
                        baju
                        <span class="absolute left-0 -bottom-1 w-0 h-[2px] bg-sky-400 
                                     transition-all duration-300 group-hover:w-full"></span>
                    </a>
                </li>

                <li>
                    <a href="#peraturan" class="relative group">
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
                    <a href="#contac" class="relative group">
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
                <li><a href="#home" class="hover:text-sky-500 transition">Home</a></li>
                <li><a href="{{ route('absensi.index') }}" class="hover:text-sky-500 transition">Absensi</a></li>
                <li><a href="#baju" class="hover:text-sky-500 transition">Baju</a></li>
                <li><a href="#peraturan" class="hover:text-sky-500 transition">Peraturan</a></li>
                <li><a href="#petugas" class="hover:text-sky-500 transition">Jadwal Petugas</a></li>
                <li><a href="#contac" class="hover:text-sky-500 transition">Contact</a></li>
                <li>
                    <a href="{{ route('login') }}" class=" block text-center px-4 py-2 bg-sky-400 text-white rounded-lg
                        shadow hover:bg-sky-500 transition">
                        Login Admin
                    </a>
                </li>
            </ul>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="relative bg-white pt-44 pb-20">
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">

            <!-- Kiri: Text -->
            <div class="space-y-6">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-800 leading-tight">
                    Selamat Datang di <span class="text-sky-600">SPPG Jambuluwuk</span>
                </h1>
                <p class="text-lg text-gray-600 max-w-lg">
                    Sistem Pengelolaan Program Gabungan (SPPG) Jambuluwuk hadir untuk membantu
                    mengatur jadwal, peraturan, dan informasi kegiatan dengan lebih mudah,
                    cepat, serta terintegrasi bagi seluruh relawan SPPG.
                </p>
                <div class="flex space-x-4">
                    <a href="#petugas" class="px-6 py-3 bg-sky-600 text-white font-medium rounded-xl shadow-md 
                   hover:bg-sky-700 transition">
                        Lihat Jadwal
                    </a>
                    <a href="#peraturan" class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl 
                   hover:bg-gray-200 transition">
                        Baca Peraturan
                    </a>
                </div>
            </div>

            <!-- Kanan: Gambar sedang tanpa card -->
            <div class="flex justify-center">
                <img src="/assets/img/logo-bgn.png" alt="Ilustrasi SPPG" class="w-[350px] h-auto">
            </div>
        </div>
    </section>
    <!-- Section Jadwal Pemakaian Baju -->
    <section id="baju" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">
                Jadwal Pemakaian Baju
            </h2>

            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">

                @forelse($jadwals as $jadwal)
                <div class="bg-white rounded-2xl shadow-md shadow-sky-200/70 overflow-hidden 
                    hover:shadow-xl hover:shadow-sky-300/80 transition flex flex-col">
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center">
                        @if($jadwal->gambar)
                        <img src="{{ asset('assets/img/'.$jadwal->gambar) }}" alt="Baju {{ $jadwal->hari }}"
                            class="max-h-full max-w-full object-contain">
                        @else
                        <span class="text-gray-400">Tidak ada gambar</span>
                        @endif
                    </div>
                    <div class="p-5 text-center">
                        <h3 class="text-xl font-semibold text-gray-800">{{ $jadwal->hari }}</h3>
                        <p class="text-gray-600 text-sm mt-2">{{ $jadwal->nama_baju }}</p>
                        @if($jadwal->deskripsi)
                        <p class="text-gray-500 text-xs mt-1">{{ $jadwal->deskripsi }}</p>
                        @endif
                    </div>
                </div>
                @empty
                <p class="col-span-5 text-center text-gray-500">Belum ada jadwal yang ditambahkan</p>
                @endforelse

            </div>
        </div>
    </section>

    <section id="peraturan" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">
                Peraturan & Tata Tertib Semua Divisi
            </h2>

            <div class="grid gap-8 sm:grid-cols-2 md:grid-cols-3">
                @foreach($peraturans as $peraturan)
                <div class="bg-white rounded-2xl shadow-md shadow-sky-200/70 overflow-hidden 
                    hover:shadow-xl hover:shadow-sky-300/80 transition flex flex-col p-6">
                    <h3 class="text-xl font-semibold text-sky-600 mb-4">Divisi {{ $peraturan->tugas }}</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-600 text-sm">
                        @foreach(explode("\n", $peraturan->deskripsi) as $poin)
                        <li>{{ $poin }}</li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @php
    $tugasList = ['Persiapan', 'Masak', 'Packing', 'Distribusi', 'Kebersihan', 'Pencucian'];
    @endphp

    <section id="petugas" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold text-center text-gray-800 mb-12">
                Jadwal Karyawan
            </h2>

            @foreach($tugasList as $tugas)
            <div class="mb-12">
                <h3 class="text-2xl font-semibold text-sky-600 mb-4">Tugas {{ $tugas }}</h3>
                <div class="overflow-x-auto rounded-2xl shadow-md shadow-sky-200/70">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-sky-500 text-white">
                            <tr>
                                <th class="px-6 py-3">No</th>
                                <th class="px-6 py-3">Nama</th>
                                <th class="px-6 py-3">Hari</th>
                                <th class="px-6 py-3">Jam Masuk</th>
                                <th class="px-6 py-3">Jam Pulang</th>
                                <th class="px-6 py-3">Tugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @if(isset($petugasByTugas[$tugas]))
                            @foreach($petugasByTugas[$tugas] as $index => $p)
                            <tr class="hover:bg-sky-50 transition">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium">{{ $p->karyawan->nama ?? 'Tidak ada' }}</td>
                                <td class="px-6 py-4">{{ \Carbon\Carbon::parse($p->created_at)->isoFormat('dddd') }}
                                </td>
                                <td class="px-6 py-4">{{ $p->jam_masuk }}</td>
                                <td class="px-6 py-4">{{ $p->jam_pulang ?? 'Selesai' }}</td>
                                <td class="px-6 py-4">{{ $p->tugas }}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-400">Belum ada data</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </section>


    <!-- Footer -->
    <footer id="contac" class="bg-sky-600 text-white mt-20">
        <div class="max-w-7xl mx-auto px-6 py-12 grid md:grid-cols-3 gap-8">

            <!-- Logo & Deskripsi -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <img src="/assets/img/logo-bgn.png" alt="Logo BGN" class="w-10 h-10 object-contain">
                    <span class="text-xl font-bold">SPPG Jambuluwuk</span>
                </div>
                <p class="text-gray-200 text-sm max-w-xs">
                    Sistem Pengelolaan Program Gabungan (SPPG) Jambuluwuk membantu mengatur jadwal, peraturan, dan
                    informasi kegiatan dengan mudah dan terintegrasi.
                </p>
            </div>

            <!-- Link Cepat -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Link Cepat</h3>
                <ul class="space-y-2 text-gray-200">
                    <li><a href="#home" class="hover:text-white transition">Home</a></li>
                    <li><a href="#peraturan" class="hover:text-white transition">Peraturan</a></li>
                    <li><a href="#jadwal" class="hover:text-white transition">Jadwal</a></li>
                    <li><a href="#contact" class="hover:text-white transition">Contact</a></li>
                </ul>
            </div>

            <!-- Kontak / Informasi -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <ul class="space-y-2 text-gray-200 text-sm">
                    <li>Email: <a href="mailto:info@sppg-jambuluwuk.com"
                            class="hover:text-white transition">info@sppg-jambuluwuk.com</a></li>
                    <li>Telepon: <a href="tel:+628123456789" class="hover:text-white transition">+62 812 3456 789</a>
                    </li>
                    <li>Alamat: Jl. Contoh No.1, Kota Contoh, Indonesia</li>
                </ul>
            </div>

        </div>

        <!-- Bottom Footer -->
        <div class="border-t border-sky-500 py-4 text-center text-gray-200 text-sm">
            &copy; {{ date('Y') }} SPPG Jambuluwuk. Semua hak cipta dilindungi.
        </div>
    </footer>




    <script>
    // JS untuk toggle menu mobile
    const toggleBtn = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    toggleBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Scroll halus untuk semua link navbar
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                // Hitung offset karena navbar fixed (tinggi navbar ~64px)
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth"
                });

                // Tutup menu mobile jika terbuka
                if (!mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.add('hidden');
                }
            }
        });
    });
    </script>

</body>

</html>