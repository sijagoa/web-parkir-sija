<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Location;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        $vehicleTypes = VehicleType::all();

        foreach ($locations as $location) {
            $activeMotorcycle = Transaction::where('id_lokasi', $location->id)
                ->whereHas('vehicleType', fn($q) => $q->where('jenis', 'motorcycle'))
                ->whereNull('keluar')
                ->count();

            $activeCar = Transaction::where('id_lokasi', $location->id)
                ->whereHas('vehicleType', fn($q) => $q->where('jenis', 'car'))
                ->whereNull('keluar')
                ->count();

            $activeOther = Transaction::where('id_lokasi', $location->id)
                ->whereHas('vehicleType', fn($q) => $q->where('jenis', 'other'))
                ->whereNull('keluar')
                ->count();

            $location->available_motorcycle = $location->max_motorcycle - $activeMotorcycle;
            $location->available_car = $location->max_car - $activeCar;
            $location->available_other = $location->max_other - $activeOther;
        }

        $activeTickets = Transaction::whereNull('keluar')
            ->orderBy('masuk', 'desc')
            ->get();

        return view('transactions.index', compact('locations', 'vehicleTypes', 'activeTickets'));
    }

    public function enterVehicle(Request $request)
    {
        $request->validate([
            'id_lokasi' => 'required|exists:locations,id',
            'id_jenis'  => 'required|exists:vehicle_types,id',
        ]);

        $location = Location::findOrFail($request->id_lokasi);
        $vehicleType = VehicleType::findOrFail($request->id_jenis);

        $jenisField = match ($vehicleType->jenis) {
            'motorcycle' => 'max_motorcycle',
            'car'        => 'max_car',
            'other'      => 'max_other',
        };

        $activeCount = Transaction::where('id_lokasi', $location->id)
            ->where('id_jenis', $vehicleType->id)
            ->whereNull('keluar')
            ->count();

        $maxCapacity = $location->{$jenisField};

        if ($activeCount >= $maxCapacity) {
            return response()->json([
                'success' => false,
                'message' => "Kapasitas {$vehicleType->jenis} di {$location->location_name} sudah penuh!"
            ], 422);
        }

        $now = Carbon::now();
        $noTiket = $now->format('YmdHis') . rand(10, 99);

        $transaction = Transaction::create([
            'id_lokasi'        => $location->id,
            'no_tiket'         => $noTiket,
            'no_polisi'        => '',
            'id_jenis'         => $vehicleType->id,
            'masuk'            => $now,
            'keluar'           => null,
            'perjam_pertama'   => $vehicleType->perjam_pertama,
            'perjam_berikutnya'=> $vehicleType->perjam_berikutnya,
            'max_perhari'      => $vehicleType->max_perhari,
            'total_jam'        => 0,
            'total_hari'       => 0,
            'total_bayar'      => 0,
        ]);

        $newActiveCount = $activeCount + 1;
        $remaining = $maxCapacity - $newActiveCount;

        return response()->json([
            'success'    => true,
            'message'    => 'Kendaraan berhasil masuk!',
            'no_tiket'   => $noTiket,
            'masuk'      => $now->format('Y-m-d H:i:s'),
            'location'   => $location->location_name,
            'jenis'      => $vehicleType->jenis,
            'remaining'  => $remaining,
            'id_lokasi'  => $location->id,
            'jenis_key'  => $jenisField,
        ]);
    }

    public function exitVehicle(Request $request)
    {
        $request->validate([
            'no_tiket'  => 'required|string',
            'no_polisi' => 'required|string|max:15',
        ]);

        $transaction = Transaction::where('no_tiket', $request->no_tiket)
            ->whereNull('keluar')
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan atau kendaraan sudah keluar!'
            ], 404);
        }

        $now = Carbon::now();
        $masuk = Carbon::parse($transaction->masuk);

        $totalMenit = max(1, (int) ceil($masuk->diffInSeconds($now) / 60));

        $totalBayar = $this->hitungBiaya(
            $totalMenit,
            $transaction->perjam_pertama,
            $transaction->perjam_berikutnya,
            $transaction->max_perhari
        );

        $totalHari = (int) floor($totalMenit / 1440);

        $transaction->update([
            'keluar'     => $now,
            'no_polisi'  => $request->no_polisi,
            'total_jam'  => $totalMenit,
            'total_hari' => $totalHari,
            'total_bayar'=> $totalBayar,
        ]);

        $vehicleType = $transaction->vehicleType;
        $location = $transaction->location;

        $jenisField = match ($vehicleType->jenis) {
            'motorcycle' => 'max_motorcycle',
            'car'        => 'max_car',
            'other'      => 'max_other',
        };

        $activeCount = Transaction::where('id_lokasi', $location->id)
            ->where('id_jenis', $vehicleType->id)
            ->whereNull('keluar')
            ->count();

        $remaining = $location->{$jenisField} - $activeCount;

        return response()->json([
            'success'     => true,
            'message'     => 'Kendaraan berhasil keluar!',
            'total_bayar' => $totalBayar,
            'total_menit' => $totalMenit,
            'total_hari'  => $totalHari,
            'no_tiket'    => $transaction->no_tiket,
            'id_lokasi'   => $location->id,
            'jenis_key'   => $jenisField,
            'remaining'   => $remaining,
        ]);
    }

    private function hitungBiaya(int $totalMenit, int $perjamPertama, int $perjamBerikutnya, int $maxPerhari): int
    {
        if ($totalMenit <= 0) {
            return 0;
        }

        if ($totalMenit <= 1440) {
            $total = $perjamPertama + ($perjamBerikutnya * ($totalMenit - 1));

            if ($total > $maxPerhari) {
                $total = $maxPerhari;
            }

            return $total;
        }

        $totalHari = (int) floor($totalMenit / 1440);
        $sisaMenit = $totalMenit % 1440;

        $biayaPerHari = (int) ($maxPerhari * 0.6);
        $biayaHari = $totalHari * $biayaPerHari;

        $biayaSisa = 0;
        if ($sisaMenit > 0) {
            $biayaSisa = $perjamPertama + ($perjamBerikutnya * ($sisaMenit - 1));
            if ($biayaSisa > $maxPerhari) {
                $biayaSisa = $maxPerhari;
            }
        }

        return $biayaHari + $biayaSisa;
    }

    public function getTicketInfo($noTiket)
    {
        $transaction = Transaction::where('no_tiket', $noTiket)->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Tiket tidak ditemukan!'
            ], 404);
        }

        return response()->json([
            'success'   => true,
            'no_tiket'  => $transaction->no_tiket,
            'no_polisi' => $transaction->no_polisi,
            'masuk'     => $transaction->masuk->format('Y-m-d H:i:s'),
            'keluar'    => $transaction->keluar ? $transaction->keluar->format('Y-m-d H:i:s') : null,
            'location'  => $transaction->location->location_name,
            'jenis'     => $transaction->vehicleType->jenis,
        ]);
    }

    public function generatePdf($noTiket)
    {
        $transaction = Transaction::with(['location', 'vehicleType'])
            ->where('no_tiket', $noTiket)
            ->firstOrFail();

        $pdf = Pdf::loadView('transactions.ticket', [
            'transaction' => $transaction,
        ]);

        $pdf->setPaper([0, 0, 226.77, 425.20], 'portrait');

        $filename = $noTiket . '.pdf';
        $path = storage_path('app/public/tickets/' . $filename);

        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $pdf->save($path);

        return $pdf->stream($filename);
    }

    public function allTransactions()
    {
        $transactions = Transaction::with(['location', 'vehicleType'])
            ->orderBy('masuk', 'desc')
            ->get()
            ->map(function ($t) {
                return [
                    'id'               => $t->id,
                    'no_tiket'         => $t->no_tiket,
                    'no_polisi'        => $t->no_polisi,
                    'location_name'    => $t->location->location_name,
                    'jenis'            => $t->vehicleType->jenis,
                    'masuk'            => $t->masuk ? $t->masuk->format('Y-m-d H:i:s') : '-',
                    'keluar'           => $t->keluar ? $t->keluar->format('Y-m-d H:i:s') : '-',
                    'perjam_pertama'   => $t->perjam_pertama,
                    'perjam_berikutnya'=> $t->perjam_berikutnya,
                    'max_perhari'      => $t->max_perhari,
                    'total_jam'        => $t->total_jam,
                    'total_hari'       => $t->total_hari,
                    'total_bayar'      => $t->total_bayar,
                ];
            });

        return response()->json([
            'success'      => true,
            'transactions' => $transactions,
        ]);
    }
}
