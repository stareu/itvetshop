<?php


namespace App\PaymentSystems;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use App\PaymentSystems\PaymentSystem;
use Exception;

// боевой
const API_ENDPOINT = 'https://securepay.tinkoff.ru/v2/';

// Тестовые платежи проверяются с боевым терминалом и тестовым API
// const API_ENDPOINT = 'https://rest-api-test.tinkoff.ru/v2/';

// Тестовые карты:
// https://developer.tbank.ru/eacq/intro/errors/test

class TinkoffPayment implements PaymentSystem {
	public function createPayment(Order $order) {
		$url = $this->getRequestPath('Init');

		$payload = $this->createPayload($order);

		$response = Http::asJson()
			->async(false)
			->post($url, $payload);

		if ($response->successful()) {
			$responseJSON = $response->json();

			if ($responseJSON['Success'] === true) {
				return (object) [
					'id' => $responseJSON['PaymentId'],
					'order_id' => $order->id,
					'payment_data' => $responseJSON['PaymentURL']
				];
			}

			throw new Exception(json_encode($responseJSON, JSON_UNESCAPED_UNICODE));
		}

		// todo: мб показывать сообщение?
		throw new Exception(json_encode($response));
	}

	private function createPayload(Order $order) {
		$payload = [
			'TerminalKey' => env('TBANK_TERMINAL'),
			// Сюда придёт POST-запрос о статусе выполнения
			'NotificationURL' => 'https://shop.itvet.ru/tbank',
			'SuccessURL' => 'https://shop.itvet.ru/payment-success?orderId=' . $order->id,
			'FailURL' => 'https://shop.itvet.ru/payment-fail?orderId=' . $order->id,
			// Срок жизни ссылки или QR
			'RedirectDueDate' => now()->addMinutes(10)->format('Y-m-d\TH:i:sP'),
			'OrderId' => 'order' . $order->id,
			'Description' => 'Заказ №'.$order->id,
			'DATA' => [
				'OperationInitiatorType' => 0
			],
			'Password' => env('TBANK_TERMINAL_PASSWORD'),
			'Receipt' => [
				'Email' => 'noxioustoo@yandex.ru',
				'Phone' => '+79637420197',
				'Taxation' => 'osn',
				// 'Items' => [
				// 	[
				// 		'Name' => 'Наименование товара 1',
				// 		'Price' => 10000,
				// 		'Quantity' => 1,
				// 		'Amount' => 10000,
				// 		'Tax' => 'vat10',
				// 		'Ean13' => '303130323930303030630333435'
				// 	],
				// ]
			]
		];

		$receiptItems = [];
		$totalAmount = 0;

		foreach ($order->orderItems as $orderItem) {
			$orderItemPrice = $orderItem->product_price * $orderItem->quantity;
			$totalAmount += $orderItemPrice;

			$receiptItems[] = [
				'Name' => $orderItem->product->name,
				// Цена в копейках
				'Price' => $orderItemPrice * 100,
				// todo: нужно ли указывать более 1 если сам рассчитал общую сумму...
				'Quantity' => 1,
				// Сумма в копейках
				'Amount' => $orderItemPrice * 100,
				'Tax' => 'none',
			];
		}

		// Сумма в копейках
		$payload['Amount'] = $totalAmount * 100;
		$payload['Receipt']['Items'] = $receiptItems;
		$payload['Token'] = $this->createToken($payload);

		return $payload;
	}

	private function createToken(array $input): string
	{
		$filtered = array_filter($input, function ($item) {
			return !is_array($item) && !is_object($item);
		});

		ksort($filtered);

		$filtered = array_map(function($item) {
			if ($item === true) {
				return 'true';
			}

			if ($item === false) {
				return 'false';
			}

			return $item;
		}, $filtered);

		$concatenated = implode('', array_values($filtered));

		return hash('sha256', $concatenated);
	}

	private function getState($paymentID)
	{
		$payload = [
			'TerminalKey' => env('TBANK_TERMINAL'),
			'DATA' => [
				'OperationInitiatorType' => 0
			],
			'Password' => env('TBANK_TERMINAL_PASSWORD'),
			'PaymentId' => $paymentID
		];

		// todo: return type
		// 'Success': true,
		// 'ErrorCode': '0',
		// 'Message': 'OK',
		// 'TerminalKey': 'TinkoffBankTest',
		// 'Status': 'AUTHORIZED',
		// 'PaymentId': '13660',
		// 'OrderId': '21050',

		$payload['Token'] = TinkoffPayment::createToken($payload);

		$url = $this->getRequestPath('GetState');

		$response = Http::withHeaders([
				'Content-Type' => 'application/json'
			])
			->async(false)
			->withoutVerifying()
			->post($url, $payload);

		return $response->json();
	}

	private function getRequestPath(string $name): string {
		return API_ENDPOINT . $name;
	}

	public function checkAndUpdateOrder(Order $order)
	{
		if ($order->isPending() && $order->payment_id) {
			$orderPaymentInfo = TinkoffPayment::getState($order->payment_id);

			// todo: выводить в блоке заказа
			if (!$orderPaymentInfo['Success']) {
				return;
			}

			$orderStatus = $orderPaymentInfo['Status'];

			if ($orderStatus === 'CONFIRMED') {
				$order->markAsDone();
			}
			else {
				// в API Тбанка canceled с одной l
				$isCancelled = $orderStatus === 'CANCELED' || $orderStatus === 'DEADLINE_EXPIRED' || $orderStatus === 'REJECTED' || $orderStatus === 'AUTH_FAIL';

				if ($isCancelled) {
					$order->markAsCancelled();
				}
			}
		}
	}

	// public function cancel($paymentID)
	// {
	// 	$payload = [
	// 		'TerminalKey' => TERMINAL,
	// 		'DATA' => [
	// 			'OperationInitiatorType' => 0
	// 		],
	// 		'Password' => PASSWORD,
	// 		'PaymentId' => $paymentID
	// 	];

	// 	// {
	// 	//   'TerminalKey': 'TinkoffBankTest',
	// 	//   'OrderId': '21057',
	// 	//   'Success': true,
	// 	//   'Status': 'REVERSED',
	// 	//   'OriginalAmount': 13000,
	// 	//   'NewAmount': 5000,
	// 	//   'PaymentId': '2304882',
	// 	//   'ErrorCode': '0',
	// 	//   'Message': 'OK',
	// 	//   'Details': 'None',
	// 	//   'ExternalRequestId': '756478567845678436'
	// 	// }
	// 	$payload['Token'] = TinkoffPayment::createToken($payload);

	// 	$url = $this->getRequestPath('Cancel');

	// 	$response = Http::withHeaders([
	// 			'Content-Type' => 'application/json'
	// 		])
	// 		->post($url, $payload);

	// 	$json = $response->json();

	// 	if (!$json['Success']) {
	// 		abortAndReturnResponse(400, $response['Message']);
	// 	}

	// 	return $json;
	// }
}