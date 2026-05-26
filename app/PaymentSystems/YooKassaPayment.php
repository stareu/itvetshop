<?php
namespace Stareu\Olonia\Classes\PaymentSystems;

// use YooKassa\Client;
use Stareu\Olonia\Models\Order;
use Stareu\Olonia\Classes\PaymentSystems\PaymentSystem;

class YooKassaPayment implements PaymentSystem {
	private Client $client;

	// public function __construct()
	// {
	// 	$this->client = new Client();
	// 	$this->client->setAuth('1076403', 'test_11oOQiWyVcPeruNd73wov3JjnB62PZg1hSeaiCl0qjU');
	// }

	public function checkAndUpdateOrder(Order $order)
	{
		if ($order->isPending() && $order->payment_id) {
			$orderPaymentInfo = $this->client->getPaymentInfo($order->payment_id);

			if ($orderPaymentInfo->paid) {
				$order->markAsDone();
			}
			else {
				// в YooKassa API canceled с одной l
				$isCancelled = $orderPaymentInfo->status === 'canceled';

				if ($isCancelled) {
					$order->markAsCancelled();
				}
			}
		}
	}

	public function createPayment(Order $order)
	{
		$idempotenceKey = uniqid('', true);
		$totalAmount = 0;
		$positions = $order->order_positions()->get();

		foreach ($positions as $position) {
			$totalAmount += ($position->sell_price * $position->quantity);
		}

		$payment = $this->client->createPayment(
			array(
				'amount' => array(
					'value' => $totalAmount,
					'currency' => 'RUB',
				),
				'confirmation' => array(
					'type' => 'embedded',
				),
				'capture' => true,
				'description' => 'Заказ №'.$order->id,
			),
			$idempotenceKey
		);

		$confirmation_token = $payment->getConfirmation()->getConfirmationToken();

		// todo: сделать класс и возвращать инстанс
		return (object) [
			'id' => $payment->getId(),
			'order_id' => $order->id,
			'payment_info' => $confirmation_token
		];
	}
}