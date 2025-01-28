<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function getStudentSubmissions(Request $request)
    {
        try {
            $userRole = $request->user()->role;
    
            if ($userRole === 'teacher') {
                $uniqueClasses = User::where('class', '!=', 'teacher')->distinct()->pluck('class');
    
                $sortedClasses = $uniqueClasses->sort(function($a, $b) {
                    return $this->extractNumericPart($a) <=> $this->extractNumericPart($b);
                })->values();
    
                $filteredClasses = $sortedClasses->filter(function($class) {
                    $currentYear = date('Y');
                    $classYear = $this->extractNumericPart($class);
                    $currentDate = date('Y-m-d');
                    $julyFirst = "$currentYear-07-01";
    
                    if ($classYear + 2004 == $currentYear && $currentDate <= $julyFirst || $classYear + 2004 > $currentYear) {
                        return true;
                    }
                    return false;
                })->values();
    
                return response()->json(['classes' => $filteredClasses]);
            } else {
                $studentId = $request->user()->id;
    
                $submissions = Submission::where('author', $studentId)->with('task')->get();
    
                return response()->json($submissions);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch submissions.'], 500);
        }
    }    
    

    public function getUniqueClasses()
    {
        try {
            $uniqueClasses = User::where('class', '!=', 'teacher')->distinct()->pluck('class');
    
            $sortedClasses = $uniqueClasses->sort(function($a, $b) {
                return $this->extractNumericPart($a) <=> $this->extractNumericPart($b);
            })->values();
    
            $filteredClasses = $sortedClasses->filter(function($class) {
                $currentYear = date('Y');
                $classYear = $this->extractNumericPart($class);
                $currentDate = date('Y-m-d');
                $julyFirst = "$currentYear-07-01";
    
                if ($classYear + 2004 == $currentYear && $currentDate <= $julyFirst || $classYear + 2004 > $currentYear) {
                    return true;
                }
                return false;
            })->values();
    
            return response()->json(['classes' => $filteredClasses]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch unique classes.'], 500);
        }
    }
    
    private function extractNumericPart($className)
    {
        preg_match('/\d+/', $className, $matches);
        return isset($matches[0]) ? (int) $matches[0] : 0;
    }
}
