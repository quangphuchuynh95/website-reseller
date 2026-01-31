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
        'name',
        'sequence',
        'payment_interval',
        'price',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'sequence' => 'integer',
        'price' => 'decimal:2',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
