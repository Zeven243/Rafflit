<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Inertia\Inertia;
use App\Models\CartItem;
use App\Models\Listings;
use App\Models\Purchase;
use App\Models\RaffleEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\RaffleEntryController;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.listing')->where('user_id', auth()->id())->first();

        return Inertia::render('Cart/index', [
            'cart' => $cart,
        ]);
    }

    public function store(Request $request)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        $cartItem = $cart->items()->where('listing_id', $request->listing_id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'listing_id' => $request->listing_id,
                'quantity' => $request->quantity,
            ]);
        }

        $this->updatePotentialTickets($request->listing_id, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        $this->updatePotentialTickets($cartItem->listing_id, $request->quantity - $cartItem->quantity);

        return response()->json([
            'message' => 'Cart item quantity updated successfully.',
        ]);
    }

    public function destroy(CartItem $cartItem)
    {
        $listingId = $cartItem->listing_id;
        $cartItem->delete();

        $this->updatePotentialTickets($listingId, -$cartItem->quantity);

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $cart = Cart::with('items.listing')->where('user_id', auth()->id())->first();

        if ($cart) {
            foreach ($cart->items as $item) {
                $listing = $item->listing;
                $listing->increment('tickets_sold', $item->quantity);
                $listing->update([
                    'potential_tickets' => 0, // Reset potential_tickets to 0 after checkout
                ]);

                // Record the purchase
                Purchase::create([
                    'user_id' => auth()->id(),
                    'listing_id' => $listing->id,
                    'quantity' => $item->quantity,
                    'first_name' => auth()->user()->first_name,
                    'last_name' => auth()->user()->last_name,
                ]);

                // Create raffle entries
                for ($i = 0; $i < $item->quantity; $i++) {
                    RaffleEntry::create([
                        'user_id' => auth()->id(),
                        'listing_id' => $listing->id,
                    ]);
                }

                // Check if all tickets are sold and select a winner
                if ($listing->tickets_sold >= $listing->amount_of_tickets) {
                    app(RaffleEntryController::class)->selectWinner($listing->id);
                }

                $item->delete();
            }
        }

        return redirect()->route('cart.index')->with('success', 'Checkout successful.');
    }

    private function updatePotentialTickets($listingId, $quantityChange)
    {
        $listing = Listings::findOrFail($listingId);
        $listing->increment('potential_tickets', $quantityChange);
    }
}
