<?php

namespace App\Models;

use Database\Factories\DkmMemberFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DkmMember extends Model
{
    /** @use HasFactory<DkmMemberFactory> */
    use HasFactory;

    protected $guarded = ['id'];
}
