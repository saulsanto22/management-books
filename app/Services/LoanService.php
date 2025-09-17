<?php

namespace App\Services;

use App\Helpers\ApiResponse;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendBookBorrowedEmail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoanService
{
    public function borrow(User $user, int $bookId)
    {
        return DB::transaction(function () use ($user, $bookId) {
            // Lock row untuk hindari race condition
            $book = Book::where('id', $bookId)->lockForUpdate()->first();

            if (! $book) {
                throw new ModelNotFoundException("Book not found");
            }

            if ($book->stock <= 0) {
                throw new HttpException(422, 'Book out of stock');
            }

            // Kurangi stok
            $book->decrement('stock');

            // Attach ke pivot
            $user->loans()->attach($book->id, [
                'borrowed_at' => now(),
            ]);

            SendBookBorrowedEmail::dispatch($user, $book);
            return $book;
        });
    }

    public function getUserLoans(int $userId)
    {
        $user = User::find($userId);

        if (! $user) {
            throw new ModelNotFoundException("User not found");
        }

        // Hanya buku yang belum dikembalikan
        return $user->loans()->wherePivotNull('returned_at')->get();
    }
}
