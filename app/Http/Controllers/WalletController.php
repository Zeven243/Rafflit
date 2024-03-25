<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display the user's wallet.
     */
    public function index()
    {
        $wallet = Wallet::firstOrCreate(
            ['user_id' => Auth::id()],
            ['balance' => 0.00]
        );

        return Inertia::render('Wallet/Index', [
            'balance' => $wallet->balance,
        ]);
    }

    /**
     * Update the user's wallet balance.
     */
    public function update(Request $request)
    {
        $request->validate([
            'balance' => 'required|numeric',
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->firstOrFail();
        $wallet->balance = $request->balance;
        $wallet->save();

        return redirect()->back()->with('success', 'Wallet balance updated successfully.');
    }
}