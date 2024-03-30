<?php


namespace App\Http\Controllers;

use App\Models\BusinessCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Schema(
 *     schema="BusinessCard",
 *     required={"id", "name", "company", "title", "email", "phone"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="company", type="string", example="ABC Inc."),
 *     @OA\Property(property="title", type="string", example="CEO"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="phone", type="string", example="123456789"),
 * )
 */

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
     * Display all business cards of the authenticated user.
     *
     * @OA\Get(
     *     path="/business_cards",
     *     summary="Get all business cards",
     *     tags={"Business Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/BusinessCard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function getAllBusinessCards()
    {
        $user = Auth::user();
        $businessCards = $user->businessCards()->get();

        return response()->json(['business_cards' => $businessCards]);
    }
    /**
     * Create a new business card.
     *
     * @OA\Post(
     *     path="/business_cards",
     *     summary="Create a new business card",
     *     tags={"Business Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Business card data",
     *         @OA\JsonContent(
     *             required={"name", "company", "title", "email", "phone"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="company", type="string", example="ABC Inc."),
     *             @OA\Property(property="title", type="string", example="CEO"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Business card created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Business card created successfully"),
     *             @OA\Property(property="card", ref="#/components/schemas/BusinessCard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
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
    
    /**
     * Update an existing business card.
     *
     * @OA\Post(
     *     path="/update_business_cards/{id}",
     *     summary="Update an existing business card",
     *     tags={"Business Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the business card to update",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Updated business card data",
     *         @OA\JsonContent(
     *             required={"name", "company", "title", "email", "phone"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="company", type="string", example="ABC Inc."),
     *             @OA\Property(property="title", type="string", example="CEO"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="phone", type="string", example="123456789"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Business card updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Business card updated successfully"),
     *             @OA\Property(property="card", ref="#/components/schemas/BusinessCard")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Business card not found",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to update this business card",
     *     )
     * )
     */
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
     * Delete a business card.
     *
     * @OA\Delete(
     *     path="/delete_card/{id}",
     *     summary="Delete a business card",
     *     tags={"Business Cards"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the business card to delete",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Business card deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Business card deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Business card not found",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to delete this business card",
     *     )
     * )
     */
     public function deleteBusinessCard($id)
{
    $user = Auth::user();
    $businessCard = BusinessCard::find($id);

    if (!$businessCard) {
        return response()->json(['message' => 'Business card not found'], 404);
    
    } elseif($user->id !== $businessCard->user_id) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $businessCard->delete();

    return response()->json(['message' => 'Business card deleted successfully']);
}

}
