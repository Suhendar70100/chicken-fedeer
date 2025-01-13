<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MotorLog;

class MotorlogController extends Controller{
    // This method logs motor data sent from the ESP32
    public function logMotorStatus(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'motor_status' => 'required|string',
            'timestamp' => 'required'  // Use a timestamp or datetime format
        ]);

        // Save data to the motor_logs table
        MotorLog::create([
            'status' => $request->input('motor_status'),
            'timestamp' => $request->input('timestamp'),
        ]);

        return response()->json(['message' => 'Data logged successfully'], 200);
    }
}
