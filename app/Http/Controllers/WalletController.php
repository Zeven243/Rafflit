<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletController extends Controller
{
    // Display the user's wallet
    public function index(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        return Inertia::render('Wallet/index', [
            'wallet' => $wallet,
        ]);
    }

    // Update the wallet balance
    public function update(Request $request)
    {
        $request->validate([
            'balance' => 'required|numeric|min:0',
        ]);

        $user = $request->user();
        $wallet = $user->wallet()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        $wallet->update([
            'balance' => $request->balance,
        ]);

        return redirect()->back()->with('success', 'Wallet updated successfully.');
    }
}