<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class LoanCreatedMail extends Mailable
{
    use Queueable;

    public function __construct(public string $userName, public string $bookTitle) {}

    public function build()
    {
        return $this->subject('Konfirmasi Peminjaman Buku')
            ->view('emails.loan_created', [
                'userName'  => $this->userName,
                'bookTitle' => $this->bookTitle,
            ]);
    }
}
