<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Option extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'option_product_assignments');
    }

    public function values(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class, 'option_value_assignments');
    }
}
