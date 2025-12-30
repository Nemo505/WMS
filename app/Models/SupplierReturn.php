<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierReturn extends Model
{
    use HasFactory;
    protected $table = "supplier_returns";
    protected $guarded = [];

    public function shelfNum() { return $this->belongsTo(ShelfNumber::class, 'shelf_number_id'); }
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function code() { return $this->belongsTo(Code::class); }
    
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function updater() { return $this->belongsTo(User::class, 'updated_by'); }

    protected static function booted()
    {
        static::addGlobalScope('activeCode', function ($builder) {
            $builder->whereHas('code', function ($query) {
                $query->whereNull('canceled_at');
            });
        });
    }

}
