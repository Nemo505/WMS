<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class IssueReturn extends Model
{
    use HasFactory;
    protected $table = "issue_returns";
    protected $guarded = [];
}
