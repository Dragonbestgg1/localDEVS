<?php

namespace App\Http\Controllers;

use App\Models\Competition;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompetitionController extends Controller
{
    public function create()
    {
        $tasks = Task::all();
        return view('competitions.create', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'time' => 'nullable|string',
            'from' => 'nullable|date',
            'till' => 'nullable|date',
            'description' => 'required|string',
            'information' => 'required|string',
            'difficulty' => 'required|string',
            'tasks' => 'required|array',
        ]);

        $competitionData = $request->all();
        $competitionData['tasks'] = json_encode($request->input('tasks'));

        Competition::create($competitionData);

        return redirect()->back()->with('success', 'Competition created successfully.');
    }

    public function index(Request $request)
    {
        $competitions = Competition::all();

        foreach ($competitions as $competition) {

            $idsArray = json_decode($competition->tasks, true);
    
            $tasks = Task::whereIn('id', $idsArray)->get();
            
            $competition->tasks = $tasks;
        }
    
        Log::info('Retrieved Competitions with Tasks:', $competitions->toArray());
    
        return response()->json($competitions);
    }
    
    
}    
