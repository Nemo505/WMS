<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ShelfNumber extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "shelf_numbers";
    protected $guarded = [];

    public function warehouse() { return $this->belongsTo(Warehouse::class); }
    public function shelf() { return $this->belongsTo(Shelf::class); }

}
