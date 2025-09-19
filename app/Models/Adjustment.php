<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Adjustment extends Model
{
    use HasFactory;
    protected $table = "adjustments";
    protected $guarded = [];

    public function code() { return $this->belongsTo(Code::class); }
    public function product() { return $this->belongsTo(Product::class); }
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
