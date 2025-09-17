<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Requests\StoreLoanRequest;
use App\Http\Resources\BookResource;
use App\Services\LoanService;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoanController extends Controller
{
     public function __construct(private LoanService $loanService)
    {
    }

    public function store(StoreLoanRequest $request)
    {
        try {
            $book = $this->loanService->borrow($request->user(), $request->book_id);

            return ApiResponse::success(new BookResource($book), 'Book borrowed', 201);
        } catch (HttpException $e) {
            return ApiResponse::error($e->getMessage(), $e->getStatusCode());
        }
    }

    public function index(int $userId)
    {
        $loans = $this->loanService->getUserLoans($userId);

        return ApiResponse::success(BookResource::collection($loans), 'User loans');
    }
}
