@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6 p-6 bg-blue-100 rounded-xl shadow-md shadow-blue-500">
        <h1 class="text-2xl font-bold text-black">Hallo Selamat Datang</h1>
    </div>


    <!-- Cards Tugas -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-6">
        @foreach($jumlahPerTugas as $data)
        <div
            class="relative bg-{{ $data['warna'] }}-100 border border-{{ $data['warna'] }}-300 rounded-lg p-6 shadow-md text-{{ $data['warna'] }}-800 flex items-center justify-between transform hover:scale-105 transition duration-300">

            <!-- Badge Jumlah -->
            <div
                class="absolute top-2 right-2 bg-{{ $data['warna'] }}-300 text-white text-xs px-2 py-1 rounded-full shadow">
                {{ $data['jumlah'] }}
            </div>

            <div>
                <h3 class="text-sm font-semibold mb-2">{{ $data['tugas'] }}</h3>
                <p class="text-xl font-bold">{{ $data['jumlah'] }} Karyawan</p>
            </div>

            <div class="text-5xl">
                @switch($data['tugas'])
                @case('Persiapan') 🍳 @break
                @case('Memasak') 🔥 @break
                @case('Packing') 📦 @break
                @case('Distribusi') 🚚 @break
                @case('Kebersihan') 🧹 @break
                @case('Pencucian') 🧼 @break
                @default 👷‍♂️
                @endswitch
            </div>
        </div>
        @endforeach
    </div>

    <!-- Section Ringkasan Absensi Hari Ini -->
    <h2 class="text-xl font-bold text-gray-700 mb-4">📊 Ringkasan Absensi Hari Ini</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-green-100 rounded-lg p-4 shadow flex justify-between items-center">
            <div>
                <h3 class="text-sm font-semibold">Hadir</h3>
                <p class="text-2xl font-bold">{{ $jumlahHadir ?? 0 }}</p>
            </div>
            <div class="text-3xl">✅</div>
        </div>
        <div class="bg-red-100 rounded-lg p-4 shadow flex justify-between items-center">
            <div>
                <h3 class="text-sm font-semibold">Tidak Hadir</h3>
                <p class="text-2xl font-bold">{{ $jumlahTidakHadir ?? 0 }}</p>
            </div>
            <div class="text-3xl">❌</div>
        </div>
        <div class="bg-yellow-100 rounded-lg p-4 shadow flex justify-between items-center">
            <div>
                <h3 class="text-sm font-semibold">Total Karyawan</h3>
                <p class="text-2xl font-bold">{{ $totalKaryawan ?? 0 }}</p>
            </div>
            <div class="text-3xl">👥</div>
        </div>
    </div>

</div>
@endsection