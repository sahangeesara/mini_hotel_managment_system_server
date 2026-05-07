<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Hotels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $hotels = Hotels::where('is_available', 1)
                ->orderBy('created_at',"DESC")
                ->get();

            return response()->json($hotels);
        }catch (\Exception $e) {
            // Log the error and return an appropriate response
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while retrieving hotel.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        info($request->all());
        $validatedData = $request->validate([
            'hotel_name' => 'required|max:255',
            'location' => 'required |max:255',
            'city' => 'required |max:255',
            'country' => 'required|max:255',
            'price_per_night' => 'required|numeric |min:0',
            'star_rating' => 'required |integer |min:1|max:5',
            'amenities' => 'required |array',
            'description' => 'nullable |string',
            'image' => ''
        ]);

        info($validatedData);
        try {
            $imagePath = null;

            $nextId = (Hotels::max('id') ?? 0) + 1;
            $hNo = 'HN' . str_pad((string) $nextId, 5, '0', STR_PAD_LEFT);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')
                    ->store('public/hotel/images');
            }

            $hotel = Hotels::create([
                'hotel_code' => $hNo,
                'hotel_name' => $validatedData['hotel_name'],
                'location' => $validatedData['location'],
                'city' => $validatedData['city'],
                'country' => $validatedData['country'],
                'price_per_night' => $validatedData['price_per_night'],
                'star_rating' => $validatedData['star_rating'],
                'amenities' => $validatedData['amenities'],
                'description' => $validatedData['description'] ?? null,
                'image' => $imagePath,
            ]);
            return response()->json(['message' => 'hotel created successfully', 'hotel' => $hotel], 201);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while adding hotel.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $hotel = Hotels::findOrFail($id);

            return response()->json($hotel);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while retrieving hotel.'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'hotel_name' => 'required |max:255',
            'location' => 'required|max:255',
            'city' => 'required | max:255',
            'country' => 'required |max:255',
            'price_per_night' => 'required| numeric|min:0',
            'star_rating' => 'required|integer|min:1max:5',
            'amenities' => 'required |array',
            'description' => 'nullable |string',
            'image' => 'image'
        ]);

        try {
            $hotel = Hotels::findOrFail($id);

            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('public/hotel/images');
            }

            $hotel->update($validatedData);

            return response()->json([
                'message' => 'Hotel updated successfully.',
                'hotel' => $hotel->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while updating the hotel.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $hotel = Hotels::findOrFail($id);
            $hotel->is_available = false;
            $hotel->save();
            return response()->json(['message' => 'Hotel deactivated successfully.'], 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'An error occurred while deactivating the hotel.'], 500);
        }
    }
}
