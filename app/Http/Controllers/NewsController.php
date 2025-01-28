<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function store(Request $request)
    {
        // Log the incoming request data
        Log::info('Incoming request data:', $request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'author' => 'required|string|max:255',
        ]);

        try {
            $news = new News([
                'title' => $request->get('title'),
                'description' => $request->get('description'),
                'author' => $request->get('author'),
            ]);

            $news->save();

            // Log successful creation
            Log::info('News item added successfully:', $news->toArray());

            return response()->json(['message' => 'News item added successfully'], 201);
        } catch (\Exception $e) {
            // Log any errors that occur during the save process
            Log::error('Error adding news item:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Error adding news item'], 500);
        }
    }

    public function index()
    {
        try {
            $news = News::all();

            // Log successful retrieval
            Log::info('Retrieved all news items:', $news->toArray());

            return response()->json($news, 200);
        } catch (\Exception $e) {
            // Log any errors that occur during the retrieval process
            Log::error('Error retrieving news items:', ['error' => $e->getMessage()]);

            return response()->json(['message' => 'Error retrieving news items'], 500);
        }
    }
}
