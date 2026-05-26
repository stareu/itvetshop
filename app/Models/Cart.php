<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $session_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CartItem> $cartItems
 * @property-read int|null $cart_items_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

    public function cartItems(): HasMany
	{
		return $this->hasMany(CartItem::class);
	}

	public static function getCart()
	{
		return Auth::check()
			? Cart::query()->firstWhere([
				'user_id' => Auth::id()
			])
			: Cart::query()->firstWhere([
				'session_id' => Session::getId()
			]);
	}

	public static function getOrCreateCart()
	{
		return Auth::check()
			? Cart::query()->firstOrCreate([
				'user_id' => Auth::id()
			])
			: Cart::query()->firstOrCreate([
				'session_id' => Session::getId()
			]);
	}
}
