<?php

namespace App\Models;

use App\Models\Concerns\BumpsContentCache;
use Illuminate\Database\Eloquent\Model;

class UiText extends Model
{
    use BumpsContentCache;

    protected $table = 'ui_texts';

    protected $fillable = ['key', 'en', 'ne'];
}
