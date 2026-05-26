<?php
namespace Stareu\Olonia\Classes\PaymentSystems;

use Stareu\Olonia\Models\Order;
use Stareu\Olonia\Classes\PaymentSystems\PaymentSystem;

// ID сайта в личном кабинете
const PUBLIC_ID = 'pk_2318872ef341cf2a5a603d2e22572';

class CloudPayment implements PaymentSystem {
	public function checkAndUpdateOrder(Order $order)
	{
		if ($order->isPending()) {
			if ($order->created_at->addMinutes(30)->isPast()) {
				$order->markAsCancelled();
			}
		}
	}

	public function createPayment(Order $order)
	{
		$payOptions = [
            'publicId' => PUBLIC_ID,
            'description' => 'Оплата заказа №'.$order->id,
            'amount' => $order->getTotalAmount(),
            'currency' => 'RUB',
            'invoiceId' => $order->id
            // accountId: 'user@example.com', //идентификатор плательщика (необязательно)
            // email: 'user@example.com', //email плательщика (необязательно)
            // skin: "mini", //дизайн виджета (необязательно)
            // data: {
            //     myProp: 'myProp value'
            // }
		];

		return (object) [
			'id' => $order->id,
			'order_id' => $order->id,
			'payment_info' => json_encode($payOptions)
		];
	}
}