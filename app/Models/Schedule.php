<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'schedule';
    protected $primaryKey = 'id';
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'id',
        'frequency',
        'start_date',
        'end_date',
        'age',
        'pakan'
    ];
}
