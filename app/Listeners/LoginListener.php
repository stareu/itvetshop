<?php

namespace App\Listeners;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Validated;

class LoginListener
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
    public function handle(Validated $event): void
    {
		$cart = Cart::query()->firstWhere([
			'session_id' => Session::getId()
		]);

		if ($cart) {
			$user = User::find($event->user->id);

			if (!$user->cart) {
				$cart->session_id = null;
				$cart->user_id = $user->id;
				$cart->save();
			}

			// если у юзера уже была корзина - её и оставляем
		}
    }
}
