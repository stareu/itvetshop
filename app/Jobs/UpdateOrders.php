<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateOrders implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        Order::where('status', OrderStatus::PENDING)
			->each(function(Order $order) {
				if (now() >= $order->created_at->addMinutes(10)) {
					$order->cancel();
				}
			});
    }
}
