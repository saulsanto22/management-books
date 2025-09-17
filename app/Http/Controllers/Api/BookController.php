<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Services\BookService;

class BookController extends Controller
{
    public function __construct(private BookService $bookService){}

    public function index(Request $request)
    {
        $filters = $request->only(['q', 'author', 'year']);
        $perPage = (int) $request->query('per_page', 10);

        $books = $this->bookService->paginate($filters, $perPage);

        return ApiResponse::paginated(BookResource::collection($books), 'Book list');
    }

    public function show(int $id)
    {
        $book = $this->bookService->findOrFail($id);

        return ApiResponse::success(new BookResource($book), 'Book detail');
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookService->create($request->validated());

        return ApiResponse::success(new BookResource($book), 'Book created', 201);
    }

    public function update(UpdateBookRequest $request, int $id)
    {
        $book = $this->bookService->update($id, $request->validated());
        return ApiResponse::success(new BookResource($book), 'Book updated');
    }

    public function destroy(int $id)
    {
        $this->bookService->delete($id);
        return ApiResponse::success(null, 'Book deleted', 204);
    }
}
