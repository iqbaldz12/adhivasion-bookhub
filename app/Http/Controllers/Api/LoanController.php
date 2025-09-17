<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Jobs\SendLoanEmailJob;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    // POST /api/loans body: { "book_id": 1 }  (user = auth()->user())
    public function store(Request $request)
    {
        $data = $request->validate([
            'book_id' => ['required','exists:books,id'],
        ]);

        $user = $request->user();
        $book = Book::lockForUpdate()->findOrFail($data['book_id']);

        return DB::transaction(function () use ($user, $book) {
            if ($book->stock <= 0) {
                return response()->json(['message' => 'Stok habis'], 422);
            }

            // Buat pinjaman aktif
            BookLoan::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'loaned_at' => now(),
            ]);

            // Kurangi stok
            $book->decrement('stock');

            // Kirim email lewat Queue (mailer log)
            dispatch(new SendLoanEmailJob($user->id, $book->id));

            return response()->json([
                'message' => 'Peminjaman berhasil',
                'book'    => new BookResource($book->fresh()),
            ], 201);
        });
    }

    // GET /api/loans/{user} -> daftar buku yang sedang dipinjam (returned_at null)
    public function index(User $user)
    {
        $activeLoans = BookLoan::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->with('book')
            ->latest()
            ->get()
            ->pluck('book');

        return BookResource::collection($activeLoans);
    }
public function mine(\Illuminate\Http\Request $request)
{
    $active = \App\Models\BookLoan::where('user_id', $request->user()->id)
        ->whereNull('returned_at')
        ->with('book')
        ->latest()
        ->get()
        ->pluck('book');

    return \App\Http\Resources\BookResource::collection($active);
}
}
