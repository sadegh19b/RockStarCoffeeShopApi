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

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_option_value_assignments');
    }

    public function firstOption(): mixed
    {
        return $this->options()->wherePivot('option_value_id', $this->pivot->option_value_id)->first();
    }
}
