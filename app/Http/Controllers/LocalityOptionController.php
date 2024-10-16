<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LocalityOption;
class LocalityOptionController extends Controller
{
    public function getLocalities()
    {
        try {
            $localities = LocalityOption::all();
            return response()->json($localities);
        } catch (\Exception $e) {
            Log::error('Error fetching localities: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function storeLocality(Request $request)
    {
        try {
            $validated = $request->validate([
                'locality' => 'required|string|max:255',
                'code' => 'required|string|max:255',
            ]);

            $locality = LocalityOption::create($validated);

            return response()->json(['success' => true, 'data' => $locality]);
        } catch (\Exception $e) {
            Log::error('Error storing locality: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
