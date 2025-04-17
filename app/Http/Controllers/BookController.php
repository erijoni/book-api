<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Author;
use App\Services\BookService;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books,isbn',
            'author_id' => 'required|exists:authors,id',
        ]);
    
        $coverImage = $this->bookService->fetchCoverImage($validated['isbn'], $validated['title']);
        
        if (!$coverImage) {
            return response()->json([
                'message' => 'Unable to fetch cover image for the book.'
            ], 400);  
        }
    
        $book = Book::create([
            'title' => $validated['title'],
            'isbn' => $validated['isbn'],
            'author_id' => $validated['author_id'],
            'cover_image' => $coverImage,
        ]);
    
        return response()->json($book, 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'message' => 'Validation error',
            'errors' => $e->errors(), 
        ], 422);  
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while creating the book.',
            'error' => $e->getMessage(),  
        ], 500);  
    }
}

    public function index(Request $request)
    {
        $query = Book::with('author');
        $query->when($request->has('title') && $request->title !== null, function ($query) use ($request) {
            $searchValue = $request->title;
    
            $query->where(function ($query) use ($searchValue) {
                $query->where('title', 'like', '%' . $searchValue . '%')
                    ->orWhere('isbn', 'like', '%' . $searchValue . '%');
            });
        });
        $sortField = $request->get('sortField', 'id'); 
        $sortOrder = $request->get('sortOrder', 'desc'); 
        
       
        if (in_array($sortField, ['id', 'title',]) && in_array($sortOrder, ['asc', 'desc'])) {
            $query->orderBy($sortField, $sortOrder);
        } else {
            $query->orderBy('id', 'desc');
        }
        $perPage = $request->get('per_page', 3);  
        $books = $query->orderBy('id', 'desc')->paginate($perPage);
    
        if ($books->isEmpty()) {
            return response()->json([
                'error' => 'No books found in the database.',
                'message' => 'Please ensure that there are books available.',
            ], 404);  
        }
    
        return response()->json($books);
    }

    public function show($id)
    {
        $book = Book::with('author')->find($id);
    
        if (!$book) {
            return response()->json([
                'error' => 'Book not found.',
                'message' => 'No book found with the provided ID.',
            ], 404);  
        }
        return new BookResource($book);
    }
    public function showBook($id)
    {
        $book = Book::with('author') 
            ->find($id); 

            if (!$book) {
                return response()->json([
                    'message' => 'Book not found',
                ], 404);
            }
        return response()->json($book);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books,isbn,' . $id,
            'author_id' => 'required|exists:authors,id',
        ]);

        $book = Book::findOrFail($id);
        if (!$book) {
            return response()->json([
                'message' => 'Book not found',
            ], 404);
        }

        $book->update($validated);
        return response()->json($book);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        if (!$book) {
            return response()->json([
                'message' => 'Book not found',
            ], 404);
        }
        $book->delete();
        return response()->json(null, 204);
    }
}
