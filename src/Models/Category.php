<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends BaseModel
{
    protected $table = 'wr_categories';

    protected $fillable = [
        'name',
        'image',
    ];

    protected $casts = [
        'name' => SafeContent::class,
    ];

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class, 'wr_theme_category', 'category_id', 'theme_id')
            ->withTimestamps();
    }
}
