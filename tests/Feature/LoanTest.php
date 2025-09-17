<?php
uses(TestCase::class)->in('Feature', 'Unit');
use App\Models\User;
use App\Models\Book;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = $this->user->createToken('test-token')->plainTextToken;
});

it('can borrow a book and decrease stock', function () {
    $book = Book::factory()->create(['stock' => 2]);

    $response = $this->postJson('/api/loans', [
        'book_id' => $book->id,
    ], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(201);

    $this->assertDatabaseHas('books', [
        'id' => $book->id,
        'stock' => 1,
    ]);
});

it('cannot borrow a book when out of stock', function () {
    $book = Book::factory()->create(['stock' => 0]);

    $response = $this->postJson('/api/loans', [
        'book_id' => $book->id,
    ], [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertStatus(422);
});

it('can list user loans', function () {
    $book = Book::factory()->create(['stock' => 1]);

    $this->user->loans()->attach($book->id, ['borrowed_at' => now()]);

    $response = $this->getJson('/api/loans/' . $this->user->id, [
        'Authorization' => 'Bearer ' . $this->token,
    ]);

    $response->assertOk()
        ->assertJsonStructure(['status', 'message', 'data']);
});
