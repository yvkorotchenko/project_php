<?php

namespace App\MtSports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'action',
        'type_req',
        'path_req',
        'data_req',
        'ip',
    ];
}
