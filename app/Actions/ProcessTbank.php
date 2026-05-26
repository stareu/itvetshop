<?php

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Enums\VirtualAssetStatus;
use App\Http\Requests\TbankRequest;
use App\Models\Order;
use Error;

class ProcessTbank
{
    public function execute(TbankRequest $request)
    {
		$this->checkToken($request->all());

		if ($request->Status === 'AUTHORIZED') {
			return;
		}

		$order = Order::query()->findOrFail(
			str_replace('order', '', $request->OrderId)
		);

		$isOrderValidAndDone = $request->Success === true
			&& $request->Status === 'CONFIRMED'
			&& $order->payment_id == $request->PaymentId
			&& $order->getTotalAmount() === ($request->Amount / 100);

		$order->confirmOrCancel($isOrderValidAndDone);
    }

	// https://developer.tbank.ru/eacq/intro/developer/notification
	private function checkToken(array $orderData)
	{
		$actualToken = $orderData['Token'];

		$orderData = array_filter(
			$orderData,
			function($key) {
				return $key !== 'Token' && $key !== 'Data' && $key !== 'Receipt';
			},
			ARRAY_FILTER_USE_KEY
		);

		$orderData = array_map(function($item) {
			if ($item === true) {
				return 'true';
			}

			if ($item === false) {
				return 'false';
			}

			return $item;
		}, $orderData);

		$orderData['Password'] = env('TBANK_TERMINAL_PASSWORD');

		ksort($orderData);

		$concatenated = implode('', array_values($orderData));

		$expectedToken = hash('sha256', $concatenated);

		if (!hash_equals($expectedToken, $actualToken)) {
			throw new Error('Токен уведомлений не совпадает.');
		}
	}
}
