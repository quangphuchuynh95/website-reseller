<?php

namespace QuangPhuc\WebsiteReseller\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends BaseModel implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'wr_customers';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $casts = [
        'name' => SafeContent::class,
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
    ];

    public function websites(): HasMany
    {
        return $this->hasMany(Website::class);
    }
}
