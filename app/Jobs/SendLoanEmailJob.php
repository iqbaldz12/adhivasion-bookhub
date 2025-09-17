<?php

namespace App\Jobs;

use App\Mail\LoanCreatedMail;
use App\Models\Book;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendLoanEmailJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(public int $userId, public int $bookId) {}

    public function handle(): void
    {
        $user = User::findOrFail($this->userId);
        $book = Book::findOrFail($this->bookId);

        Mail::to($user->email)->send(new LoanCreatedMail($user->name, $book->title));
    }
}
