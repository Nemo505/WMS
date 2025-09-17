<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Code extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "codes";
    protected $guarded = []; 

    protected static function booted()
    {
        static::addGlobalScope('notCanceled', function ($builder) {
            $builder->whereNull('canceled_at'); // only show active codes by default
        });
    }

}
