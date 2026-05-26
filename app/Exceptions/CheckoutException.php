<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

class CheckoutException extends Exception
{
    public function __construct(public MessageBag $messageBag)
	{
	}
}
