<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    protected function getSessionId(): string
    {
        if (!Session::has('zyricz_wishlist_session')) {
            Session::put('zyricz_wishlist_session', Session::getId());
        }

        return Session::get('zyricz_wishlist_session');
    }

    public function getItems(): Collection
    {
        $query = Wishlist::with(['product.brand', 'product.categories']);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query->latest()->get()->pluck('product')->filter();
    }

    public function toggle(int $productId): array
    {
        $userId = Auth::id();
        $sessionId = $this->getSessionId();

        $query = Wishlist::where('product_id', $productId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $added = false;
            $message = 'Removed from your wishlist.';
        } else {
            Wishlist::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $productId,
            ]);
            $added = true;
            $message = 'Saved to your wishlist.';
        }

        return [
            'success' => true,
            'added' => $added,
            'count' => $this->count(),
            'message' => $message,
        ];
    }

    public function has(int $productId): bool
    {
        $query = Wishlist::where('product_id', $productId);
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query->exists();
    }

    public function count(): int
    {
        $query = Wishlist::query();
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_id', $this->getSessionId());
        }

        return $query->count();
    }
}
