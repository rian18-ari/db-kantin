<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RfidCard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RfidRegistrationController extends Controller
{
    /**
     * POST /api/rfid/register
     * Mendaftarkan UID kartu baru sebagai status 'pending'.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rfid_uid' => 'required|string|max:64',
        ]);

        $rfidUid = $validated['rfid_uid'];

        $card = RfidCard::where('rfid_uid', $rfidUid)->first();

        if ($card) {
            return response()->json([
                'success' => false,
                'message' => $card->status === 'pending'
                    ? 'Kartu sudah terdaftar sebagai pending.'
                    : 'Kartu sudah terdaftar dan aktif.',
                'status' => $card->status,
            ], 422);
        }

        $newCard = RfidCard::create([
            'rfid_uid' => $rfidUid,
            'status' => 'pending',
            'user_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kartu berhasil didaftarkan sebagai pending.',
            'data' => [
                'rfid_uid' => $newCard->rfid_uid,
                'status' => $newCard->status,
                'created_at' => $newCard->created_at,
            ]
        ], 201);
    }
}
