<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdjustmentHistory extends Model
{
    use HasFactory;
    protected $table = "adjustment_histories";
    protected $guarded = [];
}
