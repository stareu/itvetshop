<?php

namespace App\Http\Controllers;

use Number;
use Inertia\Inertia;
use App\Models\Cart;
use App\Models\CartItem;
use App\Http\Requests\UpdateCartRequest;
use App\Actions\CreateOrUpdateCart;

class CartController extends Controller
{
	public function index()
	{
		$cart = Cart::getCart();

		if ($cart) {
			return Inertia::render('cart/cart', [
				'cartTotalSum' => Number::currency(
					number: $cart->cartItems->sum(function($item) {
						return $item->product->price * $item->quantity;
					}),
					precision: 0
				),
				'cartProducts' => $cart->cartItems->map(function(CartItem $item) {
					return [
						'id' => $item->product->id,
						'name' => $item->product->name,
						'price' => $item->product->price,
						'price_text' => Number::currency($item->product->price, precision: 0),
						'quantity' => $item->quantity,
						'image' => $item->product->image_url
					];
				})
			]);
		}

		return Inertia::render('cart/cart');
	}

    public function update(UpdateCartRequest $request, CreateOrUpdateCart $action)
	{
		$action->execute(
			$request->product_id,
			$request->quantity
		);

		return back();
	}
}
