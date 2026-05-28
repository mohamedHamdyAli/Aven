<?php

namespace Webkul\GiftCard\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Webkul\GiftCard\Models\GiftCard;

class GiftCardController extends Controller
{
    public function index(): View
    {
        $cards = GiftCard::orderByDesc('id')->paginate(30);

        return view('gift-card::admin.gift-cards.index', compact('cards'));
    }

    public function create(): View
    {
        return view('gift-card::admin.gift-cards.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'initial_balance'  => 'required|numeric|min:1',
            'expires_at'       => 'nullable|date|after:today',
            'recipient_email'  => 'nullable|email',
            'recipient_name'   => 'nullable|string|max:255',
            'message'          => 'nullable|string|max:500',
            'quantity'         => 'required|integer|min:1|max:100',
        ]);

        $qty = (int) $data['quantity'];

        for ($i = 0; $i < $qty; $i++) {
            GiftCard::create([
                'code'            => GiftCard::generateCode(),
                'initial_balance' => $data['initial_balance'],
                'expires_at'      => $data['expires_at'] ?? null,
                'recipient_email' => $data['recipient_email'] ?? null,
                'recipient_name'  => $data['recipient_name'] ?? null,
                'message'         => $data['message'] ?? null,
            ]);
        }

        session()->flash('success', "{$qty} gift card(s) created.");

        return redirect()->route('admin.gift-cards.index');
    }

    public function destroy(int $id): RedirectResponse
    {
        GiftCard::findOrFail($id)->delete();

        session()->flash('success', 'Gift card deleted.');

        return redirect()->route('admin.gift-cards.index');
    }
}
