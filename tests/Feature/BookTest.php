<?php

uses(TestCase::class)->in('Feature', 'Unit');
use App\Models\User;
use App\Models\Book;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test-token')->plainTextToken;
});

it('can create a book', function () {
    $response = $this->postJson('/api/books', [
        'title' => 'Clean Code',
        'author' => 'Robert C. Martin',
        'published_year' => 2008,
        'isbn' => 'ISBN-12345',
        'stock' => 5,
    ], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.title', 'Clean Code');
});

it('cannot create a book with duplicate isbn', function () {
    Book::factory()->create(['isbn' => 'DUPLICATE']);

    $response = $this->postJson('/api/books', [
        'title' => 'Another Book',
        'author' => 'Someone',
        'published_year' => 2020,
        'isbn' => 'DUPLICATE',
        'stock' => 2,
    ], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(422);
});

it('can list books with pagination', function () {
    Book::factory()->count(15)->create();

    $response = $this->getJson('/api/books?per_page=10', [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertOk()
        ->assertJsonStructure(['data', 'meta']);
});
