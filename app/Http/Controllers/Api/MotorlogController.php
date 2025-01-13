<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MotorLog; // Ensure you have a MotorLog model
use Illuminate\Support\Facades\Schema; // Import Schema facade
use Illuminate\Database\Schema\Blueprint; // Import Blueprint

class MotorLogController extends Controller
{
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'motor_status' => 'required|string',
            'timestamp' => 'required|string',
        ]);

        // Create and save the log entry
        MotorLog::create([
            'status' => $request->motor_status,
            'timestamp' => $request->timestamp,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Data logged successfully'], 201);
    }
}
