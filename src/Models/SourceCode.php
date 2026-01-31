<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $slug
 * @property string $caddy_template
 * @property string $setup_command
 */
class SourceCode extends BaseModel
{
    protected $table = 'wr_source_codes';

    protected $fillable = [
        'name',
        'slug',
        'caddy_template',
        'setup_command',
    ];

    protected $casts = [
        'name' => SafeContent::class,
    ];
}
