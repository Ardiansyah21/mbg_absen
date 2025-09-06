<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | SPPG Jambuluwuk</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-sky-100 to-sky-300 min-h-screen flex items-center justify-center">

    <!-- Card Login -->
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="flex flex-col items-center mb-8">
            <img src="/assets/img/logo-bgn.png" alt="Logo" class="w-16 h-16 mb-3">
            <h1 class="text-2xl font-bold text-gray-800">SPPG Jambuluwuk</h1>
            <p class="text-gray-500 text-sm">Login untuk melanjutkan</p>
        </div>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm
            focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition" placeholder="you@example.com">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required class="mt-1 w-full px-4 py-3 rounded-xl border border-gray-300 shadow-sm
            focus:ring-2 focus:ring-sky-400 focus:border-sky-400 transition" placeholder="••••••••">
            </div>

            @if($errors->any())
            <div class="text-red-500 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Tombol -->
            <button type="submit" class="w-full py-3 rounded-xl bg-sky-500 hover:bg-sky-600
        text-white font-semibold shadow-md transition">
                Masuk
            </button>
        </form>

    </div>
</body>

</html>