<?php

namespace App\Http\Controllers;

use App\Models\RfidCard;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_cards' => RfidCard::where('status', 'active')->count(),
            'pending_cards' => RfidCard::where('status', 'pending')->count(),
            'total_balance' => User::sum('balance'),
            'today_transactions' => Transaction::whereDate('created_at', today())->count(),
            'recent_transactions' => Transaction::with(['user', 'rfidCard'])->latest()->take(5)->get(),
        ];

        return view('welcome', compact('stats'));
    }
}
