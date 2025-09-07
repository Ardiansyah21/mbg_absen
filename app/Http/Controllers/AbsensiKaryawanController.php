<?php

namespace App\Http\Controllers;

use App\Models\AbsensiKaryawan;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiKaryawanController extends Controller
{
    // ================= Halaman Absensi =================
    public function index()
    {
        $karyawans = Karyawan::all();
        $registrasiData = session('registrasi') ?? null;

        return view('absensi.absen', compact('karyawans', 'registrasiData'));
    }

    // ================= Registrasi =================
    public function store(Request $request)
    {
        $request->validate([
            'karyawan_id'  => 'required|exists:karyawans,id',
            'tanda_tangan' => 'required|string',
        ]);

        session([
            'registrasi' => [
                'karyawan_id'  => $request->karyawan_id,
                'tanda_tangan' => $request->tanda_tangan,
            ]
        ]);

        $karyawan = Karyawan::find($request->karyawan_id);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi tersimpan. Silakan lanjut fingerprint untuk absen.',
            'data'    => [
                'karyawan'    => $karyawan,
                'tanda_tangan'=> $request->tanda_tangan
            ]
        ]);
    }

    // ================= Absensi Fingerprint =================
public function fingerprint(Request $request)
{
    try {
        $registrasi = session('registrasi');
        if (!$registrasi) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan registrasi dulu sebelum fingerprint.'
            ], 400);
        }

        $karyawan_id  = $registrasi['karyawan_id'];
        $tanda_tangan = $request->tanda_tangan ?? $registrasi['tanda_tangan'] ?? null;

        $lat_user = $request->lat;
        $lng_user = $request->lng;

        if (!$lat_user || !$lng_user) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa mendapatkan lokasi. Aktifkan GPS terlebih dahulu.'
            ], 400);
        }

        // Lokasi kantor
        $lat_office = -6.691640391234676; 
        $lng_office = 106.88689131829916;
        $radius_m  = 20; // meter

        $distance = $this->haversineDistance($lat_office, $lng_office, $lat_user, $lng_user);

        if ($distance > $radius_m) {
            return response()->json([
                'success' => false,
                'need_izin' => true,
                'message' => 'Anda berada di luar lokasi kantor!'
            ], 200);
        }

        $now = Carbon::now('Asia/Jakarta');
        Carbon::setLocale('id');

        // Cek absensi hari ini, hanya yang HADIR, bukan izin
        $absensi = AbsensiKaryawan::where('karyawan_id', $karyawan_id)
            ->whereDate('tanggal', $now->toDateString())
            ->where('status', 'Hadir')
            ->latest('id')
            ->first();

        if (!$absensi || (!is_null($absensi->waktu_masuk) && !is_null($absensi->waktu_keluar))) {
            // buat record baru = masuk
            $absensi = AbsensiKaryawan::create([
                'karyawan_id'  => $karyawan_id,
                'tanggal'      => $now->toDateString(),
                'hari'         => $now->translatedFormat('l'),
                'waktu_masuk'  => $now->toTimeString(),
                'status'       => 'Hadir',
                'metode'       => 'fingerprint',
                'tanda_tangan' => $tanda_tangan,
                'latitude'     => $lat_user,
                'longitude'    => $lng_user,
            ]);
            $msg = "Absensi masuk berhasil!";
        } else {
            // update record yang sama = keluar
            $absensi->update([
                'waktu_keluar' => $now->toTimeString(),
                'tanda_tangan' => $tanda_tangan,
                'latitude'     => $lat_user,
                'longitude'    => $lng_user,
            ]);
            $msg = "Absensi keluar berhasil!";
        }

        return response()->json([
            'success' => true,
            'message' => $msg,
            'data'    => $absensi
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: '.$e->getMessage()
        ], 500);
    }
}


    private function haversineDistance($lat1, $lon1, $lat2, $lon2) {
        $earth_radius = 6371000; // meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earth_radius * $c;
    }

    // ================= Izin =================
  public function izin(Request $request)
{
    $input = $request->json()->all(); // ambil data JSON

    // Validasi
    $validator = \Validator::make($input, [
        'karyawan_id' => 'required|exists:karyawans,id',
        'tipe_izin'  => 'required|in:sakit,alpha,izin',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    try {
        $karyawan_id = $input['karyawan_id'];
        $tipe_izin   = $input['tipe_izin'];
        $lat_user    = $input['lat'] ?? null;
        $lng_user    = $input['lng'] ?? null;
        $tanda_tangan = $input['tanda_tangan'] ?? ''; // kosong jika tidak ada
        $now         = Carbon::now('Asia/Jakarta');

        $absensi = AbsensiKaryawan::create([
            'karyawan_id'  => $karyawan_id,
            'tanggal'      => $now->toDateString(),
            'hari'         => $now->translatedFormat('l'),
            'status'       => $tipe_izin,
            'metode'       => 'izin',
            'latitude'     => $lat_user,
            'longitude'    => $lng_user,
            'tanda_tangan' => $tanda_tangan,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Izin $tipe_izin berhasil dicatat",
            'data'    => $absensi
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}


    // ================= Reset Registrasi =================
    public function reset()
    {
        session()->forget('registrasi');
        return response()->json(['success' => true, 'message' => 'Registrasi direset, silakan isi form lagi.']);
    }
}