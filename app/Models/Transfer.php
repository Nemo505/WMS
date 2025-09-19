<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;
    protected $table = "transfers";
    protected $guarded = [];

    public function fromShelf() { return $this->belongsTo(ShelfNumber::class, 'from_shelf_number_id'); }
    public function toShelf() { return $this->belongsTo(ShelfNumber::class, 'to_shelf_number_id'); }
    public function product() { return $this->belongsTo(Product::class); }
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
