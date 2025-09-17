<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // GET /api/books?author=&year=&q=
    public function index(Request $request)
    {
        $q = Book::query();

        if ($author = $request->query('author')) {
            $q->where('author', 'like', "%{$author}%");
        }
        if ($year = $request->query('year')) {
            $q->where('published_year', $year);
        }
        if ($term = $request->query('q')) {
            $q->where('title', 'like', "%{$term}%");
        }

        return BookResource::collection($q->orderBy('id','desc')->paginate(10));
    }

    // POST /api/books
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());
        return (new BookResource($book))->response()->setStatusCode(201);
    }

    // PUT/PATCH /api/books/{book}
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());
        return new BookResource($book);
    }

    // DELETE /api/books/{book}
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json(['message' => 'deleted']);
    }
}
