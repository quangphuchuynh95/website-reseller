<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends BaseModel
{
    protected $table = 'wr_themes';

    protected $fillable = [
        'name',
        'image',
        'preview_url',
        'database_file',
        'source_code_id',
    ];

    protected $casts = [
        'name' => SafeContent::class,
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'wr_theme_package', 'theme_id', 'package_id')
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'wr_theme_category', 'theme_id', 'category_id')
            ->withTimestamps();
    }

    public function sourceCode(): BelongsTo
    {
        return $this->belongsTo(SourceCode::class);
    }

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }
}
