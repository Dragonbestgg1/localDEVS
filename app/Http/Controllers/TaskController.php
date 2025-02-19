<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    public function submitTask(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'code' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'time_limit' => 'required|integer',
            'memory_limit' => 'required|numeric',
            'definition' => 'required|string',
            'input_definition' => 'required|string',
            'output_definition' => 'required|string',
            'examples' => 'nullable|string',
            'correct_answer' => 'required|string',
        ]);

        // Log the validated data
        Log::info('Validated Data:', $validatedData);

        // Create a new task instance
        $task = new Task();
        $task->code = $validatedData['code'];
        $task->name = $validatedData['name'];
        $task->time_limit = $validatedData['time_limit'];
        $task->memory_limit = $validatedData['memory_limit']; // This field now allows decimals
        $task->definition = $validatedData['definition'];
        $task->input_definition = $validatedData['input_definition'];
        $task->output_definition = $validatedData['output_definition'];
        $task->examples = $validatedData['examples']; // This field is now nullable
        $task->correct_answer = json_encode($validatedData['correct_answer']);

        // Log the task object before saving
        Log::info('Task Object:', $task->toArray());

        try {
            // Save the task to the database
            $task->save();
            Log::info('Task saved successfully.');
        } catch (\Exception $e) {
            // Log any errors that occur during the save process
            Log::error('Error saving task:', ['error' => $e->getMessage()]);
        }

        // Redirect the user back to the tasks page with a success message
        return redirect('/tasks')->with('success', 'Uzdevums veiksmīgi izveidots!');
    }

    public function index()
    {
        // Retrieve all tasks
        $tasks = Task::all();

        // Log the retrieved tasks
        Log::info('Retrieved Tasks:', $tasks->toArray());

        return response()->json($tasks);
    }
}
