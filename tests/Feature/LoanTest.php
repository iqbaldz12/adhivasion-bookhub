<?php

use App\Models\User;
use App\Models\Book;
use Laravel\Sanctum\Sanctum;

it('can borrow a book and decrement stock', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 2]);
    Sanctum::actingAs($user); // <<— di sini

    $this->postJson('/api/loans', ['book_id' => $book->id])
        ->assertCreated();

    $this->assertDatabaseHas('books', ['id' => $book->id, 'stock' => 1]);
});

it('cannot borrow when stock is zero', function () {
    $user = User::factory()->create();
    $book = Book::factory()->create(['stock' => 0]);
    Sanctum::actingAs($user); // <<— di sini

    $this->postJson('/api/loans', ['book_id' => $book->id])
        ->assertStatus(422)
        ->assertJson(['message' => 'Stok habis']);
});
