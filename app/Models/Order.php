<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Enums\VirtualAssetStatus;
use Carbon\Carbon;
use Number;
use PhpParser\Node\Stmt\TraitUseAdaptation\Precedence;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $customer_id
 * @property OrderStatus $status
 * @property string|null $payment_id
 * @property string|null $payment_data
 * @property string|null $payment_system
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderItem> $orderItems
 * @property-read int|null $order_items_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order wherePaymentSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

    public function orderItems(): HasMany
	{
		return $this->hasMany(OrderItem::class);
	}

	protected $casts = [
		'status' => OrderStatus::class
	];

	public function getTotalAmount()
	{
		return $this->orderItems->sum(function($item) {
			return $item->product_price * $item->quantity;
		});
	}

	public function getData()
	{
		return [
			'id' => $this->id,
			'status' => $this->status->getLabel(),
			'payment_data' => $this->status === OrderStatus::PENDING ? $this->payment_data : '',
			'created_at' => $this->created_at->format('d.m.Y H:i:s'),
			'total_amount' => Number::currency(
				$this->getTotalAmount(),
				precision: 0
			),
			'order_items' => $this->orderItems->map(function(OrderItem $orderItem) {
				$item = [
					'id' => $orderItem->id,
					'product_name' => $orderItem->product_name,
					'product_price' => Number::currency($orderItem->product_price, precision: 0),
					'quantity' => $orderItem->quantity,
					'image_url' => $orderItem->product?->image_url
				];

				if ($orderItem->virtualAssets->isNotEmpty()) {
					if ($orderItem->virtualAssets->some('status', VirtualAssetStatus::PAID)) {
						$item['assets'] = $orderItem->virtualAssets->map(function ($asset) {
							return $asset->value;
						});
					}
				}

				return $item;
			})
		];
	}

	public function confirmOrCancel(bool $confirm)
	{
		foreach ($this->orderItems as $orderItem) {
			if ($orderItem->virtualAssets->isNotEmpty()) {
				foreach ($orderItem->virtualAssets as $asset) {
					$asset->status = $confirm
						? VirtualAssetStatus::PAID
						: VirtualAssetStatus::FREE;

					$asset->save();
				}
			}
			else {
				if (!$confirm && $orderItem->product) {
					$orderItem->product->quantity += $orderItem->quantity;
					$orderItem->product->save();
				}
			}
		}

		$this->payment_data = null;

		$this->status = $confirm
			? OrderStatus::DONE
			: OrderStatus::CANCELED;

		$this->save();
	}

	public function cancel()
	{
		$this->confirmOrCancel(false);
	}
}
