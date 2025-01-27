<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Submission;

class StudentController extends Controller
{
    public function getStudentSubmissions(Request $request)
    {
        // Assume the student's ID is available in the request
        $studentId = $request->user()->id;
        $submissions = Submission::where('author', $studentId)->with('task')->get();

        return response()->json($submissions);
    }

}
