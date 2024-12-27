<?php

namespace App\Http\Controllers;

use App\Models\student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index(student $model)
    {
        return Inertia::render('StudentsDashboard', [
            'studentsData' => $model->all(),
            'count' => $model->count(),
        ]);
    }

    /**
    * Create
    */
    public function store(Request $request, student $model)
    {
        $model->create($request->validate([
            'first_name' => 'required|max:255|min:2',
            'last_name' => 'required|max:255|min:2',
            'department' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:students,email',
        ]));

        return back()->with('message', 'Student added successfully');
    }

    /**
    * Update
    */
    public function update(Request $request, student $model, $student_id)
    {

        Log::info('Update request received for student ID: ' . $student_id);
        Log::info('Update request data: ', $request->all());


        $validatedData = $request->validate( 
            [
                'first_name' => 'required|max:255|min:2',
                'last_name' => 'required|max:255|min:2',
                'department' => 'required|max:255|min:2',
                'email' => 'required|email|max:255',
            ],
            [
                'email.unique' => 'The email has already been taken.', 
            ]
        );

        $student = $model->findOrFail($student_id);

        $student->update($validatedData);

        return back()->with('message', 'Student updated successfully');
    }

    /**
    * Delete
    */
    public function destroy(student $model, $student_id)
    {
        $student = $model->findOrFail($student_id);

        $student->delete();

        // redirect()->route('/')
        return back()->with('message', 'Student deleted successfully');
    }    
}
