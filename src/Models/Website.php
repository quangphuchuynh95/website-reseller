<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use QuangPhuc\WebsiteReseller\Observers\WebsiteObserver;

/**
 * @property string $domain
 * @property string $database_name
 * @property SourceCode $sourceCode
 */
#[ObservedBy(WebsiteObserver::class)]
class Website extends BaseModel
{
    protected $table = 'wr_websites';

    protected $fillable = [
        'customer_id',
        'subscription_id',
        'theme_id',
        'source_code_id',
        'domain',
        'status',
    ];

    protected $casts = [
        'domain' => SafeContent::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    public function sourceCode(): BelongsTo
    {
        return $this->belongsTo(SourceCode::class);
    }
}
