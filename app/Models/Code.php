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
    protected $casts = [
        'canceled_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope('notCanceled', function ($builder) {
            $builder->whereNull('canceled_at'); // only show active codes by default
        });
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class);
    }

    public function issueReturns()
    {
        return $this->hasMany(IssueReturn::class);
    }

    public function supplierReturns()
    {
        return $this->hasMany(SupplierReturn::class);
    }

    public function adjustments()
    {
        return $this->hasMany(Adjustment::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function commodity()
    {
        return $this->belongsTo(Commodity::class);
    }


}
