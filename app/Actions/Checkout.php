<?php

namespace App\Actions;

use Illuminate\Support\MessageBag;
use App\Enums\VirtualAssetStatus;
use App\Exceptions\CheckoutException;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Enums\OrderStatus;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\OrderItem;
use App\PaymentSystems\PaymentSystemFactory;
use Illuminate\Support\Facades\DB;

class Checkout
{
	public function __construct(private PaymentSystemFactory $paymentSystemFactory)
	{
	}

    public function execute(CheckoutRequest $request)
    {
		$messageBag = new MessageBag();
		$cart = Auth::check()
			? Auth::user()->cart
			: Cart::query()->firstWhere('session_id', $request->session()->getId());
		$cartItems = $cart->cartItems;

		foreach ($cartItems as $item) {
			if (!$item->product->is_active) {
				$messageBag->add('product.' . $item->product->id, 'inactive');
				$messageBag->add('cart_updated_remove', 'Корзина обновлена. Некоторые товары стали недоступны и удалены.');

				$item->delete();
			}
			else {
				if ($item->product->isVirtual()) {
					if ($item->product->virtualAssets
						->where('status', VirtualAssetStatus::FREE)
						->take($item->quantity)
						->count() !== $item->quantity) {
						$messageBag->add('product.' . $item->product->id, 'Недопустимое количество.');
					}
				}
				else {
					if ($item->product->quantity < $item->quantity) {
						$messageBag->add('product.' . $item->product->id, 'Недопустимое количество.');
					}
				}
			}
			// todo: мб изменилась цена - сообщение и обновление корзины
		}

		if ($messageBag->isNotEmpty()) {
			throw new CheckoutException($messageBag);
		}

		$order = $this->makeOrder($request->payment_system, $cartItems);

		if (Auth::check()) {
			$order->user_id = Auth::id();
		}
		else {
			$order->customer_id = Customer::query()
				->firstOrCreate([
					'email' => $request->string('email')
				])
				->id;

			session([ 'order_id' => $order->id ]);
		}

		$order->save();

		$cart->delete();

		$payment = $this->makePayment($order, $request->payment_system);

		$order->payment_id = $payment->id;
		$order->payment_data = $payment->payment_data;
		$order->save();

		return $order->payment_data;
    }

	private function makeOrder(string $payment_system, $cartItems): Order
	{
		return DB::transaction(function() use ($payment_system, $cartItems) {
			$order = Order::create([
				'status' => OrderStatus::PENDING,
				'payment_system' => $payment_system
			]);

			foreach ($cartItems as $cartItem) {
				$orderItem = OrderItem::create([
					'order_id' => $order->id,
					'product_id' => $cartItem->product->id,
					'product_name' => $cartItem->product->name,
					'product_price' => $cartItem->product->price,
					'quantity' => $cartItem->quantity
				]);

				if ($cartItem->product->isVirtual()) {
					$cartItem->product->virtualAssets
						->where('status', VirtualAssetStatus::FREE)
						->take($cartItem->quantity)
						->each(function($asset) use ($orderItem) {
							$asset->order_item_id = $orderItem->id;
							$asset->reserve();
							$asset->save();
						});
				}
				else {
					$cartItem->product->quantity -= $cartItem->quantity;
					$cartItem->product->save();
				}
			}

			return $order;
		});
	}

	private function makePayment(Order $order, string $payment_system)
	{
		$paymentSystem = $this->paymentSystemFactory->make($payment_system);
		$payment = $paymentSystem->createPayment($order);

		return $payment;
	}
}
