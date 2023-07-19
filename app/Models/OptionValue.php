<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OptionValue extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function options(): BelongsToMany
    {
        return $this->belongsToMany(Option::class, 'option_value_assignments');
    }
}
