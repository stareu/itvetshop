<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;

class RegisteredListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
		$user = User::query()->find($event->user->id);

		$this->storeCart($user);
		$this->storeOrders($user);
    }

	private function storeCart(User $user)
	{
		$cart = Cart::query()->firstWhere([
			'session_id' => Session::getId()
		]);

		if ($cart) {
			$cart->session_id = null;
			$cart->user_id = $user->id;
			$cart->save();
		}
	}

	private function storeOrders(User $user)
	{
		$customer = Customer::query()->firstWhere('email', $user->email);

		if ($customer) {
			foreach ($customer->orders as $customerOrder) {
				$customerOrder->user_id = $user->id;
				$customerOrder->save();
			}
		}

		// customer не удаляем и не очищаем order->customer_id,
		// так как можно будет получить историю заказов без авторизации, по email
	}
}
