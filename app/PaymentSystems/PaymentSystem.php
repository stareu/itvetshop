<?php

namespace App\PaymentSystems;

use App\Models\Order;

interface PaymentSystem
{
	public function checkAndUpdateOrder(Order $order);
	public function createPayment(Order $order);
}