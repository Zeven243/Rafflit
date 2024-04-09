<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;

class WalletController extends Controller
{
    // Display the user's wallet
    public function index(Request $request)
    {
        $user = $request->user();
        $wallet = $user->wallet()->firstOrCreate([
            'user_id' => $user->id,
        ]);

        // Log the wallet view activity
        activity()
            ->causedBy($user)
            ->performedOn($wallet)
            ->log('viewed wallet');

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

        $oldBalance = $wallet->balance;

        $wallet->update([
            'balance' => $request->balance,
        ]);

        // Log the wallet update activity
        activity()
            ->causedBy($user)
            ->performedOn($wallet)
            ->withProperties([
                'old_balance' => $oldBalance,
                'new_balance' => $request->balance,
            ])
            ->log('updated wallet balance');

        return redirect()->back()->with('success', 'Wallet updated successfully.');
    }
}
