<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use QuangPhuc\WebsiteReseller\Enums\SubscriptionStatusEnum;

class Subscription extends BaseModel
{
    protected $table = 'wr_subscriptions';

    protected $fillable = [
        'customer_id',
        'theme_id',
        'package_id',
        'package_price_id',
        'name',
        'commit_price',
        'payment_interval',
        'start_at',
        'next_expires_at',
        'status',
        'charge_id',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'commit_price' => 'decimal:2',
        'start_at' => 'datetime',
        'next_expires_at' => 'datetime',
        'status' => SubscriptionStatusEnum::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function packagePrice(): BelongsTo
    {
        return $this->belongsTo(PackagePrice::class);
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }
}
