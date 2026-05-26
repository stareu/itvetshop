<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\AuthenticateRequest;
use App\Actions\AuthenticateUser;
use App\Actions\Checkout;
use App\Models\Product;
use App\Http\Middleware\ShareCart;
use App\Actions\ProcessTbank;
use App\Http\Requests\TbankRequest;
use App\Exceptions\CheckoutException;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CartController;

Route::middleware([ ShareCart::class ])->group(function() {
	Route::get('/', function() {
		return Inertia::render('catalog/catalog', [
			'products' => Product::where('is_active', true)
				->get()
				->map(function(Product $product) {
					return [
						'id' => $product->id,
						'name' => $product->name,
						'price' => Number::currency($product->price, precision: 0),
						'description' => $product->description,
						'image' => $product->image_url,
						'is_sold' => $product->isSoldOut()
					];
				})
		]);
	})->name('home');

	Route::get('orders', function() {
		if (Auth::check()) {
			return Inertia::render('orders/orders', [
				'orders' => Auth::user()
					->orders
					->sortByDesc('created_at')
					->values()
					->map(function(Order $order) {
						return $order->getData();
					})
			]);
		}
	});

	Route::get('offer', function() {
		return Inertia::render('docs/offer');
	});

	Route::get('privacy', function() {
		return Inertia::render('docs/privacy');
	});
});

Route::get('cart', [CartController::class, 'index'])
	->middleware(ShareCart::class);

Route::post('cart', [CartController::class, 'update']);

Route::post('checkout', function(CheckoutRequest $request, Checkout $action) {
	try {
		return Inertia::location(
			$action->execute($request)
		);
	}
	catch (CheckoutException $e) {
		return back()->withErrors($e->messageBag);
	}
});

Route::withoutMiddleware('web')
	->post('tbank', function(TbankRequest $request, ProcessTbank $action) {
		$action->execute($request);

		return 'OK';
	});

Route::get('payment-{status}', function(Request $request, string $status) {
	if ($status === 'success' || $status === 'fail') {
		$orderId = $request->query('orderId');
		$sessionOrderId = session('order_id');

		if ($orderId && ($orderId == $sessionOrderId || Auth::check())) {
			$order = Auth::check()
				? Auth::user()->orders->find($orderId)
				: Order::query()->find($orderId);

			if ($order) {
				return Inertia::render('payment/payment-' . $status, [
					'order' => $order->getData()
				]);
			}
		}
	}

	return redirect('/');
});

Route::get('tg', function() {
	if (Auth::check()) {
		return redirect('/');
	}

	return Inertia::render('telegram/telegram');
});

Route::post('tg', function(AuthenticateRequest $request, AuthenticateUser $action) {
	$action->execute($request->initData);
	
	return redirect('/');
});

Route::get('/opt', function() {
    Artisan::call('optimize');
    Artisan::call('config:cache');
    Artisan::call('event:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

	return ['success'=> true];
});

Route::get('/deopt', function() {
    Artisan::call('optimize:clear');
    Artisan::call('config:clear');
    Artisan::call('event:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

	return ['success'=> true];
});

require __DIR__.'/settings.php';
