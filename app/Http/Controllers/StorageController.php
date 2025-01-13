<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function storage(Request $r)
    {
        $validatedData = $r->validate([
            'STORAGE' => 'required|numeric|min:0',
        ]);

        // Retrieve current storage amount from session
        $currentStorage = session('storage_amount', 0); 

        // Update the storage amount
        $newStorage = $currentStorage - $validatedData['STORAGE'];
    
        // Store the storage amount in session
        session(['storage_amount' => $validatedData['STORAGE']]);
    
        return response()->json(['message' => 'Storage amount updated successfully.']);
    }
}
