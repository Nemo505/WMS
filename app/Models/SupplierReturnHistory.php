<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierReturnHistory extends Model
{
    use HasFactory;
    protected $table = "supplier_return_histories";
    protected $guarded = [];
}
