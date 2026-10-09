<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['items.product', 'trackings'])
            ->latest()
            ->paginate(10);

        $addresses = $user->addresses()->latest()->get();

        return view('pages.account.index', compact('user', 'orders', 'addresses'));
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;

        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->with('error', 'Your current password does not match our records.');
            }
            $user->password = Hash::make($validated['new_password']);
        }

        $user->save();

        return back()->with('success', 'Profile details updated successfully.');
    }

    public function saveAddress(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:30',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        if (!empty($validated['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        Address::create([
            'user_id' => $user->id,
            'type' => 'shipping',
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $user->email,
            'phone' => $validated['phone'] ?? null,
            'address_line_1' => $validated['address_line_1'],
            'address_line_2' => $validated['address_line_2'] ?? null,
            'city' => $validated['city'],
            'state' => $validated['state'] ?? null,
            'postal_code' => $validated['postal_code'],
            'country' => $validated['country'],
            'is_default' => !empty($validated['is_default']),
        ]);

        return back()->with('success', 'Delivery address saved.');
    }
}
