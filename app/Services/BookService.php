<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function paginate(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Book::query();

        if (! empty($filters['q'])) {
            $q = $filters['q'];
            $query->where('title', 'like', "%{$q}%");
        }

        if (! empty($filters['author'])) {
            $query->where('author', 'like', '%' . $filters['author'] . '%');
        }

        if (! empty($filters['year'])) {
            $query->where('published_year', $filters['year']);
        }

        return $query->orderBy('title')->paginate($perPage);
    }

    public function findOrFail(int $id): Book
    {
        return Book::findOrFail($id);
    }

    public function create(array $data): Book
    {
        return DB::transaction(function () use ($data) {
            return Book::create($data);
        });
    }

    public function update(int $id, array $data): Book
    {
        return DB::transaction(function () use ($id, $data) {
            $book = Book::find($id);

            if (! $book) {
                throw new ModelNotFoundException("Book not found");
            }

            $book->update($data);

            return $book->refresh();
        });
    }

    public function delete(int $id): void
    {
        $book = Book::find($id);

        if (! $book) {
            throw new ModelNotFoundException("Book not found");
        }

        $book->delete();
    }
}
    