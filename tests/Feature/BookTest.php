<?php

use App\Models\User;
use App\Models\Book;
use Laravel\Sanctum\Sanctum;

it('can create a book', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user); // <<— di sini

    $payload = Book::factory()->make()->toArray();

    $this->postJson('/api/books', $payload)
        ->assertCreated()
        ->assertJsonPath('data.title', $payload['title']);

    $this->assertDatabaseHas('books', ['isbn' => $payload['isbn']]);
});
