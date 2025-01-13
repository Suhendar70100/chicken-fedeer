<?php

use App\Http\Controllers\Api\MotorLogController;

Route::post('/motor-logs', [MotorLogController::class, 'store']);