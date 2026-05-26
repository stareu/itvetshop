<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ShareCart
{
    public function handle(Request $request, Closure $next): Response
    {
		$cart = Cart::getCart();

		if ($cart) {
			Inertia::share('cart',  [
				'totalQuantity' => $cart->cartItems?->count() ?? 0,
				'products' => $cart->cartItems->map(function($item) {
					return $item->product_id;
				})
			]);
		}

        return $next($request);
    }
}
