<?php

uses(TestCase::class)->in('Feature', 'Unit');
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can register a new user', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Test User',
        'email' => 'testuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['status', 'message', 'data' => ['user', 'token']]);
});

it('can login with valid credentials', function () {
    $user = User::factory()->create([
       'password' => bcrypt('password'),

    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['status', 'message', 'data' => ['user', 'token']]);
});

it('cannot login with invalid credentials', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'wrong@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422); // ValidationException
});
