<?php

namespace App\Http\Controllers;
use App\Http\Resources\AuthorResource;
use Illuminate\Http\Request;
use App\Models\Author;
class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();
    
        if ($request->has('name') && $request->name !== null) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        $perPage = $request->get('per_page', 2);
        $authors = $query->orderBy('id', 'desc')->paginate($perPage);
    
        return response()->json($authors);
    }
    public function allAuthors()
{
    $authors = Author::orderBy('name')->get();
    return response()->json($authors);
}
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            ]);
    
            $author = Author::create($validated);
    
            return new AuthorResource($author);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),  
            ], 422);
        }
    }
    public function show($id)
    {
        $author = Author::find($id);
    
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
    
        return new AuthorResource($author);
    }
    
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
    
        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }
    
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $author->update($validated);
        return new AuthorResource($author);
    }
    public function destroy($id)
    {
        $author = Author::findOrFail($id);

        if (!$author) {
            return response()->json(['message' => 'Author not found'], 404);
        }

        $author->delete();
        return response()->json(null, 204);
    }
}
