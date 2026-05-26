<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;
use App\Models\OrderItem;
use App\Enums\VirtualAssetStatus;

/**
 * @property int $id
 * @property string $value
 * @property VirtualAssetStatus $status
 * @property int $product_id
 * @property int|null $order_item_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read OrderItem|null $orderItem
 * @property-read Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VirtualAsset whereValue($value)
 * @mixin \Eloquent
 */
class VirtualAsset extends Model
{
    public function orderItem(): BelongsTo
	{
		return $this->belongsTo(OrderItem::class);
	}

    public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	protected $casts = [
		'status' => VirtualAssetStatus::class
	];

	public function reserve()
	{
		$this->status = VirtualAssetStatus::RESERVED;
	}
}
