<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script src="https://unpkg.com/feather-icons"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gradient-to-r from-blue-100 to-white min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-blue-600 text-white flex flex-col py-8 px-4 shadow-lg">
        <div class="text-2xl font-bold flex items-center gap-2 mb-10">
            <!-- Ukuran ikon pakai kelas Tailwind -->
            <i data-feather="activity" class="w-6 h-6"></i>
            <span>Admin Panel</span>
        </div>

        <nav class="flex flex-col gap-4 text-white text-lg">
            <a href="{{ route('admin') }}" class="flex items-center gap-3 hover:bg-blue-500 p-2 rounded transition">
                <i data-feather="home" class="w-5 h-5"></i> Dashboard
            </a>

            <a href="{{ route('karyawan.index') }}"
                class="flex items-center gap-3 hover:bg-blue-500 p-2 rounded transition">
                <i data-feather="users" class="w-5 h-5"></i> Petugas
            </a>

            <a href="{{ route('admin.absensi') }}"
                class="flex items-center gap-3 hover:bg-blue-500 p-2 rounded transition">
                <i data-feather="check-circle" class="w-5 h-5"></i> Absensi Petugas
            </a>

            <a href="{{ route('admin.baju') }}"
                class="flex items-center gap-3 hover:bg-blue-500 p-2 rounded transition">
                <i data-feather="tag" class="w-5 h-5"></i> Jadwal Baju
            </a>


            <a href="{{ route('admin.petugas') }}"
                class="flex items-center gap-3 hover:bg-blue-500 p-2 rounded transition">
                <i data-feather="calendar" class="w-5 h-5"></i> Jadwal Petugas
            </a>

            <a href="{{ route('peraturan.index') }}"
                class="flex items-center gap-3 hover:bg-blue-500 p-2 rounded transition">
                <i data-feather="settings" class="w-5 h-5"></i> Pengaturan
            </a>
            <!-- Sidebar Logout -->
            <div class="mt-auto">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 hover:bg-red-500 p-2 rounded transition text-white">
                        <i data-feather="log-out" class="w-5 h-5"></i> Logout
                    </button>
                </form>
            </div>

        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        @yield('content')
    </main>

    <!-- Panggil feather.replace() setelah DOM siap -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        feather.replace({
            'width': 20,
            'height': 20
        });
    });
    </script>

</body>

</html>