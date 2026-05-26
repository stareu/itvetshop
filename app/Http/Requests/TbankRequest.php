<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;

class TbankRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Request $request): bool
    {
		return IpUtils::checkIp(
			$request->ip(),
			[
				'91.194.226.0/23',
				'91.218.132.0/24',
				'91.218.133.0/24',
				'91.218.134.0/24',
				'91.218.135.0/24',
				'212.49.24.0/24',
				'212.233.80.0/24',
				'212.233.81.0/24',
				'212.233.82.0/24',
				'212.233.83.0/24',
				'91.194.226.181'
			]
		);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
			// 'TerminalKey' => 'required',
			// 'OrderId' => 'required',
			// 'Success' => 'required',
			// 'Status' => 'required',
			// 'PaymentId' => 'required',
			// 'ErrorCode' => 'required',
			// 'Amount' => 'required',
			// 'CardId' => 'required',
			// 'Pan' => 'required',
			// 'ExpDate' => 'required',
			// 'Token' => 'required'
        ];
    }
}
