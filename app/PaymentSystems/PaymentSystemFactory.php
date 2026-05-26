<?php

namespace App\PaymentSystems;

use App\PaymentSystems\PaymentSystem;
use App\PaymentSystems\TinkoffPayment;
use Exception;

class PaymentSystemFactory {
	public function make(string $name): PaymentSystem
	{
		return match ($name) {
			'tbank' => new TinkoffPayment(),
			default => throw new Exception('Incorrect payment system.')
		};
	}
}