<?php

namespace App\Http\Controllers;

use App\Models\RfidCard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RfidRegistrationController extends Controller
{
    /**
     * Tampilkan halaman registrasi RFID.
     */
    public function index()
    {
        $pendingCards = RfidCard::where('status', 'pending')->latest()->get();
        $users = User::doesntHave('rfidCards')->get(); // User yang belum punya kartu
        $isRegistrationMode = Cache::get('rfid_registration_mode', false);

        return view('admin.rfid.registration', compact('pendingCards', 'users', 'isRegistrationMode'));
    }

    /**
     * Hubungkan UID kartu ke user_id yang dipilih.
     */
    public function assign(Request $request, RfidCard $rfidCard)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $rfidCard->update([
            'user_id' => $request->user_id,
            'status' => 'active',
        ]);

        return back()->with('success', "Kartu {$rfidCard->rfid_uid} berhasil dihubungkan ke user.");
    }

    /**
     * Toggle status mode registrasi.
     */
    public function toggleMode()
    {
        $current = Cache::get('rfid_registration_mode', false);
        Cache::put('rfid_registration_mode', !$current);

        $status = !$current ? 'Aktif' : 'Non-aktif';
        return back()->with('success', "Registration Mode sekarang: {$status}");
    }

    /**
     * Buat user baru secara cepat.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'balance' => 'required|numeric|min:0',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'balance' => $validated['balance'],
            'password' => bcrypt('password123'), // Default password
        ]);

        return back()->with('success', "User {$validated['name']} berhasil dibuat.");
    }

    /**
     * Fungsi API untuk menyimpan UID kartu baru sebagai 'pending'.
     * Dipanggil dari ScanCardController jika dalam mode registrasi.
     */
    public function storePending(string $rfidUid)
    {
        // Cek apakah UID sudah ada
        $card = RfidCard::where('rfid_uid', $rfidUid)->first();

        if ($card) {
            return $card;
        }

        return RfidCard::create([
            'rfid_uid' => $rfidUid,
            'status' => 'pending',
            'user_id' => null,
        ]);
    }
}
