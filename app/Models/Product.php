<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'option_product_assignments');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
