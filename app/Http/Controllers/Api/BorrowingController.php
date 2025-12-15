<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings.
     */
    public function index()
    {
        try {
            $borrowings = Borrowing::with(['member', 'book'])->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Borrowings retrieved successfully',
                'data' => $borrowings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve borrowings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new borrowing.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'nullable|date',
            'due_date' => 'nullable|date|after:borrowed_at',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $book = Book::find($request->book_id);

            // Check stock availability
            if ($book->available_stock <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Book is not available for borrowing'
                ], 400);
            }

            // Set default dates
            $borrowedAt = $request->borrowed_at ? Carbon::parse($request->borrowed_at) : Carbon::now();
            $dueDate = $request->due_date ? Carbon::parse($request->due_date) : Carbon::now()->addDays(14);

            $borrowing = Borrowing::create([
                'member_id' => $request->member_id,
                'book_id' => $request->book_id,
                'borrowed_at' => $borrowedAt,
                'due_date' => $dueDate,
                'status' => 'borrowed',
            ]);

            // Decrease available stock
            $book->decrement('available_stock');

            return response()->json([
                'success' => true,
                'message' => 'Book borrowed successfully',
                'data' => $borrowing->load(['member', 'book'])
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create borrowing',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified borrowing.
     */
    public function show($id)
    {
        try {
            $borrowing = Borrowing::with(['member', 'book'])->find($id);

            if (!$borrowing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Borrowing not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Borrowing retrieved successfully',
                'data' => $borrowing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve borrowing',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return a borrowed book.
     */
    public function returnBook(Request $request, $id)
    {
        try {
            $borrowing = Borrowing::find($id);

            if (!$borrowing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Borrowing not found'
                ], 404);
            }

            if ($borrowing->status === 'returned') {
                return response()->json([
                    'success' => false,
                    'message' => 'Book has already been returned'
                ], 400);
            }

            $borrowing->update([
                'returned_at' => Carbon::now(),
                'status' => 'returned',
            ]);

            // Increase available stock
            $borrowing->book->increment('available_stock');

            return response()->json([
                'success' => true,
                'message' => 'Book returned successfully',
                'data' => $borrowing->load(['member', 'book'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to return book',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified borrowing.
     */
    public function update(Request $request, $id)
    {
        $borrowing = Borrowing::find($id);

        if (!$borrowing) {
            return response()->json([
                'success' => false,
                'message' => 'Borrowing not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'due_date' => 'sometimes|required|date',
            'status' => 'sometimes|required|in:borrowed,returned,overdue',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $borrowing->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Borrowing updated successfully',
                'data' => $borrowing->load(['member', 'book'])
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update borrowing',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified borrowing.
     */
    public function destroy($id)
    {
        try {
            $borrowing = Borrowing::find($id);

            if (!$borrowing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Borrowing not found'
                ], 404);
            }

            $borrowing->delete();

            return response()->json([
                'success' => true,
                'message' => 'Borrowing deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete borrowing',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
