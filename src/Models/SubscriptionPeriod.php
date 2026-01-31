<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPeriod extends BaseModel
{
    protected $table = 'wr_subscription_periods';

    protected $fillable = [
        'name',
        'interval_value',
        'sequence',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'sequence' => 'integer',
    ];

    public function packagePrices(): HasMany
    {
        return $this->hasMany(PackagePrice::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get CarbonInterval from interval_value (ISO 8601 duration format)
     * Examples: P1D (1 day), P1W (1 week), P1M (1 month), P3M (3 months), P1Y (1 year)
     */
    public function getInterval(): CarbonInterval
    {
        return CarbonInterval::make($this->interval_value) ?? CarbonInterval::month();
    }
}
