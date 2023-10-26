<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueHistory extends Model
{
    use HasFactory;
    protected $table = "issue_histories";
    protected $guarded = [];
}
