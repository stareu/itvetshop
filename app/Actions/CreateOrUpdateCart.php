<?php

namespace App\Actions;

use App\Models\Cart;
use App\Models\CartItem;

class CreateOrUpdateCart
{
    public function execute(int $product_id, int|null $quantity)
    {
		$cart = Cart::getOrCreateCart();

		$cartItem = CartItem::query()->firstOrCreate(
			[
				'cart_id' => $cart->id,
				'product_id' => $product_id
			],
			[ 'quantity' => $quantity ]
		);

		if ($cartItem->quantity !== $quantity) {
			if ($quantity > 0) {
				$cartItem->quantity = $quantity;
				$cartItem->save();
			}
			else {
				$cartItem->delete();
			}
		}
    }
}
