<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class JsonExportController extends Controller
{
    public function exportClassesToJson()
    {
        try {
            $uniqueClasses = User::where('class', '!=', 'teacher')->distinct()->pluck('class');

            // Create a directory for JSON files
            $jsonDirectory = public_path('json');
            if (!File::exists($jsonDirectory)) {
                File::makeDirectory($jsonDirectory, 0755, true);
            }

            // Loop through each unique class and create a JSON file
            foreach ($uniqueClasses as $class) {
                $classUsers = User::where('class', $class)->get();

                // Hide unwanted attributes
                $classUsers->makeHidden(['email_verified_at', 'created_at', 'updated_at']);

                // Generate JSON file name
                $fileName = $class . '.json';
                $filePath = $jsonDirectory . '/' . $fileName;

                // Save user data to JSON file with pretty print
                File::put($filePath, $classUsers->toJson(JSON_PRETTY_PRINT));
            }

            return response()->json(['message' => 'JSON files created successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create JSON files: ' . $e->getMessage()], 500);
        }
    }
}
