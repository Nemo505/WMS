<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "products";
    protected $guarded = [];

    public function unit() { return $this->belongsTo(Unit::class); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function shelfNum() { return $this->belongsTo(ShelfNumber::class, 'shelf_number_id'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }
    public function code() { return $this->belongsTo(Code::class); }
    protected static function booted()
    {
        static::addGlobalScope('activeCode', function ($builder) {
            $builder->whereHas('code', function ($query) {
                $query->whereNull('canceled_at');
            });
        });
    }

}
