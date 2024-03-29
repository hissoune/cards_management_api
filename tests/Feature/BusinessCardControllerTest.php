<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\BusinessCard;

class BusinessCardControllerTest extends TestCase
{
    use RefreshDatabase; // Reset the database after each test

    public function test_create_business_card()
{
    // Create a user for testing purposes
    $user = User::factory()->create();

    // Simulate authentication
    $this->actingAs($user);

    // Create request data
    $requestData = [
        'name' => 'John Doe',
        'company' => 'Acme Inc',
        'title' => 'CEO',
        'email' => 'john@example.com',
        'phone' => '123456789',
    ];

    // Call the createBusinessCard method
    $response = $this->post(route('business_cards'), $requestData);

    // Assert that the response is successful
    $response->assertStatus(200);

    // Assert that the business card was created in the database
    $this->assertDatabaseHas('business_cards', $requestData);

    // Assert that the business card is associated with the authenticated user
    $this->assertEquals(1, BusinessCard::where('user_id', $user->id)->count());
}
public function test_update_business_card_validation_error()
{
    // Create a user for testing purposes
    $user = User::factory()->create();

    // Simulate authentication
    $this->actingAs($user);

    // Create a business card owned by the user
    $businessCard = BusinessCard::factory()->create(['user_id' => $user->id]);

    // Update request data with missing required fields
    $invalidUpdateData = [
        // Missing 'name'
        'company' => 'Updated Company',
        'title' => 'Updated Title',
        'email' => 'updated@example.com',
        'phone' => '987654321',
    ];

    // Call the updateBusinessCard method with invalid data
    $response = $this->post(route('update_business_cards', ['id' => $businessCard->id]), $invalidUpdateData);

    // Assert that the response status is 422 (Unprocessable Entity)
    $response->assertStatus(422);
}


public function test_delete_business_card()
{
    $user = User::factory()->create();

    $this->actingAs($user);

    $businessCard = BusinessCard::factory()->create(['user_id' => $user->id]);

    $response = $this->delete(route('delete_card', ['id' => $businessCard->id]));
    $response->assertStatus(200);

 $this->assertDatabaseMissing('business_cards', ['id' => $businessCard->id]);

   
     $response = $this->delete(route('delete_card', ['id' => 999]));
    $response->assertStatus(404);

    $otherUser = User::factory()->create();
    $this->actingAs($otherUser);

    $businessCard = BusinessCard::factory()->create(['user_id' => 3]);

    $response = $this->delete(route('delete_card', ['id' => $businessCard->id]));
    $response->assertStatus(403);
}



}
