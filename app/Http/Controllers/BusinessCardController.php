<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusinessCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getAllBusinessCards()
    {
        $user = Auth::user();
        $businessCards = $user->businessCards()->get();

        return response()->json(['business_cards' => $businessCards]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function createBusinessCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'company' => 'required|string',
            'title' => 'required|string',
            'email' => 'required',
            'phone' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();

        $businessCard = new BusinessCard([
            'name' => $request->name,
            'company' => $request->company,
            'title' => $request->title,
            'email' => $request->email,
            'phone' => $request->phone,

            'user_id' => $user->id,
        ]);
        $businessCard->save();


        return response()->json(['message' => 'Business card created successfully', 'card' => $businessCard]);
    }

    public function updateBusinessCard(Request $request, $id)
    {
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'company' => 'required',
            'title' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);
   
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Get the authenticated user
        $user = Auth::user();
    
        // Find the business card by ID
        $businessCard = BusinessCard::where('id', $id)->first();
    
        // If business card not found, return error response
        if (!$businessCard) {
            return response()->json(['message' => 'Business card not found'], 404);
        }
    
        // Check if the authenticated user is the owner of the business card
        if ($businessCard->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to update this business card'], 403);
        }
    
        // Update the business card with the new data
        $businessCard->update([
            'name' => $request->name,
            'company' => $request->company,
            'title' => $request->title,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
    
        // Return success response
        return response()->json(['message' => 'Business card updated successfully', 'card' => $businessCard]);
    }

    /**
     * Display the specified resource.
     */
    public function show(BusinessCard $businessCard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BusinessCard $businessCard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BusinessCard $businessCard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteBusinessCard($id)
    {
        $user = Auth::user();

        $businessCard = BusinessCard::find($id);


        if (!$businessCard) {
            return response()->json(['message' => 'Business card not found'], 404);
        }


        // police service
        if ($user->id !== $businessCard->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }



        $businessCard->delete();

        return response()->json(['message' => 'Business card deleted successfully']);
    }
}
