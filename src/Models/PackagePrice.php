<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackagePrice extends BaseModel
{
    protected $table = 'wr_package_prices';

    protected $fillable = [
        'package_id',
        'subscription_period_id',
        'name',
        'description',
        'sequence',
        'price',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'sequence' => 'integer',
        'price' => 'decimal:2',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function subscriptionPeriod(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPeriod::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
