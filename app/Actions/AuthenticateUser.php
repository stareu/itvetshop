<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Error;

class AuthenticateUser
{
	public function execute(string $initData)
	{
		parse_str($initData, $data);

		$hash = $data['hash'];
		unset($data['hash']);

		ksort($data);

		$dataCheckString = implode("\n", array_map(
			fn($k, $v) => "$k=$v",
			array_keys($data),
			$data
		));

		$secretKey = hash_hmac('sha256', env('TG_BOT_TOKEN'), 'WebAppData', true);
		$calculatedHash = hash_hmac('sha256', $dataCheckString, $secretKey);

		if (hash_equals($calculatedHash, $hash)) {
			$userData = json_decode($data['user']);

			$user = User::query()->firstWhere('tg_id', $userData->id);

			if (!$user) {
				$user = User::create([
					'tg_id' => $userData->id,
					'name' => $userData->username,
					'avatar' => $userData->photo_url,
					'platform' => 'tg'
				]);
			}

			Auth::login($user, true);
		}
		else {
			throw new Error('Хеши не совпадают.');
		}
	}
}