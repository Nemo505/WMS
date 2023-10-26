<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueReturnHistory extends Model
{
    use HasFactory;
    protected $table = "issue_return_histories";
    protected $guarded = [];
}
