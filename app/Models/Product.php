<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\VirtualAsset;
use App\Enums\ProductType;
use App\Enums\VirtualAssetStatus;

/**
 * @property int $id
 * @property string $name
 * @property int $is_active
 * @property ProductType $type
 * @property numeric $price
 * @property numeric|null $quantity
 * @property string|null $description
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, VirtualAsset> $virtualAssets
 * @property-read int|null $virtual_assets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
	protected $casts = [
		'type' => ProductType::class
	];

    public function virtualAssets(): HasMany
	{
		return $this->hasMany(VirtualAsset::class);
	}

	public function hasFreeVirtualAssets()
	{
		return $this
			->virtualAssets
			->some('status', VirtualAssetStatus::FREE);
	}

	public function isSoldOut()
	{
		if ($this->isVirtual()) {
			return !$this->hasFreeVirtualAssets();
		}
		else {
			return $this->quantity === 0;
		}
	}

	public function isVirtual()
	{
		return $this->type === ProductType::VIRTUAL;
	}

	public function isPhysical()
	{
		return $this->type === ProductType::PHYSICAL;
	}

    public function getImageUrlAttribute()
    {
        return $this->image 
            ? asset('storage/' . $this->image) 
            : null;
    }
}
