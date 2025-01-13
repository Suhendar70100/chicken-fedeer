<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorLog extends Model
{
    protected $table = 'motor_logs';

    protected $fillable = [
        'status',
        'timestamp',
    ];

    public $timestamps = true;

}
